<?php

namespace App\Products;

use App\Product;
use App\Commands\ChangeQualityCommand;

class BackstagePass extends Product
{

    public const NAME = 'Backstage passes to a TAFKAL80ETC concert';


    protected static $quality_step = 1;


    protected static $day_range_multiplier = [
        '10' => 2,
        '5' => 3,
    ];


    public function update(): void
    {
        parent::update();
        $this->expiresAfterSale();
    }


    public function expiresAfterSale(): void
    {
        if ($this->isAfterSale()) {
            (new ChangeQualityCommand($this))->setQuality(0)->execute();
        }
    }
}
