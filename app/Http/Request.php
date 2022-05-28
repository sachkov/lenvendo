<?php
namespace App\Http;

class Request
{
    private $query=[];
    private $request=[];
    private $cookies=[];
    private $parameters=[];
    private $path='';

    public function __construct()
    {
        $this->query = $_GET;
        $this->request = $_POST;
        $this->cookies = $_COOKIE;
        $this->parameters = $_SERVER;
        $this->preparePathInfo();
    }

    public function get(string $name)
    {
        if(isset($this->$name)) return $this->$name;
        return [];
    }

    public function getHttpMethod():string
    {
        return strtoupper($this->parameters['REQUEST_METHOD']?:'GET');
    }

    protected function preparePathInfo()
    {
        if(isset($this->parameters['REQUEST_URI']) 
            && $this->parameters['REQUEST_URI'] != ''
            && $this->parameters['REQUEST_URI'] != '/'
        ){
            $this->path = parse_url($this->parameters['REQUEST_URI'], PHP_URL_PATH);
        }
        $this->path = '/';
    }
}