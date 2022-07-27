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
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ];
        self::$db = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
        
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