<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;

/**
 * @method attributeIf(string|null $for, string $string, string $fieldName): Label
 */
class Label extends BaseElement
{
    protected $tag = 'label';

    /**
     * @param string|null $for
     *
     * @return static
     */
    public function for(?string $for): self
    {
        return $this->attribute('for', $for);
    }
}
