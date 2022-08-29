<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Required
{
    /**
     * @param bool $required
     *
     * @return static
     */
    public function required($required = true): Required
    {
        return $required
            ? $this->attribute('required')
            : $this->forgetAttribute('required');
    }
}
