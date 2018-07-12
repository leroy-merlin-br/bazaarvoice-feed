<?php
namespace BazaarVoice;

use BazaarVoice\Elements\ProductElement;
use BazaarVoice\Product\Feed;
use PHPUnit\Framework\TestCase;

class ProductFeedTest extends TestCase
{
    /** @test */
    public function it_generates_an_interaction_feed()
    {
        // Set
        $feed = new Feed();
        $element = $feed->newFeed('ProductFeed');
        $product = new ProductElement(12345678, 'Product Name', 'ProductCategoryId123', 'http://localhost/12345678', 'http://localhost/12345678/image');
        $product2 = new ProductElement(12345679, 'Product Name 2', 'ProductCategoryId123', 'http://localhost/12345679', 'http://localhost/12345679/image');
        $element->addProduct($product);
        $element->addProduct($product2);
        $expectedFeed = file_get_contents('tests/fixtures/product-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        // Work aroud nice
        $result = $this->prepareResultForTesting($result);
        $expectedFeed = $this->prepareResultForTesting($expectedFeed);

        // Assertions
        $this->assertEquals($expectedFeed, $result);
    }

    public function prepareResultForTesting(string $result): string
    {
        $firstPos = strpos($result, 'extractDate="');

        return substr_replace($result, 'extractDate="', $firstPos, 32);
    }
}
