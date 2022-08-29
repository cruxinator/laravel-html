<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\Autofocus;
use Cruxinator\LaravelHtml\Elements\Attributes\Disabled;
use Cruxinator\LaravelHtml\Elements\Attributes\MinMaxLength;
use Cruxinator\LaravelHtml\Elements\Attributes\Name;
use Cruxinator\LaravelHtml\Elements\Attributes\Placeholder;
use Cruxinator\LaravelHtml\Elements\Attributes\Readonly;
use Cruxinator\LaravelHtml\Elements\Attributes\Required;
use Cruxinator\LaravelHtml\Elements\Attributes\Type;
use Cruxinator\LaravelHtml\Elements\Attributes\Value;

/**
 * @method attributeIf(bool $param, string $string, string|null $value): Input
 * @method attributeIfNotNull(string|null $min, string $string, string|null $min1): Input
 */
class Input extends BaseElement
{
    use Autofocus;
    use Disabled;
    use MinMaxLength;
    use Name;
    use Placeholder;
    use Readonly;
    use Required;
    use Type;
    use Value;

    protected $tag = 'input';

    /**
     * @return static
     */
    public function unchecked(): self
    {
        return $this->checked(false);
    }

    /**
     * @param bool $checked
     *
     * @return static
     */
    public function checked($checked = true): self
    {
        return $checked
            ? $this->attribute('checked', 'checked')
            : $this->forgetAttribute('checked');
    }

    /**
     * @param string|null $size
     *
     * @return static
     */
    public function size(?string $size): self
    {
        return $this->attribute('size', $size);
    }
}
