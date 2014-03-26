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

require_once($dirLiqpay.'/demo/Orders.php');

$fileOrders = $dirLiqpay.'/demo/orders';

try {
    $orders = new Orders($fileOrders);
}
catch (OrdersFileException $e) {
    echo $e->getMessage();
}

?>
<html>
<body>
    <table border="1">
    <caption>Orders</caption>
    <tr>
        <th>order_id</th>
        <th>amount</th>
        <th>currency</th>
        <th>description</th>
        <th>status</th>
    </tr>
    <?php foreach ($orders->getAllOrders() as $order):?>
    <tr>
        <td><?=$order['order_id']?></td>
        <td><?=$order['amount']?></td>
        <td><?=$order['currency']?></td>
        <td><?=$order['description']?></td>
        <td><?=$order['status']?></td>
    </tr>
    <?php endforeach?>
    </table>
<body>
</html>