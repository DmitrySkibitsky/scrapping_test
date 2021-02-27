<?php

namespace App\Console\Commands;

use App\Scrapping\AmazonRecommendation;
use Illuminate\Console\Command;

/**
 * Class AmazonScrappingCommand.
 */
class AmazonScrappingCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'scrapping:amazon {keywords?} {fileName?}';

    /**
     * @var string
     */
    protected $description = 'Getting recommendations';

    /**
     * @return void
     */
    public function handle()
    {
        $arguments = $this->argument();

        $keywords = $arguments['keywords'] ?? $this->ask('Keywords: ');

        $ar = new AmazonRecommendation($keywords, $arguments['fileName']);
        $ar->getRecommendations()->saveToCsv();

        $this->info('Path to CSV: ' . $ar->csv->getPathToFile());
        $this->info('URL to CSV: ' . $ar->csv->getUrlToFile());
    }
}
