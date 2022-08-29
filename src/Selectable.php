<?php

namespace Cruxinator\LaravelHtml;

interface Selectable
{
    /**
     * @return static
     */
    public function selected(): self;

    /**
     * @param bool $condition
     * @return static
     */
    public function selectedIf(bool $condition): self;

    /**
     * @return static
     */
    public function unselected(): self;
}
