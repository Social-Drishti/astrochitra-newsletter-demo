<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid username or password';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | AstroChitra</title>
<style>
  :root{--cream:#faf6ee;--paper:#fffdf8;--ink:#33270e;--muted:#7a6a4f;--gold:#c9a227;--gold-light:#f0d98a;
        --crimson:#ae172a;--night:#2b1005;--line:#e4d9c3;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:Georgia,'Times New Roman',serif;background:var(--night);min-height:100vh;
       display:flex;justify-content:center;align-items:center;padding:20px;}
  .card{background:var(--paper);border-radius:16px;padding:38px 32px;width:100%;max-width:360px;
        border-top:4px solid var(--gold);box-shadow:0 18px 40px rgba(0,0,0,.45);}
  h1{font-size:1.3rem;color:var(--night);text-align:center;margin-bottom:4px;}
  .sub{font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);
       text-align:center;margin-bottom:24px;}
  label{display:block;font-size:.8rem;font-weight:bold;color:var(--cocoa,#471d0b);margin-bottom:14px;}
  input{width:100%;padding:11px 13px;margin-top:5px;font-size:.95rem;font-family:inherit;color:var(--ink);
        background:var(--cream);border:1px solid var(--line);border-radius:9px;outline:none;}
  input:focus{border-color:var(--gold);}
  button{width:100%;padding:12px;background:var(--crimson);color:#fff;border:none;border-radius:999px;
         font-family:inherit;font-weight:bold;font-size:.95rem;cursor:pointer;border:2px solid var(--gold);}
  button:hover{background:#7d0f1d;}
  .error{background:#fbeee9;border:1px solid #eec9c0;color:var(--crimson);border-radius:9px;
         padding:10px 14px;font-size:.85rem;text-align:center;margin-bottom:16px;}
</style>
</head>
<body>
<form class="card" method="post" autocomplete="off">
  <h1>AstroChitra</h1>
  <p class="sub">Newsletter Admin</p>
  <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <label>Username
    <input type="text" name="username" required autofocus>
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <button type="submit">Sign In</button>
</form>
</body>
</html>
