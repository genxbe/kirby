<?php

namespace Kirby\Section;

use Kirby\Cms\ModelWithContent;
use Kirby\Schema\Schema;
use Kirby\Toolkit\I18n;

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
     * @param array $options
     */
    public function __construct(ModelWithContent $model, string $name, array $options = [])
    {
        $this->model = $model;
        $this->name  = $name;

        // load the schema
        $schema = new Schema($this->schema());

        // create the typed options array
        $this->options = $schema->apply($options);
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
     * @return \Kirby\Cms\App
     */
    public function kirby()
    {
        return $this->model->kirby();
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
        return [];
    }

    /**
     * Lifecycle hook when the instance is created
     *
     * @return void
     */
    protected function setup(): void
    {

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
        return [
            'name'  => $this->name(),
            'props' => $this->props(),
            'type'  => $this->type(),
        ];
    }

    /**
     * @return string
     */
    abstract public function type(): string;

}
