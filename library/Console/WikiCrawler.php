<?php

namespace Genedys\Console;


use Genedys\Api\Request;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Genedys\Processor\WikiCrawler as WikiCrawlerProcessor;

class WikiCrawler extends Command
{
    const API_URI = '/challenge/backend/crawler/api';

    protected function configure()
    {
        $this
            ->setName('genedys:wikicrawl')
            ->setDescription('Récupère la date d\'appartion du langage programmation fourni par l\'API');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client([
            'base_uri' => 'http://defi.genedys.com',
            'timeout' => 2.0,
        ]);

        $apiReq = new Request($client);
        $req = $apiReq->get(self::API_URI);
        if (!$req) {
            $output->writeln('Error: on GET');
            die;
        }

        $language = json_decode($req, true)['language'];
        $output->writeln('Language: ' . $language);

        $result = $this->getYear($language);
        if (!$result) {
            $output->writeln('Error: can\'t get the year');
            die;
        }

        $response = $apiReq->post(self::API_URI, json_encode(['creation' => $result]));
        if (!$response) {
            $output->writeln('Error: on POST');
            die;
        }
        $output->writeln('API response: ' . $response);
    }

    private function getYear($language)
    {
        $client = new Client([
            'base_uri' => 'http://fr.wikipedia.org',
            'timeout' => 2.0,
        ]);

        $crawler = new Crawler();

        $wikiCrawler = new WikiCrawlerProcessor($crawler, $client);
        $res = $wikiCrawler->getYear($language);

        return $res;
    }
}