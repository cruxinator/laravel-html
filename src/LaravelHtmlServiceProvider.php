<?php

namespace Cruxinator\LaravelHtml;

use Cruxinator\Package\Package;
use Cruxinator\Package\PackageServiceProvider;
use Cruxinator\LaravelHtml\Commands\LaravelHtmlCommand;
use Cruxinator\LaravelHtml\Html;

class LaravelHtmlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-html')
            ->hasCommand(LaravelHtmlCommand::class);
    }

    public function register(): self
    {
        parent::register();

        $this->app->singleton(Html::class);

        return $this;
    }
}
