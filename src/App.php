<?php

declare(strict_types=1);

namespace App;

final class App
{
    /**
     * @var array
     */
    private static $items = [];

    /**
     * @var \App\ProductFactory
     */
    public static $product_factory;

    public function __construct(array $items, ProductFactory $productFactory)
    {
        static::$items = $items;
        static::$product_factory = $productFactory;
    }

    public static function updateQuality(): void
    {
        foreach (static::$items as $item) {
            static::$product_factory->build($item)->update();
        }
    }
}
