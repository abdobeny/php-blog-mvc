<?php include 'includes/header.php'; ?>

<header class="hero">
    <h1>Latest Blog Posts</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a class="btn-slate" href="/admin/add_article">Create Post</a>
    <?php else: ?>
        <a class="btn-slate" href="/login">Login to Create Post</a>
    <?php endif; ?>
</header>
<hr class="hero-divider">

<main class="posts">
    <?php if (empty($articles)): ?>
        <article class="post">
            <p class="excerpt">No articles have been published yet.</p>
        </article>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <article class="post">
            <h2><a href="/single/show/<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h2>
            <div class="meta">
                <span>By <?php echo htmlspecialchars($article['username']); ?></span>
                <?php
                $cats = $article_handler->getCategories($article['id']);
                if (!empty($cats)) {
                    echo '<span>in <a href="/category/show/' . $cats[0]['id'] . '">' . htmlspecialchars($cats[0]['name']) . '</a></span>';
                }
                ?>
                <span><?php echo date('M j, Y', strtotime($article['created_at'])); ?></span>
            </div>
            <p class="excerpt"><?php echo htmlspecialchars(substr($article['content'], 0, 220)); ?>...</p>
            <a class="btn-readmore" href="/single/show/<?php echo $article['id']; ?>">Read More</a>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php if (isset($total_pages) && $total_pages > 1): ?>
<div class="pagination-custom">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php if ($i == $page): ?>
            <span class="active"><?php echo $i; ?></span>
        <?php else: ?>
            <a href="/home?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
