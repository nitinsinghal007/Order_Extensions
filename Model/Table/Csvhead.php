<?php
namespace Dotsquares\Imexport\Model\Table;

class Csvhead extends  \Magento\Framework\model\AbstractModel
{
    public function getCSVHead()
    {
        $head = array();
        $head = array(
            "Purchase Date",
            "Purchase Time",
            "Order Id",
			"Payment Ref",
            "Product Attribute Set",
			"Product Name",
			"Product Type",
            "Sku",
			"RPtech SKU",
            "Quantity Ordered",
            "Selling Price",
            "Original Price",
            "InternalCode",
            "Internalcountrycode",
            "CategoryCode",
            "GST/Tax ID",
			"Billing To Name",
            "Shipping To Name",
            "Grand Total",
            "Base Grand Total",
            "Status",
			"Payment Status",
            "Billing Address",
            "Shipping Address",
            "Shipping Information",
            "Customer Email",
            "Customer Group",
            "Subtotal",
            "Base Subtotal",
            "Shipping Method",
            "Shipping Cost",
            "Customer Name",
            "Payment Method",
            "City",
//            "Company",
            "Country",
            "First Name",
            "Last Name",
            "Postcode",
            "Region",
            "Street",
            "Telephone",
            "Coupon code Used",
			"Tracking Id",
            "Assisted Sales Rep Name",
            "ISR",
            "GST Number",
            "UTM Source",
            "UTM Medium",
            "UTM Campaign",
            "Coupon Rule Name"
        );
        return $head;
    }
}
