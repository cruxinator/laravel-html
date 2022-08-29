<?php

namespace Cruxinator\LaravelHtml\Facades;

use Cruxinator\LaravelHtml\Facades\Contracts\LaravelHtmlContract;
use Illuminate\Support\Facades\Facade;

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
