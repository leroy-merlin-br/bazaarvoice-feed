<?php
namespace BazaarVoice\Elements;

interface ElementInterface
{
    public function getExternalId(): string;

    public function setExternalId(string $externalId): ElementInterface;

    public function setName(string $name): ElementInterface;

    public function setRemoved(bool $removed = true): ElementInterface;

    /**
   * Creates an array for the element that can be used to build an XML document.
   *
   * @return array
   *  Key/value array to represent element for XML generation.
   *  known keys (all optional):
   *   - #name : the name of the element.
   *   - #value : element value.
   *   - #attributes : key/value array of XML attributes for element.
   *   - #children : array of child element arrays.
   */
    public function generateXMLArray(): array;

    /**
   * Builds array for specific XML element.
   *
   * @param string     $name
   *   Element name.
   *
   * @param bool|mixed $value
   *   (optional) element value.
   *
   * @param array      $attributes
   *   (optional) Array of XML attributes.
   *
   * @return array
   *  Key/value array to represent element for XML generation.
   *  known keys (all optional):
   *   - #name : the name of the element.
   *   - #value : element value.
   *   - #attributes : key/value array of XML attributes for element.
   *   - #children : array of child element arrays.
   */
    public function generateElementXMLArray(string $name, $value, array $attributes = []): array;

    /**
   * Builds array for an XML element that represents multiple values.
   *
   * @param string $multipleName
     *   Name of plural element.
   *
   * @param string $singleName
   *   Name of single element.
   *
   * @param array  $elements
   *   array of values to add as single elements to multiple element.
   *
   *  Key/value array to represent element for XML generation.
   *  known keys (all optional):
   *   - #name : the name of the multiple element.
   *   - #children : array of child elements containing
   *        - #name :  single element name
   *        - #value : element value.
   *
   *  @return array
   */
    public function generateMultipleElementsXMLArray(string $multipleName, string $singleName, array $elements): array;

    /**
   * Builds array for an XML element that represents locale varaints of an element.
   *
   * @param string $multipleName
   *   Name of plural element.
   *
   * @param string $singleName
   *   Name of single element.
   *
   * @param array  $localeElements
   *   Key/value array of elements to create.
   *    - locale_code => value.
   *
   *  Key/value array to represent element for XML generation.
   *  known keys (all optional):
   *   - #name : the name of the multiple element.
   *   - #children : array of child elements containing
   *        - #name :  single element name
   *        - #value : element value.
   *        - #attributes : array with locale key
   *            - locale => (locale_string)
   *
   * @return array
   */
    public function generateLocaleElementsXMLArray(string $multipleName, string $singleName, array $localeElements): array;
}
