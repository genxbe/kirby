<?php

namespace Kirby\Schema;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class WidthProperty extends EnumProperty
{
    /**
     * @var string
     */
    protected $default = '1/1';

    /**
     * @var array
     */
    protected $values = ['1/1', '1/2', '1/3', '1/4', '2/3'];
}
