<?php

namespace Kirby\Section;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
trait Label
{
    /**
     * @return string
     */
    public function label(): string
    {
        if ($this->options['label'] === null) {
            return ucfirst($this->name);
        }

        return $this->model()->toString($this->options['label']);
    }
}
