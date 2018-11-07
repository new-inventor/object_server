<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 07.11.18
 * Time: 2:24
 */

namespace App\Command;


use App\Entity\ObjectParameter;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
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
            ->setName('app:init')
            ->setDescription('Первично инициализировать сервер объекта')
            ->addArgument('server', InputArgument::REQUIRED, 'IP сервера для инициализации');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = $input->getArgument('server');
        $client = new Client([
            'base_uri' => $server,
            'timeout' => 2.0
        ]);

        $response = $client->post($server . '/main/initObject', [
            'json' => ['token' => '1111111']
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            $output->writeln('Server responded with status ' . $statusCode . '. Error message: ' . $response->getBody()->getContents());
            return;
        }
        $objectIdFromServer = json_decode($response->getBody()->getContents(), true)['objectId'];
        $idParameter = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', 'objectId')
            ->getQuery()
            ->getResult();
        if (\count($idParameter) === 0) {
            $idParameter = new ObjectParameter('objectId', '' . json_decode($response->getBody()->getContents())['objectId']);
            $this->em->persist($idParameter);
        } else {
            $this->em->createQueryBuilder()
                ->update(ObjectParameter::class, 'op')
                ->where('op.name = \'objectId\'')
                ->set('op.value', "'$objectIdFromServer'")
                ->getQuery()
                ->execute();
        }
        $this->em->flush();
    }


}