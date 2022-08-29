<?php

namespace Cruxinator\LaravelHtml;

use Cruxinator\Package\Package;
use Cruxinator\Package\PackageServiceProvider;
use Cruxinator\LaravelHtml\Commands\LaravelHtmlCommand;

class LaravelHtmlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-html')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-html_table')
            ->hasCommand(LaravelHtmlCommand::class);
    }
}
