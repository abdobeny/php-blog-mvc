<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin &middot; PHP Blog</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$isAdminUser = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$navActive = function ($prefix) use ($path) {
    return strpos($path, $prefix) === 0 ? ' active' : '';
};
?>
<div class="admin-shell">
    <aside class="sidebar">
        <a class="sidebar-brand" href="/">PHP&nbsp;Blog</a>
        <p class="sidebar-role">
            <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
            <span class="badge badge-<?php echo htmlspecialchars($_SESSION['role'] ?? 'user'); ?>"><?php echo htmlspecialchars(ucfirst($_SESSION['role'] ?? 'user')); ?></span>
        </p>
        <nav class="sidebar-nav">
            <a class="<?php echo $navActive('/admin/index'); ?>" href="/admin/index">Dashboard</a>
            <a class="<?php echo $navActive('/admin/articles'); ?>" href="/admin/articles">Articles</a>
            <a class="<?php echo $navActive('/admin/add_article'); ?>" href="/admin/add_article">+ New Article</a>
            <?php if ($isAdminUser): ?>
                <a class="<?php echo $navActive('/admin/categories'); ?>" href="/admin/categories">Categories</a>
                <a class="<?php echo $navActive('/admin/comments'); ?>" href="/admin/comments">Comments</a>
                <a class="<?php echo $navActive('/admin/users'); ?>" href="/admin/users">Users</a>
            <?php endif; ?>
        </nav>
        <div class="sidebar-foot">
            <a href="/">View site</a>
            <a href="/login/logout">Logout</a>
        </div>
    </aside>
    <main class="admin-main">
