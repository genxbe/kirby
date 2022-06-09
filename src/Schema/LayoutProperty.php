<?php

namespace Kirby\Schema;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class LayoutProperty extends EnumProperty
{
    /**
     * @var string
     */
    protected $default = 'list';

    /**
     * @var array
     */
    protected $values = ['cardlets', 'cards', 'list', 'table'];
}
