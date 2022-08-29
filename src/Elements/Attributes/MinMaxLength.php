<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait MinMaxLength
{
    /**
     * @param int $minlength
     *
     * @return static
     */
    public function minlength(int $minlength): MinMaxLength
    {
        return $this->attribute('minlength', $minlength);
    }

    /**
     * @param int $maxlength
     *
     * @return static
     */
    public function maxlength(int $maxlength): MinMaxLength
    {
        return $this->attribute('maxlength', $maxlength);
    }
}
