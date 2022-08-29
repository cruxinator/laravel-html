<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\Disabled;

class Optgroup extends BaseElement
{
    use Disabled;

    protected $tag = 'optgroup';

    /**
     * @param string|null $label
     *
     * @return static
     */
    public function label(?string $label): self
    {
        return $this->attribute('label', $label);
    }
}
