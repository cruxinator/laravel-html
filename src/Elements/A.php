<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\OnClick;
use Cruxinator\LaravelHtml\Elements\Attributes\Target;

/**
 * @method attributeIf(string|null $condition, string $attribute, string|null $value): A
 * @method attributeUnless(string|null $condition, string $attribute, string|null $assignment): A
 * @method attributeIfNotNull(string|null $condition, string $attribute, string|null $assignment): A
 */
class A extends BaseElement
{
    use Target;
    use Onclick;

    protected $tag = 'a';

    /**
     * @param string|null $href
     *
     * @return static
     */
    public function href(?string $href): self
    {
        return $this->attribute('href', $href);
    }
}
