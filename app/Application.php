<?php
namespace App;
use App\Http;


class Application
{
    public static $container;
    public static $db;

    public function __construct()
    {
        $container = new Container;

        $config = require __DIR__ . '/config/main.php';

        $container->set($config);

        self::$container = $container;

        $this->boot();
    }

    /**
     * Define env variables and application constants
     */
    private function boot()
    {
        $connectionParams = [
            'dbname' => getenv('MYSQL_DATABASE'),
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'port' => getenv('DB_PORT'),
            'host' => getenv('DB_HOST'),
            'driver' => 'pdo_mysql',
            'charset' => 'utf8'
        ];

        try {
            if(!getenv('DB_HOST')) throw new \Exception('no ENV.');
            $conf = new \Doctrine\DBAL\Configuration();

            $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $conf);
            $conn->connect();
            self::$db = $conn;
        } catch (\Exception $e) {
            echo 'connection fail '.$e->getMessage().'<br>';
        }
    }

    /**
     * Get http request data
     */
    public function getRequest():Http\RequestInterface
    {
        return self::$container->get(Http\RequestInterface::class);
    }

    /**
     * Query handling - transmit query to controller through the router
     */
    public function handle(Http\RequestInterface $request):Http\ResponseInterface
    {
        $router = self::$container->get(Router::class);

        $response = $router->handle($request);

        return $response;
    }

    /**
     * Send response
     */
    public function end(Http\ResponseInterface $response, Http\RequestInterface $request)
    {
        $response->send();
    }
}