<?php

namespace Kirby\Section;

use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;

/**
 * @package   Kirby Section
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier
 * @license   https://opensource.org/licenses/MIT
 */
trait ParentModel
{
    /**
     * @var \Kirby\Cms\Site|\Kirby\Cms\Page
     */
    protected $parent;

    /**
     * Getter for the parent model
     *
     * @return \Kirby\Cms\Site|\Kirby\Cms\Page
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * Setter for the parent model
     *
     * @param string|null $query
     * @return \Kirby\Cms\Site|\Kirby\Cms\Page
     */
    public function setParent(?string $query = null)
    {
        if ($query === null) {
            return $this->parent = $this->model;
        }

        $parent = $this->model->query($query);

        if (empty($parent) === true) {
            throw new NotFoundException('The parent for the query "' . $query . '" cannot be found');
        }

        if (
            is_a($parent, 'Kirby\Cms\Page') === false &&
            is_a($parent, 'Kirby\Cms\Site') === false
        ) {
            throw new InvalidArgumentException('The parent is invalid. You must choose the site or a page as parent.');
        }

        return $this->parent = $parent;
    }

}
