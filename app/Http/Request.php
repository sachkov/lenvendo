<?php
namespace app\Http;

class Request
{
    private $query;
    private $request;
    private $cookies;
    private $parameters;

    public function __construct()
    {
        $this->query = $_GET;
        $this->request = $_POST;
        $this->cookies = $_COOKIE;
        $this->paramenters = $_SERVER;
    }

    public function get(string $name)
    {
        if(isset($this->$name)) return $this->$name;
        return [];
    }
}