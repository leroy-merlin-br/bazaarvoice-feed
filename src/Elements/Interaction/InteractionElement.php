<?php
namespace BazaarVoice\Elements\Interaction;

use BazaarVoice\Elements\ElementBase;

class InteractionElement extends ElementBase
{
    /**
     * @var string
     */
    protected $transactionDate;

    /**
     * @var string
     */
    protected $emailAddress;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $userId;
    /**
     * @var array
     */
    protected $products = [];

    public function __construct(
        string $transactionDate,
        string $emailAddress,
        string $userName,
        string $userId,
        string $locale,
        array $products
    ) {
        $this->setTransactionDate($transactionDate);
        $this->setEmailAddress($emailAddress);
        $this->setUserName($userName);
        $this->setUserId($userId);
        $this->setLocale($locale);
        $this->setProducts($products);
    }

    public function setTransactionDate(string $transactionDate): self
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    public function setEmailAddress(string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function setProducts(array $products): self
    {
        foreach ($products as $product) {
            $this->products[] = $this->createNewProductElement($product);
        }

        return $this;
    }

    public function generateXMLArray(): array
    {
        $element = parent::generateXMLArray();

        $element['#name'] = 'Interaction';

        $element['#children'] = [
            $this->generateElementXMLArray('TransactionDate', $this->transactionDate),
            $this->generateElementXMLArray('EmailAddress', $this->emailAddress),
            $this->generateElementXMLArray('UserName', $this->userName),
            $this->generateElementXMLArray('UserID', $this->userId),
            $this->generateElementXMLArray('Locale', $this->locale),
            $this->generateProductsXMLArray(),
        ];

        return $element;
    }

    private function generateProductsXMLArray(): array
    {
        if (!count($this->products)) {
            return [];
        }

        $element = parent::generateElementXMLArray('Products');
        foreach ($this->products as $product) {
            $element['#children'][] = $product->generateXMLArray();
        }

        return $element;
    }

    private function createNewProductElement(array $product): ProductElement
    {
        return new ProductElement($product['id'], $product['name'], $product['price']);
    }
}
