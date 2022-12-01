<?php
namespace Dotsquares\Imexport\Model;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
class selectorder extends \Dotsquares\Imexport\Model\Functional\Export
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer) {
      $this->serializer = $serializer;
    }

    const ENCLOSURE = '"';
    const DELIMITER = ',';
    public function allorders($orders)
    {
        $i = 0;
        $len = count($orders);
        foreach ($orders as $item) {
            if ($i == 0) {
                $startid = $item;
            } else if ($i == $len - 1) {
                $lastid = $item;
            }
            $i++;
        }
        if($lastid == 0 || $lastid == null || empty($lastid)){
            $lastid = $startid;
        }
        if($startid > $lastid){
            $lastid1 = $lastid;
            $lastid = $startid;
            $startid = $lastid1;
        }
        $fileName = 'exportorder_'.date("Ymd_His").'.csv';
        $object_manager = \Magento\Framework\App\ObjectManager::getInstance();
        $mediaDir = $object_manager->get('Magento\Framework\App\Filesystem\DirectoryList')->getPath('media');
        $locatio = $mediaDir.'/dotsquares/ordercsv'; 
        $fp = fopen($locatio .'/'.$fileName, 'w');
        $this->writeHeadRow($fp);
        foreach ($orders as $order) {
            $objectManagers = \Magento\Framework\App\ObjectManager::getInstance();
            $order = $objectManagers->create('Magento\Sales\Model\Order')->load($order);
            $this->writeOrder($order, $fp);
        }
        fclose($fp);
        return $fileName;
    }
	
    public function selectOrdersdownload($orders)
    {
       $objectManagers = \Magento\Framework\App\ObjectManager::getInstance();
        $i = 0;
        $len = count($orders);
        $lastid = 0;	
        foreach ($orders as $item){
            if($i == 0){
                $startid = $item;
            }else if($i == $len - 1){
                $lastid = $item;
            }
            $i++;
        }
        if($lastid == 0 || $lastid == null || empty($lastid)){
            $lastid = $startid;
        }
        if($startid > $lastid){
            $lastid1 = $lastid;
            $lastid = $startid;
            $startid = $lastid1;
        }
        $fileName = 'exportorder_'.date("Ymd_His").'.csv';
        $object_manager = \Magento\Framework\App\ObjectManager::getInstance();
        $mediaDir = $object_manager->get('Magento\Framework\App\Filesystem\DirectoryList')->getPath('media');
        $locatio = $mediaDir.'/dotsquares/ordercsv'; 
        $fp = fopen($locatio .'/'.$fileName, 'w');
        $this->writeHeadRow($fp);
        foreach ($orders as $order) {
            $order = $objectManagers->create('Magento\Sales\Model\Order')->load($order);
            $this->writeOrder($order, $fp);
        }
        fclose($fp);
        return $fileName;
    }

    public function writeHeadRow($fp)
    {
        $objectManagers = \Magento\Framework\App\ObjectManager::getInstance();
        $head=$objectManagers->create('Dotsquares\Imexport\Model\Table\Csvhead')->getCSVHead();
        fputcsv($fp, $head , self::DELIMITER, self::ENCLOSURE);
    }

    public function writeOrder($order, $fp)
    {
        $objectManagers = \Magento\Framework\App\ObjectManager::getInstance();
        $common =$objectManagers->create('Dotsquares\Imexport\Model\Table\Csvcontent')->getCSVvalue($order);
		$blank = $this->getBlankOrderValues($order);
        $orderItems = $order->getItemsCollection();
		//echo "<pre>"; print_r($orderItems); die();
        $itemInc = 0;
        $data = array();
        $count = 0;
        foreach ($orderItems as $item)
        {
            if($count==0)
            {
                $record = array_merge($this->getOrderItemValues($item, $order, ++$itemInc),$common);
                fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
            }
            else
            {
                $record = array_merge($this->getOrderItemValues($item, $order, ++$itemInc),$blank);
				//echo "<pre>"; print_r($record); die();
                fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
            }
            $count++;
        }
    }
	
    public function getBlankOrderValues($order)
    {
	 $address = $order->getShippingAddress();
	 $countryCode = $address->getCountryId();
        return array('','','','',$order->getStatus(),'','','','','','','','','','','','','',$countryCode,'','','','','','','');
    }

    public function getOrderItemValues($item, $order, $itemInc=1)
    {
		$product_id = $item->getProductId();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);
		if($product->getId() != ''){
		    $attributeSet = $objectManager->create('Magento\Eav\Api\AttributeSetRepositoryInterface');
            $attributeSetRepository = $attributeSet->get($product->getAttributeSetId());
            $attribute_set_name = $attributeSetRepository->getAttributeSetName();
            
            if ($product->getResource()->getAttributeRawValue($product->getId(),'internal_code',$order->getStore()->getId())) {
                $internalCode = $product->getResource()->getAttributeRawValue($product->getId(),'internal_code',$order->getStore()->getId());
            } else {
                $internalCode = "";
            }
            
            if ($product->getResource()->getAttributeRawValue($product->getId(),'internal_code_country',$order->getStore()->getId())) {
                $internalCodeCountry = $product->getResource()->getAttributeRawValue($product->getId(),'internal_code_country',$order->getStore()->getId());
            } else {
                $internalCodeCountry = "";
            }
            
            if($product->getResource()->getAttributeRawValue($product->getId(),'category_code',$order->getStore()->getId())){
                $categoryName = $product->getResource()->getAttributeRawValue($product->getId(),'category_code',$order->getStore()->getId());
            } else {
                $categoryName = "";
            }
		}else{
			$attribute_set_name = '';
            $internalCode = '';
            $internalCodeCountry = '';
            $categoryName = '';
		}
	    
        /* $timeZone = $objectManager->create('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
		$orderCreate = $order->getUpdatedAt();
		$dateTimeAsTimeZone = $timeZone->date(new \DateTime($orderCreate))->format('d/m/y H:i:s'); */

        $orderCreate = $order->getCreatedAt();
        $scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $timezone = new \DateTimeZone((string)$scopeConfig->getValue(
            'general/locale/timezone',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $order->getStore()->getId()
        ));
        $dateFormatted = new \DateTime($orderCreate);
        $dateFormatted->setTimezone($timezone);
        $dateFormatted = $dateFormatted->format('d/m/y H:i:s');

		$order_dates = explode(" ",$dateFormatted);

        $regularPrice = $item->getOriginalPrice();

		return array(
		    $order_dates[0],
		    $order_dates[1],
			$order->getIncrementId(),
			$order->getParentOrder(),
            $attribute_set_name,
            $this->formatText($item->getName()),
            $item->getProductType(),
            $this->getItemSku($item),
			'',
            (int)$item->getQtyOrdered(),
            $item->getPrice(),
            $regularPrice,
            $internalCode,
            $internalCodeCountry,
            $categoryName,
            $item->getTaxAmount()
        );
    }
}
