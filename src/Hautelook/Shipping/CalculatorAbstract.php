<?php
namespace Hautelook\Shipping;
use Hautelook\Cart;
use Hautelook\Exception;

/**
 * Based for all shipping calculators
 */
abstract class CalculatorAbstract
{
    protected $config;

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * Gets a specific key value from the config
     * @param $key string
     * @return mixed
     * @throws Exception
     */
    protected function getConfig($key)
    {
        if (!isset($this->config[$key])) {
            throw new Exception('Invalid shipping configuration key provided: ' . $key);
        }
        return $this->config[$key];
    }

    /**
     * This is where it all happens!
     * @param Cart $cart
     * @return mixed
     */
    abstract public function run(Cart $cart);
}