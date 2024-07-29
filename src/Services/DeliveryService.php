<?php

namespace Services;

use Entities\Basket;
use Helpers\PriceHelper;

class DeliveryService
{
    use PriceHelper;

    private Basket $basket;

    const array PRICE_STEPS = [
        5000 => 495,
        9000 => 295
    ];

    public function __construct()
    {
        $this->basket = new Basket();
    }

    final public function calculate(string $basketId, int $withBasketPrice = 1): float|int
    {
        $basketPrice = $this->basket->calculateTotalPrice($basketId);
        $deliveryPrice = 0;
        foreach (self::PRICE_STEPS as $step => $price) {
            if ($basketPrice < $step) {
                $deliveryPrice = $price;
                break;
            }
        }

        return $this->normalizePrice(($withBasketPrice ? $basketPrice : 0) + $deliveryPrice);
    }
}
