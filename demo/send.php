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

$baseUrl = 'http://'.implode('/', array_slice(explode('/', $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']), 0, -1)).'/';

$resultUrl = $baseUrl.'result.php';
$serverUrl = $baseUrl.'server.php';

$amount = 2.56;
$currency = 'RUB';
$description = 'Demo order';

$fileOrders = $dirLiqpay.'/demo/orders';

try {
    $orders = new Orders($fileOrders);
}
catch (OrdersFileException $e) {
    echo $e->getMessage();
}

$order_id = $orders->create($amount, $currency, $description);

try {
    echo $liqpay->setTypeAsBuy()
                ->setLanguage('ua')
                ->setCurrency($currency)
                ->setResultUrl($resultUrl)
                ->setServerUrl($serverUrl)
                ->getPayForm($order_id, $amount, $description);
}
catch (LiqpayCurrencyException $e) {
    echo $e->getMessage();
}



