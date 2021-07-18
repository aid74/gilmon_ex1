<?php

namespace App\Commands;

use App\Command;
use App\Product;

class ChangeQualityCommand extends Command
{

    private int $quality = 0;

    public function setQuality(int $quality): self
    {
        $this->quality = $quality;

        return $this;
    }

    public function execute(): void
    {
        $this->product->getItem()->quality = $this->quality;
    }
}
