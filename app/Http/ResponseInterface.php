<?php
namespace App\Http;

interface ResponseInterface
{
    public function send():Response;

    public function setContent(string $content):Response;

    public function setError(int $code=500, string $message=''):Response;
}