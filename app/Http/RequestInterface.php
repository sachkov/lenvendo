<?php
namespace App\Http;

interface RequestInterface
{
    public function get(string $name);

    public function getKey(string $name, $key);

    public function getHttpMethod():string;

    public function setUser($user);
}