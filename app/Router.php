<?php
namespace App;
use App\Http;


class Router
{
    private $defaultControllerFolder = 'App\\Controller\\';
    private $defaultControllerName = 'Common';
    private $firstPageController = 'FirstPage';
    private $request;

    public function handle(Http\Request $request):Http\Response
    {
        $this->request = $request;

        $controller = $this->getController($request->get('path'));

        return $controller->execute();
    }

    private function getController(string $path)
    {
        $clName = $this->defaultControllerName;

        if($path == '/') $clName = $this->firstPageController;
        
        if(strpos($path, '/') !== false)
            $clName = $this->getClassName($path);

        $class = $this->defaultControllerFolder.$clName;
        if(class_exists($class))
            return new $class($this->request);
        else 
            throw new \Exception("Can't find class for path:'".$path."'");
    }

    private function getClassName(string $path):string
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

    private function realName(string $name):string
    {
        $res = '';
        $word = explode('_',$name);
        foreach($word as $part){
            $res .= ucfirst(strtolower($part));
        }
        return $res;
    }
}