<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 11.11.18
 * Time: 17:06
 */

namespace App\Command;


use App\Service\ApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendDevices extends Command
{
    /**
     * @var ApiService
     */
    private $apiService;

    public function __construct(?string $name = null, ApiService $apiService)
    {
        parent::__construct($name);
        $this->apiService = $apiService;
    }

    protected function configure()
    {
        $this
            ->setName('app:send-devices')
            ->setDescription('Отправить девайсы');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $devices = $this->apiService->getDevises();
        $result = $this->apiService->makeApiRequest('/objects/syncDevices', $devices, false);
        $output->writeln('Synchronisation complete');
    }
}