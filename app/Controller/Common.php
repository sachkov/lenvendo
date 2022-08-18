<?php
namespace App\Controller;
use App\Http;
use App\Middleware\Common as Middleware;

/**
 * Base class for all controllers
 * 1. Constructor with dependencies implemented in subclasses.
 * 2. Main method execute which was called in router.
 * 3. Defines which method must be called based on request http method.
 * 4. Added middleware stages (redefines in subclasses).
 * 5. Called request handler, which reimplemented in subclass.
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

    public function setRequest(Http\Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function setResponse(Http\ResponseInterface $response)
    {
        $this->response = $response;
        return $this;
    }

    public function execute():Http\ResponseInterface
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

    protected function POST():Http\ResponseInterface
    {
        return $this->response->setError(404);
    }

    protected function GET():Http\ResponseInterface
    {
        return $this->response->setError(404);
    }

    protected function PUT():Http\ResponseInterface
    {
        return $this->response->setError(404);
    }

    protected function PATCH():Http\ResponseInterface
    {
        return $this->response->setError(404);
    }

    protected function DELETE():Http\ResponseInterface
    {
        return $this->response->setError(404);
    }
}