<?php
require_once __DIR__ . '/config.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Не удалось подключиться к базе данных. Проверьте XAMPP и настройки в config.php.');
}
function h($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function check_csrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $sent)) {
        http_response_code(403); exit('Проверка формы не пройдена. Обновите страницу и попробуйте снова.');
    }
}
function require_login(): void {
    if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
}
function require_admin(): void {
    require_login();
    if (($_SESSION['user']['role'] ?? '') !== 'admin') { http_response_code(403); exit('Доступ только для администратора.'); }
}
function page_header(string $title): void {
    $user = $_SESSION['user'] ?? null;
    echo '<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . h($title) . ' — ТехЗаявка</title><link rel="stylesheet" href="assets/style.css"></head><body>';
    echo '<header class="topbar"><a class="brand" href="index.php">ТехЗаявка</a><nav>';
    if ($user) {
        echo '<a href="dashboard.php">Заявки</a>';
        if (($user['role'] ?? '') === 'admin') echo '<span class="role">Администратор</span>';
        echo '<form class="inline" method="post" action="logout.php"><input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '"><button class="link-button">Выйти</button></form>';
    } else {
        echo '<a href="login.php">Войти</a><a class="nav-cta" href="register.php">Регистрация</a>';
    }
    echo '</nav></header><main class="container">';
}
function page_footer(): void { echo '</main><footer class="footer">Учебный проект «ТехЗаявка»</footer></body></html>'; }
