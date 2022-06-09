<?php

namespace Kirby\Schema;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class AnyProperty extends Property
{
    /**
     * @param mixed|null $value
     * @return null
     */
    public function set($value = null): ?array
    {
        return $value;
    }
}
