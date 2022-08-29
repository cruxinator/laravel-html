<?php

namespace Cruxinator\LaravelHtml;

use BadMethodCallException;
use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Cruxinator\LaravelHtml\Exceptions\InvalidChild;
use Cruxinator\LaravelHtml\Exceptions\InvalidHtml;
use Cruxinator\LaravelHtml\Exceptions\MissingTag;

/**
 * @method attributeIf(string|null $condition, string $attribute, string|null $value): A
 * @method attributeUnless(string|null $condition, string $attribute, string|null $assignment): A
 * @method attributeIfNotNull(string|null $condition, string $attribute, string|null $assignment): A
 */
abstract class BaseElement implements Htmlable, HtmlElement
{
    use Macroable {
        __call as __macro_call;
    }

    /** @var string */
    protected $tag;

    /** @var Attributes */
    protected $attributes;

    /** @var Collection */
    protected $children;

    /**
     * BaseElement constructor.
     * @throws MissingTag
     */
    public function __construct()
    {
        if (empty($this->tag)) {
            throw MissingTag::onClass(static::class);
        }

        $this->attributes = new Attributes();
        $this->children = new Collection();
    }

    /**
     * @return static
     */
    public static function create()//: BaseElement
    {
        return new static();
    }

    /**
     * @param string $attribute
     * @param string|null $value
     *
     * @return static
     */
    public function attribute(string $attribute, $value = null): self
    {
        $element = clone $this;

        $element->attributes->setAttribute($attribute, (string) $value);

        return $element;
    }

    /**
     * @param iterable $attributes
     *
     * @return static
     */
    public function attributes(iterable $attributes): self
    {
        $element = clone $this;

        $element->attributes->setAttributes($attributes);

        return $element;
    }

    /**
     * @param string $attribute
     *
     * @return static
     */
    public function forgetAttribute(string $attribute): self
    {
        $element = clone $this;

        $element->attributes->forgetAttribute($attribute);

        return $element;
    }

    /**
     * @param string $attribute
     * @param mixed $fallback
     *
     * @return mixed
     */
    public function getAttribute(string $attribute, $fallback = null)
    {
        return $this->attributes->getAttribute($attribute, $fallback);
    }

    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function hasAttribute(string $attribute): bool
    {
        return $this->attributes->hasAttribute($attribute);
    }

    /**
     * @param iterable|string $class
     *
     * @return static
     */
    public function class($class): self
    {
        return $this->addClass($class);
    }

    /**
     * Alias for `class`.
     *
     * @param iterable|string $class
     *
     * @return static
     */
    public function addClass($class): self
    {
        $element = clone $this;

        $element->attributes->addClass($class);

        return $element;
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public function id(string $id): self
    {
        return $this->attribute('id', $id);
    }

    /**
     * @param array|string|null $style
     *
     * @return static
     */
    public function style($style): self
    {
        if (is_array($style)) {
            $style = implode('; ', array_map(function ($value, $attribute) {
                return "{$attribute}: {$value}";
            }, $style, array_keys($style)));
        }

        return $this->attribute('style', $style);
    }

    /**
     * @param string $name
     * @param null $value
     *
     * @return static
     */
    public function data(string $name, $value = null): self
    {
        return $this->attribute("data-{$name}", $value);
    }

    /**
     * @param HtmlElement|string|iterable|int|float|null $children
     * @param callable|null $mapper
     *
     * @return static
     * @throws InvalidChild
     */
    public function addChildren($children, $mapper = null): self
    {
        if (is_null($children)) {
            return $this;
        }

        $children = $this->parseChildren($children, $mapper);

        $element = clone $this;

        $element->children = $element->children->merge($children);

        return $element;
    }

    /**
     * Alias for `addChildren`.
     *
     * @param HtmlElement|string|iterable|int|float|null $child
     * @param callable|null $mapper
     *
     * @return static
     * @throws InvalidChild
     */
    public function addChild($child, $mapper = null): self
    {
        return $this->addChildren($child, $mapper);
    }

    /**
     * Alias for `addChildren`.
     *
     * @param HtmlElement|string|iterable|int|float|null $child
     * @param callable|null $mapper
     *
     * @return static
     * @throws InvalidChild
     */
    public function child($child, $mapper = null): self
    {
        return $this->addChildren($child, $mapper);
    }

    /**
     * Alias for `addChildren`.
     *
     * @param HtmlElement|string|iterable|int|float|null $children
     * @param callable|null $mapper
     *
     * @return static
     * @throws InvalidChild
     */
    public function children($children, $mapper = null): self
    {
        return $this->addChildren($children, $mapper);
    }

    /**
     * Replace all children with an array of elements.
     *
     * @param HtmlElement[]|Collection|string $children
     * @param callable|null $mapper
     *
     * @return static
     * @throws InvalidChild
     */
    public function setChildren($children, $mapper = null): self
    {
        $element = clone $this;

        $element->children = new Collection();

        return $element->addChildren($children, $mapper);
    }

    /**
     * @param HtmlElement|string|iterable|int|float|null $children
     * @param callable|null $mapper
     *
     * @return static
     * @throws InvalidChild
     */
    public function prependChildren($children, $mapper = null): self
    {
        $children = $this->parseChildren($children, $mapper);

        $element = clone $this;

        $element->children = $children->merge($element->children);

        return $element;
    }

    /**
     * Alias for `prependChildren`.
     *
     * @param HtmlElement|string|iterable|int|float|null $children
     * @param callable|null $mapper
     *
     * @return static
     * @throws InvalidChild
     */
    public function prependChild($children, $mapper = null): self
    {
        return $this->prependChildren($children, $mapper);
    }

    /**
     * @param string|null $text
     *
     * @return static
     * @throws InvalidHtml
     * @throws InvalidHtml
     * @throws InvalidChild
     */
    public function text(?string $text): self
    {
        return $this->html(htmlentities($text, ENT_QUOTES, 'UTF-8', false));
    }

    /**
     * @param string|null $html
     *
     * @return static
     * @throws InvalidHtml
     * @throws InvalidHtml
     * @throws InvalidChild
     */
    public function html(?string $html)//: static
    {
        if ($this->isVoidElement()) {
            throw new InvalidHtml("Can't set inner contents on `{$this->tag}` because it's a void element");
        }

        return $this->setChildren($html);
    }

    /**
     * Conditionally transform the element. Note that since elements are
     * immutable, you'll need to return a new instance from the callback.
     *
     * @param bool $condition
     * @param Closure $callback
     *
     * @return mixed
     */
    public function if(bool $condition, Closure $callback): self
    {
        return $condition ? $callback($this) : $this;
    }

    /**
     * Conditionally transform the element. Note that since elements are
     * immutable, you'll need to return a new instance from the callback.
     *
     * @param bool $condition
     * @param Closure $callback
     *
     * @return mixed
     */
    public function unless(bool $condition, Closure $callback): self
    {
        return $this->if(! $condition, $callback);
    }

    /**
     * Conditionally transform the element. Note that since elements are
     * immutable, you'll need to return a new instance from the callback.
     *
     * @param mixed $value
     * @param Closure $callback
     *
     * @return mixed
     */
    public function ifNotNull($value, Closure $callback): self
    {
        return ! is_null($value) ? $callback($this) : $this;
    }

    /**
     * @return Htmlable
     */
    public function open()
    {
        $tag = $this->attributes->isEmpty()
            ? '<'.$this->tag.'>'
            : "<{$this->tag} {$this->attributes->render()}>";

        $children = $this->children->map(function ($child): string {
            if ($child instanceof HtmlElement) {
                return $child->render();
            }

            if (is_null($child)) {
                return '';
            }

            if (is_string($child) || is_numeric($child)) {
                return $child;
            }

            throw InvalidChild::childMustBeAnHtmlElementOrAString();
        })->implode('');

        return new HtmlString($tag.$children);
    }

    /**
     * @return Htmlable
     */
    public function close()
    {
        return new HtmlString(
            $this->isVoidElement()
                ? ''
                : "</{$this->tag}>"
        );
    }

    /**
     * @return Htmlable
     */
    public function render(): Htmlable
    {
        return new HtmlString(
            $this->open().$this->close()
        );
    }

    public function isVoidElement(): bool
    {
        return in_array($this->tag, [
            'area', 'base', 'br', 'col', 'embed', 'hr',
            'img', 'input', 'keygen', 'link', 'menuitem',
            'meta', 'param', 'source', 'track', 'wbr',
        ]);
    }

    /**
     * Dynamically handle calls to the class.
     * Check for methods finishing by If or fallback to Macroable.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $arguments)
    {
        if (Str::endsWith($name, $conditions = ['If', 'Unless', 'IfNotNull'])) {
            foreach ($conditions as $condition) {
                if (! method_exists($this, $method = str_replace($condition, '', $name))) {
                    continue;
                }

                return $this->callConditionalMethod($condition, $method, $arguments);
            }
        }

        return $this->__macro_call($name, $arguments);
    }

    protected function callConditionalMethod($type, $method, array $arguments): self
    {
        $value = array_shift($arguments);
        $callback = function () use ($method, $arguments) {
            return $this->{$method}(...$arguments);
        };

        switch ($type) {
            case 'If':
                return $this->if((bool) $value, $callback);
            case 'Unless':
                return $this->unless((bool) $value, $callback);
            case 'IfNotNull':
                return $this->ifNotNull($value, $callback);
            default:
                return $this;
        }
    }

    public function __clone()
    {
        $this->attributes = clone $this->attributes;
        $this->children = clone $this->children;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function toHtml(): string
    {
        return $this->render();
    }

    /**
     * @param $children
     * @param null $mapper
     * @return Collection
     * @throws InvalidChild
     */
    protected function parseChildren($children, $mapper = null): Collection
    {
        if ($children instanceof HtmlElement) {
            $children = [$children];
        }

        $children = Collection::make($children);

        if ($mapper) {
            $children = $children->map($mapper);
        }

        $this->guardAgainstInvalidChildren($children);

        return $children;
    }

    /**
     * @param Collection $children
     * @throws InvalidChild
     */
    protected function guardAgainstInvalidChildren(Collection $children)
    {
        foreach ($children as $child) {
            if ($child instanceof HtmlElement || is_null($child) || is_string($child) || is_numeric($child)) {
                continue;
            }

            throw InvalidChild::childMustBeAnHtmlElementOrAString();
        }
    }
}
