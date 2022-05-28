<?php
namespace App;
use App\Http;


class Router
{
    private $defaultControllerFolder = 'App\\Controller\\';
    private $defaultControllerName = 'Common';
    private $request;

    public function handle(Http\Request $request):Http\Response
    {
        $this->request = $request;

        $controller = $this->getController($request->get('path'));

        return $controller->execute();

        //echo '<br><pre>'.print_r($controller,1).'</pre><br>';

        // $response->setContent('GET:<pre>'.print_r($request->get('query'),1).'</pre></br>');
        // $response->setContent('POST:<pre>'.print_r($request->get('request'),1).'</pre></br>');
        // $response->setContent('COOKIE:<pre>'.print_r($request->get('cookies'),1).'</pre></br>');
        // $response->setContent('SERVER:<pre>'.print_r($request->get('paramenters'),1).'</pre></br>');

        // $response->setContent('PATH:<pre>'.$request->get('path').'</pre></br>');

        // $response->setContent('Controller:<pre>'.get_class($controller).'</pre></br>');
    }

    private function getController(string $path)
    {
        if(strpos($path, '/') !== false){
            $clName = str_replace('/','\\',$path);
            $arPath = explode('\\',$clName);
            $clName = '';
            foreach($arPath as $path){
                if(!$path) continue;
                if($clName) $clName .= '\\';
                $clName .= $this->realName($path);
            }
            $clName = $this->defaultControllerFolder.$clName;
            if(class_exists($clName)) 
                return new $clName($this->request);
        }

        $class = $this->defaultControllerFolder.$this->defaultControllerName;

        return new $class($this->request);

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