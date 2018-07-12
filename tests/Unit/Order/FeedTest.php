<?php
namespace BazaarVoice\Order;

use BazaarVoice\Elements\InteractionElement;
use BazaarVoice\Elements\ProductElement;
use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase
{
    public function testNewOrderElement()
    {
        // Set
        $feed = new Feed();
        $transationDate = '2018-07-11T08:36:47';
        $emailAddress = 'john@example.com';
        $userName = 'John Doe';
        $userId = substr(md5(uniqid()), 0, 8);
        $locale = 'pt_BR';
        $products = [
            new ProductElement(
                substr(md5(uniqid()), 0, 8),
                'Product Name',
                'Category',
                'http://www.example.com/test-product',
                'http://www.example.com/test-product/product-name.jpg'
            ),
        ];

        // Actions
        $order = $feed->newOrder(
            $transationDate,
            $emailAddress,
            $userName,
            $userId,
            $locale,
            $products
        );

        // Assertions
        $this->assertInstanceOf(InteractionElement::class, $order);
    }
}
