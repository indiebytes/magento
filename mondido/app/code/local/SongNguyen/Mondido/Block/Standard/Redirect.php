<?php

class SongNguyen_Mondido_Block_Standard_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $standard = Mage::getModel('mondido/standard');
		$fields = $standard->getCheckoutFields();
        $metadata = json_encode($fields);

        $form = new Varien_Data_Form();
        $form->setAction('https://pay.mondido.com/v1/form')
            ->setId('mondido_checkout')
            ->setName('mondido_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
        $form->addField('payment_ref', 'hidden', array('name'=>'payment_ref', 'value' => $fields['payment_ref']));
        $form->addField('customer_ref', 'hidden', array('name'=>'customer_ref', 'value' => $fields['customer_ref']));
        $form->addField('amount', 'hidden', array('name'=>'amount', 'value'=>$fields['amount']));
        $form->addField('currency', 'hidden', array('name'=>'currency', 'value'=> $fields['currency']));
        $form->addField('hash', 'hidden', array('name'=>'hash', 'value' => $fields['hash']));
        $form->addField('merchant_id', 'hidden', array('name'=>'merchant_id', 'value' => $fields['merchant_id']));
        $form->addField('success_url', 'hidden', array('name'=>'success_url', 'value' => Mage::getUrl('mondido/standard/success')));
        $form->addField('error_url', 'hidden', array('name'=>'error_url', 'value' => Mage::getUrl('mondido/standard/cancel')));
        $form->addField('test', 'hidden', array('name'=>'test', 'value' => $fields['status']));
        $form->addField('metadata', 'hidden', array('name'=>'metadata', 'value' => $metadata));
        $idSuffix = Mage::helper('core')->uniqHash();
        $submitButton = new Varien_Data_Form_Element_Submit(array(
            'value'    => $this->__('Click here if you are not redirected within 10 seconds...'),
        ));
        $id = "submit_to_mondido_button_{$idSuffix}";
        $submitButton->setId($id);
        $form->addElement($submitButton);
        $html = '<html><body>';
        $html.= $this->__('You will be redirected to the Mondido website in a few seconds.');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("mondido_checkout").submit();</script>';
        $html.= '</body></html>';

        return $html;
    }
}
