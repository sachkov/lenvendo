<?php
namespace App;
use App\Http;


class Router implements RouterInterface
{
    protected $defaultControllerFolder = 'App\\Controller\\';
    protected $defaultControllerName = 'Common';
    protected $firstPageController = 'Index';
    protected $request;
    protected $response;

    public function __construct(Http\ResponseInterface $response)
    {
        $this->response = $response;
    }
    
    /**
     * Get and execute Controller
     * @param App\Http\RequestInterface
     * @return App\Http\ResponseInterface
     */
    public function handle(Http\RequestInterface $request):Http\ResponseInterface
    {
        $this->request = $request;

        $controller = $this->getController($request->get('path'));

        return $controller->execute();
    }

    protected function getController(string $path)
    {
        $clName = $this->defaultControllerName;

        if(strpos($path, '/') !== false )
            $clName = $this->getClassName($path);

        $class = $this->defaultControllerFolder.$clName;
        if(!class_exists($class)){
            $class = $this->defaultControllerFolder.$this->defaultControllerName;
        }
        $controller = Application::$container->get($class);

        $controller
            ->setRequest($this->request)
            ->setResponse($this->response);

        return $controller;         
    }

    protected function getClassName(string $path):string
    {
        $arPath = explode('/',$path);
        $clName = '';
        foreach($arPath as $k=>$name){
            if(!$clName && !$name && $k<(count($arPath)-1)) continue;
            if($clName) $clName .= '\\';
            $clName .= $this->realName($name);
        }
        return $clName;
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