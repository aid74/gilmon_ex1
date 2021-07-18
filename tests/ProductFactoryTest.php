<?php

namespace Tests;

use App\Item;
use App\ProductFactory;
use App\ProductFactoryRegistry;
use App\Products\AgedBrie;
use App\Products\BackstagePass;
use App\Products\Conjured;
use App\Products\RegularProduct;
use App\Products\Sulfuras;
use PHPUnit\Framework\TestCase;

class ProductFactoryTest extends TestCase
{
    /**
     * @var ProductFactory
     */
    private $product_factory;

    /**
     * @var ProductFactoryRegistry | \PHPUnit\Framework\MockObject\MockObject
     */
    private $product_factory_registry;


    protected function setUp(): void
    {
        $this->product_factory_registry = $this->getMockBuilder(ProductFactoryRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->product_factory = new ProductFactory($this->product_factory_registry);
    }


    public function buildableClassDataProvider(): array
    {
        return [
            'regular product' => ['Regular Product', RegularProduct::class],
            'random name' => ['Milk', RegularProduct::class],
            'random name v1' => ['Test', RegularProduct::class],
            'Aged Brie' => ['Aged Brie', AgedBrie::class],
            'Sulfuras' => ['Sulfuras, Hand of Ragnaros', Sulfuras::class],
            'Tickets' => ['Backstage passes to a TAFKAL80ETC concert', BackstagePass::class],
            'Conjured item' => ['Conjured Mana Cake', Conjured::class],
        ];
    }

    /**
     * @dataProvider buildableClassDataProvider
     * @covers \App\ProductFactory::build
     *
     * @param string $itemName
     * @param class-string<object> $expectsClass
     * @throws \Exception
     */
    public function testIfBuildsTheRightClass($itemName, $expectsClass): void
    {
        $this->product_factory_registry->expects($this->any())
            ->method('getProducts')->will($this->returnValue([$itemName => $expectsClass]));

        $this->assertInstanceOf($expectsClass, $this->product_factory->build(new Item($itemName, 10, 0)));
    }
}
