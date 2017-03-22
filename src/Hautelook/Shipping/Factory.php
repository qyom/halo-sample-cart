<?php
namespace Hautelook\Shipping;
use Hautelook\Exception;
use Hautelook\Shipping\CalculatorAbstract;

/**
 * Factory class to dynamically generate shipping related models
 */
class Factory
{
    const CALCULATOR_TYPE_DEFAULT = 'default';
    /**
     * Generates the calculator object
     * @param $config array
     * @return CalculatorAbstract
     * @throws Exception
     */
    public static function getCalculator($config)
    {
        // Figure out the type
        if (!isset($config['shipping']['type']) || !($type = $config['shipping']['type'])) {
            $type = self::CALCULATOR_TYPE_DEFAULT;
        }
        // Figure out the parameters
        if (!empty($config['shipping']['parameters'])) {
            $parameters = $config['shipping']['parameters'];
        } else {
            $parameters = [];
        }
        // Generate the Calculator object
        switch ($type)
        {
            case self::CALCULATOR_TYPE_DEFAULT:
                return new CalculatorDefault($parameters);
            default:
                throw new Exception('Unknown Shipping Calculator type is provided: ' . $type);
        }
    }
}