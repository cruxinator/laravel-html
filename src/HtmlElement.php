<?php

namespace Cruxinator\LaravelHtml;

use Illuminate\Contracts\Support\Htmlable;

interface HtmlElement
{
    /**
     * @return Htmlable
     */
    public function render(): Htmlable;
}
