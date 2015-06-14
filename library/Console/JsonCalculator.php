<?php

namespace Genedys\Console;


use Genedys\Api\Request;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Genedys\Processor\JsonCalculator as JsonCalcProcessor;

class JsonCalculator extends Command
{
    const API_URI = '/challenge/backend/operation/api';

    protected function configure()
    {
        $this
            ->setName('genedys:jsoncalc')
            ->setDescription('Récupère une opératon mathématique depuis l\'API et retourne le résultat');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client([
            'base_uri' => 'http://defi.genedys.com',
            'timeout' => 2.0,
        ]);

        $apiReq = new Request($client);

        if (!$res = $apiReq->get(self::API_URI)) {
            $output->writeln('Error: on GET');
            die;
        }

        $output->writeln('Request JSON: ' . $res);
        $calculator = new JsonCalcProcessor();
        $result = $calculator->calc($res);
        $output->writeln('Result is: ' . $result);

        if (!$res = $apiReq->post(self::API_URI, json_encode(['result' => $result]))) {
            $output->writeln('Error on POST' );
            die;
        }
        $output->writeln('API response: ' . $res);
    }
}