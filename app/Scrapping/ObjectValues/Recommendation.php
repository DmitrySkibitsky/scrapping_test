<?php

namespace App\Scrapping\ObjectValues;

/**
 * Class Recommendation.
 */
class Recommendation
{
    /**
     * @var string
     */
    public string $keyword;

    /**
     * @var string|null
     */
    public ?string $publisher = null;

    /**
     * @var string|null
     */
    public ?string $articleName = null;

    /**
     * @var string|null
     */
    public ?string $publishDate = null;

    /**
     * @var string|null
     */
    public ?string $articleUrl = null;

    /**
     * @var string|null
     */
    public ?string $scrappingDate = null;

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'keyword'       => $this->keyword,
            'publisher'     => $this->publisher ?? 'no data',
            'articleName'   => $this->articleName ?? 'no data',
            'publishDate'   => $this->publishDate ?? 'no data',
            'articleUrl'    => $this->articleUrl ?? 'no data',
            'scrappingDate' => $this->scrappingDate ?? 'no data',
        ];
    }
}
