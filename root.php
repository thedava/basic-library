<?php

define('DH_BASIC_LIB_MODE_STANDALONE', 1);
define('DH_BASIC_LIB_MODE_EMBEDDED', 2);

$mode = DH_BASIC_LIB_MODE_STANDALONE;
$file = getcwd() . DIRECTORY_SEPARATOR . 'root.php';

// Embed root.php of parent project
if ($file !== __FILE__ && file_exists($file)) {
    require_once $file;
    $mode = DH_BASIC_LIB_MODE_EMBEDDED;
} else {
    chdir(__DIR__);
    require_once __DIR__ . '/vendor/autoload.php';
}

if (!defined('_ROOT_')) {
    define('_ROOT_', getcwd());
}
define('DH_BASIC_LIB_MODE', $mode);
