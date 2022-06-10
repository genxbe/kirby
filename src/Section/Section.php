<?php

namespace Kirby\Section;

use Kirby\Cms\ModelWithContent;
use Kirby\Schema\Schema;
use Kirby\Toolkit\I18n;
use Throwable;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
abstract class Section
{
    /**
     * Untyped array of attributes as receieved by
     * the constructor
     *
     * @var array
     */
    protected $attrs;

    /**
     * @var \Kirby\Cms\ModelWithContent
     */
    protected $model;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param \Kirby\Cms\ModelWithContent $model
     * @param array $attrs
     */
    public function __construct(ModelWithContent $model, string $name, array $attrs = [])
    {
        $this->model = $model;
        $this->name  = $name;

        $this->createOptions($attrs);
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function attrs(): array
    {
        return $this->attrs;
    }

    /**
     * @param array $attrs
     * @return array
     */
    protected function createOptions(array $attrs = []): array
    {
        // keep the original attributes
        $this->attrs = $attrs;

        // load the schema
        $schema = $this->schema();

        // create the typed options array
        $options = empty($schema) === false ? (new Schema($schema))->apply($attrs) : $attrs;

        return $this->options = $options;
    }

    /**
     * @param string $error
     * @param array $placeholders
     * @param int|null $form
     * @return string|null
     */
    public function errorMessage(string $error, array $placeholders = [], int $form = null): ?string
    {
        return I18n::template($this->errorMessageKey($error, $form), $placeholders);
    }

    /**
     * Create an error message key for the section and error type
     *
     * @param string $error
     * @param int|null $form
     * @return string
     */
    public function errorMessageKey(string $error, ?int $form = null): string
    {
        $key = 'error.section.' . $this->type() . '.' . $error;

        if ($form === null) {
            return $key;
        }

        // add plural or singular
        return $key . '.' . I18n::form($form);
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        try {
            $this->validate();
            return [];
        } catch (Throwable $e) {
            return [
                'label'   => $this->label(),
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param array|string|null $value
     * @return string|null
     */
    public function i18n($value = null): ?string
    {
        return $value === null ? null : I18n::translate($value, $value);
    }

    /**
     * @return string|null
     */
    public function help(): ?string
    {
        $help = $this->stringTemplate($this->options['help'] ?? null);
        $help = $this->stringToKirbytext($help);

        return $help;
    }

    /**
     * @return \Kirby\Cms\App
     */
    public function kirby()
    {
        return $this->model->kirby();
    }

    /**
     * @return string
     */
    public function label(): string
    {
        $this->options['label'] ??= ucfirst($this->name);
        return $this->stringTemplate($this->options['label']);
    }

    /**
     * @return \Kirby\Cms\ModelWithContent
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function props(): array
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public static function schema(): array
    {
        return [
            'help' => [
                'type' => 'i18n',
            ],
            'label' => [
                'type'  => 'i18n',
                'alias' => 'headline'
            ],
        ];
    }

    /**
     * @param string|null $query
     * @return string|null
     */
    public function stringQuery(?string $query = null)
    {
        if ($query === null) {
            return null;
        }

        return $this->model()->query($query);
    }

    /**
     * @param string|null $template
     * @param bool $safe
     * @return string|null
     */
    public function stringTemplate(?string $template = null, bool $safe = true): ?string
    {
        if ($template === null) {
            return null;
        }

        if ($safe === true) {
            return $this->model()->toSafeString($template);
        }

        return $this->model()->toString($template);
    }

    /**
     * @param string|null $text
     * @return string|null
     */
    public function stringToKirbytext(string $text = null): ?string
    {
        if ($text === null) {
            return null;
        }

        return $this->kirby()->kirbytext($text);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->props();
    }

    /**
     * @return array
     */
    public function toResponse(): array
    {
        return array_merge([
            'code'   => 200,
            'name'   => $this->name(),
            'status' => 'ok',
            'type'   => $this->type(),
        ], $this->props());
    }

    /**
     * @return string
     */
    abstract public function type(): string;

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }
}
