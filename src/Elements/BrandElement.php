<?php
namespace BazaarVoice\Elements;

class BrandElement extends ElementBase implements BrandElementInterface
{
    public function __construct(string $externalId, string $name)
    {
        $this->setExternalId($externalId);
        $this->setName($name);
    }

    public function generateXMLArray(): array
    {
        $element = parent::generateXMLArray();
        $element['#name'] = 'Brand';
        return $element;
    }
}
