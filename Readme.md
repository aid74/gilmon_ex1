### Requirements

**php**: 7.4

### Usage

Example in file texttest\_fixture.php

```php
<?php

$items = [
    'RegularProduct1'=> new Item('+5 Dexterity Vest', 10, 20),
    'AgedBrie1'      => new Item('Aged Brie', 2, 0),
    'RegularProduct2'=> new Item('Elixir of the Mongoose', 5, 7),
    'sulfuras1'      => new Item('Sulfuras, Hand of Ragnaros', 0, 80),
    'sulfuras2'      => new Item('Sulfuras, Hand of Ragnaros', -1, 80),
    'BackstagePass1' => new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
    'BackstagePass2' => new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
    'BackstagePass3' => new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
    'conjured1'      => new Item('Conjured Mana Cake', 3, 6)
];


$productFactoryRegistry = new ProductFactoryRegistry();

$productFactoryRegistry->register(RegularProduct::class);
$productFactoryRegistry->register(Sulfuras::class);
$productFactoryRegistry->register(BackstagePass::class);
$productFactoryRegistry->register(AgedBrie::class);
$productFactoryRegistry->register(Conjured::class);

$productFactory = new ProductFactory($productFactoryRegistry);

$app = new App($items, $productFactory);
```

### Tests

`phpunit`

or

`php composer.phar run-script test`
`php composer.phar run-script tests`
`php composer.phar run-script test-coverage`

### Description

Test  [Gilmon](https://gilmon.ru).
The following patterns were applied in the work:

- ** Abstract factory ** creation of `Products` (a kind of implements` Items`, since the `Items` condition is a finalized class)
- ** Decorator ** for `Products` with it, the value of` Item` can be changed.
- ** Command ** to change `Item` values according to the specified rules. 

### Product

All values of `Product` can be used. installed dynamically.
- Properties
  - const `name`. The name by which `ProductFactory` will identify` Product`. ** Default: ** `empty`
  - `max_quality`. The maximum quality value. ** Default: ** `50`
  - `min_quality`. The minimum value (quality). ** Default: ** `0`
  - `quality_step`. Determines the direction and rate of change for the quality parameter. ** Default: ** `-1`
  - `day_range_multiplier`. Determines the dependence of the quality of the goods from the days before the sale of the goods.
   ** Default: ** `[0 => 2]` this means that after the date of sale (0) the rate of decline in quality doubles (2).
- Public methods
  - `update`. Updating `Item` for a new time period (day by default).
  - `getItem`. Returns the `Item` associated with this product.
  - `getMaxQuality`. getter for `max_quality`
  - `getMinQuality`. getter for `min_quality`
  - `getQualityStep`. Returns the quality step with multipliers.
  - `getNewQuality`. Returns the quality that the Product will have on the new day.
  - `isAfterSale`. Returns information about whether a product is sold before or after the expiration date 

### New Product
To create a new product, you need to create a new class in the `Products` directory by analogy with` RegularProduct.php`
And, of course, remember to register the `ProductFactoryRegistry` in the container. 

```php
$productFactoryRegistry->register(MyNewProduct::class);
```
