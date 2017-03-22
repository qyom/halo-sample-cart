<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;
use Hautelook\Cart;
use Hautelook\Cart\Coupon\CouponPercent;
use Hautelook\Filter;
use \Hautelook\Shipping\Factory as ShippingFactory;


/**
 * Features context.
 */
class FeatureContext extends BehatContext
{

    private $cart;

    private $config;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @Given /^I have an empty cart$/
     */
    public function iHaveAnEmptyCart()
    {
        $this->cart = new Cart([], [], ShippingFactory::getCalculator($this->config));
    }

    /**
     * @Then /^My subtotal should be "([^"]*)" dollars$/
     */
    public function mySubtotalShouldBeDollars($subtotal)
    {
        Assert::assertEquals($subtotal, $this->cart->subtotal() - $this->cart->discounts());
    }

    /**
     * @When /^I add a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iAddADollarItemNamed($dollars, $product_name)
    {
        $this->cart->addItem(new Cart\Item([
            Cart\Item::KEY_AMOUNT => $dollars,
            Cart\Item::KEY_NAME => $product_name
        ]));
    }
    
    /**
     * @When /^I add a "([^"]*)" dollar "([^"]*)" lb item named "([^"]*)"$/
     */
    public function iAddADollarItemWithWeight($dollars, $lb, $product_name)
    {
        $this->cart->addItem(new Cart\Item([
            Cart\Item::KEY_AMOUNT => $dollars,
            Cart\Item::KEY_NAME => $product_name,
            Cart\Item::KEY_WEIGHT => $lb
        ]));
    }
    
    /**
     * @Then /^My total should be "([^"]*)" dollars$/
     */
    public function myTotalShouldBeDollars($total)
    {
        Assert::assertEquals($total, $this->cart->getTotalAmount());
    }

    /**
     * @Then /^My quantity of products named "([^"]*)" should be "([^"]*)"$/
     */
    public function myQuantityOfProductsShouldBe($product_name, $quantity)
    {
        Assert::assertEquals($quantity, $this->cart->getTotalItemCount([
            new Filter(Cart\Item::KEY_NAME, $product_name),
        ]));
    }
    

    /**
     * @Given /^I have a cart with a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iHaveACartWithADollarItem($item_price, $product_name)
    {
        $this->cart = new Cart(
            [
                new Cart\Item([
                    Cart\Item::KEY_AMOUNT => $item_price,
                    Cart\Item::KEY_NAME => $product_name,
                ])
            ],
            [],
            ShippingFactory::getCalculator($this->config)
        );
    }

    /**
     * @When /^I apply a "([^"]*)" percent coupon code$/
     */
    public function iApplyAPercentCouponCode($discount)
    {
        $this->cart->addCoupon(new CouponPercent($discount));
    }

    /**
     * @Then /^My cart should have "([^"]*)" item\(s\)$/
     */
    public function myCartShouldHaveItems($item_count)
    {
        Assert::assertEquals($item_count, $this->cart->getTotalItemCount() );
    }
}
