<?php
namespace BazaarVoice\Elements\Interaction;

use BazaarVoice\Elements\ElementBase;
use BazaarVoice\Elements\ElementInterface;

class ProductElement extends ElementBase
{
    /**
     * @var string
     */
    protected $price;

    public function __construct(string $externalId, string $name, string $price)
    {
        $this->setExternalId($externalId);
        $this->setName($name);
        $this->setPrice($price);

        return $this;
    }

    public function setPrice(string $price): ElementInterface
    {
        $this->price = $price;

        return $this;
    }

    public function generateXMLArray(): array
    {
        $element = parent::generateXMLArray();

        $element['#name'] = 'Product';

        $element['#children'][] = $this->generateElementXMLArray('Price', $this->price);

        return $element;
    }
}
