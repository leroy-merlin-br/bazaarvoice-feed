<?php
namespace BazaarVoice\Product;

use BazaarVoice\AbstractFeed;
use BazaarVoice\Elements\BrandElementInterface;
use BazaarVoice\Elements\CategoryElementInterface;
use BazaarVoice\Elements\ProductElementInterface;
use BazaarVoice\FeedInterface;
use BazaarVoice\Elements\BrandElement;
use BazaarVoice\Elements\CategoryElement;
use BazaarVoice\Elements\ProductElement;

class Feed extends AbstractFeed implements FeedInterface
{
    public function newProduct(string $externalId, string $name, string $categoryId, string $pageUrl, string $imageUrl): ProductElementInterface
    {
        return new ProductElement($externalId, $name, $categoryId, $pageUrl, $imageUrl);
    }

    public function newBrand(string $externalId, string $name): BrandElementInterface
    {
        return new BrandElement($externalId, $name);
    }

    public function newCategory(string $externalId, string $name, string $pageUrl): CategoryElementInterface
    {
        return new CategoryElement($externalId, $name, $pageUrl);
    }
}
