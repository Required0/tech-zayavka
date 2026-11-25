<?php
require __DIR__ . '/bootstrap.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
check_csrf(); $title = trim($_POST['title'] ?? ''); $description = trim($_POST['description'] ?? '');
if ($title === '' || $description === '' || mb_strlen($title) > 120 || mb_strlen($description) > 2000) { $_SESSION['flash'] = 'Проверьте тему и описание заявки.'; header('Location: dashboard.php'); exit; }
$stmt = $pdo->prepare('INSERT INTO tickets (user_id, title, description) VALUES (?, ?, ?)');
$stmt->execute([$_SESSION['user']['id'], $title, $description]);
header('Location: dashboard.php'); exit;
