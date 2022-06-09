<?php

namespace Kirby\Schema;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class BooleanProperty extends Property
{
    /**
     * @param boolean|null $value
     * @return boolean|null
     */
    public function set(bool $value = null): ?bool
    {
        return $value;
    }
}
