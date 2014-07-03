<?php
class Mondido_Mondido_Block_Onepage_Failure extends Mage_Core_Block_Template
{
    public function getContinueShoppingUrl()
    {
        return Mage::getUrl('checkout/cart');
    }

    public function getErrorMessage ()
    {
        $error = Mage::getSingleton('checkout/session')->getErrorMessage();
        // Mage::getSingleton('checkout/session')->unsErrorMessage();
        return $error;
    }
}
