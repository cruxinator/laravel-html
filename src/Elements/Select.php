<?php

namespace Cruxinator\LaravelHtml\Elements;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\Autofocus;
use Cruxinator\LaravelHtml\Elements\Attributes\Disabled;
use Cruxinator\LaravelHtml\Elements\Attributes\Name;
use Cruxinator\LaravelHtml\Elements\Attributes\Readonly;
use Cruxinator\LaravelHtml\Elements\Attributes\Required;
use Cruxinator\LaravelHtml\Exceptions\InvalidChild;
use Cruxinator\LaravelHtml\Exceptions\InvalidHtml;
use Cruxinator\LaravelHtml\HtmlElement;
use Cruxinator\LaravelHtml\Selectable;

/**
 * @method attributeIf(string|null $name, string $string, string $fieldName): Select
 */
class Select extends BaseElement
{
    use Autofocus;
    use Disabled;
    use Name;
    use Required;
    use Readonly;

    /** @var string */
    protected $tag = 'select';

    /** @var array */
    protected $options = [];

    /** @var string|iterable */
    protected $value = '';

    /**
     * @return static
     */
    public function multiple(): self
    {
        $element = clone $this;

        $element = $element->attribute('multiple');

        $name = $element->getAttribute('name');

        if ($name && ! Str::endsWith($name, '[]')) {
            $element = $element->name($name.'[]');
        }

        $element->applyValueToOptions();

        return $element;
    }

    /**
     * @param iterable $options
     *
     * @return static
     * @throws InvalidChild
     */
    public function options(iterable $options): self
    {
        return $this->addChildren($options, function ($text, $value) {
            if (is_array($text)) {
                return $this->optgroup($value, $text);
            }

            return Option::create()
                ->value($value)
                ->text($text)
                ->selectedIf($value === $this->value);
        });
    }

    /**
     * @param string $label
     * @param iterable $options
     *
     * @return Optgroup
     * @throws InvalidChild
     */
    public function optgroup(string $label, iterable $options): Optgroup
    {
        return Optgroup::create()
            ->label($label)
            ->addChildren($options, function ($text, $value) {
                return Option::create()
                    ->value($value)
                    ->text($text)
                    ->selectedIf($value === $this->value);
            });

        //return $this->addChild($optgroup);
    }

    /**
     * @param string|null $text
     *
     * @return static
     * @throws InvalidHtml
     * @throws InvalidHtml
     * @throws InvalidChild
     */
    public function placeholder(?string $text): self
    {
        return $this->prependChild(
            Option::create()
                ->value(null)
                ->text($text)
                ->selectedIf(! $this->hasSelection())
        );
    }

    /**
     * @param string|iterable $value
     *
     * @return static
     */
    public function value($value = null): self
    {
        $element = clone $this;

        $element->value = $value;

        $element->applyValueToOptions();

        return $element;
    }

    protected function hasSelection()
    {
        return $this->children->contains->hasAttribute('selected');
    }

    protected function applyValueToOptions()
    {
        $value = Collection::make($this->value);

        if (! $this->hasAttribute('multiple')) {
            $value = $value->take(1);
        }

        $this->children = $this->applyValueToElements($value, $this->children);
    }

    protected function applyValueToElements($value, Collection $children):Collection
    {
        return $children->map(function (HtmlElement $child) use ($value) {
            if ($child instanceof Optgroup) {
                return $child->setChildren($this->applyValueToElements($value, $child->children));
            }

            if ($child instanceof Selectable) {
                return $child->selectedIf($value->contains($child->getAttribute('value')));
            }

            return $child;
        });
    }
}
