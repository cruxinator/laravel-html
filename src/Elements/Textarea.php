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
use Cruxinator\LaravelHtml\Exceptions\InvalidChild;
use Cruxinator\LaravelHtml\Exceptions\InvalidHtml;

/**
 * @method attributeIf(string|null $name, string $string, string $fieldName):Textarea
 */
class Textarea extends BaseElement
{
    use Autofocus;
    use Placeholder;
    use Name;
    use Required;
    use Disabled;
    use Readonly;
    use MinMaxLength;

    protected $tag = 'textarea';

    /**
     * @param string|null $value
     *
     * @return static
     * @throws InvalidHtml
     * @throws InvalidChild
     */
    public function value(?string $value): self
    {
        return $this->html($value);
    }

    /**
     * @param int $rows
     *
     * @return static
     */
    public function rows(int $rows): self
    {
        return $this->attribute('rows', $rows);
    }

    /**
     * @param int $cols
     *
     * @return static
     */
    public function cols(int $cols): self
    {
        return $this->attribute('cols', $cols);
    }
}
