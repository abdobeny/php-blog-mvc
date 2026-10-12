<?php

class User {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function register($username, $email, $password) {
        if ($this->usernameExists($username) || $this->emailExists($email)) {
            return false;
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        return $stmt->execute([$username, $email, $hashed_password]);
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function usernameExists($username) {
        return $this->exists('username', $username);
    }

    public function emailExists($email) {
        return $this->exists('email', $email);
    }

    private function exists($column, $value) {
        // $column is only ever a hard-coded literal supplied by this class.
        $stmt = $this->conn->prepare("SELECT 1 FROM users WHERE $column = ? LIMIT 1");
        $stmt->execute([$value]);
        return (bool) $stmt->fetchColumn();
    }

    public function getAll() {
        return $this->conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateRole($id, $role) {
        $stmt = $this->conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }

    public function countAll() {
        return $this->conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}
