<?php

function view(string $path = 'index.view.php', array $vars = [])
{
    extract($vars);

    ob_start();
    include('./views/' . $path);

    return ob_get_clean();
}