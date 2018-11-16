<?php


namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorCommand extends Command
{

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:generate')
            ->setDescription('Первично инициализировать сервер объекта')
            ->addArgument('device', InputArgument::REQUIRED, 'sensor | actuator | sensor_log | actuator_log | controller')
            ->addOption('log-type', 'l', InputOption::VALUE_OPTIONAL, 'bit | int')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $device = $input->getArgument('device');
        $logType = $input->getOption('log-type');
        if($device === 'sensor'){

        }elseif($device === 'actuator'){

        }elseif($device === 'controller'){

        }elseif($device === 'actuator_log'){

        }elseif($device === 'sensor_log'){

        }
    }
}