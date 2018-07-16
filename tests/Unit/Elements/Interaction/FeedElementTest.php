<?php
namespace Tests\Unit\Elements\Interaction;

use BazaarVoice\Elements\Interaction\FeedElement;
use BazaarVoice\Elements\FeedElement as BaseFeedElement;
use PHPUnit\Framework\TestCase;

class FeedElementTest extends TestCase
{
    /** @test */
    public function it_extends_base_feed_element()
    {
        // Set
        $interactionFeed = new FeedElement('interaction');

        // Assertions
        $this->assertInstanceOf(BaseFeedElement::class, $interactionFeed);
    }

    /** @test */
    public function it_overrides_some_methods_of_feed_element()
    {
        // Set
        $interactionFeed = new FeedElement('interaction');
        $expectedNamespace = 'http://www.bazaarvoice.com/xs/PRR/PostPurchaseFeed/5.6';
        $expectedAttributes = [
            'xmlns' => $expectedNamespace,
        ];

        // Actions
        $basicAttributes = $interactionFeed->getBasicXmlAttributes();
        $namespace = $interactionFeed->getNamespace();

        // Assertions
        $this->assertSame($expectedNamespace, $namespace);
        $this->assertSame($expectedAttributes, $basicAttributes);
    }
}
