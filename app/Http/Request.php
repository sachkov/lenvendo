<?php
namespace App\Http;

class Request implements RequestInterface
{
    protected $query=[];
    protected $request=[];
    protected $cookies=[];
    protected $parameters=[];
    protected $path='/';
    protected $user;

    public function __construct()
    {
        $this->query = $_GET;
        $this->request = $_REQUEST;
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

    public function getUser()
    {
        return $this->user;
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