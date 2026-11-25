<?php
require __DIR__ . '/bootstrap.php'; require_login();
$user = $_SESSION['user']; $isAdmin = $user['role'] === 'admin';
if ($isAdmin) {
    $stmt = $pdo->query('SELECT t.*, u.full_name, u.login FROM tickets t JOIN users u ON u.id = t.user_id ORDER BY t.created_at DESC');
} else {
    $stmt = $pdo->prepare('SELECT t.*, u.full_name, u.login FROM tickets t JOIN users u ON u.id = t.user_id WHERE t.user_id = ? ORDER BY t.created_at DESC');
    $stmt->execute([$user['id']]);
}
$tickets = $stmt->fetchAll();
page_header('Заявки');
?>
<div class="page-heading"><div><p class="eyebrow"><?= $isAdmin ? 'ПАНЕЛЬ АДМИНИСТРАТОРА' : 'ЛИЧНЫЙ КАБИНЕТ' ?></p><h1>Здравствуйте, <?= h($user['full_name']) ?>!</h1><p><?= $isAdmin ? 'Все обращения пользователей.' : 'Здесь отображаются ваши обращения.' ?></p></div></div>
<?php if (!$isAdmin): ?><section class="panel"><h2>Новая заявка</h2><form method="post" action="create_ticket.php" class="stack-form"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><label>Тема<input name="title" required maxlength="120" placeholder="Например: Не работает интернет"></label><label>Описание<textarea name="description" required maxlength="2000" rows="4" placeholder="Опишите проблему"></textarea></label><button class="button" type="submit">Отправить заявку</button></form></section><?php endif; ?>
<section class="panel"><div class="section-title"><h2><?= $isAdmin ? 'Все заявки' : 'Мои заявки' ?></h2><span class="count"><?= count($tickets) ?></span></div>
<?php if (!$tickets): ?><p class="empty">Заявок пока нет.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>№</th><?php if ($isAdmin): ?><th>Пользователь</th><?php endif; ?><th>Тема и описание</th><th>Создана</th><th>Статус</th><?php if ($isAdmin): ?><th>Действие</th><?php endif; ?></tr></thead><tbody>
<?php foreach ($tickets as $ticket): ?><tr><td>#<?= (int)$ticket['id'] ?></td><?php if ($isAdmin): ?><td><?= h($ticket['full_name']) ?><br><small>@<?= h($ticket['login']) ?></small></td><?php endif; ?><td><strong><?= h($ticket['title']) ?></strong><br><span class="muted"><?= nl2br(h($ticket['description'])) ?></span></td><td><?= h(date('d.m.Y H:i', strtotime($ticket['created_at']))) ?></td><td><span class="status status-<?= h($ticket['status']) ?>"><?= h($ticket['status']) ?></span></td><?php if ($isAdmin): ?><td><form method="post" action="update_status.php" class="status-form"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>"><select name="status" aria-label="Новый статус"><option <?= $ticket['status']==='Новая'?'selected':'' ?>>Новая</option><option <?= $ticket['status']==='В работе'?'selected':'' ?>>В работе</option><option <?= $ticket['status']==='Закрыта'?'selected':'' ?>>Закрыта</option></select><button class="small-button">Сохранить</button></form></td><?php endif; ?></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<?php page_footer(); ?>
