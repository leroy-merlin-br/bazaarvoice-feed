<?php
namespace Tests\Unit\Elements;

use BazaarVoice\Elements\FeedElement;
use BazaarVoice\Elements\InteractionFeedElement;
use PHPUnit\Framework\TestCase;

class InteractionFeedElementTest extends TestCase
{
    /** @test */
    public function it_extends_base_feed_element()
    {
        // Set
        $interactionFeed = new InteractionFeedElement('interaction');

        // Assertions
        $this->assertInstanceOf(FeedElement::class, $interactionFeed);
    }

    /** @test */
    public function it_overrides_some_methods_of_feed_element()
    {
        // Set
        $interactionFeed = new InteractionFeedElement('interaction');
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
