<?php
namespace Tests\Feature;

use BazaarVoice\Elements\CategoryElement;
use PHPUnit\Framework\TestCase;

class CategoryElementTest extends TestCase
{
    /** @test */
    public function it_generates_xml_array()
    {
        $externalId = '123';
        $categoryName = 'Some Category';
        $categoryUrl = 'https://example.com/some-category';
        $categoryElement = new CategoryElement($externalId, $categoryName, $categoryUrl);
        $expectedCategoryXMLArray = [
            '#children' => [
                [
                    '#name' => 'ExternalId',
                    '#value' => '123',
                ],
                [
                    '#name' => 'Name',
                    '#value' => 'Some Category',
                ],
                [
                    '#name' => 'CategoryPageUrl',
                    '#value' => 'https://example.com/some-category',
                ]
            ],
            '#name' => 'Category',
        ];

        $categoryXMLArray = $categoryElement->generateXMLArray();

        $this->assertSame($expectedCategoryXMLArray, $categoryXMLArray);
    }
}
