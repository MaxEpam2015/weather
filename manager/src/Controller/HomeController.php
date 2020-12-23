<?php

namespace App\Controller;

use App\Repository\Contracts\ClickhouseRepositoryInterface;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Homepage
 */
class HomeController extends AbstractController
{
    /**
     * @var ClickhouseRepositoryInterface
     */
    protected $clickhouseRepository;

    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * HomeController constructor.
     * @param ClickhouseRepositoryInterface $clickhouseRepository
     * @param Client $guzzleClient
     */
    public function __construct(
        ClickhouseRepositoryInterface $clickhouseRepository,
        Client $guzzleClient
    )
    {
        $this->clickhouseRepository = $clickhouseRepository;
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $statement = $this->clickhouseRepository->select('weather');
        $this->clickhouseRepository->executeAsync();
        echo '<pre>';
        print_r($statement->rows());
        return new Response('', Response::HTTP_OK);
    }
}
