<?php

namespace Kirby\Section;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
trait Pagination
{
    /**
     * @return integer
     */
    public function limit(): int
    {
        return $this->options['limit'] ?? 20;
    }

    /**
     * @return integer
     */
    public function page(): int
    {
        return $this->options['page'] ?? get('page') ?? 1;
    }

    /**
     * @return array
     */
    public function pagination(): array
    {
        $pagination = new \Kirby\Toolkit\Pagination([
            'limit' => $this->limit(),
            'page'  => $this->page(),
            'total' => $this->total()
        ]);

        return [
            'limit'  => $pagination->limit(),
            'offset' => $pagination->offset(),
            'page'   => $pagination->page(),
            'total'  => $pagination->total(),
        ];
    }

    /**
     * @return int
     */
    public function total(): int
    {
        return 0;
    }
}
