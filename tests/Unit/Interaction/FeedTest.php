<?php
namespace Tests\Unit\Interaction;

use BazaarVoice\Elements\Interaction\InteractionElement;
use BazaarVoice\Interaction\Feed;
use DateTime;
use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase
{
    /** @test */
    public function it_generates_a_new_interaction_element()
    {
        // Set
        $feed = new Feed();
        $transactionDate = new DateTime('1987-03-22 01:01:01');
        $emailAddress = 'john@example.com';
        $userName = 'John Doe';
        $userId = substr(md5(uniqid()), 0, 8);
        $locale = 'pt_BR';
        $products = [
            [
                'id' => 12345678,
                'name' => 'Product Name',
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
