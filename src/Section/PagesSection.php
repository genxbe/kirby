<?php

namespace Kirby\Section;

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
     * @return array
     */
    public function props(): array
    {
        return array_merge(parent::props(), [
            'status'    => $this->status(),
            'templates' => $this->templates(),
        ]);
    }

    /**
     * @return array
     */
    public static function schema(): array
    {
        return array_merge(parent::schema(), [
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
        return $this->options['templates'];
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
