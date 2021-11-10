<?php

require_once dirname(__DIR__)."/vendor/autoload.php";

use app\Services\App;

date_default_timezone_set("Asia/Bangkok");

$app = new App();

define('auth', $app->auth->getAuth());

$app->run();
