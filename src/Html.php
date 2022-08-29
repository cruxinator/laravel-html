<?php

namespace Cruxinator\LaravelHtml;

use ArrayAccess;
use Cruxinator\LaravelHtml\Elements\A;
use Cruxinator\LaravelHtml\Elements\Button;
use Cruxinator\LaravelHtml\Elements\Div;
use Cruxinator\LaravelHtml\Elements\Element;
use Cruxinator\LaravelHtml\Elements\Fieldset;
use Cruxinator\LaravelHtml\Elements\File;
use Cruxinator\LaravelHtml\Elements\Form;
use Cruxinator\LaravelHtml\Elements\I;
use Cruxinator\LaravelHtml\Elements\Img;
use Cruxinator\LaravelHtml\Elements\Input;
use Cruxinator\LaravelHtml\Elements\Label;
use Cruxinator\LaravelHtml\Elements\Legend;
use Cruxinator\LaravelHtml\Elements\Li;
use Cruxinator\LaravelHtml\Elements\Option;
use Cruxinator\LaravelHtml\Elements\P;
use Cruxinator\LaravelHtml\Elements\Select;
use Cruxinator\LaravelHtml\Elements\Span;
use Cruxinator\LaravelHtml\Elements\Strong;
use Cruxinator\LaravelHtml\Elements\Textarea;
use Cruxinator\LaravelHtml\Elements\Ul;
use DateTimeImmutable;
use Exception;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use ReflectionException;

class Html
{
    use Macroable;

    public const HTML_DATE_FORMAT = 'Y-m-d';

    public const HTML_TIME_FORMAT = 'H:i:s';

    /** @var Request */
    protected $request;

    /** @var ArrayAccess|array */
    protected $model;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string|null $href
     * @param string|null $contents
     *
     * @return A
     */
    public function a($href = null, $contents = null): A
    {
        return A::create()
            ->attributeIf($href, 'href', $href)
            ->html($contents);
    }

    /**
     * @param string|null $contents
     *
     * @return I
     * @throws Exceptions\InvalidHtml
     * @throws Exceptions\InvalidHtml
     * @throws Exceptions\InvalidChild
     */
    public function i($contents = null): I
    {
        return I::create()
            ->html($contents);
    }

    /**
     * @param string|null $name
     * @param string|null $type
     * @param HtmlElement|string|null $contents
     *
     * @return Button
     */
    public function button($contents = null, $type = null, $name = null): Button
    {
        return Button::create()
            ->attributeIf($type, 'type', $type)
            ->attributeIf($name, 'name', $this->fieldName($name))
            ->html($contents);
    }

    /**
     * @param Collection|iterable|string $classes
     *
     * @return Htmlable
     */
    public function class($classes): Htmlable
    {
        if ($classes instanceof Collection) {
            $classes = $classes->toArray();
        }

        $attributes = new Attributes();
        $attributes->addClass($classes);

        return new HtmlString(
            $attributes->render()
        );
    }

    /**
     * @param string|null $name
     * @param bool $checked
     * @param string|null $value
     *
     * @return Input
     */
    public function checkbox($name = null, $checked = null, $value = '1'): Input
    {
        return $this->input('checkbox', $name, $value)
            ->attributeIf(! is_null($value), 'value', $value)
            ->attributeIf((bool) $this->old($name, $checked), 'checked');
    }

    /**
     * @param HtmlElement|string|null $contents
     *
     * @return Div
     * @throws Exceptions\InvalidChild
     */
    public function div($contents = null): Div
    {
        return Div::create()->children($contents);
    }

    /**
     * @param string|null $name
     * @param string|null $value
     *
     * @return Input
     */
    public function email($name = null, $value = null): Input
    {
        return $this->input('email', $name, $value);
    }

    /**
     * @param string|null $name
     * @param string|null $value
     * @param bool $format
     *
     * @return Input
     */
    public function date($name = '', $value = null, $format = true): Input
    {
        $element = $this->input('date', $name, $value);

        if (! $format || empty($element->getAttribute('value'))) {
            return $element;
        }

        return $element->value($this->formatDateTime($element->getAttribute('value'), self::HTML_DATE_FORMAT));
    }

    /**
     * @param string|null $name
     * @param string|null $value
     * @param bool $format
     *
     * @return Input
     */
    public function datetime($name = '', $value = null, $format = true): Input
    {
        $element = $this->input('datetime-local', $name, $value);

        if (! $format || empty($element->getAttribute('value'))) {
            return $element;
        }

        return $element->value($this->formatDateTime(
            $element->getAttribute('value'),
            self::HTML_DATE_FORMAT.'\T'.self::HTML_TIME_FORMAT
        ));
    }

    /**
     * @param string|null $name
     * @param string|null $value
     * @param string|null $min
     * @param string|null $max
     * @param string|null $step
     *
     * @return Input
     */
    public function range($name = '', $value = '', $min = null, $max = null, $step = null): Input
    {
        return $this->input('range', $name, $value)
            ->attributeIfNotNull($min, 'min', $min)
            ->attributeIfNotNull($max, 'max', $max)
            ->attributeIfNotNull($step, 'step', $step);
    }

    /**
     * @param string|null $name
     * @param string|null $value
     * @param bool $format
     *
     * @return Input
     */
    public function time($name = '', $value = null, $format = true): Input
    {
        $element = $this->input('time', $name, $value);

        if (! $format || empty($element->getAttribute('value'))) {
            return $element;
        }

        return $element->value($this->formatDateTime($element->getAttribute('value'), self::HTML_TIME_FORMAT));
    }

    /**
     * @param string $tag
     *
     * @return Element
     * @throws ReflectionException
     * @throws ReflectionException
     */
    public function element(string $tag): Element
    {
        return Element::withTag($tag);
    }

    /**
     * @param string|null $type
     * @param string|null $name
     * @param string|null $value
     *
     * @return Input
     */
    public function input($type = null, $name = null, $value = null): Input
    {
        $hasValue = $name && ($type !== 'password' && ! is_null($this->old($name, $value)) || ! is_null($value));

        return Input::create()
            ->attributeIf($type, 'type', $type)
            ->attributeIf($name, 'name', $this->fieldName($name))
            ->attributeIf($name, 'id', $this->fieldName($name))
            ->attributeIf($hasValue, 'value', $this->old($name, $value));
    }

    /**
     * @param HtmlElement|string|null $legend
     *
     * @return Fieldset
     * @throws Exceptions\InvalidHtml
     * @throws Exceptions\InvalidChild
     */
    public function fieldset($legend = null): Fieldset
    {
        return $legend ?
            Fieldset::create()->legend($legend) : Fieldset::create();
    }

    /**
     * @param string $method
     * @param string|null $action
     *
     * @return Form
     * @throws Exceptions\InvalidChild
     * @throws Exceptions\InvalidChild
     */
    public function form($method = 'POST', $action = null): Form
    {
        $method = strtoupper($method);
        $form = Form::create();

        // If Laravel needs to spoof the form's method, we'll append a hidden
        // field containing the actual method
        if (in_array($method, ['DELETE', 'PATCH', 'PUT'])) {
            $form = $form->addChild($this->hidden('_method')->value($method));
        }

        // On any other method that get, the form needs a CSRF token
        if ($method !== 'GET') {
            $form = $form->addChild($this->token());
        }

        return $form
            ->method($method === 'GET' ? 'GET' : 'POST')
            ->attributeIf($action, 'action', $action);
    }

    /**
     * @param string|null $name
     * @param string|null $value
     *
     * @return Input
     */
    public function hidden($name = null, $value = null): Input
    {
        return $this->input('hidden', $name, $value);
    }

    /**
     * @param string|null $src
     * @param string|null $alt
     *
     * @return Img
     */
    public function img($src = null, $alt = null): Img
    {
        return Img::create()
            ->attributeIf($src, 'src', $src)
            ->attributeIf($alt, 'alt', $alt);
    }

    /**
     * @param HtmlElement|iterable|string|null $contents
     * @param string|null $for
     *
     * @return Label
     */
    public function label($contents = null, $for = null): Label
    {
        return Label::create()
            ->attributeIf($for, 'for', $this->fieldName($for))
            ->children($contents);
    }

    /**
     * @param HtmlElement|string|null $contents
     *
     * @return Legend
     * @throws Exceptions\InvalidHtml
     * @throws Exceptions\InvalidHtml
     * @throws Exceptions\InvalidChild
     */
    public function legend($contents = null): Legend
    {
        return Legend::create()->html($contents);
    }

    /**
     * @param string $email
     * @param string|null $text
     *
     * @return A
     */
    public function mailto(string $email, $text = null): A
    {
        return $this->a('mailto:'.$email, $text ?: $email);
    }

    /**
     * @param string|null $name
     * @param iterable $options
     * @param string|iterable|null $value
     *
     * @return Select
     */
    public function multiselect($name = null, $options = [], $value = null): Select
    {
        return Select::create()
            ->attributeIf($name, 'name', $this->fieldName($name))
            ->attributeIf($name, 'id', $this->fieldName($name))
            ->options($options)
            ->value($name ? $this->old($name, $value) : $value)
            ->multiple();
    }

    /**
     * @param string|null $name
     * @param string|null $value
     * @param string|null $min
     * @param string|null $max
     * @param string|null $step
     *
     * @return Input
     */
    public function number($name = null, $value = null, $min = null, $max = null, $step = null): Input
    {
        return $this->input('number', $name, $value)
                ->attributeIfNotNull($min, 'min', $min)
                ->attributeIfNotNull($max, 'max', $max)
                ->attributeIfNotNull($step, 'step', $step);
    }

    /**
     * @param string|null $text
     * @param string|null $value
     * @param bool $selected
     *
     * @return Option
     * @throws Exceptions\InvalidHtml
     * @throws Exceptions\InvalidHtml
     * @throws Exceptions\InvalidChild
     */
    public function option($text = null, $value = null, $selected = false): Option
    {
        return Option::create()
            ->text($text)
            ->value($value)
            ->selectedIf($selected);
    }

    /**
     * @param string|null $name
     *
     * @return Input
     */
    public function password(string $name = null): Input
    {
        return $this->input('password', $name);
    }

    /**
     * @param string|null $name
     * @param bool $checked
     * @param string|null $value
     *
     * @return Input
     */
    public function radio($name = null, $checked = null, $value = null): Input
    {
        return $this->input('radio', $name, $value)
            ->attributeIf($name, 'id', $value === null ? $name : ($name.'_'.Str::slug($value)))
            ->attributeIf(! is_null($value), 'value', $value)
            ->attributeIf((! is_null($value) && $this->old($name) == $value) || $checked, 'checked');
    }

    /**
     * @param string|null $name
     * @param iterable $options
     * @param string|iterable|null $value
     *
     * @return Select
     */
    public function select($name = null, $options = [], $value = null): Select
    {
        return Select::create()
            ->attributeIf($name, 'name', $this->fieldName($name))
            ->attributeIf($name, 'id', $this->fieldName($name))
            ->options($options)
            ->value($name ? $this->old($name, $value) : $value);
    }

    /**
     * @param HtmlElement|string|null $contents
     *
     * @return Span
     * @throws Exceptions\InvalidChild
     */
    public function span($contents = null): Span
    {
        return Span::create()->children($contents);
    }

    /**
     * @param string|null $text
     *
     * @return Button
     */
    public function submit($text = null): Button
    {
        return $this->button($text, 'submit');
    }

    /**
     * @param string|null $text
     *
     * @return Button
     */
    public function reset($text = null): Button
    {
        return $this->button($text, 'reset');
    }

    /**
     * @param string $number
     * @param string|null $text
     *
     * @return A
     */
    public function tel(string $number, $text = null): A
    {
        return $this->a('tel:'.$number, $text ?: $number);
    }

    /**
     * @param string|null $name
     * @param string|null $value
     *
     * @return Input
     */
    public function text($name = null, $value = null): Input
    {
        return $this->input('text', $name, $value);
    }

    /**
     * @param $contents
     * @return Ul
     * @throws Exceptions\InvalidChild
     */
    public function ul($contents = null): Ul
    {
        return Ul::create()->children($contents);
    }

    /**
     * @param $contents
     * @return Li
     * @throws Exceptions\InvalidChild
     */
    public function li($contents = null): Li
    {
        return Li::create()->children($contents);
    }

    /**
     * @param $contents
     * @return P
     * @throws Exceptions\InvalidChild
     */
    public function p($contents = null): P
    {
        return P::create()->children($contents);
    }

    /**
     * @param $contents
     * @return Strong
     * @throws Exceptions\InvalidChild
     */
    public function strong($contents = null): Strong
    {
        return Strong::create()->children($contents);
    }

    /**
     * @param string|null $name
     *
     * @return File
     */
    public function file($name = null): File
    {
        return File::create()
            ->attributeIf($name, 'name', $this->fieldName($name))
            ->attributeIf($name, 'id', $this->fieldName($name));
    }

    /**
     * @param string|null $name
     * @param string|null $value
     *
     * @return Textarea
     */
    public function textarea($name = null, $value = null): Textarea
    {
        return Textarea::create()
            ->attributeIf($name, 'name', $this->fieldName($name))
            ->attributeIf($name, 'id', $this->fieldName($name))
            ->value($this->old($name, $value));
    }

    /**
     * @return Input
     */
    public function token(): Input
    {
        return $this
            ->hidden()
            ->name('_token')
            ->value($this->request->session()->token());
    }

    /**
     * @param ArrayAccess|array $model
     *
     * @return $this
     */
    public function model($model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param ArrayAccess|array $model
     * @param string|null $method
     * @param string|null $action
     *
     * @return Form
     * @throws Exceptions\InvalidChild
     */
    public function modelForm($model, $method = 'POST', $action = null): Form
    {
        $this->model($model);

        return $this->form($method, $action);
    }

    /**
     * @return $this
     */
    public function endModel(): self
    {
        $this->model = null;

        return $this;
    }

    /**
     * @return Htmlable
     * @throws Exceptions\InvalidChild
     */
    public function closeModelForm(): Htmlable
    {
        $this->endModel();

        return $this->form()->close();
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return mixed
     */
    protected function old(string $name, $value = null)
    {
        if (empty($name)) {
            return null;
        }

        // Convert array format (sth[1]) to dot notation (sth.1)
        $name = preg_replace('/\[(.+)\]/U', '.$1', $name);

        // If there's no default value provided, the html builder currently
        // has a model assigned and there aren't old input items,
        // try to retrieve a value from the model.
        if (is_null($value) && $this->model && empty($this->request->old())) {
            $value = data_get($this->model, $name) ?? '';
        }

        return $this->request->old($name, $value);
    }

    /**
     * Retrieve the value from the current session or assigned model. This is
     * a public alias for `old`.
     *
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function value(string $name, $default = null)
    {
        return $this->old($name, $default);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function fieldName(string $name): string
    {
        return $name;
    }

    /**
     * @throws Exception
     */
    protected function ensureModelIsAvailable()
    {
        if (empty($this->model)) {
            throw new Exception('Method requires a model to be set on the html builder');
        }
    }

    /**
     * @param string $value
     * @param string $format DateTime formatting string supported by date_format()
     * @return string
     */
    protected function formatDateTime(string $value, string $format): string
    {
        if (empty($value)) {
            return $value;
        }

        try {
            $date = new DateTimeImmutable($value);

            return $date->format($format);
        } catch (Exception $e) {
            return $value;
        }
    }
}
