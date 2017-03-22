<?php

namespace Hautelook\Cart\Coupon;

use Hautelook\Cart;

abstract class CouponAbstract
{
    protected $discount;

    public function __construct($discount)
    {
        $this->discount = $discount;
    }

    abstract public function getDiscountApplied(Cart $cart);
}