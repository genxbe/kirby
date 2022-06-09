<?php

namespace Kirby\Schema;

use Kirby\Exception\InvalidArgumentException;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class EnumProperty extends Property
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options = [])
    {
        if (isset($options['values']) === true) {
            $this->setValues($options['values']);
        }

        parent::__construct($name, $options);
    }

    /**
     * @param mixed|null $value
     * @return mixed
     */
    public function set($value = null)
    {
        if ($value !== null && in_array($value, $this->values, true) === false) {
            throw new InvalidArgumentException('The value for "' . $this->name . '" is not allowed.');
        }

        return $value;
    }

    /**
     * @param array $values
     * @return static
     */
    public function setValues(array $values = [])
    {
        $this->values = $values;
        return $this;
    }
}
