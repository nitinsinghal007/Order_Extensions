<?php
namespace Dotsquares\Imexport\Controller\Adminhtml\Import;

class Orders extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ){
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dotsquares_Imexport::import');
        $resultPage->getConfig()->getTitle()->prepend(__('Import Invoice'));
        $resultPage->addBreadcrumb(__('Import'), __('Pdf'));
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('Dotsquares\Imexport\Block\Adminhtml\Orders\Form')
        );
        return $resultPage;
    }
}