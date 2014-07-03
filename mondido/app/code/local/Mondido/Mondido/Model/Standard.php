<?php

/**
 * Our test CC module adapter
 */
class Mondido_Mondido_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
    protected $_code  = 'mondido';
    protected $_isGateway = true;
    protected $_formBlockType = 'mondido/standard_form';
    protected $_infoBlockType = 'mondido/payment_info';
    protected $_isInitializeNeeded       = true;
    protected $_canUseInternal          = false;
    protected $_canUseForMultishipping  = false;
    protected $paymentAction  = 'Sale';

    /**
     * Config instance
     * @var Mage_Mondido_Model_Config
     */
    protected $_config = null;


    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('mondido/standard/redirect', array('_secure' => true));
    }

    public function initialize($paymentAction, $stateObject)
    {
        $state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
        $stateObject->setState($state);
        $stateObject->setStatus('pending_payment');
        $stateObject->setIsNotified(false);
    }


    /**
     * Get mondido session namespace
     *
     * @return Mage_Mondido_Model_Session
     */
    public function getSession()
    {
        return Mage::getSingleton('mondido/session');
    }

    /**
     * Get checkout session namespace
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current quote
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    /**
     * Create main block for standard form
     *
     */
    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('mondido/standard_form', $name)
            ->setMethod('mondido')
            ->setPayment($this->getPayment())
            ->setTemplate('mondido/standard/form.phtml');

        return $block;
    }

	public function getCheckoutFields(){
        $orderIncrementId = $this->getCheckout()->getLastRealOrderId();
        $data = $this->generate_mondido_data($orderIncrementId);
        return $data;
	}

    /*
	 * Generate mondido data
	 */
    function generate_mondido_data($order_id, $callback = false, $status = "") {
        //Get Order Information
        $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);

        $customer_id = $order->getCustomerId();
//		$orderSymbolCode = Mage::app()->getLocale()->currency($order->getOrderCurrencyCode())->getSymbol();

//		$amount = $order->getGrandTotal() - $order->getShippingAmount();
        $amount = $order->getGrandTotal();
        $amount = number_format($amount, 2, '.', '');

        $serect = trim(Mage::getStoreConfig('payment/mondido/merchant_serect',Mage::app()->getStore()));
        $merchant_id = trim(Mage::getStoreConfig('payment/mondido/merchant_id',Mage::app()->getStore()));
        $test = Mage::getStoreConfig('payment/mondido/test_mode',Mage::app()->getStore());
        $test_mode = false;
        if($test == 1) $test_mode = true;
        $currency = $order->getOrderCurrencyCode();

        //Getnerate hash
        if ($callback) {
            $str = "" . $merchant_id . "" . $order_id . "" . $customer_id . "" . $amount . "" . strtolower($currency) . "" . strtolower($status) . "" . $serect . "";
        } else {
            $str = "" . $merchant_id . "" . $order_id . "" . $customer_id . "" . $amount . "" . $serect . "";
        }
        $hash = md5($str);

        $metadata = array();


        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $cust = Mage::getModel('customer/customer')->load($customer->getId());
        $customerData = $cust->getData();
        $customerAddresses = $cust->getAddresses();
        $customerAddress = "";
        if(count($customerAddresses) > 0){
            $customerAddress = array_shift(array_values($customerAddresses))->getData();
        }
        $customer = array("entity_id" => $customerData["entity_id"], "website_id" => $customerData["website_id"], "email" => $customerData["email"], "firstname" => $customerData["firstname"], "lastname" => $customerData["lastname"], "address" => $customerAddress);
        $metadata['customer'] = $customer;
        $metadata['order'] = $order->getData();
        $prods = array();
        $orderItems = $order->getItemsCollection();
        foreach($orderItems as $sItem) {
            $nProduct = Mage::getModel('catalog/product')->load($sItem->getProductId());
            array_push($prods,$nProduct->getData());
        }
        $metadata['products'] = $prods;

        //Return Data
        $data = array(
            'merchant_id' => $merchant_id,
            'payment_ref' => $order_id,
            'customer_ref' =>$customer_id,
            'amount' => $amount,
            'currency' => $currency,
            'secret' => $serect,
            'hash' => $hash,
            'test' => $test_mode,
            'metadata' => $metadata
        );
        return $data;
    }
}
