<?php

class AdminController extends BaseController {

    public function __construct() {
        // Every route in this controller requires an authenticated user.
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }

        // Refresh the user's identity/role from the database so that role
        // changes (or account deletion) take effect immediately.
        $user = $this->model('User')->getById($_SESSION['user_id']);
        if (!$user) {
            $_SESSION = [];
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            }
            $this->redirect('/login');
        }
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
    }

    /** Abort unless the current user is an administrator. */
    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/admin/index');
        }
    }

    // ------------------------------------------------------------------ dashboard

    public function index() {
        $articleModel = $this->model('Article');

        if ($this->isAdmin()) {
            $data = [
                'articles_count'   => $articleModel->countAll(),
                'categories_count' => $this->model('Category')->countAll(),
                'comments_count'   => $this->model('Comment')->countAll(),
                'users_count'      => $this->model('User')->countAll(),
                'recent_articles'  => $articleModel->getRecent(5),
            ];
        } else {
            $data = [
                'my_articles_count' => $articleModel->countByUserId($_SESSION['user_id']),
                'my_articles'       => $articleModel->getByUserId($_SESSION['user_id'], 5, 0),
            ];
        }

        $this->view('admin/index', $data);
    }

    // ------------------------------------------------------------------ articles

    public function articles() {
        $article = $this->model('Article');

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        if ($this->isAdmin()) {
            $total    = $article->countAll();
            $articles = $article->getAll($limit, $offset);
        } else {
            $total    = $article->countByUserId($_SESSION['user_id']);
            $articles = $article->getByUserId($_SESSION['user_id'], $limit, $offset);
        }

        $data = [
            'articles'    => $articles,
            'total_pages' => (int) ceil($total / $limit),
            'page'        => $page,
        ];

        $this->view('admin/articles', $data);
    }

    public function add_article() {
        $category_handler = $this->model('Category');
        $data = ['categories' => $category_handler->getAll()];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $title        = trim($_POST['title'] ?? '');
            $content      = trim($_POST['content'] ?? '');
            $category_ids = array_values(array_filter(array_map('intval', (array) ($_POST['categories'] ?? []))));

            if ($title === '' || $content === '') {
                $data['error'] = 'Title and content are required.';
                $data['old']   = ['title' => $title, 'content' => $content, 'categories' => $category_ids];
            } else {
                $article = $this->model('Article');
                $article_id = $article->create($title, $content, $_SESSION['user_id']);
                if ($article_id) {
                    $article->assignCategories($article_id, $category_ids);
                    $this->redirect('/admin/articles');
                }
                $data['error'] = 'Failed to create the article.';
                $data['old']   = ['title' => $title, 'content' => $content, 'categories' => $category_ids];
            }
        }

        $this->view('admin/add_article', $data);
    }

    public function edit_article($id = null) {
        $article_handler = $this->model('Article');
        $article = $id ? $article_handler->getById($id) : null;

        if (!$article) {
            http_response_code(404);
            $this->view('notfound');
            return;
        }

        // Authors may only edit their own articles; admins may edit any.
        if (!$this->isAdmin() && (int) $article['user_id'] !== (int) $_SESSION['user_id']) {
            $this->redirect('/admin/articles');
        }

        $data = [
            'article'              => $article,
            'categories'           => $this->model('Category')->getAll(),
            'article_category_ids' => array_column($article_handler->getCategories($id), 'id'),
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $title        = trim($_POST['title'] ?? '');
            $content      = trim($_POST['content'] ?? '');
            $category_ids = array_values(array_filter(array_map('intval', (array) ($_POST['categories'] ?? []))));

            if ($title === '' || $content === '') {
                $data['error'] = 'Title and content are required.';
                $data['article']['title'] = $title;
                $data['article']['content'] = $content;
                $data['article_category_ids'] = $category_ids;
            } elseif ($article_handler->update($id, $title, $content)) {
                $article_handler->assignCategories($id, $category_ids);
                $this->redirect('/admin/articles');
            } else {
                $data['error'] = 'Failed to update the article.';
            }
        }

        $this->view('admin/edit_article', $data);
    }

    public function delete_article($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/articles');
        }
        $this->verifyCsrf();

        $article_handler = $this->model('Article');
        $article = $id ? $article_handler->getById($id) : null;

        if (!$article) {
            $this->redirect('/admin/articles');
        }

        if (!$this->isAdmin() && (int) $article['user_id'] !== (int) $_SESSION['user_id']) {
            $this->redirect('/admin/articles');
        }

        $article_handler->delete($id);
        $this->redirect('/admin/articles');
    }

    // ------------------------------------------------------------------ categories

    public function categories() {
        $this->requireAdmin();
        $this->view('admin/categories', ['categories' => $this->model('Category')->getAll()]);
    }

    public function add_category() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $name = trim($_POST['name'] ?? '');
            if ($name === '') {
                $this->view('admin/add_category', ['error' => 'The category name is required.', 'old' => $name]);
                return;
            }
            if ($this->model('Category')->create($name)) {
                $this->redirect('/admin/categories');
            }
            $this->view('admin/add_category', ['error' => 'Failed to create the category.', 'old' => $name]);
            return;
        }

        $this->view('admin/add_category');
    }

    public function edit_category($id = null) {
        $this->requireAdmin();

        $category_handler = $this->model('Category');
        $category = $id ? $category_handler->getById($id) : null;

        if (!$category) {
            http_response_code(404);
            $this->view('notfound');
            return;
        }

        $data = ['category' => $category];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $name = trim($_POST['name'] ?? '');
            if ($name === '') {
                $data['error'] = 'The category name is required.';
            } elseif ($category_handler->update($id, $name)) {
                $this->redirect('/admin/categories');
            } else {
                $data['error'] = 'Failed to update the category.';
            }
        }

        $this->view('admin/edit_category', $data);
    }

    public function delete_category($id = null) {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
        }
        $this->verifyCsrf();

        if ($id) {
            $this->model('Category')->delete($id);
        }
        $this->redirect('/admin/categories');
    }

    // ------------------------------------------------------------------ comments

    public function comments() {
        $this->requireAdmin();
        $this->view('admin/comments', ['comments' => $this->model('Comment')->getAll()]);
    }

    public function delete_comment($id = null) {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/comments');
        }
        $this->verifyCsrf();

        if ($id) {
            $this->model('Comment')->delete($id);
        }
        $this->redirect('/admin/comments');
    }

    // ------------------------------------------------------------------ users

    public function users() {
        $this->requireAdmin();
        $this->view('admin/users', ['users' => $this->model('User')->getAll()]);
    }

    public function update_user_role() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
        }
        $this->verifyCsrf();

        $id   = (int) ($_POST['id'] ?? 0);
        $role = $_POST['role'] ?? '';

        // Can't demote yourself, and only valid roles are accepted.
        if ($id && $id !== (int) $_SESSION['user_id'] && in_array($role, ['admin', 'user'], true)) {
            $this->model('User')->updateRole($id, $role);
        }

        $this->redirect('/admin/users');
    }

    public function delete_user($id = null) {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
        }
        $this->verifyCsrf();

        $id = (int) $id;
        // Can't delete your own account.
        if ($id && $id !== (int) $_SESSION['user_id']) {
            $this->model('User')->delete($id);
        }

        $this->redirect('/admin/users');
    }
}
