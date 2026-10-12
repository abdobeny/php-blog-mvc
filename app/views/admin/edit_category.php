<?php include 'includes/header.php'; ?>

<div class="page-head">
    <h1>Edit Category</h1>
    <a class="btn btn-light" href="/admin/categories">Cancel</a>
</div>

<div class="panel form-panel form-narrow">
    <?php if (!empty($error)): ?>
        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="/admin/edit_category/<?php echo $category['id']; ?>">
        <?php echo $this->csrfField(); ?>
        <div class="form-group">
            <label for="name">Name</label>
            <input class="input" type="text" name="name" id="name" value="<?php echo htmlspecialchars($category['name']); ?>" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
