<?php

class Mondido_Mondido_Block_Standard_Form extends Mage_Payment_Block_Form
{
    /**
     * Payment method code
     * @var string
     */
    protected $_methodCode = 'mondido';

    /**
     * Config model instance
     *
     * @var Mage_Paypal_Model_Config
     */
    protected $_config;

    /**
     * Set template and redirect message
     */
    protected function _construct()
    {
        $locale = Mage::app()->getLocale();
        $mark = Mage::getConfig()->getBlockClassName('core/template');
        $mark = new $mark;
        $mark->setTemplate('mondido/payment/mark.phtml');
        $this->setTemplate('mondido/payment/redirect.phtml')
            ->setRedirectMessage(
                Mage::helper('mondido')->__('You will be redirected to the Mondido website when you place an order.')
            )
            ->setMethodTitle('') // Output PayPal mark, omit title
            ->setMethodLabelAfterHtml($mark->toHtml())
        ;
        return parent::_construct();
    }
}
