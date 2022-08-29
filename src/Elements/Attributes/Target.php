<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Target
{
    /**
     * @param string $target
     *
     * @return static
     */
    public function target(string $target): Target
    {
        return $this->attribute('target', $target);
    }
}
