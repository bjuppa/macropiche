<?php
function macropiche_blade_view()
{
    return new \duncan3dc\Laravel\BladeInstance(
        realpath(__DIR__),
        realpath(__DIR__ . '/blade-cache')
    );
}

require 'test.php';