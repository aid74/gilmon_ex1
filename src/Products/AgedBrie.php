<?php

namespace App\Products;

use App\Product;

class AgedBrie extends Product
{
    public const NAME = 'Aged Brie';

    # Для товара «Aged Brie» качество увеличивается пропорционально возрасту;
    protected static $quality_step = 1;
}
