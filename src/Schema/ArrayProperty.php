<?php

namespace Kirby\Schema;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class ArrayProperty extends Property
{
    /**
     * @param array|null $value
     * @return array|null
     */
    public function set(array $value = null): ?array
    {
        return $value;
    }
}
