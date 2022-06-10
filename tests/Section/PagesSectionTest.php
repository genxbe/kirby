<?php

namespace Kirby\Section;

use Kirby\Cms\Page;

/**
 * @covers \Kirby\Section\PagesSection
 */
class PagesSectionTest extends TestCase
{
    public function section(array $options = [])
    {
        $model = new Page(['slug' => 'test']);
        return new PagesSection($model, 'test', $options);
    }

    /**
     * @covers ::add
     */
    public function testAdd()
    {
        // addable
        $section = $this->section();
        $this->assertTrue($section->add());

        $section = $this->section(['status' => 'all']);
        $this->assertTrue($section->add());

        $section = $this->section(['status' => 'draft']);
        $this->assertTrue($section->add());

        // blocked
        $section = $this->section(['status' => 'listed']);
        $this->assertFalse($section->add());

        $section = $this->section(['status' => 'published']);
        $this->assertFalse($section->add());

        $section = $this->section(['status' => 'unlisted']);
        $this->assertFalse($section->add());
    }

    /**
     * @covers ::blueprints
     */
    public function testBlueprints()
    {
    }

    /**
     * @covers ::create
     */
    public function testCreate()
    {
        // default
        $section = $this->section();
        $this->assertTrue($section->create());

        // deactivated
        $section = $this->section(['create' => false]);
        $this->assertFalse($section->create());

        // array
        $section = $this->section(['create' => ['article', 'video']]);
        $this->assertSame(['article', 'video'], $section->create());
    }

    /**
     * @covers ::data
     */
    public function testData()
    {
    }

    /**
     * @covers ::models
     */
    public function testModels()
    {
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
     * @covers ::sortable
     */
    public function testSortable()
    {
        // sortable status
        $section = $this->section(['status' => 'listed']);
        $this->assertTrue($section->sortable());

        $section = $this->section(['status' => 'published']);
        $this->assertTrue($section->sortable());

        $section = $this->section(['status' => 'all']);
        $this->assertTrue($section->sortable());

        // unsortable status
        $section = $this->section(['status' => 'draft']);
        $this->assertFalse($section->sortable());

        $section = $this->section(['status' => 'unlisted']);
        $this->assertFalse($section->sortable());
    }

    /**
     * @covers ::status
     */
    public function testStatus()
    {
        // default
        $section = $this->section();
        $this->assertSame('all', $section->status());

        // custom
        $section = $this->section(['status' => 'draft']);
        $this->assertSame('draft', $section->status());
    }

    /**
     * @covers ::templates
     */
    public function testTemplates()
    {
        // default
        $section = $this->section();
        $this->assertSame([], $section->templates());

        // custom
        $section = $this->section(['templates' => ['home']]);
        $this->assertSame(['home'], $section->templates());
    }

    /**
     * @covers ::text
     */
    public function testText()
    {
        // default
        $section = $this->section();
        $this->assertSame('{{ page.title }}', $section->text());

        // custom
        $section = $this->section(['text' => '{{ page.subtitle }}']);
        $this->assertSame('{{ page.subtitle }}', $section->text());
    }

    /**
     * @covers ::type
     */
    public function testType()
    {
        $section = $this->section();
        $this->assertSame('pages', $section->type());
    }
}
