<?php
/**
 * @author moudni Salaheddine <moudni.salaheddine@gmail.com>
 */
namespace App\Command;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

class FetchPictureCommand extends Command {

    private $entityManager;
    private $pictureRepository;
    private $nasaApiKey;
    protected static $defaultName = 'app:run:picture';

    public function __construct(EntityManagerInterface $entityManager, pictureRepository $pictureRepository, $nasaApiKey)
    {
        $this->entityManager = $entityManager;
        $this->pictureRepository = $pictureRepository;
        $this->nasaApiKey = $nasaApiKey;
        parent::__construct();

    }
    protected function configure()
    {
        $this->setDescription('Fetch the picture command from NASA API');
    }


    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $date = date('Y-m-d');
        $io = new SymfonyStyle($input, $output);

        $exist = $this->pictureRepository->findOneBy(['date' => $date]);
        if ($exist) {
            $io->error("The picture of the day $date has already been added");
            return Command::FAILURE;
        }

        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://api.nasa.gov/planetary/apod',
            ['query' => ['api_key' => $this->nasaApiKey, 'date' => $date]]
        );

        if(404 === $response->getStatusCode() ) {
            $io->error("Failed to connect to server");
            return Command::FAILURE;
        }

        $data = $response->toArray();
        $picture = new Picture();
        $picture->setDate($data['date'] ?? '');
        $picture->setExplanation($data['explanation'] ?? '');
        $picture->setMediaType($data['media_type'] ?? '');
        $picture->setTitle($data['title'] ?? '');
        $picture->setUrl($data['url'] ?? '');

        $this->entityManager->persist($picture);

        $this->entityManager->flush();

        $io->success("The NASA picture of the day $date has been saved");

        return Command::SUCCESS;
    }

}
