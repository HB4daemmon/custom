<?php
require_once dirname(__FILE__)."/../../../../app/Mage.php";
require_once dirname(__FILE__).'/../../util/connection.php' ;
require_once dirname(__FILE__).'/../../customer/action/customer_action.php' ;

class custom_order{
    public static function createOrder($order,$product){
        Mage::init();
        $customer = Mage::getModel('customer/customer')->load($order['customer_id']);
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
            ->setOrigin_order_id($order['origin_order_id'])
            ->setReferer($order['referer'])
            ->setTo_buyer($order['to_buyer'])
            ->setIs_1yuan($order['is_1yuan'])
            ->setDevice_id($order['device_id'])
            ->setShipping_type($order['shipping_type'])
            ->setCreated_at($order['create_at']);
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

    public static function import_orders($order,$product){
        try{
            $conn = db_connect();
            $order_id = $order[0];

            $customer_id_return = customer::getCustomerEntityIdFromUserId($order[3]);
            if($customer_id_return['success'] == 0){
                $errorcode = $customer_id_return['errorcode'];
                throw new Exception($customer_id_return['data']);
            }
            $customer_id = $customer_id_return['data'];
            $order_sn = $order[2];
            $order_status_code = $order[4];
            $pay_status_code = $order[6];
            $referer = $order[44];
            $to_buyer = $order[55];
            $is_1yuan = $order[69];
            $device_id = $order[70];
            $shipping_type = $order[73];
            $create_at = date('y-m-d H:i:s',$order[45]);

            if($order_status_code){

            }

            $order_list = array("origin_order_id"=>$order_id,"customer_id"=>$customer_id,"order_sn"=>$order_sn,"referer"=>$referer,"to_buyer"=>$to_buyer,
                "is_1yuan"=>$is_1yuan,"device_id"=>$device_id,"shipping_type"=>$shipping_type,"create_at"=>$create_at);


            $product_list = array();
            foreach($product as $p){
                $goods_id = self::getProductId($p[2]);
                $qty = array('qty',$p[6]);
                $product_list[$goods_id]=$qty;
            }

            self::createOrder($order_list,$product_list);

            $conn->close();
            return array('data'=>'',"success"=>1,"errorcode"=>0);
        }catch (Exception $e){
            $conn->close();
            return array('data'=>$e->getMessage(),"success"=>0,"errorcode"=>$errorcode);
        }
    }

    public static function getProductId($origin_product_id){
        try{
            $conn = db_connect();
            $sql = "select value_id from catalog_product_entity_varchar p,eav_attribute e
                                where p.attribute_id = e.attribute_id
                                and e.attribute_code = 'origin_product_id'
                                and e.entity_type_id = 4
                                and p.value = '$origin_product_id'";
            $sqlres = $conn->query($sql);
            if(!$sqlres){
                $errorcode = 10063;
                throw new Exception("GET_PRODUCT_ID_ERROR");
            }
            $row = $sqlres->fetch_assoc();
            $product_id = $row['value_id'];
            $conn->close();
            return $product_id;
        }catch (Exception $e){
            return $errorcode.":".$e->getMessage();
            $conn->close();
        }
    }

}



?>