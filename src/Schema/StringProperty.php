<?php

namespace Kirby\Schema;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class StringProperty extends Property
{
    /**
     * @param string|null $value
     * @return string|null
     */
    public function set(string $value = null): ?string
    {
        return $value;
    }
}
