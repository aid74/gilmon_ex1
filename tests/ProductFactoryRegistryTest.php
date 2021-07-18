<?php

namespace Tests;

use App\Products\AgedBrie;
use App\Products\BackstagePass;
use App\Products\Conjured;
use App\Products\RegularProduct;
use App\Products\Sulfuras;
use App\ProductFactoryRegistry;
use PHPUnit\Framework\TestCase;

class ProductFactoryRegistryTest extends TestCase
{
    /**
     * @var ProductFactoryRegistry
     */
    private $product_factory_registry;


    protected function setUp(): void
    {
        $this->product_factory_registry = new ProductFactoryRegistry();
    }

    public function classDataProvider(): array
    {
        return [
            [
                RegularProduct::NAME,
                RegularProduct::class,
            ],
            [
                AgedBrie::NAME,
                AgedBrie::class,
            ],
            [
                Sulfuras::NAME,
                Sulfuras::class,
            ],
            [
                BackstagePass::NAME,
                BackstagePass::class,
            ],
            [
                Conjured::NAME,
                Conjured::class,
            ],
        ];
    }

    /**
     * @dataProvider classDataProvider
     * @covers       \App\ProductFactoryRegistry::register
     */
    public function testIfRegistersAClass(string $name, string $class): void
    {
        $this->product_factory_registry->register($class);

        $products = $this->product_factory_registry->getProducts();

        $this->assertArraySubset([$name => $class], $products);
    }
}
