<?php

namespace Cruxinator\LaravelHtml\Elements;

use Cruxinator\LaravelHtml\BaseElement;
use Cruxinator\LaravelHtml\Elements\Attributes\Autofocus;
use Cruxinator\LaravelHtml\Elements\Attributes\Name;
use Cruxinator\LaravelHtml\Elements\Attributes\Required;
use Cruxinator\LaravelHtml\Exceptions\MissingTag;

/**
 * @method attributeIf(string|null $name, string $string, string $fieldName): File
 */
class File extends BaseElement
{
    use Autofocus;
    use Name;
    use Required;

    protected $tag = 'input';

    public const ACCEPT_AUDIO = 'audio/*';

    public const ACCEPT_VIDEO = 'video/*';

    public const ACCEPT_IMAGE = 'image/*';

    /**
     * File constructor.
     * @throws MissingTag
     */
    public function __construct()
    {
        parent::__construct();

        $this->attributes->setAttribute('type', 'file');
    }

    /**
     * @param string|null $type
     *
     * @return static
     */
    public function accept(?string $type): self
    {
        return $this->attribute('accept', $type);
    }

    /**
     * @return static
     */
    public function acceptAudio(): self
    {
        return $this->attribute('accept', self::ACCEPT_AUDIO);
    }

    /**
     * @return static
     */
    public function acceptVideo(): self
    {
        return $this->attribute('accept', self::ACCEPT_VIDEO);
    }

    /**
     * @return static
     */
    public function acceptImage(): self
    {
        return $this->attribute('accept', self::ACCEPT_IMAGE);
    }

    /**
     * @return static
     */
    public function multiple(): self
    {
        return $this->attribute('multiple');
    }
}
