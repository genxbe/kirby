<?php

namespace Kirby\Schema;

use InvalidArgumentException;

/**
 * @package   Kirby Schema
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class Schema
{
    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        foreach ($properties as $name => $options) {
            $this->add($name, $options);
        }
    }

    /**
     * @param string $property
     * @return \Kirby\Schema\Property|null
     */
    public function __get(string $property)
    {
        return $this->properties[$property] ?? null;
    }

    /**
     * @param string $property
     * @param \Kirby\Schema\Property $value
     * @return void
     */
    public function __set(string $property, Property $value): void
    {
        $this->properties[$property] = $value;
    }

    /**
     * @param string $name
     * @param array $options
     * @return \Kirby\Schema\Property
     */
    public function add(string $name, array $options = [])
    {
        $options['type'] ??= $name;
        $className = __NAMESPACE__ . '\\' . ucfirst($options['type']) . 'Property';
        $property  = new $className($name, $options);

        return $this->properties[$name] = $property;
    }

    /**
     * @param array $data
     * @return array
     */
    public function apply(array $data = []): array
    {
        $typed = [];

        foreach ($this->properties as $name => $property) {
            if (isset($data[$name]) === true) {
                $typed[$name] = $property->apply($data[$name]);
                continue;
            }

            if ($property->required === true) {
                throw new InvalidArgumentException('The argument for ' . $name . ' is missing');
            }

            $typed[$name] = $property->default;
        }

        foreach ($data as $name => $value) {
            if (isset($this->properties[$name]) === false) {
                continue;
            }

            $typed[$name] = $this->properties[$name]->apply($value);
        }

        return $typed;
    }

    /**
     * @param string $property
     * @return boolean
     */
    public function has(string $property): bool
    {
        return array_key_exists($property, $this->properties) === true;
    }

    /**
     * @return array
     */
    public function properties(): array
    {
        return $this->properties;
    }

    /**
     * @param string $key
     * @return static
     */
    public function remove(string $key)
    {
        unset($this->properties[$key]);
        return $this;
    }
}
