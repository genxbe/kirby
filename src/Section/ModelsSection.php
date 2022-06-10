<?php

namespace Kirby\Section;

use Kirby\Cms\Collection;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Toolkit\V;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
abstract class ModelsSection extends Section
{
    /**
     * @var \Kirby\Cms\Collection
     */
    protected $models;

    /**
     * @var \Kirby\Cms\ModelWithContent
     */
    protected $parent;

    /**
     * @param \Kirby\Cms\ModelWithContent $model
     * @param array $options
     */
    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->createParent($this->options['parent']);
    }

    /**
     * @return bool
     */
    public function add(): bool
    {
        if ($this->isFull() === true) {
            return false;
        }

        return true;
    }

    /**
     * @param \Kirby\Cms\Collection $models
     * @return \Kirby\Cms\Collection
     */
    public function applyFlip(Collection $models)
    {
        if ($this->options['flip'] === false) {
            return $models;
        }

        return $models->flip();
    }

    /**
     * @param \Kirby\Cms\Collection $models
     * @return \Kirby\Cms\Collection
     */
    public function applyPagination(Collection $models)
    {
        return $models->paginate([
            'limit' => $this->limit(),
            'page'  => $this->page()
        ]);
    }

    /**
     * @param \Kirby\Cms\Collection $models
     * @return \Kirby\Cms\Collection
     */
    public function applySearch(Collection $models)
    {
        if (empty($query = $this->query()) === true) {
            return $models;
        }

        return $models->search($query);
    }

    /**
     * @param \Kirby\Cms\Collection $models
     * @return \Kirby\Cms\Collection
     */
    public function applySort(Collection $models)
    {
        if ($this->options['sortBy'] === null) {
            return $models;
        }

        return $models->sort(...$models::sortArgs($this->options['sortBy']));
    }

    /**
     * Setter for the parent model
     *
     * @param string|null $query
     * @return \Kirby\Cms\ModelWithContent
     */
    protected function createParent(?string $query = null)
    {
        if ($query === null) {
            return $this->parent = $this->model;
        }

        $parent = $this->model->query($query);

        if (empty($parent) === true) {
            throw new NotFoundException('The parent for the query "' . $query . '" cannot be found');
        }

        if (is_a($parent, 'Kirby\Cms\ModelWithContent') === false) {
            throw new InvalidArgumentException('The parent is invalid. You must choose the site, a page, a file or user as parent.');
        }

        return $this->parent = $parent;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function empty(): ?string
    {
        return $this->stringTemplate($this->options['empty']);
    }

    /**
     * @return bool
     */
    public function flip(): bool
    {
        return $this->options['flip'];
    }

    /**
     * @return array|bool|string
     */
    public function image()
    {
        return $this->options['image'];
    }

    /**
     * @return string|null
     */
    public function info(): ?string
    {
        return $this->options['info'];
    }

    /**
     * @return bool
     */
    public function isFull(): bool
    {
        if ($max = $this->max()) {
            return $this->total() >= $max;
        }

        return false;
    }

    /**
     * @return string
     */
    public function layout(): string
    {
        return $this->options['layout'];
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return $this->options['limit'] ?? 20;
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
     * @return int|null
     */
    public function max(): ?int
    {
        return $this->options['max'];
    }

    /**
     * @return int
     */
    public function min(): int
    {
        return $this->options['min'];
    }

    /**
     * @return \Kirby\Cms\Collection
     */
    abstract public function models();

    /**
     * @return int
     */
    public function page(): int
    {
        return $this->options['page'] ?? $this->kirby()->request()->get('page') ?? 1;
    }

    /**
     * @return array
     */
    public function pagination(): array
    {
        $pagination = $this->models()->pagination();

        return [
            'limit'  => $pagination->limit(),
            'offset' => $pagination->offset(),
            'page'   => $pagination->page(),
            'total'  => $pagination->total(),
        ];
    }

    /**
     * Getter for the parent model
     *
     * @return \Kirby\Cms\ModelWithContent
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function props(): array
    {
        return [
            'data'    => $this->data(),
            'errors'  => $this->errors(),
            'options' => [
                'add'      => $this->add(),
                'empty'    => $this->empty(),
                'flip'     => $this->flip(),
                'help'     => $this->help(),
                'label'    => $this->label(),
                'layout'   => $this->layout(),
                'link'     => $this->link(),
                'max'      => $this->max(),
                'min'      => $this->min(),
                'query'    => $this->query(),
                'search'   => $this->search(),
                'size'     => $this->size(),
                'sortable' => $this->sortable(),
            ],
            'pagination' => $this->pagination(),
        ];
    }

    /**
     * @return string|null
     */
    public function query(): ?string
    {
        return $this->options['search'] === true ? get('query') : null;
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
            'flip' => [
                'type'    => 'boolean',
                'default' => false,
            ],
            'help' => [
                'type' => 'i18n',
            ],
            'image' => [
                'type'    => 'any',
                'default' => []
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
        ];
    }

    /**
     * @return bool
     */
    public function search(): bool
    {
        return $this->options['search'];
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
        // not sortable if deactivated
        if ($this->options['sortable'] === false) {
            return false;
        }

        // not sortable during search
        if (empty($this->query()) === false) {
            return false;
        }

        // not sortable with custom sorting
        if ($this->options['sortBy'] !== null) {
            return false;
        }

        // not sortable when flipped
        if ($this->options['flip'] === true) {
            return false;
        }

        return true;
    }

    /**
     * @return string|null
     */
    public function sortBy(): ?string
    {
        return $this->options['sortBy'];
    }

    /**
     * @return string
     */
    public function text(): string
    {
        return $this->options['text'] ?? '{{ model.id }}';
    }

    /**
     * @return int
     */
    public function total(): int
    {
        return $this->models()->pagination()->total();
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $max   = $this->max();
        $min   = $this->min();
        $total = $this->total();

        $placeholders = [
            'min'     => $min,
            'max'     => $max,
            'section' => $this->label()
        ];

        return V::value(
            $total,
            [
                'min' => $min,
                'max' => $max
            ],
            [
                'min' => $this->errorMessage('min', $placeholders, $min),
                'max' => $this->errorMessage('max', $placeholders, $max),
            ]
        );
    }
}
