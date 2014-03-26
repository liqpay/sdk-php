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
 * Payment method liqpay exception
 *
 * @author      Liqpay <support@liqpay.com>
 */
class LiqpayException extends Exception
{
}


/**
 * Payment method liqpay configuration exception
 *
 * @author      Liqpay <support@liqpay.com>
 */
class LiqpayConfigException extends LiqpayException
{
}


/**
 * Payment method liqpay currency exception
 *
 * @author      Liqpay <support@liqpay.com>
 */
class LiqpayCurrencyException extends LiqpayException
{
}


/**
 * Payment method liqpay process
 *
 * @author      Liqpay <support@liqpay.com>
 */
class Liqpay
{
    protected $_supportedCurrencies = array('EUR','UAH','USD','RUB','RUR');

    protected $_notifVars = array('amount','currency','public_key','description','order_id',
                                  'type','status','transaction_id','sender_phone');


    protected $_config;


    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        if (!is_array($config)) {
            throw new LiqpayConfigException('Configuration error');
        }

        $this->_config = $config;
    }


    /**
     * Set configuration parameter "language"
     *
     * @param string $url
     *
     * @return Liqpay
     */
    public function setLanguage($code)
    {
        $code = strtolower($code);
        $this->_config['language'] = $code == 'ru' ? 'ru' : 'en';
        return $this;
    }


    /**
     * Set configuration parameter "result_url"
     *
     * @param string $url
     *
     * @return Liqpay
     */
    public function setResultUrl($url)
    {
        $this->_config['result_url'] = $url;
        return $this;
    }


    /**
     * Set configuration parameter "server_url"
     *
     * @param string $url
     *
     * @return Liqpay
     */
    public function setServerUrl($url)
    {
        $this->_config['server_url'] = $url;
        return $this;
    }


    /**
     * Set configuration parameter "currency"
     *
     * @return Liqpay
     */
    public function setCurrency($codeCurrency)
    {
        $codeCurrency = strtoupper($codeCurrency);

        if (!in_array($codeCurrency, $this->_supportedCurrencies)) {
            throw new LiqpayCurrencyException('Currency in not supported');
        }

        if ($codeCurrency == 'RUR') {
            $codeCurrency = 'RUB';
        }

        $this->_config['currency'] = $codeCurrency;
        return $this;
    }


    /**
     * Set configuration parameter "type"
     *
     * @return Liqpay
     */
    public function setTypeAsBuy()
    {
        $this->_config['type'] = 'buy';
        return $this;
    }


    /**
     * Set configuration parameter "type"
     *
     * @return Liqpay
     */
    public function setTypeAsDonate()
    {
        $this->_config['type'] = 'donate';
        return $this;
    }


    /**
     * Get configuration parameter "private_key"
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->_config['private_key'];
    }


    /**
     * Return signature string for pay form
     *
     * @param array $signData
     *
     * @return string
     */
    protected function _getPaySignature($signData)
    {
        return $this->_getSignature($signData, $this->_config['paySignTpl']);
    }


    /**
     * Return signature string for notification
     *
     * @param array $signData
     *
     * @return string
     */
    protected function _getNotifSignature($signData)
    {
        return $this->_getSignature($signData, $this->_config['notifSignTpl']);
    }


    /**
     * Return signature string by template
     *
     * @param array  $signData
     * @param string $tpl
     *
     * @return string
     */
    protected function _getSignature($signData, $tpl)
    {
        foreach ($signData as $param => $val) {
            $tpl = str_replace('{'.$param.'}', $val, $tpl);
        }

        return base64_encode(sha1($tpl, 1));
    }


    /**
     * Return pay form
     *
     * @param string $order_id
     * @param float  $amount
     * @param string $description
     *
     * @return string
     */
    public function getPayForm($order_id, $amount, $description)
    {
        $action = $this->_config['action'];

        $public_key  = $this->_config['public_key'];
        $currency    = $this->_config['currency'];
        $result_url  = $this->_config['result_url'];
        $server_url  = $this->_config['server_url'];
        $type        = $this->_config['type'];
        $language    = $this->_config['language'];
        $private_key = $this->_config['private_key'];

        $signData = compact('private_key','amount','currency','public_key','order_id',
                            'type','description','result_url','server_url');

        $signature = $this->_getPaySignature($signData);

        $sendData = compact('public_key','amount','currency','description','order_id',
                            'result_url','server_url','type','signature','language');

        $payButton = $this->_config['payButton'];
        $language  = $this->_config['language'];
        $autoSend  = $this->_config['autoSend'];

        ob_start();
        require(dirname(__FILE__).'/view/form.phtml');
        $buff = ob_get_contents();
        ob_end_clean();
        return $buff;
    }


    /**
     * Check notification data
     *
     * @param array $posts
     *
     * @return boolean
     */
    public function checkNotifData($posts)
    {
        $success = true;

        foreach ($this->_notifVars as $var) {
            $success = $posts[$var] && $success;
        }

        return $success;
    }


    /**
     * Check signature
     *
     * @param array $posts
     *
     * @return boolean
     */
    public function checkSignature($posts)
    {
        if (!$this->checkNotifData($posts)) {
            return false;
        }

        $amount = $posts['amount'];
        $currency = $posts['currency'];
        $public_key = $posts['public_key'];
        $description = $posts['description'];
        $order_id = $posts['order_id'];
        $type = $posts['type'];
        $status = $posts['status'];
        $transaction_id = $posts['transaction_id'];
        $sender_phone = $posts['sender_phone'];

        $private_key = $this->getPrivateKey();

        $signData = compact('private_key','amount','currency','public_key','order_id','type',
                            'description','status','transaction_id','sender_phone');

        $signature = $this->_getNotifSignature($signData);

        return $signature == $posts['signature'];
    }

}