<?php


namespace App\Command;


use App\Api\WebServer\Request\ResetIpRequest;
use App\Api\WebServer\Response\ResetIpResponse;
use App\Service\WebServerApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetIpAddress extends Command
{
    /**
     * @var WebServerApiService
     */
    private $apiService;

    public function __construct(?string $name = null, WebServerApiService $apiService)
    {
        parent::__construct($name);
        $this->apiService = $apiService;
    }

    protected function configure()
    {
        $this
            ->setName('app:reset-ip')
            ->setDescription('Первично инициализировать сервер объекта');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = new ResetIpRequest();
        if ((new ResetIpResponse($this->apiService->getApiResponse($request)))->isSuccess()) {
            $output->writeln('Ip reset success.');
        } else {
            $output->writeln('Ip reset failed.');
        }
    }
}