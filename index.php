<?php

require __DIR__.'/vendor/autoload.php';

// Подключение библиотеки
$a = new sachkov\lenvendoconsolelib\Console(__DIR__);

$a->parse($argv);// Получение входных данных

// Если есть ошибки -> вывести в консоль
if($a->hasErrors()) $a->echoR($a->getErrors());

$a->handle();// Выполнить команду