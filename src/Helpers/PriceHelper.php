<?php

namespace Helpers;

trait PriceHelper
{
    final public function normalizePrice(int $price):float
    {
        return $price / 100;
    }
}
