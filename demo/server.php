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

$dirLiqpay = realpath(dirname(__FILE__).'/..');

require_once($dirLiqpay.'/Liqpay.php');
require_once($dirLiqpay.'/demo/Orders.php');

$configArr = require($dirLiqpay.'/demo/config.php');

$liqpay = new Liqpay($configArr);

$fileOrders = $dirLiqpay.'/demo/orders';

try {
    $orders = new Orders($fileOrders);
}
catch (OrdersFileException $e) {
    echo $e->getMessage();
}

if (!isset($_POST['status']) || !isset($_POST['order_id'])) { die(); }

$order_id = $_POST['order_id'];

if ($_POST['status'] == 'success') {
    try {
        $orders->setOrderStatus($order_id, Orders::STATUS_LIQPAY_SUCCESS);
        if ($liqpay->checkSignature($_POST)) {
            try {
                $orders->setOrderStatus($order_id, Orders::STATUS_PAID);
            }
            catch (OrdersDataException $e) {
                echo $e->getMessage();
            }
        }
    }
    catch (OrdersDataException $e) {
        echo $e->getMessage();
    }
}
elseif ($_POST['status'] == 'wait_secure') {
    try {
        $orders->setOrderStatus($order_id, Orders::STATUS_LIQPAY_WAIT_SECURE);
    }
    catch (OrdersDataException $e) {
        echo $e->getMessage();
    }
}
elseif ($_POST['status'] == 'failure') {
    try {
        $orders->setOrderStatus($order_id, Orders::STATUS_LIQPAY_FAILURE);
    }
    catch (OrdersDataException $e) {
        echo $e->getMessage();
    }
}