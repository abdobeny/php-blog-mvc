<?php include 'includes/header.php'; ?>

<div class="page-head">
    <h1><?php echo $isAdminUser ? 'Manage Articles' : 'My Articles'; ?></h1>
    <a class="btn btn-primary" href="/admin/add_article">+ New Article</a>
</div>

<div class="panel">
    <?php if (empty($articles)): ?>
        <p class="empty">No articles yet.</p>
    <?php else: ?>
        <table class="data">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $art): ?>
                <tr>
                    <td><a href="/single/show/<?php echo $art['id']; ?>"><?php echo htmlspecialchars($art['title']); ?></a></td>
                    <td><?php echo htmlspecialchars($art['username']); ?></td>
                    <td><?php echo date('M j, Y', strtotime($art['created_at'])); ?></td>
                    <td class="right actions">
                        <a class="btn btn-light btn-sm" href="/admin/edit_article/<?php echo $art['id']; ?>">Edit</a>
                        <form method="POST" action="/admin/delete_article/<?php echo $art['id']; ?>" class="inline" onsubmit="return confirm('Delete this article?');">
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

<?php if ($total_pages > 1): ?>
<div class="pagination-custom">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php if ($i == $page): ?>
            <span class="active"><?php echo $i; ?></span>
        <?php else: ?>
            <a href="/admin/articles?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
