<?php
require __DIR__ . '/../vendor/autoload.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Macropiche test</title>

  <style>
    <?= include('../assets/prism-syntax-highlighter/prism.css') ?>
  </style>
  <style>
    <?= include('../assets/css/code-toggle.css') ?>
  </style>
  <style>
    <?= include('../assets/css/optional-style.css') ?>
  </style>

</head>
<body>
<h1>Macropiche test</h1>

<h2>HTML template</h2>
<p>An HTML only template should be rendered as the file's HTML content and the HTML put into the page.</p>
<?= macropiche('patterns/typography.html') ?>

<h2>PHP template</h2>
<p>A PHP template should be rendered as the file's PHP content, the generated HTML, and the HTML put into the page.</p>
<?= macropiche('./patterns/calculation.php', ['b' => 5]) ?>

<h2>Blade template</h2>
<p>
  If a blade renderer is available, a reference to a blade template should be rendered as the file's blade content, the generated HTML, and the HTML put into the page.
</p>
<?= macropiche('patterns/list.blade.php', ['title' => 'List']) ?>

<h2>Missing template</h2>
<p>A missing template should render an error message.</p>
<?= macropiche('1/2/3/missing.html') ?>

<script>
  <?= include('../assets/prism-syntax-highlighter/prism.js') ?>
</script>
</body>
</html>