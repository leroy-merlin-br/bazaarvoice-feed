<?php
namespace BazaarVoice\Elements\Interaction;

use BazaarVoice\Elements\FeedElement;
use BazaarVoice\Elements\FeedElementInterface;

class InteractionFeedElement extends FeedElement implements FeedElementInterface
{
    public function getBasicXmlAttributes(): array
    {
        return ['xmlns' => $this->getNamespace()];
    }

    public function getNamespace(): string
    {
        return 'http://www.bazaarvoice.com/xs/PRR/PostPurchaseFeed/'.self::$apiVersion;
    }
}
