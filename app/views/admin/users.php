<?php include 'includes/header.php'; ?>

<div class="page-head">
    <h1>Manage Users</h1>
</div>

<div class="panel">
    <?php if (empty($users)): ?>
        <p class="empty">No users yet.</p>
    <?php else: ?>
        <table class="data">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <?php $isMe = (int) $u['id'] === (int) $_SESSION['user_id']; ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($u['username']); ?>
                        <?php if ($isMe): ?><span class="badge badge-user">you</span><?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td>
                        <?php if ($isMe): ?>
                            <span class="badge badge-<?php echo htmlspecialchars($u['role']); ?>"><?php echo htmlspecialchars(ucfirst($u['role'])); ?></span>
                        <?php else: ?>
                            <form method="POST" action="/admin/update_user_role" class="inline">
                                <?php echo $this->csrfField(); ?>
                                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                <select name="role" class="select select-inline" onchange="this.form.submit()">
                                    <option value="user" <?php if ($u['role'] === 'user') echo 'selected'; ?>>User</option>
                                    <option value="admin" <?php if ($u['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                                </select>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($u['created_at'])); ?></td>
                    <td class="right actions">
                        <?php if ($isMe): ?>
                            <span class="muted">&mdash;</span>
                        <?php else: ?>
                            <form method="POST" action="/admin/delete_user/<?php echo $u['id']; ?>" class="inline" onsubmit="return confirm('Delete this user and all of their articles and comments?');">
                                <?php echo $this->csrfField(); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
