<?php
class SongNguyen_Mondido_StandardController extends Mage_Core_Controller_Front_Action
{
    /**
     * Order instance
     */
    protected $_order;

    /**
     *  Get order
     *
     *  @return  Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if ($this->_order == null) {
        }
        return $this->_order;
    }

    /**
     * Send expire header to ajax response
     *
     */
    protected function _expireAjax()
    {
        if (!Mage::getSingleton('checkout/session')->getQuote()->hasItems()) {
            $this->getResponse()->setHeader('HTTP/1.1','403 Session Expired');
            exit;
        }
    }

    /**
     * Get singleton with paypal strandard order transaction information
     *
     * @return Mage_Paypal_Model_Standard
     */
    public function getStandard()
    {
        return Mage::getSingleton('mondido/standard');
    }

    /**
     * When a customer chooses Mondido on Checkout/Payment page
     *
     */
    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setMondidoQuoteId($session->getQuoteId());
        $this->getResponse()->setBody($this->getLayout()->createBlock('mondido/standard_redirect')->toHtml());
        $session->unsQuoteId();
        $session->unsRedirectUrl();
    }

    public function cancelAction()
    {
        $standard = Mage::getModel('mondido/standard');
        $order_id = $_GET['payment_ref'];
        $status = $_GET['status'];
        $hash_return = $_GET['hash'];
        $data = $standard->generate_mondido_data($order_id, true, $status);
        $error_name  = $_GET['error_name'];

        $error = '';
        if($error_name == 'errors.card.expired') {
            $error = 'Card Expired';
        } elseif ($error_name == 'errors.card_cvv.invalid') {
            $error = 'Card CVV Invalid';
        } elseif ($error_name == 'errors.payment.declined') {
            $error = 'Payment Declined';
        }
        if($hash_return == $data['hash']) {
            $session = Mage::getSingleton('checkout/session');
            $session->setQuoteId($session->getMondidoQuoteId(true));
            if ($session->getLastRealOrderId()) {
                $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
                if ($order->getId()) {
                    $order->cancel()->save();
                }
                Mage::helper('mondido/data')->restoreQuote();
            }
//        $this->_redirect('checkout/cart');
            Mage::getSingleton('core/session')->addError($error);
            $this->_redirect('checkout/onepage/failure', array('_secure'=>true));
        }
    }

    /**
     * The order information at this point is in POST
     * variables.  However, you don't want to "process" the order until you
     * get validation from the IPN.
     */
    public function successAction()
    {
        $standard = Mage::getModel('mondido/standard');
        $order_id = $_GET['payment_ref'];
        $status = $_GET['status'];
        $hash_return = $_GET['hash'];

        if($status == 'approved') {
            $data = $standard->generate_mondido_data($order_id, true, $status);
            if($hash_return == $data['hash']) {
                $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
                $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_COMPLETE, 'Transaction ID: ' . $_GET['transaction_id']);
//                $order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);
                $order->save();

                $session = Mage::getSingleton('checkout/session');
                $session->setQuoteId($session->getMondidoQuoteId(true));
                Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
                $this->_redirect('checkout/onepage/success', array('_secure'=>true));
            }
        }
    }
}
