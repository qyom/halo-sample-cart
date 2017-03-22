<?php
namespace Hautelook\Cart\Coupon;

use Hautelook\Cart;

class CouponPercent extends CouponAbstract
{
    public function getDiscountApplied(Cart $cart)
    {
        return round($cart->subtotal() * $this->discount / 100, 2);
    }
}