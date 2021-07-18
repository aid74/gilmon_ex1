<?php

namespace App;

use App\Products\RegularProduct;

class ProductFactoryRegistry
{
    /**
     * @var array
     */
    protected $products = [];

    public const DEFAULT_PRODUCT = RegularProduct::class;

    public function register(string $product): void
    {
        if (!is_subclass_of($product, Product::class)) {
            throw new \Exception("Wrong!!! Factory Class is not a Prod");
        }

        $this->products[$product::NAME] = $product;
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}
