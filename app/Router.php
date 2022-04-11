<?php
namespace App;
use App\Http;


class Router
{
    public function handle(Http\Request $request):Http\Response
    {
        $response = new Http\Response;

        $response->setContent('<pre>'.print_r($request->get('query'),1).'</pre>');

        $response->setContent('<pre>'.getenv('APP_KEY').'</pre>');

        return $response;
    }
}