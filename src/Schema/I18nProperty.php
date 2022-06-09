<?php

namespace Kirby\Schema;

use Kirby\Toolkit\I18n;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class I18nProperty extends Property
{
    /**
     * @param string|array|null $value
     * @return string|null
     */
    public function set($value = null): ?string
    {
        return $value !== null ? I18n::translate($value, $value) : null;
    }
}
