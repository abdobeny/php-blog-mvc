<?php include 'includes/header.php'; ?>

<div class="page-head">
    <h1>Edit Article</h1>
    <a class="btn btn-light" href="/admin/articles">Cancel</a>
</div>

<div class="panel form-panel">
    <?php if (!empty($error)): ?>
        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="/admin/edit_article/<?php echo $article['id']; ?>">
        <?php echo $this->csrfField(); ?>
        <div class="form-group">
            <label for="title">Title</label>
            <input class="input" type="text" name="title" id="title" value="<?php echo htmlspecialchars($article['title']); ?>" required autofocus>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="textarea" name="content" id="content" rows="10" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="categories">Categories</label>
            <select class="select" id="categories" name="categories[]" multiple size="5">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php if (in_array($category['id'], $article_category_ids)) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="hint">Hold Ctrl (Cmd on Mac) to select more than one.</small>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
