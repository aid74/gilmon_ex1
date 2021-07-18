<?php

namespace App;

use App\Commands\ChangeQualityCommand;
use App\Commands\QualityUpdateCommand;
use App\Commands\SellInUpdateCommand;

abstract class Product
{
    /*********************************************/
    /**
     * Наименование товара
     * @var string
     */
    public const NAME = '';

    /**
     * Товар никогда не может иметь качество выше чем 50
     * @var int
     */
    protected static $max_quality = 50;

    /**
     * Качество товара никогда не может быть отрицательным
     * @var int
     */
    protected static $min_quality = 0;

    /**
     * В конце дня наша система снижает значение обоих (в данном случае $quality) свойств для каждого товара
     * @var int
     */
    protected static $quality_step = -1;

    /**
     * После того, как срок храния прошел, качество товара ухудшается в два раза быстрее (мультипликатор)
     * @var array
     */
    protected static $day_range_multiplier = [
        '0' => 2,
    ];
    /*********************************************/


    /**
     * Своего рода наследование от Item
     * @var \App\Item
     */
    protected $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function update(): void
    {
        (new SellInUpdateCommand($this))->execute();
        (new QualityUpdateCommand($this, new ChangeQualityCommand($this)))->execute();
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public static function getMaxQuality(): int
    {
        return static::$max_quality;
    }

    public static function getMinQuality(): int
    {
        return static::$min_quality;
    }

    public function getQualityStep(): int
    {
        return static::$quality_step * $this->getMultiplier();
    }

    public function getNewQuality(): int
    {
        return $this->item->quality + $this->getQualityStep();
    }

    public function isAfterSale(): bool
    {
        return $this->item->sell_in < 0;
    }

    protected function getMultiplier(): int
    {
        $multiplier = 1;

        foreach (static::$day_range_multiplier as $day => $dayMultiplier) {
            if ($this->getItem()->sell_in < $day) {
                $multiplier = $dayMultiplier;
            }
        }

        return $multiplier;
    }
}
