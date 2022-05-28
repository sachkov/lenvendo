<?php
namespace App\Controller;
use App\Http;


class Test extends Common
{
    public function handle():Http\Response
    {
        $host = '192.168.1.7';
        $port = '3307';
        $db   = getenv('MYSQL_DATABASE');
        $user = getenv('MYSQL_USER');
        $pass = getenv('MYSQL_PASSWORD');
        $charset = 'utf8';
    
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new \PDO($dsn, $user, $pass, $opt);

        $sql = 'CALL get2(:id)';

        $query = $this->request->get('query');

        if(isset($query['id'])){
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array('id' => $query['id']));
            foreach($stmt as $row){
                $this->response->setContent( '<pre>'.print_r($row,1).'</pre><br>');
            }
        }
        

        return $this->response;
    }
}