<?php
namespace Hautelook\Cart;

/**
 * Product item object in cart
 */
class Item
{
    const KEY_AMOUNT = 'amount';
    const KEY_NAME = 'name';
    const KEY_WEIGHT = 'weight';

    /**
     * @var array: (key/value)s go here
     */
    protected $data;

    public function __construct($item)
    {
        $this->data = $item;
    }

    /**
     * Check if the item has a key
     * @param string $key
     * @param bool $andIsNotEmpty: Optionally check if it's not empty
     * @return bool
     */
    public function has($key, $andIsNotEmpty = false)
    {
        return isset($this->data[$key]) && (!$andIsNotEmpty || !empty($this->data[$key]) );
    }

    /**
     * Get the whole item or a specific value by key
     * @param string $key: keep null for the whole data
     * @return array|mixed
     */
    public function get($key = null)
    {
        // If getting all data array
        if (null === $key) {
            return $this->data;
        }
        // If getting by specific key, first check for it's soft existence
        if (!$this->has($key)) {
            return null;
        }
        return $this->data[$key];
    }

    /**
     * Check if this item passes given array of filters
     * @param array $filters
     * @return bool
     */
    public function passFilters($filters = [])
    {
        foreach($filters as $filter) {
            if (!$filter->matchItem($this)) {
                return false;
            }
        }
        return true;
    }
}