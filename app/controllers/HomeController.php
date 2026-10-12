<?php

class HomeController extends BaseController {
    public function index() {
        $article_handler = $this->model('Article');

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 6;
        $offset = ($page - 1) * $limit;
        $total_articles = $article_handler->countAll();
        $total_pages = (int) ceil($total_articles / $limit);

        $articles = $article_handler->getAll($limit, $offset);

        $data = [
            'articles' => $articles,
            'total_pages' => $total_pages,
            'page' => $page,
            'article_handler' => $article_handler
        ];

        $this->view('index', $data);
    }

    public function search() {
        $query = isset($_GET['query']) ? trim($_GET['query']) : '';
        $article_handler = $this->model('Article');
        $articles = ($query === '') ? [] : $article_handler->search($query);

        $this->view('search', [
            'query' => $query,
            'articles' => $articles,
        ]);
    }

    public function notFound() {
        http_response_code(404);
        $this->view('notfound');
    }
}
