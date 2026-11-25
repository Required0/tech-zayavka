<?php
require __DIR__ . '/bootstrap.php';
if (!empty($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
page_header('Заявки в техподдержку');
?>
<section class="hero">
  <div class="hero-copy"><p class="eyebrow">ПРОСТОЙ СЕРВИС ДЛЯ ОБРАЩЕНИЙ</p><h1>Техническая проблема?<br><span>Создайте заявку.</span></h1><p>Отправьте обращение в поддержку и следите за его статусом в личном кабинете.</p><a class="button" href="register.php">Начать</a></div>
  <div class="slider" aria-label="Возможности сервиса">
    <img class="slide active" src="assets/slide1.svg" alt="Создайте заявку">
    <img class="slide" src="assets/slide2.svg" alt="Опишите проблему">
    <img class="slide" src="assets/slide3.svg" alt="Следите за статусом">
    <img class="slide" src="assets/slide4.svg" alt="Получите решение">
    <button class="slide-arrow prev" aria-label="Предыдущий слайд">‹</button><button class="slide-arrow next" aria-label="Следующий слайд">›</button>
    <div class="dots" aria-label="Выбор слайда"><button class="dot active" aria-label="Слайд 1"></button><button class="dot" aria-label="Слайд 2"></button><button class="dot" aria-label="Слайд 3"></button><button class="dot" aria-label="Слайд 4"></button></div>
  </div>
</section>
<section class="features"><article><b>01</b><h2>Создайте обращение</h2><p>Укажите тему и кратко опишите проблему.</p></article><article><b>02</b><h2>Проверяйте статус</h2><p>Заявка проходит этапы от новой до закрытой.</p></article><article><b>03</b><h2>Работа администратора</h2><p>Администратор видит список обращений и обновляет статусы.</p></article></section>
<script src="assets/slider.js"></script>
<?php page_footer(); ?>
