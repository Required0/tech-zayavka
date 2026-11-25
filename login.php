<?php
require __DIR__ . '/bootstrap.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf(); $login = trim($_POST['login'] ?? ''); $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, full_name, login, password_hash, role FROM users WHERE login = ?'); $stmt->execute([$login]); $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) $error = 'Неверный логин или пароль.';
    else { session_regenerate_id(true); unset($user['password_hash']); $_SESSION['user'] = $user; header('Location: dashboard.php'); exit; }
}
page_header('Вход');
?>
<section class="form-card"><p class="eyebrow">С ВОЗВРАЩЕНИЕМ</p><h1>Войти</h1>
<?php if ($error): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>
<form method="post" class="stack-form"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
<label>Логин<input name="login" required autocomplete="username" value="<?= h($_POST['login'] ?? '') ?>"></label>
<label>Пароль<input name="password" required type="password" autocomplete="current-password"></label>
<button class="button" type="submit">Войти</button></form><p class="form-note">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p></section>
<?php page_footer(); ?>
