<?php
namespace BazaarVoice\Elements;

class CategoryElement extends ElementBase implements CategoryElementInterface
{
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
    protected $parentId;

  /**
   * @var array
   */
    protected $pageUrls;

  /**
   * @var array
   */
    protected $imageUrls;

    public function __construct(string $externalId, string $name, string $pageUrl)
    {
        $this->setExternalId($externalId);
        $this->setName($name);
        $this->setPageUrl($pageUrl);
    }

    public function setParentId(string $parentId): CategoryElementInterface
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function setPageUrl(string $url): CategoryElementInterface
    {
        $this->pageUrl = $url;
        return $this;
    }

    public function setImageUrl(string $url): CategoryElementInterface
    {
        $this->imageUrl = $url;
        return $this;
    }

    public function addPageUrl(string $url, string $locale): CategoryElementInterface
    {
        $this->pageUrls[$locale] = $url;
        return $this;
    }

    public function addImageUrl(string $url, string $locale): CategoryElementInterface
    {
        $this->imageUrls[$locale] = $url;
        return $this;
    }

    public function generateXMLArray(): array
    {
        $element = parent::generateXMLArray();

        $element['#name'] = 'Category';

        $element['#children'][] = $this->generateElementXMLArray('CategoryPageUrl', $this->pageUrl);

        if ($this->parentId) {
            $element['#children'][] = $this->generateElementXMLArray('ParentExternalId', $this->parentId);
        }

        if (!empty($this->pageUrls)) {
            $element['#children'][] = $this->generateLocaleElementsXMLArray('CategoryPageUrls', 'CategoryPageUrl', $this->pageUrls);
        }

        if ($this->imageUrl) {
            $element['#children'][] = $this->generateElementXMLArray('ImageUrl', $this->imageUrl);
        }

        if (!empty($this->imageUrls)) {
            $element['#children'][] = $this->generateLocaleElementsXMLArray('ImageUrls', 'ImageUrl', $this->imageUrls);
        }

        return $element;
    }
}
