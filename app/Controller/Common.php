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
    protected $middlewareCommon = ['auth'];
    protected $middleware = [
        "POST"      => [],
        "GET"       => [],
        "PUT"       => [],
        "PATCH"     => [],
        "DELETE"    => []
    ];

    public function setRequest(Http\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function setResponse(Http\Response $response)
    {
        $this->response = $response;
        return $this;
    }

    public function execute():Http\Response
    {
        $method = $this->request->getHttpMethod();

        if(!method_exists($this, $method)){
            return $this->response->setError(404);
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
        return $this->response->setError(404);
    }

    protected function GET():Http\Response
    {
        return $this->response->setError(404);
    }

    protected function PUT():Http\Response
    {
        return $this->response->setError(404);
    }

    protected function PATCH():Http\Response
    {
        return $this->response->setError(404);
    }

    protected function DELETE():Http\Response
    {
        return $this->response->setError(404);
    }
}