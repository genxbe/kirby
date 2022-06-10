<?php

namespace Kirby\Schema;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class IntegerProperty extends Property
{
    /**
     * @param int|null $value
     * @return int|null
     */
    public function set(int $value = null): ?int
    {
        return $value;
    }
}
