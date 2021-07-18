### Requirements

**php**: 7.4
Думаю, однако, что и на 7.1 отработает


### Usage

Пример использование описан в файле texttest\_fixture.php

```php
<?php

$items = [
    'RegularProduct1'=> new Item('+5 Dexterity Vest', 10, 20),
    'AgedBrie1'      => new Item('Aged Brie', 2, 0),
    'RegularProduct2'=> new Item('Elixir of the Mongoose', 5, 7),
    'sulfuras1'      => new Item('Sulfuras, Hand of Ragnaros', 0, 80),
    'sulfuras2'      => new Item('Sulfuras, Hand of Ragnaros', -1, 80),
    'BackstagePass1' => new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
    'BackstagePass2' => new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
    'BackstagePass3' => new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
    'conjured1'      => new Item('Conjured Mana Cake', 3, 6)
];


$productFactoryRegistry = new ProductFactoryRegistry();

$productFactoryRegistry->register(RegularProduct::class);
$productFactoryRegistry->register(Sulfuras::class);
$productFactoryRegistry->register(BackstagePass::class);
$productFactoryRegistry->register(AgedBrie::class);
$productFactoryRegistry->register(Conjured::class);

$productFactory = new ProductFactory($productFactoryRegistry);

$app = new App($items, $productFactory);
```

### Tests

Можно просто запустить `phpunit`
Ну или как-то так
`php composer.phar run-script test`
`php composer.phar run-script tests`
`php composer.phar run-script test-coverage`

### Description

Это тестовое задание для  [Gilmon](https://gilmon.ru).
Это было весело, но заняло немного больше времени, чем я планировал.
В работе были применены следующие паттерны:

- **Абстрактная фабрика** создание `Products` (своего рода implements `Items`, т.к. по условию `Items` финализированный класс)
- **Декоратор** для `Products` с помощью него  значения `Item` можно изменять.
- **Команда** для изменения `Item` значений согласно заданным правилам.

### Product

Все значения `Product` м.б. установлены динамически.
- Свойства
  - const `name`. Имя по которому `ProductFactory` будет идентифицировать `Product`. **Default:** `empty`
  - `max_quality`. Максимальное значение качества (quality). **Default:** `50`
  - `min_quality`. Минимальное значение (quality). **Default:** `0`
  - `quality_step`. Определяет направление и скорость изменения параметра качества (quality). **Default:** `-1`
  - `day_range_multiplier`. Определяет зависимость качества товара от дней до реализации товара.
   **Default:** `[0 => 2]` это означает, что после даты продажи (0) скорость падения качества возрастает двукратно (2).
- Публичные методы  
  - `update`. Обновление `Item` для нового отрезка времени (дня по-умолчанию).
  - `getItem`. Возвращает связанную с этим продуктом `Item`.
  - `getMaxQuality`. getter для `max_quality` 
  - `getMinQuality` . getter для `min_quality`
  - `getQualityStep`. Возвращает шаг качества (quality) с учетом множителей.
  - `getNewQuality`. Возвращает качество (quality), которое будет у `Product`-а в новый день.
  - `isAfterSale`. Возвращает информацию о том продан продукт до или после дня истечения срока годности

### New Product
Для создания нового продукта необходимо создать новый класс в директории `Products` по аналогии с `RegularProduct.php`
И, конечно, не забыть зарегистрировать в контейнере `ProductFactoryRegistry`.

```php
$productFactoryRegistry->register(MyNewProduct::class);
```

