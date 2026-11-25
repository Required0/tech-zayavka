<?php
require __DIR__ . '/bootstrap.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $name = trim($_POST['full_name'] ?? ''); $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? ''); $login = trim($_POST['login'] ?? ''); $password = $_POST['password'] ?? '';
    if ($name === '' || $phone === '' || $email === '' || $login === '' || $password === '') $error = 'Заполните все поля.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Введите корректный email.';
    elseif (!preg_match('/^[0-9+()\-\s]{6,25}$/u', $phone)) $error = 'Проверьте номер телефона.';
    elseif (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $login)) $error = 'Логин: 3–30 символов, латинские буквы, цифры и _.';
    elseif (strlen($password) < 8) $error = 'Пароль должен содержать не менее 8 символов.';
    else {
        $check = $pdo->prepare('SELECT id FROM users WHERE login = ?'); $check->execute([$login]);
        if ($check->fetch()) $error = 'Такой логин уже занят.';
        else {
            $stmt = $pdo->prepare('INSERT INTO users (full_name, phone, email, login, password_hash) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $phone, $email, $login, password_hash($password, PASSWORD_DEFAULT)]);
            $_SESSION['user'] = ['id' => (int)$pdo->lastInsertId(), 'full_name' => $name, 'login' => $login, 'role' => 'user'];
            session_regenerate_id(true); header('Location: dashboard.php'); exit;
        }
    }
}
page_header('Регистрация');
?>
<section class="form-card"><p class="eyebrow">НОВЫЙ АККАУНТ</p><h1>Регистрация</h1>
<?php if ($error): ?><div class="alert error"><?= h($error) ?></div><?php endif; ?>
<form method="post" class="stack-form"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
<label>ФИО<input name="full_name" required maxlength="120" value="<?= h($_POST['full_name'] ?? '') ?>"></label>
<label>Телефон<input name="phone" required type="tel" maxlength="25" value="<?= h($_POST['phone'] ?? '') ?>"></label>
<label>Email<input name="email" required type="email" maxlength="190" value="<?= h($_POST['email'] ?? '') ?>"></label>
<label>Логин<input name="login" required minlength="3" maxlength="30" autocomplete="username" value="<?= h($_POST['login'] ?? '') ?>"></label>
<label>Пароль<input name="password" required type="password" minlength="8" autocomplete="new-password"></label>
<button class="button" type="submit">Создать аккаунт</button></form><p class="form-note">Уже зарегистрированы? <a href="login.php">Войти</a></p></section>
<?php page_footer(); ?>
