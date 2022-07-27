<?php
require __DIR__.'./../vendor/autoload.php';

$app = new App\Application;

$request = $app->getRequest();  // Получаем запрос

$response = $app->handle($request); // Запрос должен передоваться сначала в роут, затем в контроллер

$app->end($request, $response);   //Отправляем ответ
?>