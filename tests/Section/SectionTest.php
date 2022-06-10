<?php

namespace Kirby\Section;

use Kirby\Cms\Page;

class TestSection extends Section
{
    public function type(): string
    {
        return 'test';
    }
}

class TestSectionWithSchema extends TestSection
{
    public static function schema(): array
    {
        return [
            'limit' => [
                'type' => 'integer',
            ]
        ];
    }
}

/**
 * @covers \Kirby\Section\Section
 */
class SectionTest extends TestCase
{

    public function section(array $options = [])
    {
        $model = new Page(['slug' => 'test']);

        return new TestSection($model, 'test', $options);
    }

    /**
     * @covers ::attrs
     */
    public function testAttrs()
    {
        $section = $this->section($attrs = [
            'foo' => 'bar'
        ]);

        $this->assertSame($attrs, $section->attrs());
    }

    /**
     * @covers ::errorMessage
     */
    public function testErrorMessage()
    {
        $this->app([
            'translations' => [
                'en' => [
                    'error.section.test.simple'        => 'Something went wrong',
                    'error.section.test.form.singular' => 'One issue appeared',
                    'error.section.test.form.plural'   => 'Multiple issues appeared',
                    'error.section.test.placeholder'   => '{section} Section'
                ]
            ]
        ]);

        $section = $this->section();

        // without form
        $this->assertSame('Something went wrong', $section->errorMessage('simple'));

        // with form
        $this->assertSame('Multiple issues appeared', $section->errorMessage('form', [], 5));
        $this->assertSame('One issue appeared', $section->errorMessage('form', [], 1));

        // with placeholders
        $this->assertSame('Test Section', $section->errorMessage('placeholder', ['section' => 'Test']));
    }

    /**
     * @covers ::errorMessageKey
     */
    public function testErrorMessageKey()
    {
        $section = $this->section();

        // without form
        $this->assertSame('error.section.test.min', $section->errorMessageKey('min'));

        // with form
        $this->assertSame('error.section.test.min.plural', $section->errorMessageKey('min', 5));
        $this->assertSame('error.section.test.min.singular', $section->errorMessageKey('min', 1));
    }

    /**
     * @covers ::errors
     */
    public function testErrors()
    {
        $section = $this->section();
        $this->assertSame([], $section->errors());
    }

    /**
     * @covers ::help
     */
    public function testHelp()
    {
        // default
        $section = $this->section();
        $this->assertNull($section->help());

        // custom
        $section = $this->section(['help' => 'Test']);
        $this->assertSame('<p>Test</p>', $section->help());

        // custom translated
        $section = $this->section(['help' => ['en' => 'Test']]);
        $this->assertSame('<p>Test</p>', $section->help());

        // custom query
        $section = $this->section(['help' => '{{ page.slug }}']);
        $this->assertSame('<p>test</p>', $section->help());
    }

    /**
     * @covers ::i18n
     */
    public function testI18n()
    {
        $section = $this->section();
        $this->assertNull($section->i18n(null));
        $this->assertSame('test', $section->i18n('test'));
        $this->assertSame('test', $section->i18n(['en' => 'test']));
    }

    /**
     * @covers ::kirby
     */
    public function testKirby()
    {
        $section = $this->section();
        $this->assertInstanceOf('Kirby\Cms\App', $section->kirby());
    }

    /**
     * @covers ::model
     */
    public function testModel()
    {
        $section = $this->section();
        $this->assertInstanceOf('Kirby\Cms\Page', $section->model());
    }

    /**
     * @covers ::name
     */
    public function testName()
    {
        $section = $this->section();
        $this->assertSame('test', $section->name());
    }

    /**
     * @covers ::options
     * @covers ::createOptions
     */
    public function testOptions()
    {
        $section = $this->section();
        $this->assertSame([
            'help'  => null,
            'label' => null
        ], $section->options());
    }

    /**
     * @covers ::options
     * @covers ::createOptions
     */
    public function testOptionsWithSchema()
    {
        $model = new Page(['slug' => 'test']);

        $section = new TestSectionWithSchema($model, 'test', [
            'limit' => '1'
        ]);

        // untyped
        $this->assertSame('1', $section->attrs()['limit']);
        // typed
        $this->assertSame(1, $section->options()['limit']);
    }

    /**
     * @covers ::props
     */
    public function testProps()
    {
        $section = $this->section();
        $this->assertSame([
            'help'  => null,
            'label' => null
        ], $section->props());
    }

    /**
     * @covers ::schema
     */
    public function testSchema()
    {
        $section = $this->section();
        $this->assertSame([
            'help' => [
                'type' => 'i18n'
            ],
            'label' => [
                'type'  => 'i18n',
                'alias' => 'headline'
            ]
        ], $section->schema());
    }

    public function testStringQuery()
    {
        $section = $this->section();
        $this->assertInstanceOf('Kirby\Cms\App', $section->stringQuery('kirby'));
    }

    public function testStringTemplate()
    {
        $section = $this->section();
        $this->assertSame('test', $section->stringTemplate('{{ page.slug }}'));
    }

    public function testStringToKirbytext()
    {
        $section = $this->section();
        $this->assertSame('<p>test</p>', $section->stringToKirbytext('test'));
    }

    /**
     * @covers ::toArray
     */
    public function testToArray()
    {
        $section = $this->section();
        $this->assertSame([
            'help'  => null,
            'label' => null
        ], $section->toArray());
    }

    /**
     * @covers ::toResponse
     */
    public function testToResponse()
    {
        $section = $this->section();
        $this->assertSame([
            'code'   => 200,
            'name'   => 'test',
            'status' => 'ok',
            'type'   => 'test',
            'help'   => null,
            'label'  => null,
        ], $section->toResponse());
    }

    /**
     * @covers ::type
     */
    public function testType()
    {
        $section = $this->section();
        $this->assertSame('test', $section->type());
    }

    /**
     * @covers ::validate
     */
    public function testValidate()
    {
        $this->assertTrue($this->section()->validate());
    }

}
