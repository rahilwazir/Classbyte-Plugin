<?php
namespace CB;

function spl_autoloader($class)
{
    $file = CB_DIR . 'classes/' . $class . '.php';

    if (file_exists($file)) {
        include_once $file;
    }
}

spl_autoload_register(__NAMESPACE__ . '\spl_autoloader');