<?php

namespace Cruxinator\LaravelHtml\Elements\Attributes;

trait Type
{
    /**
     * @param string|null $type
     *
     * @return static
     */
    public function type(?string $type): Type
    {
        return $this->attribute('type', $type);
    }
}
