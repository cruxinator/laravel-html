<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Name
{
    /**
     * @param string $name
     *
     * @return static
     */
    public function name(string $name): Name
    {
        return $this->attribute('name', $name);
    }
}
