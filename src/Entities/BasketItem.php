<?php

namespace Entities;

use Kernel;

class BasketItem
{
    public ?string $id = null;
    public string $name;
    public string $code;
    public int $price;
    public int $quantity;

    const array DEFAULT_ITEMS = [
        'R01' => [
            'name' => 'Red Widget',
            'price' => 3295,
        ],
        'G01' => [
            'name' => 'Green Widget',
            'price' => 2495,
        ],
        'B01' => [
            'name' => 'Blue Widget',
            'price' => 795,
        ]
    ];

    const CODES_WITH_HALF_PRICE_DISCOUNT = ['R01'];

    final public function add(string $basketId): array
    {
        $basket = (new Basket())->find($basketId, 0);
        if (!empty($basket[$this->id])) {
            $basket[$this->id]['quantity'] += 1;
        } else {
            $this->id = Kernel::generateIdentifier();
            $basket[$this->id] = $this;
        }

        return $basket;
    }

    final public function remove(string $basketId): array
    {
        $basket = (new Basket())->find($basketId);
        if (!empty($basket[$this->id])) {
            unset($basket[$this->id]);
        }

        return $basket;
    }

    final public function getDefaultItem(string $code): BasketItem|null
    {
        $item = null;
        if (self::DEFAULT_ITEMS[$code]) {
            $item = new BasketItem();
            $item->code = $code;
            $item->name = self::DEFAULT_ITEMS[$code]['name'];
            $item->price = self::DEFAULT_ITEMS[$code]['price'];
        }

        return $item;
    }
}
