<?php

class RegisterController extends BaseController {
    public function index() {
        if ($this->isLoggedIn()) {
            $this->redirect('/admin/index');
        }
        $this->view('register');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $this->verifyCsrf();

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        // New accounts are always created with the normal 'user' role.

        $errors = [];
        if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
            $errors[] = 'Username must be 3-50 characters (letters, numbers and underscore only).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }
        if ($password !== $confirm) {
            $errors[] = 'The two passwords do not match.';
        }

        $user = $this->model('User');
        if (!$errors && $user->usernameExists($username)) {
            $errors[] = 'That username is already taken.';
        }
        if (!$errors && $user->emailExists($email)) {
            $errors[] = 'That email address is already registered.';
        }

        if ($errors) {
            $this->view('register', [
                'errors' => $errors,
                'old'    => ['username' => $username, 'email' => $email],
            ]);
            return;
        }

        if ($user->register($username, $email, $password)) {
            $this->redirect('/login?registered=1');
        }

        $this->view('register', [
            'errors' => ['Registration failed. Please try again.'],
            'old'    => ['username' => $username, 'email' => $email],
        ]);
    }
}
