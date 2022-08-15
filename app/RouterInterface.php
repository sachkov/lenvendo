<?php
namespace App;
use App\Http;


interface RouterInterface
{
    public function handle(Http\RequestInterface $request):Http\ResponseInterface;
}