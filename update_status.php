<?php
require __DIR__ . '/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
check_csrf(); $id = filter_input(INPUT_POST, 'ticket_id', FILTER_VALIDATE_INT); $status = $_POST['status'] ?? '';
$allowed = ['Новая', 'В работе', 'Закрыта'];
if (!$id || !in_array($status, $allowed, true)) { http_response_code(400); exit('Некорректные данные.'); }
$stmt = $pdo->prepare('UPDATE tickets SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?'); $stmt->execute([$status, $id]);
header('Location: dashboard.php'); exit;
