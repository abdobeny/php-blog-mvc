<?php include 'includes/header.php'; ?>

<header class="hero">
    <h1>Articles in "<?php echo htmlspecialchars($category['name']); ?>"</h1>
    <a class="btn-slate" href="/">Back to Blog</a>
</header>
<hr class="hero-divider">

<main class="posts">
    <?php if (empty($articles)): ?>
        <article class="post">
            <p class="excerpt">No articles found in this category.</p>
        </article>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <article class="post">
            <h2><a href="/single/show/<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h2>
            <div class="meta">
                <span>By <?php echo htmlspecialchars($article['username']); ?></span>
                <span>in <a href="/category/show/<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></a></span>
                <span><?php echo date('M j, Y', strtotime($article['created_at'])); ?></span>
            </div>
            <p class="excerpt"><?php echo htmlspecialchars(substr($article['content'], 0, 220)); ?>...</p>
            <a class="btn-readmore" href="/single/show/<?php echo $article['id']; ?>">Read More</a>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
