<?php
namespace App\Http;

class Request
{
    private $query=[];
    private $request=[];
    private $cookies=[];
    private $parameters=[];
    private $path='/';
    private $user;

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
        if(!$name || !isset($this->$name)) return [];

        return $this->$name;
    }

    public function getKey(string $name, $key)
    {
        $input = $this->get($name);

        if(empty($input)) return null;

        if(!isset($key) || !isset($input[$key])) return null;
        
        return $input[$key];
    }

    public function getHttpMethod():string
    {
        if(! isset($this->parameters['REQUEST_METHOD']) ) return 'GET';
        return strtoupper($this->parameters['REQUEST_METHOD']?:'GET');
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    protected function preparePathInfo()
    {
        $this->path = '/';

        if(isset($this->parameters['REQUEST_URI']) 
            && $this->parameters['REQUEST_URI'] != ''
            && $this->parameters['REQUEST_URI'] != '/'
        ){
            $this->path = parse_url($this->parameters['REQUEST_URI'], PHP_URL_PATH);
        }
    }
}