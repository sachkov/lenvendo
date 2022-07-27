<?php
namespace App\Controller;
use App\Http;
use App\Middleware\Common as Middleware;

/**
 * 1. Конструктор получает запрос
 * 2. Вызывается главный метод execute
 * 3. Главный метод оприделяет какой метод вызвать на основании метода запроса
 * 4. Добавляет в мидлвэар этапы(типы соответствующие запросу)
 * 5. Вызывает обработчик запроса, соответствующий методу (реализован в наследнике)
 */

class Common
{
    protected $request;
    protected $response;
    protected $middlewareCommon = [];
    protected $middleware = [
        "POST"      => [],
        "GET"       => [],
        "PUT"       => [],
        "PATCH"     => [],
        "DELETE"    => []
    ];

    public function __construct(Http\Request $request)
    {
        $this->request = $request;
        $this->response = new Http\Response;
    }

    public function execute():Http\Response
    {
        $method = $this->request->getHttpMethod();

        if(!method_exists($this, $method)){
            return $this->response->setError(501);
        }

        $middleware = $this->middlewareCommon;
        if(isset($this->middleware[$method])) 
            $middleware = array_merge($middleware, $this->middleware[$method]);

        if(count($middleware)){
            (new Middleware($this->request, $this->response))->execute($middleware);
        }

        return $this->$method();
    }

    protected function POST():Http\Response
    {
        return $this->response->setError(501);
    }

    protected function GET():Http\Response
    {
        return $this->response->setError(502);
    }

    protected function PUT():Http\Response
    {
        return $this->response->setError(503);
    }

    protected function PATCH():Http\Response
    {
        return $this->response->setError(504);
    }

    protected function DELETE():Http\Response
    {
        return $this->response->setError(505);
    }
}