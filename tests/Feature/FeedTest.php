<?php
namespace Tests\Feature;

use BazaarVoice\Elements\ProductElement;
use BazaarVoice\Interaction\Feed as InteractionFeed;
use BazaarVoice\Product\Feed as ProductFeed;
use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase
{
    /** @test */
    public function it_generates_an_interaction_feed()
    {
        // Set
        $feed = new InteractionFeed();
        $element = $feed->newFeed('InteractionFeed');
        $products = [
            [
                'id' => 12345678,
                'name' => 'Product Name',
                'category' => 'ProductCategoryId123',
                'url' => 'http://localhost/12345678',
                'imageUrl' => 'http://localhost/12345678/image',
                'price' => 29,
            ],
            [
                'id' => 12345679,
                'name' => 'Product Name 2',
                'category' => 'ProductCategoryId123',
                'url' => 'http://localhost/12345679',
                'imageUrl' => 'http://localhost/12345679/image',
                'price' => 29,
            ],
        ];
        $order = $feed->newInteraction('22/03/1987', 'john@doe.com', 'John Doe', 'userId123', 'pt_BR', $products);
        $element->addInteraction($order);
        $element->addInteraction($order);
        $expectedFeed = file_get_contents('tests/fixtures/interaction-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        // Assertions
        $this->assertXmlStringEqualsXmlString($expectedFeed, $result);
    }

    /** @test */
    public function it_generates_a_product_feed()
    {
        // Set
        $feed = new ProductFeed();
        $element = $feed->newFeed('ProductFeed');
        $product = new ProductElement(12345678, 'Product Name', 'ProductCategoryId123', 'http://localhost/12345678', 'http://localhost/12345678/image');
        $product2 = new ProductElement(12345679, 'Product Name 2', 'ProductCategoryId123', 'http://localhost/12345679', 'http://localhost/12345679/image');
        $element->addProduct($product);
        $element->addProduct($product2);
        $expectedFeed = file_get_contents('tests/fixtures/product-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        // Assertions
        $this->assertFeedXmlWasGeneratedCorrectly($expectedFeed, $result);
    }

    /** @test */
    public function it_generates_a_brand_feed()
    {
        // Set
        $feed = new ProductFeed();
        $element = $feed->newFeed('BrandFeed');
        $firstBrand = $feed->newBrand('first-brand', 'First Brand');
        $secondBrand = $feed->newBrand('second-brand', 'Second Brand');
        $element->addBrand($firstBrand);
        $element->addBrand($secondBrand);
        $expectedFeed = file_get_contents('tests/fixtures/brand-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        // Assertions
        $this->assertFeedXmlWasGeneratedCorrectly($expectedFeed, $result);
    }

    /** @test */
    public function it_generates_a_category_feed()
    {
        // Set
        $feed = new ProductFeed();
        $feedElement = $feed->newFeed('CategoryFeed');

        $firstCategory = $feed->newCategory('first-category', 'First Category', 'http://localhost/first-category');
        $secondCategory = $feed->newCategory('second-category', 'Second Category', 'http://localhost/second-category');
        $feedElement->addCategory($firstCategory);
        $feedElement->addCategory($secondCategory);
        $expectedFeed = file_get_contents('tests/fixtures/category-feed.xml');

        // Actions
        $result = $feed->printFeed($feedElement);

        // Assertions
        $this->assertFeedXmlWasGeneratedCorrectly($expectedFeed, $result);
    }

    private function assertFeedXmlWasGeneratedCorrectly(string $expectedFeed, string $result)
    {
        $result = $this->ignoreDateFromXml($result);
        $expectedFeed = $this->ignoreDateFromXml($expectedFeed);

        $this->assertXmlStringEqualsXmlString($expectedFeed, $result);
    }

    private function ignoreDateFromXml(string $result): string
    {
        $firstPos = strpos($result, 'extractDate="');

        return substr_replace($result, 'extractDate="', $firstPos, 32);
    }
}
