<?php

namespace Kirby\Section;

use Kirby\Cms\Blueprint;
use Kirby\Cms\Pages;
use Kirby\Toolkit\A;
use Throwable;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class PagesSection extends ModelsSection
{

    /**
     * @return boolean
     */
    public function add(): bool
    {
        if ($this->create() === false) {
            return false;
        }

        if (in_array($this->status(), ['draft', 'all']) === false) {
            return false;
        }

        return parent::add();
    }

    /**
     * @param \Kirby\Cms\Pages $models
     * @return \Kirby\Cms\Pages
     */
    public function applyFilter(Pages $models)
    {
        // fetch the templates to filter by
        $templates = $this->templates();

        // filters pages that are protected and not in the templates list
        // internal `filter()` method used instead of foreach loop that previously included `unset()`
        // because `unset()` is updating the original data, `filter()` is just filtering
        // also it has been tested that there is no performance difference
        // even in 0.1 seconds on 100k virtual pages
        return $models->filter(function ($page) use ($templates) {
            // remove all protected pages
            if ($page->isReadable() === false) {
                return false;
            }

            // filter by all set templates
            if ($templates && in_array($page->intendedTemplate()->name(), $templates) === false) {
                return false;
            }

            return true;
        });
    }

    /**
     * @return array
     */
    public function blueprints(): array
    {
        $create     = $this->create();
        $blueprints = [];
        $templates  = empty($create) === false ? A::wrap($create) : $this->templates();

        if (empty($templates) === true) {
            $templates = $this->kirby()->blueprints();
        }

        // convert every template to a usable option array
        // for the template select box
        foreach ($templates as $template) {
            try {
                $props = Blueprint::load('pages/' . $template);

                $blueprints[] = [
                    'name'  => basename($props['name']),
                    'title' => $props['title'],
                ];
            } catch (Throwable $e) {
                $blueprints[] = [
                    'name'  => basename($template),
                    'title' => ucfirst($template),
                ];
            }
        }

        return $blueprints;
    }

    /**
     * @return array|bool
     */
    public function create()
    {
        return $this->options['create'];
    }

    /**
     * @return array
     */
    public function data(): array
    {
        $data   = [];
        $image  = $this->image();
        $info   = $this->info() ?? false;
        $text   = $this->text();
        $layout = $this->layout();

        foreach ($this->models() as $item) {
            $panel       = $item->panel();
            $permissions = $item->permissions();

            $data[] = [
                'dragText'    => $panel->dragText(),
                'id'          => $item->id(),
                'image'       => $panel->image($image, $layout),
                'info'        => $item->toSafeString($info),
                'link'        => $panel->url(true),
                'parent'      => $item->parentId(),
                'permissions' => [
                    'sort'         => $permissions->can('sort'),
                    'changeSlug'   => $permissions->can('changeSlug'),
                    'changeStatus' => $permissions->can('changeStatus'),
                    'changeTitle'  => $permissions->can('changeTitle'),
                ],
                'status'      => $item->status(),
                'template'    => $item->intendedTemplate()->name(),
                'text'        => $item->toSafeString($text),
            ];
        }

        return $data;
    }

    /**
     * @return \Kirby\Cms\Pages
     */
    public function models()
    {
        if ($this->models !== null) {
            return $this->models;
        }

        switch ($this->status()) {
            case 'draft':
                $models = $this->parent()->drafts();
                break;
            case 'listed':
                $models = $this->parent()->children()->listed();
                break;
            case 'published':
                $models = $this->parent()->children();
                break;
            case 'unlisted':
                $models = $this->parent()->children()->unlisted();
                break;
            default:
                $models = $this->parent()->childrenAndDrafts();
        }

        $models = $this->applyFilter($models);
        $models = $this->applySearch($models);
        $models = $this->applySort($models);
        $models = $this->applyFlip($models);
        $models = $this->applyPagination($models);

        return $this->models = $models;
    }

    /**
     * @return array
     */
    public static function schema(): array
    {
        return array_merge(parent::schema(), [
            'create' => [
                'type' => 'any'
            ],
            'status' => [
                'type'    => 'enum',
                'values'  => ['all', 'draft', 'published', 'listed', 'unlisted'],
                'default' => 'all'
            ],
            'templates' => [
                'type' => 'array',
            ]
        ]);
    }

    /**
     * @return bool
     */
    public function sortable(): bool
    {
        // unsortable status
        if (in_array($this->options['status'], ['listed', 'published', 'all']) === false) {
            return false;
        }

        return parent::sortable();
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->options['status'];
    }

    /**
     * @return array|null
     */
    public function templates(): ?array
    {
        return A::wrap($this->options['templates'] ?? []);
    }

    /**
     * @return string
     */
    public function text(): string
    {
        return $this->options['text'] ?? '{{ page.title }}';
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'pages';
    }
}
