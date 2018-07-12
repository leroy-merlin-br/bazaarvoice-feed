<?php
namespace BazaarVoice;

use BazaarVoice\Elements\ProductElement;
use BazaarVoice\Order\Feed;
use PHPUnit\Framework\TestCase;

class InteractionFeedTest extends TestCase
{
    /** @test */
    public function it_generates_an_interaction_feed()
    {
        // Set
        $feed = new Feed();
        $element = $feed->newFeed('BazaarVoid');
        $product = new ProductElement(12345678, 'Product Name', 'categoryId123', 'http://localhost/12345678', 'http://localhost/12345678/image');
        $product2 = new ProductElement(12345679, 'Product Name', 'categoryId123', 'http://localhost/12345679', 'http://localhost/12345679/image');
        $order = $feed->newOrder('22/03/1987', 'john@doe.com', 'John Doe', 'userId123', 'pt_BR', [$product, $product2]);
        $element->addInteraction($order);
        $element->addInteraction($order);
        $expectedFeed = file_get_contents('tests/fixtures/interaction-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        // Assertions
        var_dump($result);
        $this->assertEquals($expectedFeed, $result);
    }
}

function date($parameter, $timestamp) {
    return 'diego';
}
