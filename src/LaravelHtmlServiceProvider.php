<?php

namespace Cruxinator\LaravelHtml;

use Cruxinator\LaravelHtml\Commands\LaravelHtmlCommand;
use Cruxinator\Package\Package;
use Cruxinator\Package\PackageServiceProvider;

class LaravelHtmlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-html')
            ->hasCommand(LaravelHtmlCommand::class);
    }

    public function register(): PackageServiceProvider
    {
        parent::register();

        $this->app->singleton(Html::class);

        return $this;
    }
}
