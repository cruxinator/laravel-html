<?php

namespace Cruxinator\LaravelHtml\Exceptions;

use Exception;
use Cruxinator\LaravelHtml\HtmlElement;

class InvalidChild extends Exception
{
    public static function childMustBeAnHtmlElementOrAString(): self
    {
        return new static('The given child should implement `'.HtmlElement::class.'` or be a string');
    }
}
