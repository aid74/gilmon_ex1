<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\App;
use App\Item;
use App\ProductFactory;
use App\ProductFactoryRegistry;
use App\Products\AgedBrie;
use App\Products\BackstagePass;
use App\Products\Conjured;
use App\Products\RegularProduct;
use App\Products\Sulfuras;

echo "OMGHAI!" . PHP_EOL;

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

$days = 2;
if (count($argv) > 1) {
    $days = (int) $argv[1];
}


for ($i = 0; $i < $days; $i++) {
    echo("-------- day $i --------" . PHP_EOL);
    echo("name, sellIn, quality" . PHP_EOL);
    foreach ($items as $item) {
        echo $item . PHP_EOL;
    }
    echo PHP_EOL;
    $app->updateQuality();
}
