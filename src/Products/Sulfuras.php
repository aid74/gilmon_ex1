<?php

namespace App\Products;

use App\Item;
use App\Product;

class Sulfuras extends Product
{
    # «Sulfuras» является легендарным товаром, поэтому у него нет срока хранения и не подвержен ухудшению качества;
    public const NAME = 'Sulfuras, Hand of Ragnaros';
    public const QUALITY = 80;


    public function __construct(Item $item)
    {
        $item->quality = self::QUALITY;

        parent::__construct($item);
    }


    public function update(): void
    {
    }
}
