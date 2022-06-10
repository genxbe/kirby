<?php

namespace Kirby\Section;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class StatsSection extends Section
{
    /**
     * @return array
     */
    public static function schema(): array
    {
        return [
            'label' => [
                'type' => 'i18n'
            ],
            'reports' => [
                'type' => 'any'
            ],
            'size' => [
                'type'    => 'string',
                'default' => 'large'
            ]
        ];
    }

    /**
     * @return array
     */
    public function props(): array
    {
        return [
            'label'   => $this->label(),
            'reports' => $this->reports(),
            'size'    => $this->size()
        ];
    }

    /**
     * @return array
     */
    public function reports(): array
    {
        $reports = $this->options['reports'];

        if (is_string($reports) === true) {
            $reports = $this->stringQuery($reports);
        }

        if (is_array($reports) === false) {
            return [];
        }

        $data = [];

        foreach ($reports as $report) {
            if (is_string($report) === true) {
                $report = $this->stringQuery($report);
            }

            if (is_array($report) === false) {
                continue;
            }

            $data[] = [
                'label' => $this->i18n($report['label']),
                'value' => $this->stringTemplate($report['value'] ?? null),
                'info'  => $this->stringTemplate($report['info'] ?? null),
                'link'  => $this->stringTemplate($report['link'] ?? null),
                'theme' => $this->stringTemplate($report['theme'] ?? null)
            ];
        }

        return [];
    }

    /**
     * @return string|null
     */
    public function size(): ?string
    {
        return $this->options['size'];
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'stats';
    }

}
