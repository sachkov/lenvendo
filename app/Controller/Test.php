<?php
namespace App\Controller;
use App\Http;


class Test extends Common
{
    protected $middlewareCommon = ['auth'];
    
    protected function GET():Http\Response
    {
        $this->response->setContent('DB_HOST: '.getenv('DB_HOST'));
        $this->response->setContent('<br>');
        $this->response->setContent('DB_PORT: '.getenv('DB_PORT'));
        $this->response->setContent('<br>');
        $this->response->setContent('TIMEZONE: '.getenv('TIMEZONE'));
        $this->response->setContent('<br>');
        $this->response->setContent('CACHE_TIME: '.getenv('CACHE_TIME'));
        $this->response->setContent('<br>');
        $this->response->setContent('MYSQL_ROOT_PASSWORD: '.getenv('MYSQL_ROOT_PASSWORD'));
        $this->response->setContent('<br>');


        $sql = "SHOW TABLES";
        $stmt = \App\Application::$db->query($sql);
        $q = [];
        while (($row = $stmt->fetchAssociative()) !== false) {
            $q[] = $row;
        }

        $this->response->setContent('<br>');

        $this->response->setContent(print_r($q,1));

        $this->response->setContent('<br><br>');

        return $this->response;
    }

    protected function POST():Http\Response
    {
        return $this->response->setContent('testtest!');
    }
}