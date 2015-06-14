<?php

namespace Genedys\Processor;


use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class WikiCrawler
{
    const BASE_WIKI_PAGE = '/wiki/Liste_des_langages_de_programmation';

    /** @var  Crawler */
    private $crawler;

    /** @var Client  */
    private $client;

    /**
     * @param Crawler $crawler
     * @param Client $client
     */
    public function __construct(Crawler $crawler, Client $client)
    {
        $this->crawler = $crawler;
        $this->client = $client;
    }

    /**
     * @param string $language
     * @return bool|int
     */
    public function getYear($language)
    {
        $pageUri = $this->getLanguagePageUri($language);
        if ($pageUri) {
            return $this->getLanguageYear($pageUri);
        }

        return false;
    }

    /**
     * @param string $language
     * @return bool|string
     */
    private function getLanguagePageUri($language)
    {
        $page = $this->client->get(self::BASE_WIKI_PAGE)->getBody()->getContents();
        $this->crawler->add($page);

        foreach ($this->crawler->filter('#mw-content-text ul li a') as $node) {
            if (strpos($node->nodeValue, $language) !== false) {
                if (substr($node->nodeValue, 0, 1) == substr($language,0,1)) {
                    if (substr($node->getAttribute('href'), 0, 1) == '#') {
                        continue;
                    }
                    return $node->getAttribute('href');
                }
            }
        }

        return false;
    }

    /**
     * @param string $pageUri
     * @return bool|int
     */
    private function getLanguageYear($pageUri)
    {
        $page = $this->client->get($pageUri)->getBody()->getContents();
        $this->crawler->clear();
        $this->crawler->add($page);

        foreach ($this->crawler->filter('tr > th') as $node) {
            if ($node->nodeValue == 'Apparu en') {
                $raw = $node->parentNode->childNodes->item(2)->nodeValue;
                $res = (int)$raw;
                if ($res < 100) {
                    $res = (int)substr($raw,-4);
                }

                return $res;
            }
        }

        return false;
    }
}