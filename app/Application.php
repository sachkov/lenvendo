<?php
namespace App;
use App\Http;


class Application
{
    /**
     * Оприделение переменных окружения и констант приложения, получение сервис-контейнера
     */
    public function define()
    {

    }

    /**
     * Получение данных http запроса
     */
    public function getRequest():Http\Request
    {
        return new Http\Request;
    }

    /**
     * Обработка запроса - передача его в контроллер через роутер
     */
    public function handle(Http\Request $request):Http\Response
    {
        $router = new Router;

        $response = $router->handle($request);

        return $response;
    }

    /**
     * Отправка ответа
     */
    public function end(Http\Request $request, Http\Response $response)
    {
        $response->send();
    }
}