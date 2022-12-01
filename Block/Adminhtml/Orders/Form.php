<?php
namespace Dotsquares\Imexport\Block\Adminhtml\Orders;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
	
    protected function _construct()
    {
        parent::_construct();
        $this->setId('dotsquares_items_form');
        $this->setTitle(__('Item Information'));
    }

    protected function _prepareForm()
    {
        $object_manager = \Magento\Framework\App\ObjectManager::getInstance();
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'order_form',
                    'action' => $this->getUrl('*/*/importallOrders'),
                    'method' => 'post',
					'enctype' => 'multipart/form-data',
                ],
            ]
        );
        $fieldset = $form->addFieldset('imports_form', array('legend'=>__('Import Invoice Pdf')));
        $fieldset->addField('uploadpdf', 'hidden', array(
            'label' =>__('Import Invoice Pdf : '),
            'name' => 'uploadpdf',
            'after_element_html' => '<input id="attachment-file"
            class="attachment-file"
            name="invoiceuploadpdf[]"
            type="file"
            multiple="multiple" />',
        ));
        
        $fieldset->addField('submit', 'submit', array(
        	'value'  => 'Import',
        	'after_element_html' => '<small></small>',
        	'class' => 'form-button', 			  
        	'tabindex' => 1
        ));
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}