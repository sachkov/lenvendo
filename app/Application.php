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
     * Оприделение переменных окружения и констант приложения, получение сервис-контейнера
     */
    public function boot()
    {
        $connectionParams = [
            'dbname' => getenv('MYSQL_DATABASE'),
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'port' => '3307',
            'host' => '192.168.223.80',
            'driver' => 'pdo_mysql',
            'charset' => 'utf8'
        ];

        try {
            $conf = new \Doctrine\DBAL\Configuration();

            $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $conf);
            $conn->connect();
            self::$db = $conn;
        } catch (\Exception $e) {
            echo 'connection fail '.$e->getMessage().'<br>';
        }
    }

    /**
     * Получение данных http запроса
     */
    public function getRequest():Http\Request
    {
        return self::$container->get(Http\Request::class);
    }

    /**
     * Обработка запроса - передача его в контроллер через роутер
     */
    public function handle(Http\Request $request):Http\Response
    {
        $router = self::$container->get(Router::class);

        $response = $router->handle($request);

        return $response;
    }

    /**
     * Отправка ответа
     */
    public function end(Http\Request $request, Http\Response $response)
    {
        $response->send();
    }
}