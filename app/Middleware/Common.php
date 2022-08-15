<?php
namespace App\Middleware;
use App\Http;


class Common
{
    protected $request;
    protected $response;

    public function __construct(Http\RequestInterface $request, Http\ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function execute(array $types)
    {
        foreach($types as $type){
            $type = ucfirst(strtolower($type));

            $class = __NAMESPACE__.'\\'.$type.'Middleware';
            if(class_exists($class)){
                (new $class($this->request, $this->response))->handle();
            }
        }
    }

}