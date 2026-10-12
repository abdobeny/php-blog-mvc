<?php

class Comment {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function getByArticleId($article_id) {
        $stmt = $this->conn->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE article_id = ? ORDER BY created_at DESC");
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        return $this->conn->query("SELECT comments.*, users.username, articles.title as article_title FROM comments JOIN users ON comments.user_id = users.id JOIN articles ON comments.article_id = articles.id ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($content, $user_id, $article_id) {
        $stmt = $this->conn->prepare("INSERT INTO comments (content, user_id, article_id) VALUES (?, ?, ?)");
        return $stmt->execute([$content, $user_id, $article_id]);
    }

    public function countAll() {
        return $this->conn->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>