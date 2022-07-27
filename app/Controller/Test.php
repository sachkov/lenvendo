<?php
namespace App\Controller;
use App\Http;


class Test extends Common
{
    protected $middlewareCommon = ['auth'];
    
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

    protected function GET():Http\Response
    {
        $this->response->setContent(getenv('MYSQL_USER'));

        $reflector = new \ReflectionClass(Common::class);

        $constructor = $reflector->getConstructor();

        if(is_null($constructor)) $this->response->setContent('constructor is null.'.PHP_EOL);

        $parameters = $constructor->getParameters();

        $this->response->setContent(print_r($parameters,1));

        foreach ($parameters as $parameter) {
			// get the type hinted class
			$dependency = $parameter->getClass();
			if ($dependency === NULL) {
				$this->response->setContent($parameter->name.' NO dependency.'.PHP_EOL);
			} else {
				// get dependency resolved
				$this->response->setContent(print_r($dependency,1).PHP_EOL);
			}
		}

        $this->response->setContent('<br>');

        $this->response->setContent(print_r(\App\Application::$container,1));

        $sql = "SHOW TABLES";
        $stmt = \App\Application::$db->query($sql);

        while (($row = $stmt->fetchAssociative()) !== false) {
            $q[] = $row;
        }

        $this->response->setContent('<br>');

        $this->response->setContent(print_r($q,1));

        $this->response->setContent('<br><br>');

        return $this->response;
    }
}