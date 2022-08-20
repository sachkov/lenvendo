<?php
namespace App\Controller\Tabor;

use App\Http;
use App\Controller;
use App\Service\Tabor as ServiceTabor;

class Index extends Controller\Common
{
    protected $service;

    public function __construct(ServiceTabor\Sum $sum)
    {
        $this->service = $sum;
    }
    

    /**
     * Обработка запроса
     */
    protected function PUT():Http\ResponseInterface
    {
        $request = $this->request->get('request');

        $idemKey = false;
        if(isset($request['key']) && $request['key']) $idemKey = true;

        $idemVal = 0;
        if(isset($request['val']) && $request['val']) $idemVal = (int)$request['val'];

        $sum = $this->service->getSum($idemVal, $idemKey);

        $this->response->setContent($sum);

        return $this->response;
    }
}