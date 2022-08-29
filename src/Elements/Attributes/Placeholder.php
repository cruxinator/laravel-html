<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Placeholder
{
    /**
     * @param string|null $placeholder
     *
     * @return static
     */
    public function placeholder(?string $placeholder): Placeholder
    {
        return $this->attribute('placeholder', $placeholder);
    }
}
