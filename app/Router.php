<?php
namespace App;
use App\Http;


class Router implements RouterInterface
{
    protected $defaultControllerFolder = 'App\\Controller\\';
    protected $defaultControllerName = 'Common';
    protected $firstPageController = 'FirstPage';
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

        if($path == '/') $clName = $this->firstPageController;
        else if(strpos($path, '/') !== false )
            $clName = $this->getClassName($path);

        $class = $this->defaultControllerFolder.$clName;
        if(class_exists($class)){
            $controller = Application::$container->get($class);

            $controller
                ->setRequest($this->request)
                ->setResponse($this->response);

            return $controller;
        }else{
            throw new \Exception(
                "Can't find class for path:'".$path."'. "
                ."Class($class) not found. -".$clName."-"
            );
        }           
    }

    protected function getClassName(string $path):string
    {
        $clName = str_replace('/','\\',$path);
        $arPath = explode('\\',$clName);
        $clName = '';
        foreach($arPath as $path){
            if(!$path) continue;
            if($clName) $clName .= '\\';
            $clName .= $this->realName($path);
        }
        return $clName;
    }

    protected function realName(string $name):string
    {
        $res = '';
        $word = explode('_',$name);
        foreach($word as $part){
            $res .= ucfirst(strtolower($part));
        }
        return $res;
    }
}