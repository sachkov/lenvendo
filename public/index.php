<?php
require __DIR__.'./../vendor/autoload.php';

$app = new App\Application;

$request = $app->getRequest();

$response = $app->handle($request); // Application send response to route, then into controller

$app->end($response, $request);   // send the response
?>