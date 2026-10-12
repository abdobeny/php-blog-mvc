<?php include 'includes/header.php'; ?>

<div class="page-head">
    <h1><?php echo $isAdminUser ? 'Admin Dashboard' : 'My Dashboard'; ?></h1>
    <a class="btn btn-primary" href="/admin/add_article">+ New Article</a>
</div>

<?php if ($isAdminUser): ?>

    <div class="stat-grid">
        <div class="stat">
            <div class="num"><?php echo (int) $articles_count; ?></div>
            <div class="label">Articles</div>
            <a class="go" href="/admin/articles">Manage &rarr;</a>
        </div>
        <div class="stat">
            <div class="num"><?php echo (int) $categories_count; ?></div>
            <div class="label">Categories</div>
            <a class="go" href="/admin/categories">Manage &rarr;</a>
        </div>
        <div class="stat">
            <div class="num"><?php echo (int) $comments_count; ?></div>
            <div class="label">Comments</div>
            <a class="go" href="/admin/comments">Manage &rarr;</a>
        </div>
        <div class="stat">
            <div class="num"><?php echo (int) $users_count; ?></div>
            <div class="label">Users</div>
            <a class="go" href="/admin/users">Manage &rarr;</a>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>Recent Articles</h2>
            <a class="btn btn-light btn-sm" href="/admin/articles">View all</a>
        </div>
        <?php if (empty($recent_articles)): ?>
            <p class="empty">No articles yet. <a href="/admin/add_article">Create the first one.</a></p>
        <?php else: ?>
            <table class="data">
                <thead>
                    <tr><th>Title</th><th>Author</th><th>Published</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_articles as $a): ?>
                    <tr>
                        <td><a href="/admin/edit_article/<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['title']); ?></a></td>
                        <td><?php echo htmlspecialchars($a['username']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($a['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php else: ?>

    <div class="stat-grid">
        <div class="stat">
            <div class="num"><?php echo (int) $my_articles_count; ?></div>
            <div class="label">My Articles</div>
            <a class="go" href="/admin/articles">Manage &rarr;</a>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h2>My Recent Articles</h2>
            <a class="btn btn-light btn-sm" href="/admin/articles">View all</a>
        </div>
        <?php if (empty($my_articles)): ?>
            <p class="empty">You haven't written any articles yet. <a href="/admin/add_article">Create your first one.</a></p>
        <?php else: ?>
            <table class="data">
                <thead>
                    <tr><th>Title</th><th>Published</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($my_articles as $a): ?>
                    <tr>
                        <td><a href="/admin/edit_article/<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['title']); ?></a></td>
                        <td><?php echo date('M j, Y', strtotime($a['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
