<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Blog</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <nav class="site-nav">
        <a class="brand" href="/">PHP Blog</a>
        <div class="nav-right">
            <a href="/">Blog</a>
            <form class="search" action="/home/search" method="GET">
                <input type="search" name="query" placeholder="Search..." aria-label="Search"
                       value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/admin/index">Dashboard</a>
                <a href="/login/logout">Logout<span class="who">&nbsp;· <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span></a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php endif; ?>
        </div>
    </nav>
