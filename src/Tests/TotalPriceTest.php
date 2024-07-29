<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Services\DeliveryService;

class TotalPriceTest extends TestCase
{
    final public function testCorrectTotalPriceIsReturned():void
    {
        foreach (self::getExampleBasketIds() as $basketId => $totalPrice) {
            $this->assertSame($totalPrice, (new DeliveryService())->calculate($basketId));
        }
    }

    private static function getExampleBasketIds():array
    {
        return [
            '2bedd09a06391c1feac325de905a467f36d652e6a9cd5f97fc36fb28850d897f' => 98.27,
            '6dff618d31e59eb6229085aab5d0280f3bfb83b755ce7d6bae29500f5914fd76' => 37.85,
            '5352e8ba9b18a56f07eb57108d027a3e22c999e2548434615cad6d553b7cd682' => 54.37,
            'dcc5de2d8ae371ca1904d916f339cbb18ae6ec5f65fa4a744a4039719153d264' => 60.85
        ];
    }
}
