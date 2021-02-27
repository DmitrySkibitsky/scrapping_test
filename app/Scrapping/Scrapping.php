<?php

namespace App\Scrapping;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Scrapping.
 */
class Scrapping
{
    /**
     * @var Client
     */
    public Client $client;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var string
     */
    protected string $url;

    /**
     * Scrapping constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string|null $url
     */
    public function getRequest(?string $url = null)
    {
        $url = $url ?? $this->url;

        $this->crawler = $this->client->request('GET', $url);
    }
}
