<?php namespace Dotsquares\Imexport\Controller\Adminhtml\Import;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;
class Importallorders extends \Magento\Backend\App\Action
{
	protected $_fileUploaderFactory;
	public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        Action\Context $context,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\Message\ManagerInterface $messageManager
	){
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_filesystem = $fileSystem;
        $this->_messageManager = $messageManager;
        parent::__construct($context);
	}
    public function execute()
    {
        if ($this->getRequest()->getPostValue()){
            $files = $this->getRequest()->getFiles();
			if($files['invoiceuploadpdf'] != ''){
                try {
				    $invoiceuploadpdf = $this->getRequest()->getFiles('invoiceuploadpdf');
			        $i = 0;
                    foreach($invoiceuploadpdf as $invoicePdf){
				    	if (isset($invoicePdf['tmp_name']) && strlen($invoicePdf['tmp_name']) > 0) {
                            $uploader = $this->_fileUploaderFactory->create(['fileId' => $invoiceuploadpdf[$i]]);
                            $upload= $uploader->setAllowedExtensions(['txt', 'csv', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx']);
                            $uploader->setAllowRenameFiles(false);
                            $uploader->setFilesDispersion(false);
                            $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('indglobal/');
                            $uploader->save($path);
				    	    $i++;
				    	}
                    }
					$this->_messageManager->addSuccess(__("Successfully Imported"));
                    $this->_redirect('*/*/orders');
				} catch (Exception $e) {
                    $this->messageManager->addError('Invalid file type!!');
                    $this->_redirect('*/*/orders');
                }
            }
        }else{
			$this->messageManager->addError('Invalid file type!!');
            $this->_redirect('*/*/orders');
		}
    }
}