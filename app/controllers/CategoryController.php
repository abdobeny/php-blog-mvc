<?php

class CategoryController extends BaseController {
    public function show($id = null) {
        $article_handler = $this->model('Article');
        $category_handler = $this->model('Category');

        $category = $id ? $category_handler->getById($id) : null;

        if (!$category) {
            http_response_code(404);
            $this->view('notfound');
            return;
        }

        $articles = $article_handler->getByCategoryId($id);

        $data = [
            'category' => $category,
            'articles' => $articles
        ];

        $this->view('category_articles', $data);
    }
}
