<?php

namespace Kirby\Section;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
trait EmptyState
{
    /**
     * @return string|null
     */
    public function empty(): ?string
    {
        if (($this->options['empty'] ?? null) === null) {
            return null;
        }

        return $this->model()->toSafeString($this->options['empty']);
    }
}
