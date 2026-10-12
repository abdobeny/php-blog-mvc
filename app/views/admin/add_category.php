<?php include 'includes/header.php'; ?>

<div class="page-head">
    <h1>New Category</h1>
    <a class="btn btn-light" href="/admin/categories">Cancel</a>
</div>

<div class="panel form-panel form-narrow">
    <?php if (!empty($error)): ?>
        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="/admin/add_category">
        <?php echo $this->csrfField(); ?>
        <div class="form-group">
            <label for="name">Name</label>
            <input class="input" type="text" name="name" id="name" value="<?php echo htmlspecialchars($old ?? ''); ?>" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
