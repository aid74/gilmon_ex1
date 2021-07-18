<?php

namespace App\Commands;

use App\Command;
use App\Product;

class SellInUpdateCommand extends Command
{
    public function execute(): void
    {
        $this->product->getItem()->sell_in--;
    }
}
