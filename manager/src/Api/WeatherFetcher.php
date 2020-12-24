<?php

namespace App\Api;

use App\Repository\Contracts\ClickhouseRepositoryInterface;
use DateTime;
use GuzzleHttp\Client;

/**
 * Fetch profile changes
 */
class WeatherFetcher
{
    /**
     * @var ClickhouseRepositoryInterface
     */
    protected $clickhouseRepository;

    /**
     * @var string[]
     */
    private $citiesArray = [
        'Kiev',
        'Sumy',
        'Kharkiv',
    ];

    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * WeatherFetcher constructor.
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function fetchWeather(): void
    {
        $this->clickhouseRepository->createTable('weather');
        $values = [];
        foreach ($this->citiesArray as $city) {
            $values[] = [
                time(),
                $this->getWeatherContent($city)->location->name,
                $this->getWeatherContent($city)->current->temp_c,
                new DateTime($this->getWeatherContent($city)->location->localtime)
            ];
        }

        $fields = ['event_date', 'city', 'temperature', 'created_at'];

        $this->clickhouseRepository->insert('weather', $values, $fields);
    }

    /**
     * @param string $city
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getWeatherContent(string $city)
    {
        $getParameters = [
            'key' => $_ENV['WEATHER_API_KEY'],
            'lang' => 'en',
        ];
        $weatherInfo = $this->guzzleClient->request(
            'get',
            $_ENV['WEATHER_API_URI'] .  '/current.json?' . http_build_query(array_merge($getParameters, ['q' => $city]) )
        );
        return json_decode($weatherInfo->getBody()->getContents());
    }
}
