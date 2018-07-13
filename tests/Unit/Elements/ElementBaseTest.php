<?php
namespace Tests\Feature;

use BazaarVoice\Elements\ElementBase;
use PHPUnit\Framework\TestCase;

class ElementBaseTest extends TestCase
{
    /** @test */
    public function it_generates_xml_array()
    {
        // Set
        $elementBase =  new class extends ElementBase {
        };
        $expectedBaseElementXMLArray = [
            '#children' => [
                [
                    '#name' => 'ExternalId',
                    '#value' => '123',
                ],
                [
                    '#name' => 'Name',
                    '#value' => 'Isso',
                ]
            ],
        ];
        $elementBase->setExternalId('123');
        $elementBase->setName('Isso');

        // Actions
        $baseElementXMLArray = $elementBase->generateXMLArray();

        // Assertions
        $this->assertSame($expectedBaseElementXMLArray, $baseElementXMLArray);
    }

    /** @test */
    public function it_generates_element_xml_array()
    {
        // Set
        $elementBase =  new class extends ElementBase {
        };
        $expectedElementXMLArray = [
            '#name' => 'Some Name',
            '#value' => 'Some Value',
            '#attributes' => [
                'Attribute',
            ],
        ];
        $elementName = 'Some Name';
        $elementValue = 'Some Value';
        $elementAttributes = ['Attribute'];

        // Actions
        $baseElementXMLArray = $elementBase->generateElementXMLArray($elementName, $elementValue, $elementAttributes);

        // Assertions
        $this->assertSame($expectedElementXMLArray, $baseElementXMLArray);
    }

    /** @test */
    public function it_generates_multiple_elements_xml_array()
    {
        // Set
        $elementBase =  new class extends ElementBase {
        };
        $expectedElementXMLArray = [
            '#name' => 'Brands',
            '#children' => [
                [
                    '#name' => 'Brand Name',
                    '#value' => 'Some Brand',
                ],
                [
                    '#name' => 'Brand Name',
                    '#value' => 'Another Brand',
                ],
            ],
        ];
        $elementName = 'Brands';
        $elementSingleName = 'Brand Name';
        $elementAttributes = ['Some Brand', 'Another Brand'];

        // Actions
        $baseElementXMLArray = $elementBase
            ->generateMultipleElementsXMLArray($elementName,$elementSingleName,$elementAttributes);

        // Assertions
        $this->assertSame($expectedElementXMLArray, $baseElementXMLArray);
    }

    /** @test */
    public function it_validates_correct_locale()
    {
        // Set
        $elementBase =  new class extends ElementBase {
        };

        // Actions
        $valid = $elementBase->checkValidLocale('pt_BR');

        // Assertions
        $this->assertTrue($valid);
    }

    /** @test */
    public function it_validates_wrong_locale()
    {
        // Set
        $elementBase =  new class extends ElementBase {
        };

        // Expectations
        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage('Invalid Locale code. Must match format of "xx_YY"');

        // Actions
        $elementBase->checkValidLocale('ptBR');
    }
}
