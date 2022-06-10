<?php

namespace Kirby\Section;

use Kirby\Cms\Collection;
use Kirby\Cms\Page;
use Kirby\Cms\Pages;

class TestModelsSection extends ModelsSection
{
    public function models()
    {
        $models = $this->attrs['models'] ?? new Collection(range('a', 'z'));



        $models = $this->applySearch($models);
        $models = $this->applySort($models);
        $models = $this->applyFlip($models);
        $models = $this->applyPagination($models);

        return $models;
    }

    public function type(): string
    {
        return 'test';
    }
}

/**
 * @covers \Kirby\Section\ModelsSection
 */
class ModelsSectionTest extends TestCase
{

    public function section(array $options = [])
    {
        $model = new Page(['slug' => 'test']);
        return new TestModelsSection($model, 'test', $options);
    }

    /**
     * @covers ::data
     */
    public function testData()
    {
        $section = $this->section();
        $this->assertSame([], $section->data());
    }

    /**
     * @covers ::errors
     * @covers ::validate
     */
    public function testErrors()
    {
        $this->app([
            'translations' => [
                'en' => [
                    'error.section.test.max.plural' => 'Add no more than {max} items',
                    'error.section.test.min.plural' => 'Add at least {min} items'
                ]
            ]
        ]);

        // no errors
        $section = $this->section();
        $this->assertSame([], $section->errors());

        // max error
        $section = $this->section(['max' => 25]);
        $this->assertSame(['label' => 'Test', 'message' => 'Add no more than 25 items'], $section->errors());

        // min error
        $section = $this->section(['min' => 5, 'models' => new Collection]);
        $this->assertSame(['label' => 'Test', 'message' => 'Add at least 5 items'], $section->errors());
    }

    /**
     * @covers ::applyFlip
     * @covers ::flip
     */
    public function testFlip()
    {
        // default
        $section = $this->section();
        $this->assertFalse($section->flip());

        // active
        $section = $this->section(['flip' => true]);
        $this->assertTrue($section->flip());
        $this->assertSame('z', $section->models()->first());

        // inactive
        $section = $this->section(['flip' => false]);
        $this->assertFalse($section->flip());
        $this->assertSame('a', $section->models()->first());
    }

    /**
     * @covers ::info
     */
    public function testInfo()
    {
        // default
        $section = $this->section();
        $this->assertNull($section->info());

        // custom
        $section = $this->section(['info' => '{{ page.slug }}']);
        $this->assertSame('{{ page.slug }}', $section->info());
    }

    /**
     * @covers ::label
     */
    public function testLabel()
    {
        // default
        $section = $this->section();
        $this->assertSame('Test', $section->label());

        // custom
        $section = $this->section(['label' => 'My section']);
        $this->assertSame('My section', $section->label());

        // custom translated
        $section = $this->section(['label' => ['en' => 'My section']]);
        $this->assertSame('My section', $section->label());

        // custom query
        $section = $this->section(['label' => '{{ page.slug }}']);
        $this->assertSame('test', $section->label());
    }

    /**
     * @covers ::layout
     */
    public function testLayout()
    {
        // default
        $section = $this->section();
        $this->assertSame('list', $section->layout());

        // custom
        $section = $this->section(['layout' => 'cards']);
        $this->assertSame('cards', $section->layout());
    }

    /**
     * @covers ::link
     */
    public function testLink()
    {
        // default
        $section = $this->section();
        $this->assertNull($section->link());

        // different parent
        $section = $this->section(['parent' => 'site']);
        $this->assertSame('/site', $section->link());
    }

    /**
     * @covers ::pagination
     */
    public function testPagination()
    {
        $section = $this->section();

        $expected = [
            'limit'  => 20,
            'offset' => 0,
            'page'   => 1,
            'total'  => 26,
        ];

        $this->assertSame($expected, $section->pagination());
    }

    /**
     * @covers ::pagination
     */
    public function testPaginationWithCustomPage()
    {
        $section = $this->section(['total' => 50, 'page' => 2]);

        $expected = [
            'limit'  => 20,
            'offset' => 20,
            'page'   => 2,
            'total'  => 26,
        ];

        $this->assertSame($expected, $section->pagination());
    }

    /**
     * @covers ::pagination
     */
    public function testPaginationWithCustomPageInRequest()
    {
        $this->app([
            'request' => [
                'query' => ['page' => 2]
            ]
        ]);

        $section = $this->section(['total' => 50]);

        $expected = [
            'limit'  => 20,
            'offset' => 20,
            'page'   => 2,
            'total'  => 26,
        ];

        $this->assertSame($expected, $section->pagination());
    }

    /**
     * @covers ::pagination
     */
    public function testPaginationWithoutItems()
    {
        // default
        $section = $this->section([
            'models' => new Collection
        ]);

        $expected = [
            'limit'  => 20,
            'offset' => 0,
            'page'   => 0,
            'total'  => 0,
        ];

        $this->assertSame($expected, $section->pagination());
    }

    /**
     * @covers ::parent
     */
    public function testParent()
    {
        // default
        $section = $this->section();
        $this->assertSame($section->model(), $section->parent());

        // custom
        $section = $this->section(['parent' => 'site']);
        $this->assertInstanceOf('Kirby\Cms\Site', $section->parent());
    }

    /**
     * @covers ::parent
     */
    public function testParentInvalid()
    {
        $this->app([
            'users' => [
                [
                    'email' => 'test@getkirby.com'
                ]
            ]
        ]);

        $this->expectException('Kirby\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('The parent is invalid. You must choose the site or a page as parent.');

        $this->section(['parent' => 'kirby.users.first']);
    }

    /**
     * @covers ::parent
     */
    public function testParentNotFound()
    {
        $this->expectException('Kirby\Exception\NotFoundException');
        $this->expectExceptionMessage('The parent for the query "site.find("not-found")" cannot be found');

        $this->section(['parent' => 'site.find("not-found")']);
    }

    /**
     * @covers ::props
     */
    public function testProps()
    {
    }

    /**
     * @covers ::query
     */
    public function testQuery()
    {
        $this->app([
            'request' => [
                'query' => ['query' => 'test']
            ]
        ]);

        // without activated search
        $section = $this->section();
        $this->assertNull($section->query());

        // with activated search
        $section = $this->section(['search' => true]);
        $this->assertSame('test', $section->query());
    }

    /**
     * @covers ::applySearch
     * @covers ::search
     */
    public function testSearch()
    {
        $this->app([
            'request' => [
                'query' => ['query' => 'Project']
            ]
        ]);

        $section = $this->section([
            'search' => true,
            'models' => Pages::factory([
                [
                    'slug'    => 'a',
                    'content' => [
                        'title' => 'Project A'
                    ]
                ],
                [
                    'slug'    => 'b',
                    'content' => [
                        'title' => 'Article B'
                    ]
                ],
                [
                    'slug'    => 'c',
                    'content' => [
                        'title' => 'Project C'
                    ]
                ],
            ])
        ]);

        $this->assertSame('Project', $section->query());
        $this->assertCount(2, $section->models());
    }

    /**
     * @covers ::schema
     */
    public function testSchema()
    {

    }

    /**
     * @covers ::size
     */
    public function testSize()
    {
        // default
        $section = $this->section();
        $this->assertSame('auto', $section->size());

        // custom
        $section = $this->section(['size' => 'large']);
        $this->assertSame('large', $section->size());
    }

    /**
     * @covers ::sortable
     */
    public function testSortable()
    {
        // default
        $section = $this->section();
        $this->assertTrue($section->sortable());

        // custom
        $section = $this->section(['sortable' => false]);
        $this->assertFalse($section->sortable());
    }

    /**
     * @covers ::sortable
     */
    public function testSortableWithQuery()
    {
        $this->app([
            'request' => [
                'query' => ['query' => 'test']
            ]
        ]);

        // without activated search
        $section = $this->section();
        $this->assertTrue($section->sortable());

        // with activated search
        $section = $this->section(['search' => true]);
        $this->assertFalse($section->sortable());
    }

    /**
     * @covers ::sortable
     */
    public function testSortableWithFlip()
    {
        $section = $this->section(['flip' => true]);
        $this->assertFalse($section->sortable());
    }

    /**
     * @covers ::sortable
     */
    public function testSortableWithSortBy()
    {
        $section = $this->section(['sortBy' => 'title desc']);
        $this->assertFalse($section->sortable());
    }

    /**
     * @covers ::applySort
     * @covers ::sortBy
     */
    public function testSortBy()
    {
        $section = $this->section([
            'sortBy' => 'title asc',
            'models' => Pages::factory([
                [
                    'slug'    => 'a',
                    'content' => [
                        'title' => 'Project A'
                    ]
                ],
                [
                    'slug'    => 'b',
                    'content' => [
                        'title' => 'Article B'
                    ]
                ],
                [
                    'slug'    => 'c',
                    'content' => [
                        'title' => 'Project C'
                    ]
                ],
            ])
        ]);

        $models = $section->models();

        $this->assertSame('Article B', $models->first()->title()->value());
        $this->assertSame('Project C', $models->last()->title()->value());
    }

    /**
     * @covers ::text
     */
    public function testText()
    {
        // default
        $section = $this->section();
        $this->assertSame('{{ model.id }}', $section->text());

        // custom
        $section = $this->section(['text' => '{{ page.title }}']);
        $this->assertSame('{{ page.title }}', $section->text());
    }

}
