<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait OnClick
{
    /**
     * @param bool $onClick
     *
     * @return static
     */
    public function onClick(string $onClick = null): OnClick
    {
        return null === $onClick
            ? $this->forgetAttribute('onclick')
            : $this->attribute('onclick', $onClick);
    }
}
