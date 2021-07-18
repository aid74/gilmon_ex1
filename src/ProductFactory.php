<?php

namespace App;

use App\Products\RegularProduct;

class ProductFactory
{

    /**
     * @var \App\ProductFactoryRegistry
     */
    private $product_factory_registry;

    public function __construct(ProductFactoryRegistry $productFactoryRegistry)
    {
        $this->product_factory_registry = $productFactoryRegistry;
    }

    public function build(Item $item): Product
    {
        return self::newClass($this->findProductInRegistry($item->name), $item);
    }

    protected function getProductsFromRegistry(): array
    {
        return $this->product_factory_registry->getProducts();
    }

    /**
     *
     * @param string $name
     * @return string
     */
    protected function findProductInRegistry($name): string
    {
        if (! array_key_exists($name, $this->getProductsFromRegistry())) {
            return ProductFactoryRegistry::DEFAULT_PRODUCT;
        }

        return $this->getProductsFromRegistry()[$name];
    }

    /**
     * Creates the class of a product
     *
     * @param string $className
     * @param Item $item
     * @return \App\Product
     * @throws \Exception
     */
    protected static function newClass(string $className, Item $item): Product
    {
        if (! class_exists($className)) {
            throw new \Exception("No Factory class found");
        }

        return new $className($item);
    }
}
