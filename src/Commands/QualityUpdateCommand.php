<?php

namespace App\Commands;

use App\Command;
use App\Product;

class QualityUpdateCommand extends Command
{
    /**
     * @var ChangeQualityCommand
     */
    private $change_quality_command;


    public function __construct(Product $product, ChangeQualityCommand $changeQualityCommand)
    {
        parent::__construct($product);

        $this->change_quality_command = $changeQualityCommand;
    }


    public function execute(): void
    {
        $this->changeToNewQuality();
    // https://habr.com/ru/post/114899/
        $this->isQualityHigherThanMax() ? $this->setQualityToMax() : 0;
        $this->isQualityLowerThanMin()  ? $this->setQualityToMin() : 0;
    }


    protected function changeToNewQuality(): void
    {
        $this->change_quality_command->setQuality(
            $this->product->getNewQuality()
        )->execute();
    }


    protected function isQualityHigherThanMax(): bool
    {
        return $this->product->getItem()->quality > $this->product->getMaxQuality();
    }


    protected function isQualityLowerThanMin(): bool
    {
        return $this->product->getItem()->quality < $this->product->getMinQuality();
    }


    protected function setQualityToMax(): void
    {
        $this->change_quality_command->setQuality(
            $this->product->getMaxQuality()
        )->execute();
    }


    protected function setQualityToMin(): void
    {
        $this->change_quality_command->setQuality(
            $this->product->getMinQuality()
        )->execute();
    }
}
