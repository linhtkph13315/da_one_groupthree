<?php
spl_autoload_register("autoLoad");

function autoLoad($className) {
    $className = str_replace("\\", "/", $className);
    require_once dirname(__DIR__)."/$className.php";
}
require_once dirname(__DIR__)."/config/config.php";
require_once dirname(__DIR__)."/config/app.php";
require_once dirname(__DIR__)."/routes/web.php";
