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
 * @category        LiqPay
 * @package         liqpay/liqpay
 * @version         1.0.3
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
 * Payment method liqpay process
 *
 * @author      Liqpay <support@liqpay.com>
 */
class LiqPay
{
    protected $_supportedCurrencies = array('EUR','UAH','USD','RUB','RUR');

    protected $_supportedParams = array('public_key','amount','currency','description',
                                        'order_id','result_url','server_url','type',
                                        'signature','language','sandbox');
    private $_public_key;
    private $_private_key;


    /**
     * Constructor.
     *
     * @param string $public_key
     * @param string $private_key
     */
    public function __construct($public_key, $private_key)
    {
        if (empty($public_key)) {
            throw new Exception('public_key is empty');
        }

        if (empty($private_key)) {
            throw new Exception('private_key is empty');
        }

        $this->_public_key = $public_key;
        $this->_private_key = $private_key;
    }


    /**
     * Call API
     *
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function api($url, $params = array())
    {
        $url = 'https://www.liqpay.com/api/'.$url;

        $public_key = $this->_public_key;
        $private_key = $this->_private_key;
        $data = json_encode(array_merge(compact('public_key'), $params));
        $signature = base64_encode(sha1($private_key.$data.$private_key, 1));
        $postfields = "data={$data}&signature={$signature}";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        $server_output = curl_exec($ch);

        curl_close($ch);

        return json_decode($server_output);
    }


    /**
     * cnb_form
     *
     * @param array $params
     *
     * @return string
     */
    public function cnb_form($params)
    {
        $public_key = $params['public_key'] = $this->_public_key;
        $private_key = $this->_private_key;

        if (!isset($params['amount'])) {
            throw new Exception('Amount is null');
        }
        if (!isset($params['currency'])) {
           throw new Exception('Currency is null');
        }
        if (!in_array($params['currency'], $this->_supportedCurrencies)) {
            throw new Exception('Currency is not supported');
        }
        if ($params['currency'] == 'RUR') {
            $params['currency'] = 'RUB';
        }
        if (!isset($params['description'])) {
            throw new Exception('Description is null');
        }

        $params['signature'] = $this->cnb_signature($params);


        $language = 'ru';
        if (isset($params['language']) && $params['language'] == 'en') {
            $language = 'en';
        }

        $inputs = array();
        foreach ($params as $key => $value) {
            if (!in_array($key, $this->_supportedParams)) {
                continue;
            }
            $inputs[] = sprintf('<input type="hidden" name="%s" value="%s" />', $key, $value);
        }

        return sprintf('
                <form method="post" action="https://www.liqpay.com/api/pay" accept-charset="utf-8">
                    %s
                    <input type="image" src="//static.liqpay.com/buttons/p1%s.radius.png" name="btn_text" />
                </form>
            ',
            join("\r\n", $inputs),
            $language
        );
    }







    /**
     * cnb_signature
     *
     * @param array $params
     *
     * @return string
     */
    public function cnb_signature($params)
    {
        $public_key = $params['public_key'] = $this->_public_key;
        $private_key = $this->_private_key;


        if ($params['currency'] == 'RUR') {
            $params['currency'] = 'RUB';
        }

        $amount = $params['amount'];
        $currency = $params['currency'];
        $description = $params['description'];

        $order_id = '';
        if (isset($params['order_id'])) {
           $order_id = $params['order_id'];
        }

        $type = '';
        if (isset($params['type'])) {
           $type = $params['type'];
        }

        $result_url = '';
        if (isset($params['result_url'])) {
           $result_url = $params['result_url'];
        }

        $server_url = '';
        if (isset($params['server_url'])) {
           $server_url = $params['server_url'];
        }

        $signature = $this->str_to_sign(
            $private_key.
            $amount.
            $currency.
            $public_key.
            $order_id.
            $type.
            $description.
            $result_url.
            $server_url
        );

        return $signature;
    }




    /**
     * str_to_sign
     *
     * @param array $params
     *
     * @return string
     */
    public function str_to_sign($str)
    {

        $signature = base64_encode(sha1($str,1));

        return $signature;
    }

}