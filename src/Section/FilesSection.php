<?php

namespace Kirby\Section;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
class FilesSection extends ModelsSection
{
    /**
     * @return string
     */
    public function type(): string
    {
        return 'files';
    }
}
