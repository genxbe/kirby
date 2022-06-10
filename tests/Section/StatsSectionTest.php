<?php

namespace Kirby\Section;

use Kirby\Cms\Page;

class StatsPage extends Page
{
    public function report()
    {
        return $this->reports()[0];
    }

    public function reports()
    {
        return [
            [
                'label' => 'A',
                'value' => 'Value A',
                'info'  => 'Info A',
                'link'  => 'https://getkirby.com',
                'theme' => null,
            ],
            [
                'label' => 'B',
                'value' => 'Value B',
                'info'  => null,
                'link'  => null,
                'theme' => null,
            ]
        ];
    }
}

/**
 * @covers \Kirby\Section\StatsSection
 */
class StatsSectionTest extends TestCase
{
    public function model()
    {
        return new StatsPage(['slug' => 'test']);
    }

    public function section(array $options = [])
    {
        return new StatsSection($this->model(), 'test', $options);
    }

    /**
     * @covers ::reports
     */
    public function testReports()
    {
        $section = $this->section([
            'reports'  => $reports = $this->model()->reports()
        ]);

        $this->assertSame($reports, $section->reports());
    }

    /**
     * @covers ::reports
     */
    public function testReportsFromQuery()
    {
        $section = $this->section([
            'reports' => 'page.reports'
        ]);

        $this->assertSame($this->model()->reports(), $section->reports());
    }

    /**
     * @covers ::reports
     */
    public function testReportsFromInvalidValue()
    {
        $section = $this->section([
            'reports' => new \stdClass()
        ]);

        $this->assertSame([], $section->reports());
    }

    /**
     * @covers ::reports
     */
    public function testReportsWithQueries()
    {
        $section = $this->section([
            'reports'  => [
                'page.report'
            ]
        ]);

        $this->assertSame([$this->model()->report()], $section->reports());
    }

    /**
     * @covers ::reports
     */
    public function testReportsWithInvalidQueries()
    {
        $section = $this->section([
            'reports'  => [
                'page.somethingSomething'
            ]
        ]);

        $this->assertSame([], $section->reports());
    }

    /**
     * @covers ::size
     */
    public function testSize()
    {
        // default
        $section = $this->section();
        $this->assertSame('large', $section->size());

        // custom
        $section = $this->section(['size' => 'small']);
        $this->assertSame('small', $section->size());
    }

    /**
     * @covers ::type
     */
    public function testType()
    {
        $section = $this->section();
        $this->assertSame('stats', $section->type());
    }
}
