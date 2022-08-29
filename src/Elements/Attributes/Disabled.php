<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Disabled
{
    /**
     * @param bool $disabled
     *
     * @return static
     */
    public function disabled($disabled = true): Disabled
    {
        return $disabled
            ? $this->attribute('disabled', 'disabled')
            : $this->forgetAttribute('disabled');
    }
}
