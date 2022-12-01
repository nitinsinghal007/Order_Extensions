<?php
namespace Dotsquares\Imexport\Controller\Adminhtml\Export;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Rporders extends \Magento\Backend\App\Action
{
    protected $_resultLayoutFactory;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Rp Orders Export'));
        $resultPage->addBreadcrumb(__('Rp Orders'), __('Export'));
        return $resultPage;
    }
}