<?php

namespace App\Command;

use App\Api\WeatherFetcher;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetWeatherCommand extends Command
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var WeatherFetcher
     */
    private $weatherFetcher;

    public function __construct(
        WeatherFetcher $weatherFetcher,
        LoggerInterface $logger
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->weatherFetcher = $weatherFetcher;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('app:weather');
        $this->setDescription('Get Weather for 3 cities');
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('start receiving weather.');
        $this->weatherFetcher->fetchWeather();
        $this->logger->info('finished receiving weather.');
        return Command::SUCCESS;
    }
}
