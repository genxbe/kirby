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
            'size'    => $this->options['size']
        ];
    }

    /**
     * @return array
     */
    public function reports(): array
    {
        $reports = $this->options['reports'];

        if (is_string($reports) === true) {
            $reports = $this->model()->query($reports);
        }

        if (is_array($reports) === false) {
            return [];
        }

        $data = [];

        foreach ($reports as $report) {
            if (is_string($report) === true) {
                $report = $this->model->query($report);
            }

            if (is_array($report) === false) {
                continue;
            }

            $data[] = [
                'label' => $this->i18n($report['label']),
                'value' => $this->value($report['value'] ?? null),
                'info'  => $this->value($report['info'] ?? null),
                'link'  => $this->value($report['link'] ?? null),
                'theme' => $this->value($report['theme'] ?? null)
            ];
        }

        return [];
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'stats';
    }

    /**
     * @param string|null $value
     * @return string|null
     */
    public function value(?string $value = null): ?string
    {
        return $value === null ? null : $this->model->toString($value);
    }
}
