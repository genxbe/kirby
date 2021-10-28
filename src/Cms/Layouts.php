<?php

namespace Kirby\Cms;

use Kirby\Data\Data;
use Throwable;

/**
 * A collection of layouts
 * @since 3.5.0
 *
 * @package   Kirby Cms
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier GmbH
 * @license   https://getkirby.com/license
 */
class Layouts extends Items
{
    const ITEM_CLASS = '\Kirby\Cms\Layout';

    public static function factory(array $items = null, array $params = [])
    {
        $first = $items[0] ?? [];

        // if there are no wrapping layouts for blocks yet …
        if (array_key_exists('content', $first) === true || array_key_exists('type', $first) === true) {
            $items = [
                [
                    'id'      => uuid(),
                    'columns' => [
                        [
                            'width'  => '1/1',
                            'blocks' => $items
                        ]
                    ]
                ]
            ];
        }

        return parent::factory($items, $params);
    }

    /**
     * Checks if a given block type exists in the layouts collection
     *
     * @param string $type
     * @return bool
     */
    public function hasBlockType(string $type): bool
    {
        return $this->toBlocks()->hasType($type);
    }

    /**
     * Parse layouts data
     *
     * @param array|string $input
     * @return array
     */
    public static function parse($input): array
    {
        if (empty($input) === false && is_array($input) === false) {
            try {
                $input = Data::decode($input, 'json');
            } catch (Throwable $e) {
                return [];
            }
        }

        if (empty($input) === true) {
            return [];
        }

        return $input;
    }

    /**
     * Converts layouts to blocks
     *
     * @param bool $includeHidden Sets whether to include hidden blocks
     * @return \Kirby\Cms\Blocks
     */
    public function toBlocks(bool $includeHidden = false)
    {
        $blocks = [];

        if ($this->isNotEmpty() === true) {
            foreach ($this->data() as $layout) {
                foreach ($layout->columns() as $column) {
                    foreach ($column->blocks($includeHidden) as $block) {
                        $blocks[] = $block->toArray();
                    }
                }
            }
        }

        return Blocks::factory($blocks);
    }
}
