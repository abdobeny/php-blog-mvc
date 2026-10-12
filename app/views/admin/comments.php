<?php include 'includes/header.php'; ?>

<div class="page-head">
    <h1>Manage Comments</h1>
</div>

<div class="panel">
    <?php if (empty($comments)): ?>
        <p class="empty">No comments yet.</p>
    <?php else: ?>
        <table class="data">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Comment</th>
                    <th>Article</th>
                    <th>Posted</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($comment['username']); ?></td>
                    <td><?php echo htmlspecialchars($comment['content']); ?></td>
                    <td><a href="/single/show/<?php echo $comment['article_id']; ?>"><?php echo htmlspecialchars($comment['article_title']); ?></a></td>
                    <td><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></td>
                    <td class="right actions">
                        <form method="POST" action="/admin/delete_comment/<?php echo $comment['id']; ?>" class="inline" onsubmit="return confirm('Delete this comment?');">
                            <?php echo $this->csrfField(); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
