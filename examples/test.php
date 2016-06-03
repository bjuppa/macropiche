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

    <!-- Put prism.css and prism.js files or softlinks in the folder you're viewing the test output from to test Prism syntax highlighting -->
    <link rel="stylesheet" href="prism.css">

    <!-- Put code-toggle.css or a softlink to it in the folder you're viewing the test output from to test code toggling via css -->
    <link rel="stylesheet" href="code-toggle.css">

    <!-- Put optional-style.css or a softlink to it in the folder you're viewing the test output from to test the optional stylesheet -->
    <link rel="stylesheet" href="optional-style.css">

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
    A Blade template should be rendered as the file's blade content, the generated HTML, and the HTML put into the page.
    <strong>(Blade parsing is not yet implemented!)</strong>
</p>
<?= macropiche('patterns/list.blade.php', ['title' => 'List']) ?>

<h2>Missing template</h2>
<p>A missing template should render an error message.</p>
<?= macropiche('1/2/3/missing.html') ?>

<script src="prism.js"></script>
</body>
</html>