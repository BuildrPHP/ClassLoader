<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . 'tests/Modules/AbstractModuleTestCase.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'src/ClassLoaderInitializer.php';

(new \BuildR\ClassLoader\ClassLoaderInitializer())->load();
