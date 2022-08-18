<?php
namespace App\Controller;
use App\Http;
use App\Service;

/**
 * Main page controller
 */
class Index extends Common
{
    protected $middlewareCommon = ['auth'];

    protected $drawing;
    protected $user;

    public function __construct(Service\User $user)
    {
        $this->user = $user;
    }
    
    protected function GET():Http\ResponseInterface
    {
        $data = [
            'user'=>$this->request->get('user')
        ];

        $this->response->setTemplate('index',$data);

        return $this->response;
    }
}