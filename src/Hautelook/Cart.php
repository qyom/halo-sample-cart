<?php
namespace Hautelook;

use Hautelook\Cart\Coupon\CouponAbstract;
use Hautelook\Cart\Item;

class Cart
{
    /**
     * @var array<Item>
     */
    protected $items;

    /**
     * @var array<CouponAbstract>
     */
    protected $coupons;

    public function __construct(
        $items = [],
        $coupons = [],
        Shipping\CalculatorAbstract $shippingCalculator
    ) {
        $this->items = $items;
        $this->coupons = $coupons;
        $this->shippingCalculator = $shippingCalculator;
    }

    /**
     * Puts an item in the cart. Allows chaining.
     * @param Item $newItem
     * @return $this
     * @throws Exception
     */
    public function addItem(Item $newItem)
    {
        // If the name is not provided raise an alarm
        if (!$newItem->has(Item::KEY_NAME, true)) {
            throw new Exception("Adding a nameless item to cart is not desirable.");
        }
        $this->items[] = $newItem;
        return $this;
    }

    /**
     * returns the subtotal of items (not considering discounts)
     * @return float
     */
    public function subtotal()
    {
        $subTotal = 0;
        foreach($this->items as $item) {
            $subTotal += $item->get(Cart\Item::KEY_AMOUNT);
        }
        return $subTotal;
    }

    /**
     * calculates total amount in the cart
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->subtotal() + $this->shippingCost() + $this->tax() - $this->discounts();
    }

    /**
     * calculates total number of items in the cart using some filters
     * @param array<Filter> $filters
     * @return int
     */
    public function getTotalItemCount($filters = [])
    {
        $itemCount = 0;
        foreach($this->items as $item) {
            if ($item->passFilters($filters)) {
                $itemCount++;
            };
        };
        return $itemCount;
    }

    /**
     * Adds a coupon to the cart
     * @param CouponAbstract $coupon
     * @return $this
     */
    public function addCoupon(CouponAbstract $coupon)
    {
        $this->coupons[] = $coupon;
        return $this;
    }

    /**
     * Proxy to shipping calculator to calculate the shipping cost
     * @return float
     */
    public function shippingCost()
    {
        return $this->shippingCalculator->run($this);
    }

    /**
     * No tax logic applied yet
     * @return float
     */
    public function tax()
    {
        return 0;
    }

    /**
     * Calculates all discounts: coupons and whatever else doscount logic is used
     * @return float
     */
    public function discounts()
    {
        $discount = 0;
        foreach ($this->coupons as $coupon) {
            $discount += $coupon->getDiscountApplied($this);
        }
        return $discount;
    }

    /**
     * returns the array of Items
     * @return array<Item>
     */
    public function getItems()
    {
        return $this->items;
    }
} 
