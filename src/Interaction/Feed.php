<?php
namespace BazaarVoice\Interaction;

use BazaarVoice\AbstractFeed;
use BazaarVoice\Elements\InteractionElement;
use BazaarVoice\Elements\FeedElementInterface;
use BazaarVoice\Elements\InteractionFeedElement;
use BazaarVoice\FeedInterface;

class Feed extends AbstractFeed implements FeedInterface
{
    public function newInteraction(
        string $transactionDate,
        string $emailAddress,
        string $userName,
        string $userId,
        string $locale,
        array $products
    ): InteractionElement {
        return new InteractionElement(
            $transactionDate,
            $emailAddress,
            $userName,
            $userId,
            $locale,
            $products
        );
    }

    public function newFeed(string $name, bool $incremental = false): FeedElementInterface
    {
        return new InteractionFeedElement($name, $incremental);
    }
}
