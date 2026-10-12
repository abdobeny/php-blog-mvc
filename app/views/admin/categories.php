<?php include 'includes/header.php'; ?>

<div class="page-head">
    <h1>Manage Categories</h1>
    <a class="btn btn-primary" href="/admin/add_category">+ New Category</a>
</div>

<div class="panel">
    <?php if (empty($categories)): ?>
        <p class="empty">No categories yet.</p>
    <?php else: ?>
        <table class="data">
            <thead>
                <tr><th>Name</th><th class="right">Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td class="right actions">
                        <a class="btn btn-light btn-sm" href="/admin/edit_category/<?php echo $category['id']; ?>">Edit</a>
                        <form method="POST" action="/admin/delete_category/<?php echo $category['id']; ?>" class="inline" onsubmit="return confirm('Delete this category?');">
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
