<?php
$root = dirname(__DIR__);
// Kickstart the framework
$f3=require("{$root}/lib/base.php");

$f3->config("{$root}/app/config.ini" , true);

$f3->run();