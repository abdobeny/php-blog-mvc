<?php include 'includes/header.php'; ?>

<header class="hero">
    <h1>Search Results</h1>
    <a class="btn-slate" href="/">Back to Blog</a>
</header>
<hr class="hero-divider">

<main class="posts">
    <article class="post" style="padding:1.25rem 2.5rem;">
        <div class="meta" style="margin-bottom:0;">
            <span><?php echo count($articles); ?> result(s) for "<?php echo htmlspecialchars($query); ?>"</span>
        </div>
    </article>

    <?php if (empty($articles)): ?>
        <article class="post">
            <p class="excerpt" style="margin-bottom:0;">No articles found.</p>
        </article>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <article class="post">
            <h2><a href="/single/show/<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h2>
            <div class="meta">
                <span>By <?php echo htmlspecialchars($article['username']); ?></span>
                <span><?php echo date('M j, Y', strtotime($article['created_at'])); ?></span>
            </div>
            <p class="excerpt"><?php echo htmlspecialchars(substr($article['content'], 0, 220)); ?>...</p>
            <a class="btn-readmore" href="/single/show/<?php echo $article['id']; ?>">Read More</a>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
