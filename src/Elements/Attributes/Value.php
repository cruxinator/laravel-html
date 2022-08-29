<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Value
{
    /**
     * @param string|null $value
     *
     * @return static
     */
    public function value(?string $value): Value
    {
        return $this->attribute('value', $value);
    }
}
