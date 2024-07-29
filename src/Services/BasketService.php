<?php

namespace Services;

use Entities\Basket;
use Entities\BasketItem;
use JsonException;
use Kernel;

class BasketService
{
    private Basket $basket;
    public function __construct()
    {
        $this->basket = new Basket();
    }


    /**
     * @throws JsonException
     */
    final public function create(): string
    {
        return $this->basket->create();
    }

    final public function addDefaultItem(string $id):void
    {
        $request = Kernel::handleRequestBody();
        if (!empty($request['code']) && $request['quantity'] > 0) {
            $basketItem = (new BasketItem())->getDefaultItem($request['code']);
            if ($basketItem) {
                $basketItem->quantity = $request['quantity'];
                $basket = $basketItem->add($id);
                $this->basket->update($id, $basket);
            }
        }
    }

    final public function addCustomItem(string $id): void
    {
        $basketItem = Kernel::hydrateRequestBodyData(BasketItem::class);
        $basket = $basketItem->add($id);
        $this->basket->update($id, $basket);
    }

    final public function removeItem(string $basketId, string $itemId):void
    {
        $basketItem = new BasketItem();
        $basketItem->id = $itemId;
        $basket = $basketItem->remove($basketId);
        $this->basket->update($basketId, $basket);
    }


    /**
     * @throws JsonException
     */
    final public function find(string $id):array
    {
        return $this->basket->find($id);
    }


    final public function delete(string $id):void
    {
        $this->basket->delete($id);
    }
}
