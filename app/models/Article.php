<?php

class Article {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function getAll($limit, $offset) {
        $stmt = $this->conn->prepare("SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id, $limit, $offset) {
        $stmt = $this->conn->prepare("SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id WHERE articles.user_id = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':user_id', (int) $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecent($limit) {
        $stmt = $this->conn->prepare("SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        return $this->conn->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    }

    public function countByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM articles WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id WHERE articles.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $content, $user_id) {
        $stmt = $this->conn->prepare("INSERT INTO articles (title, content, user_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $content, $user_id])) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function assignCategories($article_id, $category_ids) {
        $stmt = $this->conn->prepare("DELETE FROM article_categories WHERE article_id = ?");
        $stmt->execute([$article_id]);

        $insert = $this->conn->prepare("INSERT IGNORE INTO article_categories (article_id, category_id) VALUES (?, ?)");
        foreach ((array) $category_ids as $category_id) {
            $insert->execute([$article_id, (int) $category_id]);
        }
    }

    public function getCategories($article_id) {
        $stmt = $this->conn->prepare("SELECT categories.* FROM categories JOIN article_categories ON categories.id = article_categories.category_id WHERE article_categories.article_id = ?");
        $stmt->execute([$article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $content) {
        $stmt = $this->conn->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
        return $stmt->execute([$title, $content, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function search($query) {
        $stmt = $this->conn->prepare("SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC");
        $stmt->execute(['%' . $query . '%', '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategoryId($category_id) {
        $stmt = $this->conn->prepare("SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id JOIN article_categories ON articles.id = article_categories.article_id WHERE article_categories.category_id = ? ORDER BY created_at DESC");
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
