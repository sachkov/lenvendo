<?php
namespace App\Controller;
use App\Http;
use App\Service;

/**
 * SingIn and LogOut
 */
class Auth extends Common
{
    protected $middlewareCommon = ['auth'];

    protected $drawing;
    protected $user;

    public function __construct(Service\User $user)
    {
        $this->user = $user;
    }
    
    /**
     * Auth form
     */
    protected function GET():Http\ResponseInterface
    {
        $data = [];
        $request = $this->request->get('request');
        if(isset($request['backurl']) && $request['backurl']){
            $data['backurl'] = $request['backurl'];
        }

        $this->response->setTemplate('auth',$data);

        return $this->response;
    }

    /**
     * User auth (login) or logout
     */
    protected function POST():Http\ResponseInterface
    {
        $request = $this->request->get('request');

        $login = $this->user->login($request);

        //При получении пользователя, запрос надо редиректить на backurl или на Referer(header),
        // если они оба не указаны то на главную страницу
        

        $template = 'auth';
        if(empty($login) || $login['id']){
            $template = 'index';
            // if(isset($request['backurl']) && $request['backurl']){
            //     header('Request Method: GET');
            //     header('Location: '.$request['backurl'], false, 307);
            //     die;
            // }
        }

        $data = [
            'user'=>$login
        ];

        $this->response->setTemplate($template,$data);

        return $this->response;
    }
}