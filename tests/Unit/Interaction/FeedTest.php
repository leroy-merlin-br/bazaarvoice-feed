<?php
namespace BazaarVoice\Interaction;

use BazaarVoice\Elements\InteractionElement;
use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase
{
    /** @test */
    public function it_generates_a_new_interaction_element()
    {
        // Set
        $feed = new Feed();
        $transactionDate = '2018-07-11T08:36:47';
        $emailAddress = 'john@example.com';
        $userName = 'John Doe';
        $userId = substr(md5(uniqid()), 0, 8);
        $locale = 'pt_BR';
        $products = [
            [
                'id' => 12345678,
                'name' => 'Product Name',
                'category' => 'Category',
                'url' => 'http://www.example.com/test-product',
                'imageUrl' => 'http://www.example.com/test-product/product-name.jpg',
                'price' => 29,
            ],
        ];

        // Actions
        $order = $feed->newInteraction(
            $transactionDate,
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
