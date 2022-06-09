<?php

namespace Kirby\Section;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
abstract class ModelsSection extends Section
{

    use EmptyState;
    use Help;
    use Label;
    use Pagination;
    use ParentModel;

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->setParent($this->options['parent']);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function info(): ?string
    {
        return $this->options['info'];
    }

    /**
     * @return string
     */
    public function layout(): string
    {
        return $this->options['layout'];
    }

    /**
     * @return string|null
     */
    public function link(): ?string
    {
        $linkToModel  = $this->model->panel()->url(true);
        $linkToParent = $this->parent->panel()->url(true);

        if ($linkToModel === $linkToParent) {
            return null;
        }

        return $linkToParent;
    }

    /**
     * @return array
     */
    public function props(): array
    {
        return [
            'data'       => $this->data(),
            'errors'     => $this->errors(),
            'empty'      => $this->empty(),
            'help'       => $this->help(),
            'label'      => $this->label(),
            'layout'     => $this->layout(),
            'link'       => $this->link(),
            'pagination' => $this->pagination(),
            'sortable'   => $this->sortable(),
            'size'       => $this->size(),
        ];
    }

    /**
     * @return array
     */
    public static function schema(): array
    {
        return [
            'empty' => [
                'type' => 'i18n'
            ],
            'info' => [
                'type' => 'i18n'
            ],
            'label' => [
                'type' => 'i18n',
            ],
            'layout' => [
                'type' => 'layout',
            ],
            'limit' => [
                'type'    => 'integer',
                'default' => 20
            ],
            'max' => [
                'type' => 'integer'
            ],
            'min' => [
                'type'    => 'integer',
                'default' => 0
            ],
            'page' => [
                'type' => 'integer',
            ],
            'parent' => [
                'type' => 'string',
            ],
            'search' => [
                'type'    => 'boolean',
                'default' => false
            ],
            'size' => [
                'type'    => 'enum',
                'default' => 'auto',
                'values'  => ['auto', 'tiny', 'small', 'medium', 'large', 'huge']
            ],
            'sortable' => [
                'type'    => 'boolean',
                'default' => true,
            ],
            'sortBy' => [
                'type' => 'string'
            ],
            'text' => [
                'type' => 'i18n',
            ],
            'help' => [
                'type' => 'i18n',
            ],
        ];
    }

    /**
     * @return string
     */
    public function size(): string
    {
        return $this->options['size'];
    }

    /**
     * @return bool
     */
    public function sortable(): bool
    {
        return $this->options['sortable'];
    }

    /**
     * @return string
     */
    public function text(): string
    {
        return $this->options['text'] ?? '{{ model.id }}';
    }


}
