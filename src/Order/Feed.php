<?php
namespace BazaarVoice;

use BazaarVoice\Elements\FeedElementInterface;

class Feed implements FeedInterface
{
    public function newFeed(string $name, bool $incremental = false): FeedElementInterface
    {
        return new FeedElement($name, $incremental);
    }

    public function printFeed(FeedElementInterface $feed): string
    {
        // TODO: Implement printFeed() method.
    }

    public function saveFeed(FeedElementInterface $feed, string $fileLocation, string $fileName)
    {
        // TODO: Implement saveFeed() method.
    }

    public function sendFeed(
        string $filePath,
        string $sftpUsername,
        string $sftpPassword,
        string $sftpDirectory = '/import-box',
        string $sftpPort = '22'
    ): bool {
        // TODO: Implement sendFeed() method.
    }
}
