<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\Disabled;
use Cruxinator\LaravelHtml\Elements\Attributes\Name;
use Cruxinator\LaravelHtml\Elements\Attributes\Type;
use Cruxinator\LaravelHtml\Elements\Attributes\Value;

/**
 * @method attributeIf(string|null $type, string $string, string|null $type1): Button
 */
class Button extends BaseElement
{
    use Disabled;
    use Value;
    use Name;
    use Type;

    protected $tag = 'button';
}
