<?php
namespace app;
use app\Http;


class Router
{
    public function handle(Http\Request $request):Http\Response
    {
        $response = new Http\Response;

        $response->setContent('<pre>'.print_r($request->get('query')).'</pre>');

        return $response;
    }
}