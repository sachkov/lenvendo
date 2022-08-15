<?php
namespace App\Http;

interface ResponseInterface
{
    public function send():ResponseInterface;

    public function setContent(string $content):ResponseInterface;

    public function setError(int $code=500, string $message=''):ResponseInterface;

    public function setTemplate(string $name='index', array $data=[]):ResponseInterface;
}