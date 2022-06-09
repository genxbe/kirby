<?php

namespace Kirby\Schema;

use Kirby\Toolkit\V;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
abstract class Property
{
    protected $name     = null;
    protected $required = false;
    protected $default  = null;
    protected $validate = null;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (count($arguments) === 0) {
            return $this->__get($name);
        }

        $this->{'set' . $name}($arguments[0]);
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->$name;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $this->{'set' . $name}($value);
    }

    /**
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options = [])
    {
        $this->setName($name);

        if (isset($options['default']) === true) {
            $this->setDefault($options['default']);
        }

        if (isset($options['required']) === true) {
            $this->setRequired($options['required']);
        }

        if (isset($options['validate']) === true) {
            $this->setValidate($options['validate']);
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function apply($value = null)
    {
        // typed setter
        $value = $this->set($value);

        // skip validation for empty values
        if ($value === null) {
            return null;
        }

        // validate the value
        if (is_array($this->validate) === true) {
            V::value($value, $this->validate);
        }

        return $value;
    }

    abstract public function set();

    /**
     * @param mixed $default
     * @return static
     */
    public function setDefault($default = null)
    {
        $this->default = $this->apply($default);
        return $this;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param boolean $required
     * @return static
     */
    public function setRequired(bool $required = true)
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @param array|null $validate
     * @return static
     */
    public function setValidate(array $validate = null)
    {
        $this->validate = $validate;
        return $this;
    }
}
