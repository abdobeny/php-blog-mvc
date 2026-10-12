<?php

class LoginController extends BaseController {
    public function index() {
        if ($this->isLoggedIn()) {
            $this->redirect('/admin/index');
        }
        $this->view('login');
    }

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('/admin/index');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('login');
            return;
        }

        $this->verifyCsrf();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->view('login', [
                'error' => 'Please enter your username and password.',
                'old'   => ['username' => $username],
            ]);
            return;
        }

        $user = $this->model('User');
        $authenticated_user = $user->login($username, $password);

        if ($authenticated_user) {
            // Prevent session fixation attacks.
            session_regenerate_id(true);

            $_SESSION['user_id']  = $authenticated_user['id'];
            $_SESSION['username'] = $authenticated_user['username'];
            $_SESSION['role']     = $authenticated_user['role'];

            $this->redirect('/admin/index');
        }

        $this->view('login', [
            'error' => 'Invalid username or password.',
            'old'   => ['username' => $username],
        ]);
    }

    public function logout() {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $this->redirect('/login');
    }
}
