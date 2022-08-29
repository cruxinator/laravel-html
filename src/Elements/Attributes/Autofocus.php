<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Autofocus
{
    /**
     * @param bool $autofocus
     *
     * @return static
     */
    public function autofocus(bool $autofocus = true): Autofocus
    {
        return $autofocus
            ? $this->attribute('autofocus')
            : $this->forgetAttribute('autofocus');
    }
}
