<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 07.11.18
 * Time: 2:24
 */

namespace App\Command;


use App\Entity\ObjectParameter;
use App\Service\ApiServise;
use App\Service\AppService;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;

class InitCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ApiServise
     */
    private $apiServise;

    public function __construct(?string $name = null, EntityManagerInterface $em, ApiServise $apiServise)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->apiServise = $apiServise;
    }

    protected function configure()
    {
        $this
            ->setName('app:init')
            ->setDescription('Первично инициализировать сервер объекта')
            ->addArgument('objectTitle', InputArgument::REQUIRED, 'Название объекта')
            ->addArgument('objectAddress', InputArgument::REQUIRED, 'Аддрес объекта')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $objectId = $this->apiServise->initObject(
            $input->getArgument('objectTitle'),
            $input->getArgument('objectAddress')
        );
        $this->apiServise->syncStructure($objectId);
    }
}