<?php


namespace App\Command;


use App\Api\WebServer\Request\SendDevicesRequest;
use App\Api\WebServer\Response\SendDevicesResponse;
use App\Service\DevisesService;
use App\Service\WebServerApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendDevices extends Command
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var WebServerApiService
     */
    private $apiService;
    /**
     * @var DevisesService
     */
    private $devisesService;

    public function __construct(
        ?string $name = null,
        EntityManagerInterface $em,
        WebServerApiService $apiService,
        DevisesService $devisesService
    ) {
        parent::__construct($name);
        $this->em = $em;
        $this->apiService = $apiService;
        $this->devisesService = $devisesService;
    }

    protected function configure()
    {
        $this
            ->setName('app:send-devices')
            ->setDescription('Отправить девайсы');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = new SendDevicesRequest(...array_values($this->devisesService->all()));
        $response = new SendDevicesResponse($this->apiService->getApiResponse($request));
        if ($response->isSuccess()) {
            $output->writeln('Synchronisation complete');
        } else {
            $output->writeln('Synchronisation failed');
        }
    }
}