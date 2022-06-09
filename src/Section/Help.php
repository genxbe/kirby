<?php

namespace Kirby\Section;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
trait Help
{
    /**
     * @return string|null
     */
    public function help(): ?string
    {
        if (($this->options['help'] ?? null) === null) {
            return null;
        }

        $help = $this->model()->toSafeString($this->options['help']);
        $help = $this->kirby()->kirbytext($help);
        return $help;
    }
}
