<?php

namespace Kirby\Section;

use Kirby\Form\Form;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class FieldsSection extends Section
{

    /**
     * @var \Kirby\Form\Form
     */
    protected $form;

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->form()->errors();
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        $fields = $this->form()->fields()->toArray();

        // the title should never be updated directly via
        // fields section to avoid conflicts with the rename dialog
        if (is_a($this->model, 'Kirby\Cms\Page') === true || is_a($this->model, 'Kirby\Cms\Site') === true) {
            unset($fields['title']);
        }

        // remove the value property from each field
        foreach ($fields as $index => $props) {
            unset($fields[$index]['value']);
        }

        return $fields;
    }

    /**
     * @return \Kirby\Form\Form
     */
    public function form()
    {
        if ($this->form !== null) {
            return $this->form;
        }

        $fields   = $this->options['fields'];
        $disabled = $this->model->permissions()->update() === false;
        $lang     = $this->model->kirby()->languageCode();
        $content  = $this->model->content($lang)->toArray();

        if ($disabled === true) {
            foreach ($fields as $key => $props) {
                $fields[$key]['disabled'] = true;
            }
        }

        return $this->form = new Form([
            'fields' => $fields,
            'values' => $content,
            'model'  => $this->model,
            'strict' => true
        ]);
    }

    /**
     * @return array
     */
    public function props(): array
    {
        return [
            'fields' => $this->fields()
        ];
    }

    /**
     * @return array
     */
    public static function schema(): array
    {
        return [
            'fields' => [
                'type'    => 'array',
                'default' => []
            ]
        ];
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'fields';
    }

}
