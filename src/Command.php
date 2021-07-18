<?php

namespace App;

abstract class Command
{
    /**
     * @var \App\Product
     */
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    abstract public function execute(): void;
}
