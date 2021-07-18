<?php

namespace Tests;

use App\Commands\ChangeQualityCommand;
use App\Item;
use App\Product;
use PHPUnit\Framework\TestCase;

class ChangeQualityCommandTest extends TestCase
{

    /**
     * @var ChangeQualityCommand
     */
    private $change_quality_command;

    /**
     * @var Product | \PHPUnit\Framework\MockObject\MockObject
     */
    private $product;

    /**
     * @var Item
     */
    private $item;


    protected function setUp(): void
    {
        $this->item = new Item('Any Item', 5, 40);

        $this->product = $this->getMockBuilder(Product::class)->disableOriginalConstructor()->getMock();

        $this->product->expects($this->any())->method('getItem')->will($this->returnValue($this->item));

        $this->change_quality_command = new ChangeQualityCommand($this->product);
    }


    public function qualityDataProvider(): array
    {
        return [
            [-10],
            [-5,],
            [0,],
            [6,],
            [20,],
            [35,],
            [50,],
            [80,],
        ];
    }


    /**
     * @dataProvider qualityDataProvider
     *
     * @covers       \App\Commands\ChangeQualityCommand::setQuality()
     * @covers       \App\Commands\ChangeQualityCommand::execute()
     *
     * @param int $quality
     */
    public function testIfChangesQualityCorrectly(int $quality): void
    {
        $this->change_quality_command->setQuality($quality)->execute();

        $this->assertEquals($quality, $this->item->quality);
    }
}
