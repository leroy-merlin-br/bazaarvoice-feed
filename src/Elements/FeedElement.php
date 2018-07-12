<?php
namespace BazaarVoice\Elements;

class FeedElement extends ElementBase implements FeedElementInterface
{
  /**
   * @var string
   */
    protected static $apiVersion = '5.6';

  /**
   * @var array
   */
    protected $products = [];

  /**
   * @var array
   */
    protected $brands = [];

  /**
   * @var array
   */
    protected $categories = [];

   /**
    * @var array
    */
    protected $interactions = [];

  /**
   * @var bool
   */
    protected $incremental = false;

    public function __construct(string $name, bool $incremental = false)
    {
        $this->setName($name);
        $this->setIncremental($incremental);
        return $this;
    }

    public function setIncremental(bool $incremental = true): FeedElementInterface
    {
        $this->incremental = $incremental;
        return $this;
    }

    public function addProduct(ProductElementInterface $product): FeedElementInterface
    {
        $this->products[$product->getExternalId()] = $product;
        return $this;
    }

    public function addInteraction(InteractionElement $interaction): FeedElementInterface
    {
        $this->interactions[] = $interaction;
        return $this;
    }

    public function addBrand(BrandElementInterface $brand): FeedElementInterface
    {
        $this->brands[$brand->getExternalId()] = $brand;
        return $this;
    }

    public function addCategory(CategoryElementInterface $category): FeedElementInterface
    {
        $this->categories[$category->getExternalId()] = $category;
        return $this;
    }

    /**
     * @param ProductElementInterface[] $products
     */
    public function addProducts(array $products): FeedElementInterface
    {
        foreach ($products as $product) {
            $this->addProduct($product);
        }
        return $this;
    }

    /**
     * @param BrandElementInterface[] $brands
     */
    public function addBrands(array $brands): FeedElementInterface
    {
        foreach ($brands as $brand) {
            $this->addBrand($brand);
        }
        return $this;
    }

    /**
     * @param CategoryElementInterface[] $categories
     */
    public function addCategories(array $categories): FeedElementInterface
    {
        foreach ($categories as $category) {
            $this->addCategory($category);
        }
        return $this;
    }

    public function generateXMLArray(): array
    {
        $element = [
            '#attributes' => [
                'xmlns' => 'http://www.bazaarvoice.com/xs/PRR/ProductFeed/'.self::$apiVersion,
                'name' => $this->name,
                'incremental' => $this->incremental ? 'true' : 'false',
                'extractDate' => date('Y-m-d\TH:i:s'),
            ],
        ];

        if ($brands = $this->generateBrandsXMLArray()) {
            $element['#children'][] = $brands;
        }

        if ($categories = $this->generateCategoriesXMLArray()) {
            $element['#children'][] = $categories;
        }

        if ($products = $this->generateProductsXMLArray()) {
            $element['#children'][] = $products;
        }

        if ($interactions = $this->generateInteractionsXMLArray()) {
            $element['#children'][] = $interactions;
        }

        return $element;
    }

    private function generateBrandsXMLArray(): array
    {
        if (!count($this->brands)) {
            return [];
        }

        $element = $this->generateElementXMLArray('Brands');
        foreach ($this->brands as $brand) {
            $element['#children'][] = $brand->generateXMLArray();
        }

        return $element;
    }

    private function generateCategoriesXMLArray(): array
    {
        if (!count($this->categories)) {
            return [];
        }

        $element = $this->generateElementXMLArray('Categories');
        foreach ($this->categories as $category) {
            $element['#children'][] = $category->generateXMLArray();
        }

        return $element;
    }

    public function generateProductsXMLArray(): array
    {
        if (!count($this->products)) {
            return [];
        }

        $element = $this->generateElementXMLArray('Products');
        foreach ($this->products as $product) {
            $element['#children'][] = $product->generateXMLArray();
        }

        return $element;
    }

    private function generateInteractionsXMLArray(): array
    {
        if (!count($this->interactions)) {
            return [];
        }

        foreach ($this->interactions as $interaction) {
            $element['#children'][] = $interaction->generateXMLArray();
        }

        return $element;
    }
}
