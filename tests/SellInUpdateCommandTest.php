<?php

namespace Tests;

use App\Commands\SellInUpdateCommand;
use App\Item;
use App\Product;
use PHPUnit\Framework\TestCase;

class SellInUpdateCommandTest extends TestCase
{
    /**
     * @var \App\Commands\SellInUpdateCommand
     */
    private $sell_in_update_command;

    /**
     * @var Product | \PHPUnit\Framework\MockObject\MockObject
     */
    private $product;

    /**
     * @var Item
     */
    private $item;


    /**
     * @param int $sellIn
     */
    protected function buildItem(int $sellIn): void
    {
        $this->item = new Item('Any Item', $sellIn, 40);

        $this->product = $this->getMockBuilder(Product::class)->disableOriginalConstructor()->getMock();

        $this->product->expects($this->any())->method('getItem')->will($this->returnValue($this->item));

        $this->sell_in_update_command = new SellInUpdateCommand($this->product);
    }


    public function sellInDataProvider(): array
    {
        return [
            [-10],
            [-5,],
            [0,],
            [5,],
            [10,],
            [15,],
            [20,],
            [25,],
            [30,],
            [35,],
            [40,],
            [45,],
            [50,],
            [80,],
        ];
    }

    /**
     * @dataProvider sellInDataProvider
     *
     * @covers       \App\Commands\SellInUpdateCommand::execute()
     *
     * @param int $sellIn
     */
    public function testIfDecreasesSellIn(int $sellIn): void
    {
        $this->buildItem($sellIn);

        $this->sell_in_update_command->execute();
        $this->assertEquals($sellIn - 1, $this->item->sell_in);

        $this->sell_in_update_command->execute();
        $this->assertEquals($sellIn - 2, $this->item->sell_in);
    }
}
