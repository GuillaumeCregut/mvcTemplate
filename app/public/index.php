<?php

use Editiel98\App;

session_start();
require_once __DIR__ . '/../vendor/Autoload.php';
$app = new App();
$app->run();
