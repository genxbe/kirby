<?php

namespace Kirby\Section;

use Kirby\Cms\File;
use Kirby\Cms\Files;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class FilesSection extends ModelsSection
{

    /**
     * @return string|null
     */
    public function accept(): ?string
    {
        if ($template = $this->template()) {
            $file = new File([
                'filename' => 'tmp',
                'parent'   => $this->model(),
                'template' => $template
            ]);

            return $file->blueprint()->acceptMime();
        }

        return null;
    }

    /**
     * @return string
     */
    public function api(): string
    {
        return $this->parent->apiUrl(true);
    }

    /**
     * @param \Kirby\Cms\Files $models
     * @return \Kirby\Cms\Files
     */
    public function applyFilter(Files $models)
    {
        return $models->template($this->template())->filter('isReadable', true);
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

        // the drag text needs to be absolute when the files
        // come from a different parent model
        $absolute = $this->model->is($this->parent) === false;

        foreach ($this->models() as $item) {
            $panel = $item->panel();

            $data[] = [
                'dragText'  => $panel->dragText('auto', $absolute),
                'extension' => $item->extension(),
                'filename'  => $item->filename(),
                'id'        => $item->id(),
                'image'     => $panel->image($image, $layout),
                'info'      => $item->toSafeString($info),
                'link'      => $panel->url(true),
                'mime'      => $item->mime(),
                'parent'    => $item->parent()->panel()->path(),
                'template'  => $item->template(),
                'text'      => $item->toSafeString($text),
                'url'       => $item->url(),
            ];
        }

        return $data;
    }

    /**
     * @return \Kirby\Cms\Files
     */
    public function models()
    {
        if ($this->models !== null) {
            return $this->models;
        }

        $models = $this->parent()->files();
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
    public function props(): array
    {
        return array_replace_recursive(parent::props(), [
            'options' => [
                'accept' => $this->accept(),
                'apiUrl' => $this->api(),
                'upload' => $this->upload()
            ]
        ]);
    }

    /**
     * @return array
     */
    public static function schema(): array
    {
        return array_merge(parent::schema(), [
            'template' => [
                'type' => 'string'
            ]
        ]);
    }

    /**
     * @return string|null
     */
    public function template(): ?string
    {
        return $this->options['template'];
    }

    /**
     * @return string
     */
    public function text(): string
    {
        return $this->options['text'] ?? '{{ file.filename }}';
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'files';
    }

    /**
     * @return array|false
     */
    public function upload()
    {
        if ($this->add() === false) {
            return false;
        }

        $sortable = $this->sortable();
        $template = $this->template();
        $total    = $this->total();
        $max      = $this->max();
        $multiple = $max && $total === $max - 1 ? false : true;

        // attributes to be added for new uploads
        $attributes = array_filter([
            'sort'     => $sortable === false     ? null : $total + 1,
            'template' => $template === 'default' ? null : $template
        ]);

        return [
            'accept'     => $this->accept(),
            'api'        => $this->api() . '/files',
            'attributes' => $attributes,
            'max'        => $max ? $max - $total : null,
            'multiple'   => $multiple,
        ];
    }
}
