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
            $type = $this->realName($type);

            $class = __NAMESPACE__.'\\'.$type.'Middleware';
            if(class_exists($class)){
                (new $class($this->request, $this->response))->handle();
            }
        }
    }

    protected function realName(string $name):string
    {
        if(!$name) return $this->firstPageController;
        $res = '';
        $word = explode('_',$name);
        foreach($word as $part){
            $res .= ucfirst(strtolower($part));
        }
        return $res;
    }

}