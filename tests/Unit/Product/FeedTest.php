<?php
namespace BazaarVoice\Product;

use BazaarVoice\Elements\BrandElementInterface;
use BazaarVoice\Elements\CategoryElement;
use BazaarVoice\Elements\FeedElementInterface;
use BazaarVoice\Elements\ProductElement;
use Exception;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class FeedTest extends TestCase
{
    /** @test */
    public function it_creates_a_new_feed_element()
    {
        // Set
        $feed = new Feed();
        $name = 'itsANewFeedName';

        // Actions
        $feed = $feed->newFeed($name);

        // Assertions
        $this->assertInstanceOf(FeedElementInterface::class, $feed);
    }

    /** @test */
    public function it_creates_a_new_brand_element()
    {
        // Set
        $feed = new Feed();
        $name = 'itsANewFeedName';
        $brandId = 'some-brand';

        // Actions
        $brand = $feed->newBrand($brandId, $name);

        // Assertions
        $this->assertInstanceOf(BrandElementInterface::class, $brand);
    }

    /** @test */
    public function it_creates_a_new_category_element()
    {
        // Set
        $feed = new Feed();
        $name = 'itsANewFeedName';
        $categoryId = 'categoryId';
        $pageUrl = 'http://www.example.com/' . $categoryId;

        // Actions
        $category = $feed->newCategory($categoryId, $name, $pageUrl);

        // Assertions
        $this->assertInstanceOf(CategoryElement::class, $category);
    }

    /** @test */
    public function it_creates_a_new_product_element()
    {
        // Set
        $feed = new Feed();
        $name = 'itsANewFeedName';
        $productId = 'someProductId123';
        $productUrl = 'http://www.example.com/' . $productId;
        $imageUrl = "{$productUrl}/{$name}.jpg";

        // Actions
        $product = $feed->newProduct($productId, $name, 'someCategoryId', $productUrl, $imageUrl);

        // Assertions
        $this->assertInstanceOf(ProductElement::class, $product);
    }

    /** @test */
    public function it_prints_a_feed()
    {
        // Set
        $feed = new Feed();
        $feedElement = $feed->newFeed('testFeed');
        $xmlString = $feed->printFeed($feedElement);

        // Actions
        new SimpleXMLElement($xmlString);
        $prev = libxml_use_internal_errors(true);

        try {
            new SimpleXMLElement($xmlString);
        } catch(Exception $e) {}

        // Assertions
        $this->assertCount(0, libxml_get_errors());

        // TearDown
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
    }

    /** @test */
    public function it_saves_a_feed()
    {
        // Set
        $testFeedFile = 'testFeed';
        $feed = new Feed();

        // Actions
        $feedElement = $feed->newFeed($testFeedFile);
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory($testFeedFile . '_dir'));
        $feed->saveFeed($feedElement, vfsStream::url($testFeedFile . '_dir'), $testFeedFile);

        // Assertions
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($testFeedFile . '.xml.gz'));
    }
}
