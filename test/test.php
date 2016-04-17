<?php
require __DIR__ . '/../vendor/autoload.php';
?>
<html>
<body>
<h1>Macropiche test</h1>
<h2>HTML</h2>
<?= macropiche('patterns/typography.html') ?>
<h2>PHP</h2>
<?= macropiche('patterns/calculation.php', ['b' => 5]) ?>
<h2>Blade</h2>
<?= macropiche('patterns/list.blade.php', ['title' => 'List']) ?>
</body>
</html>