<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\Disabled;
use Cruxinator\LaravelHtml\Elements\Attributes\Value;
use Cruxinator\LaravelHtml\Selectable;

class Option extends BaseElement implements Selectable
{
    use Disabled;
    use Value;

    /** @var string */
    protected $tag = 'option';

    /**
     * @return static
     */
    public function selected(): Selectable
    {
        return $this->attribute('selected', 'selected');
    }

    /**
     * @param bool $condition
     *
     * @return static
     */
    public function selectedIf(bool $condition): Selectable
    {
        return $condition ?
            $this->selected() :
            $this->unselected();
    }

    /**
     * @return static
     */
    public function unselected(): Selectable
    {
        return $this->forgetAttribute('selected');
    }
}
