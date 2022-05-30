<?php
namespace App;
use App\Http;


class Application
{
    /**
     * Запуск приложения
     */
    public function run():void
    {
        try{
            $request = $this->getRequest();  // Получаем запрос

            $this->define();     // Загружаем константы? Создаем контейнер?

            $response = $this->handle($request); // Запрос должен передоваться сначала в роут, затем в контроллер

        }catch(\Exception $e){
            $response->setContent('Exception:<pre>'.$e->getMessage().'</pre></br>');
        }finally{
            $this->end($request, $response);   //Отправляем ответ
        }
    }

    /**
     * Оприделение переменных окружения и констант приложения, получение сервис-контейнера
     */
    protected function define()
    {

    }

    /**
     * Получение данных http запроса
     */
    protected function getRequest():Http\Request
    {
        return new Http\Request;
    }

    /**
     * Обработка запроса - передача его в контроллер через роутер
     */
    protected function handle(Http\Request $request):Http\Response
    {
        $router = new Router;

        $response = $router->handle($request);

        return $response;
    }

    /**
     * Отправка ответа
     */
    protected function end(Http\Request $request, Http\Response $response)
    {
        $response->send();
    }
}