<?php
/**
 * Liqpay Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category        Liqpay
 * @package         Liqpay
 * @version         0.0.1
 * @author          Liqpay
 * @copyright       Copyright (c) 2014 Liqpay
 * @license         http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *
 * EXTENSION INFORMATION
 *
 * LIQPAY API       https://www.liqpay.com/ru/doc
 *
 */



/**
 * Orders exception
 *
 * @author      Liqpay <support@liqpay.com>
 */
class OrdersException extends Exception
{
}


/**
 * Orders file exception
 *
 * @author      Liqpay <support@liqpay.com>
 */
class OrdersFileException extends OrdersException
{
}


/**
 * Orders Data exception
 *
 * @author      Liqpay <support@liqpay.com>
 */
class OrdersDataException extends OrdersException
{
}


/**
 * Orders manipulate
 *
 * @author      Liqpay <support@liqpay.com>
 */
class Orders
{
    const FILE_DELIMITER_ROW = "\n";
    const FILE_DELIMITER_COL = "\t";

    const STATUS_PAID = 'paid';
    const STATUS_UNPAID = 'unpaid';

    const STATUS_LIQPAY_WAIT_SECURE = 'wait_secure';
    const STATUS_LIQPAY_FAILURE = 'failure';
    const STATUS_LIQPAY_SUCCESS = 'success';


    private $_orders;
    private $_fileOrders;

    /**
     * Constructor.
     *
     * @param string $fileOrders
     */
    public function __construct($fileOrders)
    {
        if (!file_exists($fileOrders)) {
            throw new OrdersFileException('File with orders did not find');
        }

        $this->_fileOrders = $fileOrders;
        $this->_load();
    }


    /**
     * Load orders from file
     *
     * @return void
     */
    protected function _load()
    {
        $this->_orders = array();
        $data = file_get_contents($this->_fileOrders);
        if (empty($data)) {
            return;
        }
        foreach (explode(Orders::FILE_DELIMITER_ROW, $data) as $row) {
            $row = explode(Orders::FILE_DELIMITER_COL, $row);
            $order_id = $row[0];
            $amount = floatval($row[1]);
            $currency = $row[2];
            $description = $row[3];
            $status = $row[4];
            $this->_orders[$order_id] = compact('order_id','amount','currency','description','status');
        }
    }


    /**
     * Save orders to file
     *
     * @return void
     */
    protected function _save()
    {
        $data = array();
        foreach ($this->_orders as $order) {
            $row = array(
                    $order['order_id'],
                    $order['amount'],
                    $order['currency'],
                    $order['description'],
                    $order['status'],
            );
            $data[] = join(Orders::FILE_DELIMITER_COL, $row);
        }
        $data = join(Orders::FILE_DELIMITER_ROW, $data);
        file_put_contents($this->_fileOrders, $data);
    }


    /**
     * Create order
     *
     * @return string
     */
    public function getOrder($order_id)
    {
        if (!isset($this->_orders[$order_id])){
            throw new OrdersDataException('Order did not find');
        }
        return $this->_orders[$order_id];
    }


    /**
     * Create all orders
     *
     * @return string
     */
    public function getAllOrders()
    {
        return $this->_orders;
    }


    /**
     * Change order status
     *
     * @return string
     */
    public function setOrderStatus($order_id, $status)
    {
        if (!isset($this->_orders[$order_id])){
            throw new OrdersDataException('Order did not find');
        }
        $this->_orders[$order_id]['status'] = $status;
        $this->_save();
    }


    /**
     * Create order
     *
     * @return string
     */
    public function create($amount, $currency, $description)
    {
        end($this->_orders);
        $last = each($this->_orders);
        $order_id = $last['key'] + 1;
        $amount = floatval($amount);
        $status = 'unpaid';
        $this->_orders[$order_id] = compact('order_id','amount','currency','description','status');
        $this->_save();
        return $order_id;
    }



}