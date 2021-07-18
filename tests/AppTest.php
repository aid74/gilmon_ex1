<?php

declare(strict_types=1);

namespace Tests;

use App\App;
use App\Item;
use App\ProductFactory;
use App\Products\AgedBrie;
use App\Products\BackstagePass;
use App\Products\Conjured;
use App\Products\RegularProduct;
use App\Products\Sulfuras;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    /**
     * @var ProductFactory | \PHPUnit\Framework\MockObject\MockObject
     */
    private $product_factory;


    private static function dataSetWithConsistentValues(string $name, string $className, array $array): array
    {
        return array_map(function ($dataSet) use ($name, $className) {
            array_unshift($dataSet, $name, $className);

            return $dataSet;
        }, $array);
    }


    public function regularItemDataProvider(): array
    {
        return self::dataSetWithConsistentValues(
            'Regular product',
            RegularProduct::class,
            [
            // Обычные продукт
            // sellIn, quality, expectedSellIn, expectedQuality
            'regular: Простое снижение по показателям  quality (к-во) and sellIn (срок реализации)' => [6, 23, 5, 22],
            'regular: срок храния истек, к-во товара ухудшается в два раза быстрее (SellIn)' => [0, 20, -1, 18],
            'regular: срок храния истек, к-во товара ухудшается в два раза быстрее' => [-5, 20, -6, 18],
            'regular: Качество товара никогда не может быть <0' => [2, 0, 1, 0],
            'regular: Качество товара никогда не может быть <0 (снижение с мультипликатором)' => [0, 1, -1, 0],
            ]
        );
    }


    public function agedBrieDataProvider(): array
    {
        return self::dataSetWithConsistentValues(
            'Aged Brie',
            AgedBrie::class,
            [
            // Для товара «Aged Brie» качество увеличивается пропорционально возрасту;
            // sellIn, quality, expectedSellIn, expectedQuality
            'AgedBrie: Увеличивается quality(качество) и снижается  sellIn (срок реализации)' => [2, 10, 1, 11],
            'AgedBrie: quality(качество) не может быть больше 50' => [8, 50, 7, 50],
            'AgedBrie: quality(качество) снижается вдвое после срока реализации' => [0, 10, -1, 12],
            'AgedBrie: quality(качество) не превышает максимального значения в день продажи (шаг)' => [0, 49, -1, 50],
            'AgedBrie: Обновление quality(качество) после истечения срока реализации' => [-10, 10, -11, 12],
            ]
        );
    }


    public function sulfurasDataProvider(): array
    {
        return self::dataSetWithConsistentValues(
            'Sulfuras, Hand of Ragnaros',
            Sulfuras::class,
            [
            // «Sulfuras» является легендарным товаром, поэтому у него нет срока хранения
            // и не подвержен ухудшению качества;
            // sellIn, quality, expectedSellIn, expectedQuality
            'sulfuras: Качество не меняется до       истечения срока хранения' => [5, 80, 5, 80],
            'sulfuras: Качество не меняется во время истечения срока хранения' => [0, 80, 0, 80],
            'sulfuras: Качество не меняется после    истечения срока хранения' => [-5, 80, -5, 80],
            ]
        );
    }


    public function backstagePassesDataProvider(): array
    {
        return self::dataSetWithConsistentValues(
            'Backstage passes to a TAFKAL80ETC concert',
            BackstagePass::class,
            [
                // Качество «Backstage passes» также, как и «Aged Brie», увеличивается по мере приближения
                // к сроку хранения. Качество увеличивается на 2, когда до истечения срока хранения 10 или
                // менее дней и на 3, если до истечения 5 или менее дней. При этом качество падает до 0
                // после даты проведения концерта.

                // sellIn, quality, expectedSellIn, expectedQuality
                'backstage: 11 дней до срока концерта: нормальное приращение качества' => [11, 10, 10, 11],
                'backstage: 10 дней до срока концерта: quality(к-во) увеличивается на 2' => [10, 10, 9, 12],
                'backstage: 10 дней до срока концерта: quality(к-во) не увеличивается > 50' => [10, 50, 9, 50],
                'backstage: 10 дней до срока концерта: quality(к-во) не увеличивается > 50 (шаг)' => [10, 49, 9, 50],
                'backstage: 5 дней до срока концерта: quality(к-во) увеличивается на 3' => [5, 10, 4, 13],
                'backstage: 5 дней до срока концерта: quality(к-во) не увеличивается > 50' => [5, 50, 4, 50],
                'backstage: 5 дней до срока концерта: quality(к-во) не увеличивается > 50 (шаг)' => [5, 49, 4, 50],
                'backstage: 1 день до срока концерта: quality(к-во) увеличивается на 3' => [1, 20, 0, 23],
                'backstage: 1 день до срока концерта: quality(к-во) не увеличивается > 50' => [1, 50, 0, 50],
                'backstage: 1 день до срока концерта: quality(к-во) не увеличивается > 50 (шаг)' => [1, 49, 0, 50],
                'backstage: quality(к-во) становится 0 в день после концерта' => [0, 20, -1, 0],
                'backstage: quality(к-во) становится 0 в последующие дни, после концерта' => [-2, 40, -3, 0],
            ]
        );
    }


    public function conjuredDataProvider(): array
    {
        return self::dataSetWithConsistentValues(
            'Conjured Mana Cake',
            Conjured::class,
            [
            // «Conjured» товары теряют качество в два раза быстрее, чем обычные товары.
            // sellIn, quality, expectedSellIn, expectedQuality
            'conjured: Простое снижение по показателям  quality (к-во) and sellIn (срок реализации)' => [6, 23, 5, 21],
            'conjured: quality(качество) снижается вдвое быстрее в момент срока реализации' => [0, 20, -1, 16],
            'conjured: quality(качество) снижается вдвое быстрее после срока реализации' => [-5, 20, -6, 16],
            'conjured: quality(качество) не м.б. ниже 0' => [2, 0, 1, 0],
            'conjured: quality(качество) не м.б. ниже 0, когда оно снижается вдвое быстрее' => [0, 1, -1, 0],
            ]
        );
    }


    protected function setUp(): void
    {
        $this->product_factory = $this->getMockBuilder(ProductFactory::class)->disableOriginalConstructor()->getMock();
    }


    protected function buildApp(Item $item, string $className): App
    {
        $this->product_factory->expects($this->once())->method('build')
              ->will($this->returnValue(new $className($item)));

        return new App([$item], $this->product_factory);
    }

    /**
     * @dataProvider regularItemDataProvider
     * @dataProvider agedBrieDataProvider
     * @dataProvider sulfurasDataProvider
     * @dataProvider backstagePassesDataProvider
     * @dataProvider conjuredDataProvider
     *
     * @covers       \App\App::updateQuality()
     *
     * @param string $name
     * @param string $className
     * @param int $sellIn
     * @param int $quality
     * @param int $expectedSellIn
     * @param int $expectedQuality
     * @throws \Exception
     */
    public function testIfItUpdatesItemCorrectly(
        string $name,
        string $className,
        int $sellIn,
        int $quality,
        int $expectedSellIn,
        int $expectedQuality
    ): void {

        $item = new Item($name, $sellIn, $quality);

        $this->buildApp($item, $className)->updateQuality();

        $this->assertEquals($expectedQuality, $item->quality);
        $this->assertEquals($expectedSellIn, $item->sell_in);
    }
}
