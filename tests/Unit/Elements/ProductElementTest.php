<?php
namespace Tests\Unit\Elements;

use BazaarVoice\Elements\ProductElement;
use PHPUnit\Framework\TestCase;

class ProductElementTest extends TestCase
{
    /** @test */
    public function it_generates_custom_elements()
    {
        // Set
        $categoryElement = new ProductElement('id', 'name', 'category', 'page', 'image');
        $categoryElement->addCustomElement('foo', 'bar');
        $expectedValue = [
            '#name' => 'Foo',
            '#value' => 'bar',
        ];

        // Actions
        $productXmlArray = $categoryElement->generateXMLArray();

        // Assertions
        $this->assertSame($productXmlArray['#children'][5], $expectedValue);
    }
}
