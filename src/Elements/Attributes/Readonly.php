<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Readonly
{
    /**
     * @param bool $readonly
     *
     * @return static
     */
    public function readonly($readonly = true): Readonly
    {
        return $readonly
            ? $this->attribute('readonly')
            : $this->forgetAttribute('readonly');
    }
}
