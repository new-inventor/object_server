<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 10.11.18
 * Time: 17:01
 */

namespace App\Command;


use App\Service\ApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetIpAddress extends Command
{
    /**
     * @var ApiService
     */
    private $apiServise;

    public function __construct(?string $name = null, ApiService $apiServise)
    {
        parent::__construct($name);
        $this->apiServise = $apiServise;
    }

    protected function configure()
    {
        $this
            ->setName('app:reset-ip')
            ->setDescription('Первично инициализировать сервер объекта');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->apiServise->resetIpAddress()) {
            $output->writeln('Ip reset success.');
        } else {
            $output->writeln('Ip reset failed.');
        }
    }
}