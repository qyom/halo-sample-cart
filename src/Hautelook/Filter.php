<?php
namespace Hautelook;

/**
 * Filter objects are responsible for one key/value/operator filtering logic
 * Whether it's items in the cart or anywhere else, this is where the logic can go
 */
class Filter {
    /**
     * @var string: key to filter on
     */
    protected $key;
    /**
     * @var mix: value to filter by
     */
    protected $value;

    const OPERATOR_EQUAL = '=';

    public function __construct($key, $value, $operator = self::OPERATOR_EQUAL)
    {
        $this->key = $key;
        $this->value = $value;
        $this->operator = $operator;
    }

    /**
     * See if the given cart item passes through this filter
     * @param Cart\Item $item
     * @return bool
     */
    public function matchItem(Cart\Item $item)
    {
        return $this->compare($item->get($this->key), $this->value);
    }

    /**
     * Helps comparing two values when filtering
     * @param $value1
     * @param $value2
     * @return bool
     * @throws Exception
     */
    protected function compare($value1, $value2)
    {
        switch ($this->operator)
        {
            case self::OPERATOR_EQUAL: return $value1 == $value2; break;
            default:
                throw new Exception('Unknown comparison operator: ' . $this->operator);
        }
    }
}