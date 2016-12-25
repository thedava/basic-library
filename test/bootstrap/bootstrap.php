<?php

require_once __DIR__ . '/../../vendor/autoload.php';

define('_ROOT_', realpath(__DIR__ . '/../../'));

$autoLoader = ComposerAutoloaderInitTheDava::getLoader();
$autoLoader->addPsr4('TheDavaTest\\', _ROOT_ . '/test/TheDavaTest');

$locations = \TheDava\Config::getLocations();
$locations[] = '/test/bootstrap/config/*_test.php';
\TheDava\Config::setLocations($locations);
\TheDava\Config::get(true);
