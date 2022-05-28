<?php
namespace App\Controller;
use App\Http;


class Unexpected extends Common
{
    public function handle():Http\Response
    {

        return $this->response;
    }
}