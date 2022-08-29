<?php

namespace Cruxinator\LaravelHtml\Exceptions;

use Cruxinator\LaravelHtml\HtmlElement;
use Exception;

class InvalidChild extends Exception
{
    public static function childMustBeAnHtmlElementOrAString(): self
    {
        return new static('The given child should implement `'.HtmlElement::class.'` or be a string');
    }
}
