<?php

namespace App\Scrapping;

use App\Scrapping\ObjectValues\Recommendation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AmazonRecommendation.
 */
class AmazonRecommendation extends Scrapping
{
    /**
     * @var string
     */
    public string $domain = 'https://www.amazon.com';

    /**
     * @var array
     */
    public array $keywords;
    /**
     * @var string|null
     */
    public ?string $fileName;

    /**
     * @var Recommendation
     */
    private Recommendation $recommendation;

    /**
     * @var array|Recommendation[]
     */
    private array $recommendations;

    /**
     * @var Crawler
     */
    private Crawler $blockRecommendation;

    /**
     * @var Crawler
     */
    private Crawler $cardRecommendation;

    /**
     * @var Csv
     */
    public Csv $csv;

    /**
     * AmazonScrapping constructor.
     * @param string      $keywords
     * @param string|null $fileName
     */
    public function __construct(string $keywords, ?string $fileName = null)
    {
        parent::__construct();

        $this->keywords = explode(',', $keywords);
        $this->fileName = $fileName;

        $this->url = "{$this->domain}/s?k=[%keywords%]&ref=nb_sb_noss";

        $this->csv = new Csv();
    }

    private function getUrl($keyword)
    {
        return str_replace(
            '[%keywords%]',
            $keyword,
            $this->url
        );
    }

    /**
     * @return string
     */
    private function getFileName(): string
    {
        return $this->fileName ?? 'KEYWORDWINNER_' . Carbon::now()->format('Y_m_d') . Str::random(5);
    }

    /**
     * @return $this
     */
    public function getRecommendations(): self
    {
        foreach ($this->keywords as $keyword) {
            $this->recommendation = new Recommendation();
            $this->recommendation->keyword = trim($keyword);

            $blockExists = true;

            $this->getRequest($this->getUrl($this->recommendation->keyword));

            $this->blockRecommendation = $this->crawler->filter('div[class="s-border-top-overlap"]');

            if (0 === $this->blockRecommendation->count()) {
                Log::info('Block with recommendation not found');

                $blockExists = false;
            }

            if ($blockExists) {
                $this->setPublisher();

                $searchCarousel = $this->blockRecommendation
                    ->filter('span[data-component-type="s-searchgrid-carousel"]');

                $cards = $searchCarousel->filter('li[class="a-carousel-card"]');

                if ($searchCarousel->count() > 0 && $cards->count() > 0) {
                    $this->cardRecommendation = $cards->first();

                    $this->setArticleName();
                    $this->setPublishDate();
                    $this->setArticleUrl();

                    $this->recommendation->scrappingDate = Carbon::now()->toDateTimeString();
                }
            }

            $this->recommendations[] = $this->recommendation;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function saveToCsv(): self
    {
        $this->csv->fileName = $this->getFileName();
        $this->csv->recommendationToCsv($this->recommendations);

        return $this;
    }

    private function setPublisher()
    {
        $setValue = true;

        $rowPublisher = $this->blockRecommendation->filter('div[class="a-row"] span[dir="auto"]');

        if ('by' !== strtolower($rowPublisher->text())) {
            $setValue = false;
        }

        $link = $rowPublisher->parents()->first()->filter('a[class="a-link-normal"]');

        if (0 === $link->count()) {
            $setValue = false;
        }

        if ($setValue) {
            $this->recommendation->publisher = trim($link->first()->text());
        }
    }

    private function setArticleName()
    {
        $articleName = $this->cardRecommendation->filter('h5 > span');

        if ($articleName->count() > 0) {
            $this->recommendation->articleName = trim($articleName->text());
        }
    }

    private function setPublishDate()
    {
        $publishDate = $this->cardRecommendation->filter('span[class="a-color-secondary"]');

        if ($publishDate->count() > 0) {
            $publishDate = explode('-', $publishDate->text());

            $this->recommendation->publishDate = trim($publishDate[0]);
        }
    }

    private function setArticleUrl()
    {
        $links = $this->cardRecommendation->filter('a[class="a-link-normal"]');

        if ($links->count() > 0) {
            /** @var string $href */
            $href = $links->last()->attr('href');

            $this->recommendation->articleUrl = "{$this->domain}$href";
        }
    }
}
