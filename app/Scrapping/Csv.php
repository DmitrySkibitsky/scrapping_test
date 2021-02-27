<?php

namespace App\Scrapping;

use App\Helpers\AppHelper;
use App\Scrapping\ObjectValues\Recommendation;

/**
 * Class Csv.
 */
class Csv
{
    /**
     * @var string
     */
    public string $fileName;

    /**
     * @var string
     */
    public string $path;

    /**
     * Csv constructor.
     */
    public function __construct()
    {
        $this->path = AppHelper::publicPath('csv/');

        $this->mkDir();
    }

    /**
     * @param array|Recommendation[] $recommendations
     */
    public function recommendationToCsv(array $recommendations)
    {
        $columns = array_keys($recommendations[0]->toArray());

        $file = fopen($this->getPathToFile(), 'w');

        fputcsv($file, $columns);

        foreach ($recommendations as $recommendation) {
            $data = $recommendation->toArray();

            fputcsv($file, array_values($data));
        }

        fclose($file);
    }

    private function mkDir()
    {
        if (! file_exists($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    /**
     * @return string
     */
    public function getPathToFile(): string
    {
        return "{$this->path}{$this->fileName}.csv";
    }

    /**
     * @return string
     */
    public function getUrlToFile(): string
    {
        return config('app.url') . "/csv/{$this->fileName}.csv";
    }
}
