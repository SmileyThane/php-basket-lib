<?php

namespace Entities;

use Helpers\PriceHelper;
use JsonException;
use Kernel;

class Basket
{
    use PriceHelper;

    const string STORAGE_PATH = 'storage';

    /**
     * @throws JsonException
     */
    final public function create(): string
    {
        $id = Kernel::generateIdentifier();
        $path = self::getBasketPath($id);
        file_put_contents($path, json_encode(null, JSON_THROW_ON_ERROR));

        return $id;
    }

    /**
     * @throws JsonException
     */
    final public function update(string $id, array $data): void
    {
        $path = self::getBasketPath($id);
        file_put_contents($path, json_encode($data, JSON_THROW_ON_ERROR));
    }

    /**
     * @throws JsonException
     */
    final public function find(string $id, int $normalizePrice = 1): array
    {
        if ($id) {
            $path = self::getBasketPath($id);
            $basket = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR) ?? [];

            return $normalizePrice ? $this->handleBasket($basket) : $basket;
        }

        return [];
    }

    private function handleBasket(array $basket): array
    {
        foreach ($basket as $key => $item) {
            if (!empty($item['price'])) {
                $basket[$key]['price'] = $this->normalizePrice($item['price']);
            }
        }

        return $basket;
    }

    final public function delete(string $id): void
    {
        unlink(self::getBasketPath($id));
    }

    private static function getBasketPath(string $id): string
    {
        return self::STORAGE_PATH . '/' . $id;
    }

    final public function calculateTotalPrice(string $id): int
    {
        $total = 0;
        $prettifiedItems = [];
        foreach ($this->find($id, 0) as $item) {
            if (!empty($item['code']) && !empty($item['price']) && !empty($item['quantity'])) {
                if (!empty($prettifiedItems[$item['code']])) {
                    $prettifiedItems[$item['code']]['quantity'] += $item['quantity'];
                } else {
                    $prettifiedItems[$item['code']] = $item;
                }
            }
        }

        foreach ($prettifiedItems as $prettifiedItem) {
            $total += ($prettifiedItem['price'] * $prettifiedItem['quantity']) - $this->calculateDiscount($prettifiedItem);
        }

        return (int)$total;
    }

    private function calculateDiscount(array $prettifiedItem): float|int
    {
        $discount = 0;
        if (
            $prettifiedItem['quantity'] > 1 &&
            in_array($prettifiedItem['code'], BasketItem::CODES_WITH_HALF_PRICE_DISCOUNT, true)
        ) {
            $discount = ($prettifiedItem['price'] / 2) * (int)($prettifiedItem['quantity'] / 2);
        }

        return $discount;
    }
}
