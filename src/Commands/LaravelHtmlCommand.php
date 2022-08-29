<?php

namespace Cruxinator\LaravelHtml\Commands;

use Illuminate\Console\Command;

class LaravelHtmlCommand extends Command
{
    public $signature = 'laravel-html';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
