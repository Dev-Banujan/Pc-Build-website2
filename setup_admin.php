<?php
require_once 'includes/db.php';

$done  = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? 'admin');
    $email    = trim($_POST['email']    ?? 'admin@pcbuilder.com');
    $password = $_POST['password'] ?? '';

    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (empty($username)) {
        $error = 'Username is required.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Remove existing admin or same username
        $stmt = $conn->prepare("DELETE FROM users WHERE username = ? OR role = 'admin'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->bind_param("sss", $username, $email, $hash);
        if ($stmt->execute()) {
            $done = "Admin account created! Username: <strong>$username</strong> · Password: <strong>" . htmlspecialchars($password) . "</strong><br><a href='login.php'>→ Go to Login</a>";
        } else {
            $error = 'Failed: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setup Admin — PC Builder</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-box">
        <div class="auth-logo">
            <h1>⚡ PC BUILDER</h1>
            <p>Admin Account Setup</p>
        </div>
        <h2>Create Admin Account</h2>
        <p style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:1.2rem;">
            Run this once to set up your admin login. Delete <code>setup_admin.php</code> afterwards for security.
        </p>

        <?php if ($error): ?>
            <div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($done): ?>
            <div class="alert alert-success">✓ <?= $done ?></div>
        <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label>Admin Username</label>
                <input type="text" name="username" value="admin" required>
            </div>
            <div class="form-group">
                <label>Admin Email</label>
                <input type="email" name="email" value="admin@pcbuilder.com" required>
            </div>
            <div class="form-group">
                <label>Admin Password</label>
                <input type="password" name="password" placeholder="Choose a strong password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                Create Admin Account
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
