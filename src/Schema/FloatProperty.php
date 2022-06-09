<?php

namespace Kirby\Schema;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class FloatProperty extends Property
{
    /**
     * @param float|null $value
     * @return float|null
     */
    public function set(float $value = null): ?float
    {
        return $value;
    }
}
