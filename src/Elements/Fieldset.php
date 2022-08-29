<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\Disabled;
use Cruxinator\LaravelHtml\Exceptions\InvalidChild;
use Cruxinator\LaravelHtml\Exceptions\InvalidHtml;
use Cruxinator\LaravelHtml\HtmlElement;

class Fieldset extends BaseElement
{
    use Disabled;

    protected $tag = 'fieldset';

    /**
     * @param HtmlElement|string $contents
     *
     * @return static
     * @throws InvalidHtml
     * @throws InvalidHtml
     * @throws InvalidChild
     */
    public function legend($contents): self
    {
        return $this->prependChild(
            Legend::create()->text($contents)
        );
    }
}
