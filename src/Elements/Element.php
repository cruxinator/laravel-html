<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\Attributes;
use Cruxinator\LaravelHtml\BaseElement;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;

class Element extends BaseElement
{
    /**
     * @param string $tag
     *
     * @return static
     * @throws ReflectionException
     * @throws ReflectionException
     */
    public static function withTag(string $tag): self
    {
        /**
         * @var self $element
         */
        $element = (new ReflectionClass(static::class))->newInstanceWithoutConstructor();

        $element->tag = $tag;
        $element->attributes = new Attributes();
        $element->children = new Collection();

        return $element;
    }
}
