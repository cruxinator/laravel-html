<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\Target;

/**
 * @method attributeIf(string|null $name, string $string, string $fieldName): Form
 */
class Form extends BaseElement
{
    use Target;

    protected $tag = 'form';

    /**
     * @param string|null $action
     *
     * @return static
     */
    public function action(?string $action): self
    {
        return $this->attribute('action', $action);
    }

    /**
     * @param string|null $method
     *
     * @return static
     */
    public function method(?string $method): self
    {
        return $this->attribute('method', $method);
    }

    /**
     * @param bool $novalidate
     *
     * @return static
     */
    public function novalidate($novalidate = true): self
    {
        return $novalidate
            ? $this->attribute('novalidate')
            : $this->forgetAttribute('novalidate');
    }

    /**
     * @return static
     */
    public function acceptsFiles(): self
    {
        return $this->attribute('enctype', 'multipart/form-data');
    }
}
