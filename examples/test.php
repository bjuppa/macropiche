<?php
require __DIR__ . '/../vendor/autoload.php';
?>
<html>
<head>
    <!-- Put prism.css and prism.js files or softlinks in the folder you're viewing the test output from to test Prism syntax highlighting -->
    <link rel="stylesheet" href="prism.css"/>
</head>
<body>
<h1>Macropiche test</h1>

<h2>HTML template</h2>
<p>An HTML only template should be rendered as the file's HTML content and the HTML put into the page.</p>
<?= macropiche('patterns/typography.html') ?>

<h2>PHP template</h2>
<p>A PHP template should be rendered as the file's PHP content, the generated HTML, and the HTML put into the page.</p>
<?= macropiche('patterns/calculation.php', ['b' => 5]) ?>

<h2>Blade template</h2>
<p>
    A Blade template should be rendered as the file's blade content, the generated HTML, and the HTML put into the page.
</p>
<?= macropiche('patterns/list.blade.php', ['title' => 'List']) ?>

<h2>Missing template</h2>
<p>A missing template should render an error message.</p>
<?= macropiche('patterns/missing.html') ?>

<script src="prism.js"></script>
</body>
</html>