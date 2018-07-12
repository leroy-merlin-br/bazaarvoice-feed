<?php
namespace BazaarVoice\Elements;

use ErrorException;

abstract class ElementBase implements ElementInterface
{
  /**
   * @var string
   */
    protected $externalId;

  /**
   * @var string
   */
    protected $name;

  /**
   * @var array
   */
    protected $names;

  /**
   * @var bool
   */
    protected $removed;

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setRemoved(bool $removed = true): ElementInterface
    {
        $this->removed = $removed;
        return $this;
    }

    public function setExternalId(string $externalId): ElementInterface
    {
        $this->externalId = $externalId;
        return $this;
    }

    public function setName(string $name): ElementInterface
    {
        $this->name = $name;
        return $this;
    }

    public function addName(string $name, string $locale): ElementInterface
    {
        if ($this->checkValidLocale($locale)) {
            $this->names[$locale] = $name;
        }

        return $this;
    }

    public function generateXMLArray(): array
    {
        $element = [];

        if ($this->removed) {
            $element['#attributes']['removed'] = 'true';
        }

        if ($this->externalId) {
            $element['#children'][] = $this->generateElementXMLArray('ExternalId', $this->externalId);
        }

        if ($this->name) {
            $element['#children'][] = $this->generateElementXMLArray('Name', $this->name);
        }

        if (!empty($this->names)) {
            $element['#children'][] = $this->generateLocaleElementsXMLArray('Names', 'Name', $this->names);
        }

        return $element;
    }

    public function generateElementXMLArray(string $name, $value = '', array $attributes = []): array
    {
        $element = [
            '#name' => $name,
        ];

        if ($value) {
            $element['#value'] = is_string($value) ? htmlspecialchars($value) : $value;
        }

        if (!empty($attributes)) {
            $element['#attributes'] = $attributes;
        }

        return $element;
    }

    public function generateMultipleElementsXMLArray(string $multipleName, string $singleName, array $elements): array
    {
        if (empty($elements)) {
            return [];
        }

        $element = $this->generateElementXMLArray($multipleName);

        foreach ($elements as $value) {
            $element['#children'][] = $this->generateElementXMLArray($singleName, $value);
        }
        return $element;
    }

    public function generateLocaleElementsXMLArray(string $multipleName, string $singleName, array $localeElements): array
    {
        if (empty($localeElements)) {
            return [];
        }

        $element = $this->generateElementXMLArray($multipleName);

        foreach ($localeElements as $locale => $value) {
            $element['#children'][] = $this->generateElementXMLArray($singleName, $element, ['locale' => $locale]);
        }

        return $element;
    }

    public function checkValidLocale(string $locale): bool
    {
        if (preg_match('/^[a-z]{2}\_[A-Z]{2}$/', $locale)) {
            return true;
        }

        throw new ErrorException('Invalid Locale code. Must match format of "xx_YY"');
    }
}
