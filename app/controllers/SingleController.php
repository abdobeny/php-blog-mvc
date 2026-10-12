<?php

class SingleController extends BaseController {
    public function show($id = null) {
        $article_handler = $this->model('Article');
        $comment_handler = $this->model('Comment');

        $article = $id ? $article_handler->getById($id) : null;

        if (!$article) {
            http_response_code(404);
            $this->view('notfound');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->isLoggedIn()) {
                $this->redirect('/login');
            }

            $this->verifyCsrf();

            $content = trim($_POST['content'] ?? '');
            if ($content !== '') {
                $comment_handler->create($content, $_SESSION['user_id'], $article['id']);
            }
            $this->redirect('/single/show/' . $article['id']);
        }

        $comments = $comment_handler->getByArticleId($article['id']);

        $data = [
            'article' => $article,
            'comments' => $comments,
            'article_handler' => $article_handler
        ];

        $this->view('single', $data);
    }
}
