<?php

echo 'test2';
/*
require __DIR__.'/vendor/autoload.php';

// Подключение библиотеки
$a = new sachkov\lenvendoconsolelib\Console(__DIR__);

$a->parse($argv);// Получение входных данных

// Если есть ошибки -> вывести в консоль
if($a->hasErrors()) $a->echoR($a->getErrors());

$a->handle();// Выполнить команду
*/
require __DIR__.'/vendor/autoload.php';

$app = new App\Application;

$app->define();     // Загружаем константы? Создаем контейнер?

$request = $app->getRequest();  // Получаем запрос

$response = $app->handle($request); // Запрос должен передоваться сначала в роут, затем в контроллер

$app->end($request, $response);   //Отправляем ответ

?>