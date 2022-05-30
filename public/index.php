<?php

echo 'framwork1!'.PHP_EOL;

require __DIR__.'./../vendor/autoload.php';

$app = new App\Application;

$app->run();

?>