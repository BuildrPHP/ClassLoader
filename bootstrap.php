<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . 'src/ClassLoaderInitializer.php';

(new \BuildR\ClassLoader\ClassLoaderInitializer())->load();
