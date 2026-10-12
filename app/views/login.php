<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PHP Blog</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h2>Login</h2>
        <?php if (!empty($_GET['registered'])): ?>
            <div class="alert-ok">Your account has been created. You can now sign in.</div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="/login/login">
            <?php echo $this->csrfField(); ?>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>" required autofocus>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" class="btn-slate">Login</button>
        </form>
        <p class="alt">No account? <a href="/register">Register</a></p>
    </div>
</body>
</html>
