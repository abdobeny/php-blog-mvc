<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PHP Blog</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h2>Register</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" action="/register/register">
            <?php echo $this->csrfField(); ?>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>" required autofocus>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <label for="confirm_password">Confirm password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <button type="submit" class="btn-slate">Create account</button>
        </form>
        <p class="alt">Already have an account? <a href="/login">Login</a></p>
    </div>
</body>
</html>
