<?php


namespace App\Command;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunSocketServerCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('run:socket-server')
            ->setDescription('Первично инициализировать сервер объекта')
            ->addArgument('ip', InputArgument::REQUIRED, 'ip сервера объекта')
            ->addArgument('port', InputArgument::REQUIRED, 'port сервера сокетов');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        error_reporting(E_ALL);

        /* Permitir al script esperar para conexiones. */
        set_time_limit(0);

        /* Activar el volcado de salida implícito, así veremos lo que estamo obteniendo
        * mientras llega. */
        ob_implicit_flush();

        $address = $input->getArgument('ip');
        $port = $input->getArgument('port');

        if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            echo "socket_create() falló: razón: " . socket_strerror(socket_last_error()) . "\n";
        }

        if (socket_bind($sock, $address, $port) === false) {
            echo "socket_bind() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
        }

        if (socket_listen($sock, 5) === false) {
            echo "socket_listen() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
        }

//clients array
        $clients = array();

        do {
            $read = array();
            $read[] = $sock;

            $read = array_merge($read, $clients);

            // Set up a blocking call to socket_select
            $write = null;
            $except = null;
            $select = socket_select($read, $write, $except, $tv_sec = 5);
            if ($select < 1) {
                //    SocketServer::debug("Problem blocking socket_select?");
                continue;
            }

            // Handle new Connections
            if (in_array($sock, $read)) {

                if (($msgsock = socket_accept($sock)) === false) {
                    echo "socket_accept() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
                    break;
                }
                $clients[] = $msgsock;
                $key = array_keys($clients, $msgsock);
                /* Enviar instrucciones. */
                $msg = 'CONNECTED';
                socket_write($msgsock, $msg, strlen($msg));

            }

            // Handle Input
            foreach ($clients as $key => $client) { // for each client
                if (in_array($client, $read)) {
                    if (false === ($buf = socket_read($client, 2048, PHP_NORMAL_READ))) {
                        echo "socket_read() falló: razón: " . socket_strerror(socket_last_error($client)) . "\n";
                        break 2;
                    }
                    if (!$buf = trim($buf)) {
                        continue;
                    }
                    if ($buf == 'quit') {
                        unset($clients[$key]);
                        socket_close($client);
                        break;
                    }
                    if ($buf == 'shutdown') {
                        socket_close($client);
                        break 2;
                    }
                    $talkback = 'OK';
                    socket_write($client, $talkback, strlen($talkback));
                    echo "$buf\n";
                }

            }
        } while (true);

        socket_close($sock);
    }
}