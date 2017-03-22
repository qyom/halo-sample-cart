<?php
namespace Hautelook\Shipping;
use Hautelook\Cart;

/**
 * Default calculator logic scraped out from the test cases
 */
class CalculatorDefault extends CalculatorAbstract
{
    /**
     * @param Cart $cart
     * @return float
     */
    public function run(Cart $cart)
    {
        $shippingCost = 0;
        $lightItems = [];
        // Look for heavy items and apply their cost
        foreach ($cart->getItems() as $item) {
            if ($item->get(Cart\Item::KEY_WEIGHT) >= $this->getConfig('thresholdWeightEach')) {
                $shippingCost += $this->getConfig('heavyItemCost');
            } else {
                // Keep track of all light items
                $lightItems[] = $item;
            }
        }
        // Apply flat rate If subtotal is below the threshold and there are some light items
        if ($cart->subtotal() < $this->getConfig('thresholdSubtotal') &&
            count($lightItems) > 0
        ) {
            $shippingCost += $this->getConfig('flatRate');
        }
        return $shippingCost;
    }
}