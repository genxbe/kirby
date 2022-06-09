<?php

namespace Kirby\Section;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class InfoSection extends Section
{

    use Label;

    /**
     * @return array
     */
    public static function schema(): array
    {
        return [
            'label' => [
                'type' => 'i18n'
            ],
            'text' => [
                'type' => 'i18n'
            ],
            'theme' => [
                'type' => 'string'
            ]
        ];
    }

    /**
     * @return array
     */
    public function props(): array
    {
        return [
            'label' => $this->label(),
            'text'  => $this->text(),
            'theme' => $this->options['theme']
        ];
    }

    /**
     * @return string|null
     */
    public function text(): ?string
    {
        if ($this->options['text'] === null) {
            return null;
        }

        $text = $this->model()->toSafeString($this->options['text']);
        $text = $this->kirby()->kirbytext($text);
        return $text;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'info';
    }
}
