<?php
namespace BazaarVoice\Elements;

use BazaarVoice\Elements\Interaction\InteractionElement;

interface FeedElementInterface extends ElementInterface
{
    public function setIncremental(bool $incremental = true): FeedElementInterface;

    public function addProduct(ProductElementInterface $product): FeedElementInterface;

    public function addCategory(CategoryElementInterface $category): FeedElementInterface;

    public function addBrand(BrandElementInterface $brand): FeedElementInterface;

    public function addProducts(array $products): FeedElementInterface;

    public function addCategories(array $categories): FeedElementInterface;

    public function addInteraction(InteractionElement $interaction): FeedElementInterface;

    public function addBrands(array $brands): FeedElementInterface;

    public function getBasicXmlAttributes(): array;

    public function getNamespace(): string;
}
