<?php


namespace App\Command;


use App\Service\WebServerApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncElementsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var WebServerApiService
     */
    private $apiServise;

    public function __construct(?string $name = null, EntityManagerInterface $em, WebServerApiService $apiServise)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->apiServise = $apiServise;
    }

    protected function configure()
    {
        $this
            ->setName('app:sync-elements')
            ->setDescription('Sync');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->apiServise->syncElements();
    }
}