<?php
function macropiche_blade_view()
{
    $blade = new \Jenssegers\Blade\Blade(
        [realpath(__DIR__)],
        realpath(__DIR__ . '/blade-cache')
    );
    $blade->addExtension('html', 'blade');

    return $blade;
}

require 'test.php';