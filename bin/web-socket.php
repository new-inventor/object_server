<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Actuator;
use App\Kernel;
use App\Service\WebServerApiService;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Workerman\Connection\ConnectionInterface;
use Workerman\Worker;
use Workerman\Lib\Timer;

if (!class_exists(Application::class)) {
    throw new \RuntimeException('You need to add "symfony/framework-bundle" as a Composer dependency.');
}

if (!isset($_SERVER['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
    }
    (new Dotenv())->load(__DIR__.'/../.env');
}

$input = new ArgvInput();
$env = $input->getParameterOption(['--env', '-e'], $_SERVER['APP_ENV'] ?? 'dev', true);
$kernel = new Kernel('dev', true);
$response = $kernel->handle(new Request());
global $em;
$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
$kernel->terminate(new Request(), $response);
$actuator = $em->find(Actuator::class, 1);
var_dump($actuator->getId());
//$containerBuilder = new ContainerBuilder();
//$loader = new XmlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../'));
//$loader->load('orm.xml');
//$apiService = $containerBuilder->get('doctrine.orm.entity_manager');
//var_dump($apiService);
$ws_worker = new Worker("websocket://127.0.0.1:2346");
$ws_worker->count = 4;
$ws_worker->onConnect = function($connection)
{
    echo "New connection\n";
};
$ws_worker->onMessage = function(ConnectionInterface $connection, string $data){
    WorkerMy::onMessage($connection, $data);
};
$ws_worker->onClose = function($connection)
{
    echo "Connection closed\n";
};
Worker::runAll();

class WorkerMy {
    private $value = 8;
    private $type = 'sensor';
    private $active = false;
    private $connection;
    public function onMessage(ConnectionInterface $connection, string $data){
        if(!$this->connection) {
            $this->connection = $connection;
        }
        $res = json_decode($data, true);
        if(!$res){
            $connection->send('Invalid data.' . $res);
        }
        if(is_array($res) && array_key_exists('controller', $res) && array_key_exists('sensor', $res)){
            global $em;
            $this->type = 'sensor';
            $this->active = 'true';
        }
        if(is_array($res) && array_key_exists('controller', $res) && array_key_exists('actuator', $res)){
            global $em;
            $connection->send((random_int(0, 100) / 100) + 10);
        }
//        $connection->send('Listen to controller ' . $res['controller'] . ' and sensor ' . $res['sensor']);
    }
}