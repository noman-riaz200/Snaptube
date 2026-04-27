<?php
/**
 * SnapTube Admin - Secure Login Page
 */

require_once __DIR__ . '/../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf()) {
        $error = 'Invalid security token. Please refresh and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT password_hash FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

$token = csrfToken();
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - SnapTube</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">

  <div class="admin-card">
    <div style="text-align:center;margin-bottom:1.5rem;">
      <div class="logo" style="justify-content:center;font-size:1.75rem;margin-bottom:0.5rem;">
        <span class="logo-icon">▶</span>
        <span>SnapTube</span>
      </div>
    </div>
    <h1>Admin Login</h1>
    <p>Sign in to manage site settings</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?php echo e($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo e($token); ?>">

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus autocomplete="username" placeholder="admin">
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
      </div>

      <button type="submit" class="btn btn-primary">Sign In</button>
    </form>
  </div>

</body>
</html>

