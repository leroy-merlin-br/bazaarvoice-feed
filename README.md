# Bazaarvoice Product and Interaction feed Library

[![Latest Version on Packagist](https://img.shields.io/packagist/v/leroy-merlin-br/bazaarvoice-productfeed.svg?style=flat-square)](https://packagist.org/packages/leroy-merlin-br/bazaarvoice-productfeed)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/leroy-merlin-br/bazaarvoice-productfeed.svg?style=flat-square)](https://packagist.org/packages/leroy-merlin-br/bazaarvoice-productfeed)
[![Build Status](https://travis-ci.org/leroy-merlin-br/bazaarvoice-productfeed.svg?branch=master)](https://travis-ci.org/leroy-merlin-br/bazaarvoice-productfeed)
[![Coverage Status](https://coveralls.io/repos/github/leroy-merlin-br/bazaarvoice-productfeed/badge.svg?branch=master)](https://coveralls.io/github/leroy-merlin-br/bazaarvoice-productfeed?branch=master)

A PHP library for generating and sFTPing XML [Bazaarvoice ProductFeeds](http://labsbp-docsportal.aws.bazaarvoice.com/DataFeeds/Introduction/IntroductionDataFeeds_con.html).

## Install

Via Composer

``` bash
$ composer require leroy-merlin-br/bazaarvoice-productfeed
```

## Usage

### Creating a Feed.
``` php
$productFeed = new \BazaarVoice\Product\Feed();
```

### Creating a feedElement
``` php
$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed');
```

### Creating an Incremental feed.
``` php
$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed', true);
```

``` php
$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed')
  ->setIncremental(true);
```


### Creating products and adding them to a feed.
``` php
$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed');

$productElement = $productFeed->newProduct('my_product', 'My Product', 'product_category_123', 'htttp://www.example.com/my-product', 'http://www.example.com/images/my-product.jpg');
$feedElement->addProduct($product_element);

$moreProducts = [];

$secondProduct = $productFeed->newProduct('second_product', 'Second Product', 'product_category_456', 'htttp://www.example.com/second-product', 'http://www.example.com/images/second-product.jpg');
  ->setDescription('This is my second product')
  ->addPageUrl('http://www.example.es/second-product', 'es_SP')
  ->setBrandId('my_brand_123')
  ->addUPC('012345');
  
$moreProducts[] = $secondProduct;

$moreProducts[] = $productFeed->newProduct('third_product', 'Third Product', 'product_category_789', 'htttp://www.example.com/third-product', 'http://www.example.com/images/third-product.jpg')
  ->addISBN('123-456-7890')
  ->addPageUrl('http://www.example.co.uk/third-product', 'en_UK')
  ->addCustomAttribute('PRODUCT_FAMILY', 'example_products');

$feedElement->addProducts($moreProducts);

```

### Creating categories and adding them to a feed.
``` php
$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed');

// ...

$categoryElement = $productFeed->newCategory('my_category', 'My Category', 'htttp://www.example.com/my-product');
$feedElement->addCategory($categoryElement);

$moreCategories = [];

$secondCategory = $productFeed->newCategory('second_category', 'Second Category', 'http://www.example.com/second-category')
  ->setImageUrl('http://www.example.com/images/second-category.jpg')
  ->addImageUrl('http://www.example.co.uk/images/uk-second-category.jpg', 'en_UK')
  ->setParentId('parent_category_id');

$moreCategories[] = $secondCategory;

$feedElement->addCategories($moreCategories);

```

### Creating brands and adding them to a feed.
``` php
$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed');

// ...

$brandElement = $productFeed->newBrand('my_brand', 'My Brand');
$feedElement->addBrand($brandElement);

$moreBrands = [];

$secondBrand = $productFeed->newBrand('second_brand', 'Second Brand')
  ->addName('Duo Brand', 'es_SP')
  ->addName('Brand the Second', 'en_UK');

$moreBrands[] = $secondBrand;

$moreBrands[] = $productFeed->newBrand('third_brand', 'Third Brand');

$feedElement->addBrands($moreBrands);

```

### Creating interactions (orders) and adding them to a feed.
``` php
$orderFeed = new \BazaarVoice\Interaction\Feed();
$feedElement = $orderFeed->newFeed('Order feed');

$orderProducts = [
    [
        'id' => 'productId123',
        'name' => 'Product name',
        'category' => 'Product Category',
        'url' => 'http://product-url',
        'imageUrl' => 'http://image-url',
        'price' => 29,
    ],
];
$order = $feed->newInteraction('22/03/1987', 'john@doe.com', 'John Doe', 'userId123', 'pt_BR', $orderProducts);

$feedElement->addInteraction($orderFeed);

// $orderFeed->printFeed();

```

### Print ProductFeed XML string
``` php
$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed');

// ... add products, brands & categories ...

print $productFeed->printFeed($feedElement);
```

### Saving Productfeed as an XML file.
``` php

$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed');

// ... add products, brands & categories ...

$productFeed->saveFeed($feedElement, 'path/to/dir', 'my_feed_XYZ');
```

### SFTP ProductFeed to BazaarVoice Production.
``` php

$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed');

// ... add products, brands & categories ...

if ($feedFile = $productFeed->saveFeed($feedElement, 'path/to/dir', 'my_feed_XYZ') {  
  try {
    $productFeed->sendFeed($feedFile, $sftpUsername, $sftpPassword);
  } catch (\Exception $e) {
    // Failed to FTP feed file.
  }
}

```

#### SFTP ProductFeed to Bazaarvoice Staging.
``` php

$productFeed = new \BazaarVoice\Product\Feed();
$feedElement = $productFeed->newFeed('my_feed');

// ... add products, brands & categories ...

if ($feedFile = $productFeed->saveFeed($feedElement, 'path/to/dir', 'my_feed_XYZ') {  
  try {
    $productFeed->useStage()->sendFeed($feedFile, $sftpUsername, $sftpPassword);
  } catch (\Exception $e) {
    // Failed to FTP feed file.
  }
}

```


## Testing

``` bash
$ composer test
```

## Credits

- [Mike Miles](https://github.com/mikemiles86)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
