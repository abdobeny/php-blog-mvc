<?php include 'includes/header.php'; ?>

<main class="posts" style="padding-top: 2.5rem;">
    <article class="post">
        <h2><?php echo htmlspecialchars($article['title']); ?></h2>
        <div class="meta">
            <?php if (!empty($article['username'])): ?>
                <span>By <?php echo htmlspecialchars($article['username']); ?></span>
            <?php endif; ?>
            <?php
            $cats = $article_handler->getCategories($article['id']);
            if (!empty($cats)) {
                echo '<span>in <a href="/category/show/' . $cats[0]['id'] . '">' . htmlspecialchars($cats[0]['name']) . '</a></span>';
            }
            ?>
            <span><?php echo date('M j, Y', strtotime($article['created_at'])); ?></span>
        </div>
        <p class="excerpt"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
    </article>

    <article class="post">
        <h3>Leave a Comment:</h3>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form class="comment-form" method="POST" action="/single/show/<?php echo $article['id']; ?>">
                <?php echo $this->csrfField(); ?>
                <textarea name="content" rows="4" placeholder="Write your comment..." required></textarea>
                <button type="submit" class="btn-slate">Submit</button>
            </form>
        <?php else: ?>
            <p class="excerpt" style="margin-bottom:0;">Please <a href="/login" class="link-pink">login</a> to leave a comment.</p>
        <?php endif; ?>
    </article>

    <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
        <article class="post">
            <h3><?php echo htmlspecialchars($comment['username']); ?></h3>
            <div class="meta">
                <span><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></span>
            </div>
            <p class="excerpt" style="margin-bottom:0;"><?php echo htmlspecialchars($comment['content']); ?></p>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
