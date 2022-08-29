<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;

/**
 * @method attributeIf(string|null $src, string $string, string|null $src1): Img
 */
class Img extends BaseElement
{
    protected $tag = 'img';

    /**
     * @param string|null $alt
     *
     * @return static
     */
    public function alt(?string $alt): self
    {
        return $this->attribute('alt', $alt);
    }

    /**
     * @param string|null $src
     *
     * @return static
     */
    public function src(?string $src): self
    {
        return $this->attribute('src', $src);
    }
}
