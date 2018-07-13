<?php
namespace BazaarVoice\Elements;

use ErrorException;

class ProductElement extends ElementBase implements ProductElementInterface
{
  /**
   * @var string
   */
    protected $categoryId;

  /**
   * @var string
   */
    protected $pageUrl;

  /**
   * @var string
   */
    protected $imageUrl;

  /**
   * @var string
   */
    protected $description;

  /**
   * @var string
   */
    protected $brandId;

  /**
   * @var array
   */
    protected $pageUrls;

  /**
   * @var array
   */
    protected $imageUrls;

  /**
   * @var array
   */
    protected $descriptions;

  /**
   * @var array
   */
    protected $eans;

  /**
   * @var array
   */
    protected $modelNumbers;

  /**
   * @var array
   */
    protected $manufacturePartNumbers;

  /**
   * @var array
   */
    protected $upcs;

  /**
   * @var array
   */
    protected $isbns;

  /**
   * @var array
   */
    protected $customAttributes;

    /**
     * @var array
     */
    protected $customElements;

    public function __construct(string $externalId, string $name, string $categoryId, string $pageUrl, string $imageUrl)
    {
        $this->setExternalId($externalId);
        $this->setName($name);
        $this->setCategoryId($categoryId);
        $this->setPageUrl($pageUrl);
        $this->setImageUrl($imageUrl);
        return $this;
    }

    public function setCategoryId(string $categoryId): ProductElementInterface
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function setPageUrl(string $url): ProductElementInterface
    {
        $this->pageUrl = $url;
        return $this;
    }

    public function setDescription(string $description): ProductElementInterface
    {
        $this->description = strip_tags($description);

        return $this;
    }

    public function setBrandId(string $brandId): ProductElementInterface
    {
        $this->brandId = $brandId;
        return $this;
    }

    public function setImageUrl(string $url): ProductElementInterface
    {
        $this->imageUrl = $url;
        return $this;
    }

    public function addPageUrl(string $url, string $locale): ProductElementInterface
    {
        $this->pageUrls[$locale] = $url;
        return $this;
    }

    public function addCustomElement(string $key, string $value): ProductElementInterface
    {
        $this->customElements[$key] = $value;

        return $this;
    }

    public function addDescription(string $description, string $locale): ProductElementInterface
    {
        $this->descriptions[$locale] = $description;
        return $this;
    }

    public function addImageUrl(string $url, string $locale): ProductElementInterface
    {
        $this->imageUrls[$locale] = $url;
        return $this;
    }

    public function addEAN(string $ean): ProductElementInterface
    {
        if (preg_match('/^([0-9]{8}|[0-9]{13})$/', $ean)) {
            $this->eans[$ean] = $ean;
            return $this;
        }

        throw new ErrorException('Invalid EAN. Must be a 8 or 13 digit number.');
    }

    public function addModelNumber(string $modelNumber): ProductElementInterface
    {
        $this->modelNumbers[$modelNumber] = $modelNumber;

        return $this;
    }

    public function addManufacturerPartNumber(string $partNumber): ProductElementInterface
    {
        $this->manufacturePartNumbers[$partNumber] = $partNumber;

        return $this;
    }

    public function addUPC(string $upc): ProductElementInterface
    {
        if (preg_match('/^([0-9]{6}|[0-9]{12})$/', $upc)) {
            $this->upcs[$upc] = $upc;
        }

        return $this;
    }

    public function addISBN(string $isbn): ProductElementInterface
    {
        $numericLength = strlen(preg_replace('/[^0-9]/', '', $isbn));

        if ((10 == $numericLength || 13 == $numericLength) && preg_match('/^[0-9|\-\~]+$/', $isbn)) {
            $this->isbns[$isbn] = $isbn;
            return $this;
        }

        throw new ErrorException('Invalid ISBN. Must be a 10 or 13 digit number separated with hyphens (-) or tildes (~).');
    }

    public function addCustomAttribute(string $attributeId, $value): ProductElementInterface
    {
        $this->customAttributes[$attributeId][$value] = $value;

        return $this;
    }

    public function generateXMLArray(): array
    {
        $element = parent::generateXMLArray();

        $element['#name'] = 'Product';

        $element['#children'][] = $this->generateElementXMLArray('CategoryExternalId', $this->categoryId);

        if ($this->description) {
            $element['#children'][] = $this->generateElementXMLArray('Description', $this->description);
        }

        if (!empty($this->descriptions)) {
            $element['#children'][] = $this->generateLocaleElementsXMLArray('Descriptions', 'Description', $this->descriptions);
        }

        if ($this->brandId) {
            $element['#children'][] = $this->generateElementXMLArray('BrandExternalId', $this->brandId);
        }

        $element['#children'][] = $this->generateElementXMLArray('ProductPageUrl', $this->pageUrl);

        if (!empty($this->pageUrls)) {
            $element['#children'][] = $this->generateLocaleElementsXMLArray('ProductPageUrls', 'ProductPageUrl', $this->pageUrls);
        }

        $element['#children'][] = $this->generateElementXMLArray('ImageUrl', $this->imageUrl);

        if (!empty($this->customElements)) {
            foreach ($this->customElements as $key => $value) {
                $element['#children'][] = $this->generateElementXMLArray(ucfirst($key), $value);
            }
        }

        if (!empty($this->imageUrls)) {
            $element['#children'][] = $this->generateLocaleElementsXMLArray('ImageUrls', 'ImageUrl', $this->imageUrls);
        }

        if (!empty($this->eans)) {
            $element['#children'][] = $this->generateMultipleElementsXMLArray('EANs', 'EAN', $this->eans);
        }

        if (!empty($this->modelNumbers)) {
            $element['#children'][] = $this->generateMultipleElementsXMLArray('ModelNumbers', 'ModelNumber', $this->modelNumbers);
        }

        if (!empty($this->manufacturePartNumbers)) {
            $element['#children'][] = $this->generateMultipleElementsXMLArray('ManufacturePartNumbers', 'ManufacturePartNumber', $this->manufacturePartNumbers);
        }

        if (!empty($this->upcs)) {
            $element['#children'][] = $this->generateMultipleElementsXMLArray('UPCs', 'UPC', $this->upcs);
        }

        if (!empty($this->isbns)) {
            $element['#children'][] = $this->generateMultipleElementsXMLArray('ISBNs', 'ISBN', $this->isbns);
        }

        if (!empty($this->customAttributes)) {
            $element['#children'][] = $this->generateCustomAttributesXMLArray($this->customAttributes);
        }

        return $element;
    }

    private function generateCustomAttributesXMLArray(array $customAttributes): array
    {
        if (empty($customAttributes)) {
            return [];
        }

        $element = $this->generateElementXMLArray('Attributes');

        foreach ($customAttributes as $attributeId => $attributeValues) {
            foreach ($attributeValues as $value) {
                $attribute = $this->generateElementXMLArray('Attribute', false, ['id' => $attributeId]);
                $attribute['#children'][] = $this->generateElementXMLArray('Value', $value);
                $element['#children'][] = $attribute;
            }
        }

        return $element;
    }
}
