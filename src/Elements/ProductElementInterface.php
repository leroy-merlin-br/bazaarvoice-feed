<?php
namespace BazaarVoice\Elements;

interface ProductElementInterface extends ElementInterface
{
    public function setCategoryId(string $categoryId): ProductElementInterface;

    public function setPageUrl(string $url): ProductElementInterface;

    public function setDescription(string $description): ProductElementInterface;

    public function setBrandId(string $brandId): ProductElementInterface;

    public function setImageUrl(string $url): ProductElementInterface;

    public function addName(string $name, string $locale);

    public function addPageUrl(string $url, string $locale): ProductElementInterface;

    public function addDescription(string $description, string $locale): ProductElementInterface;

    public function addImageUrl(string $url, string $locale): ProductElementInterface;

    public function addEAN(string $ean): ProductElementInterface;

    public function addModelNumber(string $modelNumber): ProductElementInterface;

    public function addManufacturerPartNumber(string $partNumber): ProductElementInterface;

    public function addUPC(string $upc): ProductElementInterface;

    public function addISBN(string $isbn): ProductElementInterface;

    public function addCustomAttribute(string $attributeId, $value): ProductElementInterface;

    public function addCustomElement(string $key, string $value): ProductElementInterface;
}
