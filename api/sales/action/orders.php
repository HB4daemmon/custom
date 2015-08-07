<?php
require_once dirname(__FILE__)."/../../../../app/Mage.php";
require_once(dirname(__FILE__).'/../../util/connection.php');

class custom_order{
    public function createOrder($customer_id,$product){
        Mage::init();
        $customer = Mage::getModel('customer/customer')->load($customer_id);
        $transaction = Mage::getModel('core/resource_transaction');
        $store_Id = $customer->getStoreId();
        //$order = Mage::getModel("sales/order")->getCollection()->getLastItem()->getIncrementId();
        $lastOrderId =Mage::getSingleton('eav/config')->getEntityType('order')->fetchNewIncrementId($store_Id);
        //$lastOrderId = $order->getIncrementId();

        $order = Mage::getModel('sales/order')
            ->setIncrementId($lastOrderId)
            ->setStoreId($store_Id)
            ->setQuoteId(0)
            ->setGlobal_currency_code('CNY')
            ->setBase_currency_code('CNY')
            ->setStore_currency_code('CNY')
            ->setOrder_currency_code('CNY')
            ->setOrigin_order_id('1')
            ->setRefer('2')
            ->setTo_buyer('3')
            ->setIs_1yuan('4')
            ->setDevice_id('5')
            ->setShipping_type('6')
            ->setCreated_at(date('y-m-d H:i:s'));
        $order->setCustomer_email($customer->getEmail())
            ->setCustomerFirstname($customer->getFirstname())
            ->setCustomerLastname($customer->getLastname())
            ->setCustomerGroupId($customer->getGroupId())
            ->setCustomer_is_guest(0)
            ->setCustomer($customer);

        $billing = $customer->getDefaultBillingAddress();
        $billingAddress = Mage::getModel('sales/order_address')
            ->setStoreId($store_Id)
            ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_BILLING)
            ->setCustomerId($customer->getId())
            ->setCustomerAddressId($customer->getDefaultBilling())
            ->setCustomer_address_id($billing->getEntityId())
            ->setPrefix($billing->getPrefix())
            ->setFirstname($billing->getFirstname())
            ->setMiddlename($billing->getMiddlename())
            ->setLastname($billing->getLastname())
            ->setSuffix($billing->getSuffix())
            ->setCompany($billing->getCompany())
            ->setStreet($billing->getStreet())
            ->setCity($billing->getCity())
            ->setCountry_id($billing->getCountryId())
            ->setRegion($billing->getRegion())
            ->setRegion_id($billing->getRegionId())
            ->setPostcode($billing->getPostcode())
            ->setTelephone($billing->getTelephone())
            ->setFax($billing->getFax());
        $order->setBillingAddress($billingAddress);

        $shipping = $customer->getDefaultShippingAddress();
        $shippingAddress = Mage::getModel('sales/order_address')
            ->setStoreId($store_Id)
            ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
            ->setCustomerId($customer->getId())
            ->setCustomerAddressId($customer->getDefaultShipping())
            ->setCustomer_address_id($shipping->getEntityId())
            ->setPrefix($shipping->getPrefix())
            ->setFirstname($shipping->getFirstname())
            ->setMiddlename($shipping->getMiddlename())
            ->setLastname($shipping->getLastname())
            ->setSuffix($shipping->getSuffix())
            ->setCompany($shipping->getCompany())
            ->setStreet($shipping->getStreet())
            ->setCity($shipping->getCity())
            ->setCountry_id($shipping->getCountryId())
            ->setRegion($shipping->getRegion())
            ->setRegion_id($shipping->getRegionId())
            ->setPostcode($shipping->getPostcode())
            ->setTelephone($shipping->getTelephone())
            ->setFax($shipping->getFax());
        $order->setShippingAddress($shippingAddress)
              ->setShipping_method('flatrate_flatrate');

        $orderPayment = Mage::getModel('sales/order_payment')
            ->setStoreId($store_Id)
            ->setCustomerPaymenyId(0)
            ->setMethod('purchaseorder')
            ->setPo_number(' - ');
        $order->setPayment($orderPayment);

        $subTotal = 0;
        $products = array(
            '2' => array(
                'qty' => 2
            )
        );

        foreach($products as $productId => $product){
            $_product = Mage::getModel('catalog/product')->load($productId);
            $rowTotal = $_product->getPrice() * $product['qty'];
            $orderItem = Mage::getModel('sales/order_item')
                ->setStoreId($store_Id)
                ->setQuoteItemId(0)
                ->setQuoteParentItemId(NULL)
                ->setProductId($productId)
                ->setProductType($_product->getTypeId())
                ->setQtyBackordered(NULL)
                ->setTotalQtyOrdered($product['qty'])
                ->setQtyOrdered($product['qty'])
                ->setName($_product->getName())
                ->setSku($_product->getSku())
                ->setPrice($_product->getPrice())
                ->setBasePrice($_product->getPrice())
                ->setOriginalPrice($_product->getPrice())
                ->setRowTotal($rowTotal)
                ->setBaseRowTotal($rowTotal);

            $subTotal += $rowTotal;
            $order->addItem($orderItem);
        }

        $order->setSubtotal($subTotal)
            ->setBaseSubtotal($subTotal)
            ->setGrandTotal($subTotal)
            ->setBaseGrandTotal($subTotal);

        $transaction->addObject($order);
        $transaction->addCommitCallback(array($order,'place'));
        $transaction->addCommitCallback(array($order,'save'));
        $transaction->save();
    }
}



?>