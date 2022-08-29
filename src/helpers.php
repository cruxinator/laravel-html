<?php

use Cruxinator\LaravelHtml\Html;

if (! function_exists('html')) {
    /**
     * @return Html
     */
    function html(): Html
    {
        return app(Html::class);
    }
}
