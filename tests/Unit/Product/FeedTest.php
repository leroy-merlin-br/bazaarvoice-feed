<?php
namespace BazaarVoice\Product;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase {

  public function testNewFeedElement() {
    $pf = new Feed();
    $name = substr( md5(rand()), 0, 8);
    $feed = $pf->newFeed($name);
    $this->assertInstanceOf('BazaarVoice\Elements\FeedElementInterface', $feed);
  }

  public function testNewBrandElement() {
    $pf = new Feed();
    $name = substr( md5(rand()), 0, 8);
    $id = 'test_brand';
    $brand = $pf->newBrand($id, $name);
    $this->assertInstanceOf('BazaarVoice\Elements\BrandElementInterface', $brand);
  }

  public function testNewCategoryElement() {
    $pf = new Feed();
    $name = substr( md5(rand()), 0, 8);
    $id = 'test_category';
    $page_url = 'http://www.example.com/' . $id;
    $category = $pf->newCategory($id, $name, $page_url);
    $this->assertInstanceOf('BazaarVoice\Elements\CategoryElement', $category);
  }

  public function testNewProductElement() {
    $pf = new Feed();
    $name = substr( md5(rand()), 0, 8);
    $id = 'test_product';
    $page_url = 'http://www.example.com/' . $id;
    $image_url = $page_url . '/' . $name . '.jpg';
    $product = $pf->newProduct($id, $name, 'test_category', $page_url, $image_url);
    $this->assertInstanceOf('BazaarVoice\Elements\ProductElement', $product);
  }

  /**
   * Test generating valid XML document.
   */
  public function testPrintFeed() {
    $pf = new Feed();
    $feed = $pf->newFeed('testFeed');
    $xml_string = $pf->printFeed($feed);
    new \SimpleXMLElement($xml_string);

    $prev = libxml_use_internal_errors(true);
    try {
      new \SimpleXMLElement($xml_string);
    } catch(\Exception $e) {
    }
    $this->assertEquals(0,count(libxml_get_errors()));
    // Tidy up.
    libxml_clear_errors();
    libxml_use_internal_errors($prev);

  }

  /**
   * Test saving Feed gzipped XML file.
   */
  public function testSaveFeed() {
    $test_feed = 'testFeed_' . substr( md5(rand()), 0, 10);
    $pf = new Feed();
    $feed = $pf->newFeed($test_feed);
    vfsStreamWrapper::register();
    vfsStreamWrapper::setRoot(new vfsStreamDirectory($test_feed . '_dir'));
    $pf->saveFeed($feed, vfsStream::url($test_feed . '_dir'), $test_feed);
    $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($test_feed . '.xml.gz'));
  }

}
