<?php

namespace Cruxinator\LaravelHtml\Facades;

use Illuminate\Support\Facades\Facade;
use Cruxinator\LaravelHtml\Facades\Contracts\LaravelHtmlContract;

/**
 * @see \Cruxinator\LaravelHtml\LaravelHtml
 */
class LaravelHtml extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LaravelHtmlContract::class;
    }
}
