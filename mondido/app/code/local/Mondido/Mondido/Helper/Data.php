<?php

class Mondido_Mondido_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Return checkout session instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Return sales quote instance for specified ID
     *
     * @param int $quoteId Quote identifier
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote($quoteId)
    {
        return Mage::getModel('sales/quote')->load($quoteId);
    }

    public function restoreQuote()
    {

        $order = $this->_getCheckoutSession()->getLastRealOrder();
        if ($order->getId()) {
            $quote = $this->_getQuote($order->getQuoteId());
            if ($quote->getId()) {
                $quote->setIsActive(1)
                    ->setReservedOrderId(null)
                    ->save();
                $this->_getCheckoutSession()
                    ->replaceQuote($quote)
                    ->unsLastRealOrderId();
                return true;
            }
        }
        return false;
    }
}
