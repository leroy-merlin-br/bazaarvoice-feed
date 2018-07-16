<?php
namespace BazaarVoice\Interaction;

use BazaarVoice\AbstractFeed;
use BazaarVoice\Elements\Interaction\FeedElement;
use BazaarVoice\Elements\Interaction\InteractionElement;
use BazaarVoice\Elements\FeedElementInterface;
use BazaarVoice\FeedInterface;
use DateTime;

class Feed extends AbstractFeed implements FeedInterface
{
    public function newInteraction(
        DateTime $transactionDate,
        string $emailAddress,
        string $userName,
        string $userId,
        string $locale,
        array $products
    ): InteractionElement {
        return new InteractionElement(
            $transactionDate->format('Y-m-d\TH:i:s'),
            $emailAddress,
            $userName,
            $userId,
            $locale,
            $products
        );
    }

    public function newFeed(string $name, bool $incremental = false): FeedElementInterface
    {
        return new FeedElement($name, $incremental);
    }
}
