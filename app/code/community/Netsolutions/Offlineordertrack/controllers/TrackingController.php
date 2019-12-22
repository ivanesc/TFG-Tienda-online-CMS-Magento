<?php
class Netsolutions_Offlineordertrack_TrackingController extends Mage_Core_Controller_Front_Action
{
	const XML_PATH_FORGOT_ORDERNUMER_EMAIL = 'netsolconfig/netsol_group/custom_template';
	const XML_PATH_FORGOT_ORDERNUMER_EMAIL_IDENTITY = 'netsolconfig/netsol_group/custom_identity';

	/****
	 * render the default layout for track order
	 * **/
	public function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	/***
	 * Check for Order Details from order number for track order with  ajax
	 * 
	 * @param Mage_Sales_Model_Order $order order object
	 * 
	 * @return json encode 
	 * 
	 * **/
	public function ajaxAction()
	{
		$params = $this->getRequest()->getParams();
		$response = array();
		$orderId =  Mage::getModel('sales/order')->loadByIncrementId($params['order_number'])->getEntityId(); 
		$order = Mage::getModel("sales/order")->load($orderId);
		$fieldmultiselect = Mage::getStoreConfig('netsolconfig/netsol_group/netsol_specific_field');
		$fieldmultiselect = explode(",",$fieldmultiselect);
		/**
		 * 1=email
		 * 2=zipcode
		 * 3=phonenumber
		 * */
		$getOrderData = $order->getData();
		/** store the ordered customer email address***/
				
		if(!empty($getOrderData)){ 
			$cEmail = $order->getCustomerEmail();
			$billing = $order->getBillingAddress();
			$shipping = $order->getShippingAddress(); 
			$zipcode = $billing->getPostcode();
			$phonenumber = $billing->getTelephone();
			$response['orderdetail_header'] = '';
			$response['orderdetail_html'] = '';
			$response['billing_info'] = '';
			$response['shipping_info'] = '';
			$response['subtotal'] = '';
			$response['shipping'] = '';
			$response['tax'] = '';
			$response['discount'] = '';
			$response['grandtotal'] = '';
			$response['order_number'] = '';
			$response['order_email'] = '';
			$response['status'] = '';
			$response['shipped_dates'] = '';
			$response['billed_on'] = '';
			
			$fieldTodisplayemail = Mage::getStoreConfig('netsolconfig/netsol_group/netsol_email');
			$fieldTodisplayzipcode = Mage::getStoreConfig('netsolconfig/netsol_group/netsol_zipcode');
			$fieldTodisplayphonenumber = Mage::getStoreConfig('netsolconfig/netsol_group/netsol_phonenumber');
			
			if(in_array(2,$fieldmultiselect) && in_array(3,$fieldmultiselect)){
				$condition = ($zipcode == trim($params['order_zipcode']) && $phonenumber === trim($params['order_phonenumber']));
			}elseif(in_array(2,$fieldmultiselect) && in_array(1,$fieldmultiselect)){
				 $condition = ($zipcode == trim($params['order_zipcode']) && $cEmail == trim($params['order_email']));
			}elseif(in_array(2,$fieldmultiselect) && in_array(1,$fieldmultiselect) && in_array(3,$fieldmultiselect)){
				 $condition = ($cEmail ==  trim($params['order_email']) && $zipcode == trim($params['order_zipcode']) && $phonenumber === trim($params['order_phonenumber']));
			}elseif(in_array(3,$fieldmultiselect) && in_array(1,$fieldmultiselect)){
				 $condition = ($phonenumber === trim($params['order_phonenumber']) && $cEmail == trim($params['order_email']));
			}elseif(in_array(1,$fieldmultiselect) || in_array(2,$fieldmultiselect) || in_array(3,$fieldmultiselect)){
				$condition = ($cEmail == trim($params['order_email']) || $zipcode == trim($params['order_zipcode']) || $phonenumber === trim($params['order_phonenumber']));
			}else{
				$condition = ($params['order_number'] != '');
			}
			
			/* get the block of sales order_item_render_default for custom options value and label */
			$block = Mage::app()->getLayout()->createBlock('sales/order_item_renderer_default');
			
			if($condition != '') {
				$response['orderdetail_header'] .='<tr><th>Items</th>';
				
				$orderVisibleItem = $order->getAllVisibleItems();
				foreach($orderVisibleItem as $item)
				{ 
					$item->getSku();
					$_product = Mage::getModel('catalog/product')->load($item->getProductId());
					$response['orderdetail_html'] .= '<tr><td><img width="55" height="55" src="'.$_product->getSmallImageUrl().'" class="dataImage"><h3 class="dataName">'.$item->getName().'</h3>';
						
					$block->setItem($item);		
						
					/* get the custom options of products */
					if ($options = $block->getItemOptions()){
						$j=0;
						foreach($options as $option){
							$label[$i] = $option['label'];
							$textvalue[$j] = $option['value'];
							$response['orderdetail_html'] .= '<p class="dataStyle">style #:'.$label[$i].':'.$textvalue[$j].'</p>';
							$j++;
						}
					}
					$response['orderdetail_html'] .='</td><td><span><span class="price">'.number_format($item->getPrice(),2).'</span></span></td><td><span>'.number_format($item->getQtyOrdered(),2).'</span></td><td class="dataTotal"><span><span class="price">'.number_format($item->getPrice(),2).'</span></span></td></tr>';
				}
				$grandTotal = $order['grand_total'];
				$orderSubtotal = $order->getSubtotal();
				$ordershippingAmount = $order->getShippingAmount();
				$orderTax = $order->getTaxAmount();
				$orderDiscount = $order->getDiscountAmount();
				$orderStatus = $order->getStatus();
				$response['orderdetail_header'] .='<th>Price</th><th>Quantity</th><th class="dataTotal">Total</th></tr>';
				$response['billing_info'] = $billing->format('html');
				$response['shipping_info'] = $shipping->format('html');
				$response['subtotal'] = '<span class="price">'.number_format($orderSubtotal,2).'</span>';
				$response['shipping'] = '<span class="price">'.number_format($ordershippingAmount,2).'</span>';
				$response['tax'] = '<span class="price">'.number_format($orderTax,2).'</span>';
				$response['discount'] = '<span class="price">'.number_format($orderDiscount,2).'</span>';
				$response['grandtotal'] = '<span class="price">'.number_format($grandTotal,2).'</span>';
				$response['order_number'] =$params['order_number'];
				$response['order_email'] = $params['order_email'];
				$response['status'] = $orderStatus;
				$shipping_method = $order->getShippingDescription();
				$payment =  $order->getPayment()->getMethodInstance()->getTitle();
				$response['payment'] = $payment;
				$response['carriers'] = $shipping_method;
				
				$orderShipmentCollection = $order->getShipmentsCollection();
				$orderCreatedAt = $order->getCreatedAt();
				foreach($orderShipmentCollection as $shipment)
				{
					$shipmentCreatedAt = $shipment->getCreatedAt();
					$response['shipped_dates'] =  date("M d, Y h:i:s A",strtotime($shipment->getCreatedAt()));
				}
				$response['billed_on'] = date("M d, Y h:i:s A",strtotime($orderCreatedAt));
					
			}else{
				$errormsg = 'Entered data is incorrect.Please try again.';
				$response['error_status'] = $errormsg;
			}
			
		}else{
			$errormsg = 'Entered data is incorrect.Please try again.';
			$response['error_status'] = $errormsg;
		}
		
		if($params['order_number'] == ''){
			$response['order_number'] =$params['order_number'];
			$response['error_status'] = 'Please fill the required field';
		}
		
		$this->getResponse()->setBody(Mage::Helper('core')->jsonEncode($response));
	}
	/***
	 * Check for Order Details from order number for track order without  ajax
	 * 
	 * @param Mage_Sales_Model_Order $order order object
	 * 
	 * @return string data with render layout
	 * **/
	public function ordertrackAction()
	{ 
		$params = $this->getRequest()->getParams();
		$response = array();
		$orderId =  Mage::getModel('sales/order')->loadByIncrementId($params['order_number'])->getEntityId(); 
		$order = Mage::getModel("sales/order")->load($orderId);
		$fieldmultiselect = Mage::getStoreConfig('netsolconfig/netsol_group/netsol_specific_field');
		$fieldmultiselect = explode(",",$fieldmultiselect);
		/**
		 * 1=email
		 * 2=zipcode
		 * 3=phonenumber
		 * */
		$getOrderData = $order->getData();
		if(empty($getOrderData))
		{
			$errormsg = 'Entered data is incorrect.Please try again.';
			$response['error_status'] = $errormsg;
		}
		
		if(!empty($getOrderData)){ 
			/** store the ordered customer email address***/
			$cEmail = $order->getCustomerEmail();
			$billing = $order->getBillingAddress();
			$zipcode = $billing->getPostcode();
			$phonenumber = $billing->getTelephone();
			$response['orderdetail_header'] = '';
			$response['orderdetail_html'] = '';
			$response['billing_info'] = '';
			$response['shipping_info'] = '';
			$response['subtotal'] = '';
			$response['shipping'] = '';
			$response['tax'] = '';
			$response['discount'] = '';
			$response['grandtotal'] = '';
			$response['order_number'] = '';
			$response['order_email'] = '';
			$response['status'] = '';
			$response['shipped_dates'] = '';
			$response['billed_on'] = '';
			
			$shipping_method = $order->getShippingDescription();
				$response['carriers'] = $shipping_method;
			if(in_array(2,$fieldmultiselect) && in_array(3,$fieldmultiselect)){
				$condition = ($zipcode == trim($params['order_zipcode']) && $phonenumber === trim($params['order_phonenumber']));
			}elseif(in_array(2,$fieldmultiselect) && in_array(1,$fieldmultiselect)){
				 $condition = ($zipcode == trim($params['order_zipcode']) && $cEmail == trim($params['order_email']));
			}elseif(in_array(2,$fieldmultiselect) && in_array(1,$fieldmultiselect) && in_array(3,$fieldmultiselect)){
				 $condition = ($cEmail ==  trim($params['order_email']) && $zipcode == trim($params['order_zipcode']) && $phonenumber === trim($params['order_phonenumber']));
			}elseif(in_array(3,$fieldmultiselect) && in_array(1,$fieldmultiselect)){
				 $condition = ($phonenumber === trim($params['order_phonenumber']) && $cEmail == trim($params['order_email']));
			}elseif(in_array(1,$fieldmultiselect) || in_array(2,$fieldmultiselect) || in_array(3,$fieldmultiselect)){
				$condition = ($cEmail == trim($params['order_email']) || $zipcode == trim($params['order_zipcode']) || $phonenumber === trim($params['order_phonenumber']));
			}
		
			/* get the block of sales order_item_render_default for custom options value and label */
			$block = Mage::app()->getLayout()->createBlock('sales/order_item_renderer_default');
			

			if ($condition != '') {
				$ship_flag = '';
				$response['orderdetail_header'] .='<tr><th>Items</th>';
				
				$orderVisibleItem = $order->getAllVisibleItems();
				foreach($orderVisibleItem as $item)
				{ 
					$item->getSku();
					$_product = Mage::getModel('catalog/product')->load($item->getProductId());
					$response['orderdetail_html'] .= '<tr><td><img width="55" height="55" src="'.$_product->getSmallImageUrl().'" class="dataImage"><h3 class="dataName">'.$item->getName().'</h3>';
						
					$block->setItem($item);	
					$i = 0;	
					/* get the custom options of products */
					if ($options = $block->getItemOptions()){
						$j=0;
						foreach($options as $option){
							$label[$i] = $option['label'];
							$textvalue[$j] = $option['value'];
							$response['orderdetail_html'] .= '<p class="dataStyle">style #:'.$label[$i].':'.$textvalue[$j].'</p>';
							$j++;
						}
					}
					$response['orderdetail_html'] .='</td><td><span><span class="price">'.number_format($item->getPrice(),2).'</span></span></td><td><span>'.number_format($item->getQtyOrdered(),2).'</span></td><td class="dataTotal"><span><span class="price">'.number_format($item->getPrice(),2).'</span></span></td></tr>';
				}
				$response['orderdetail_header'] .='<th>Price</th><th>Quantity</th><th class="dataTotal">Total</th></tr>';
				$getShippingAddress = $order->getShippingAddress();
				$getBillingAddress = $order->getBillingAddress();
				if (empty($getShippingAddress)) {
					 $ship_flag = 0;
				} else {
					 $ship_flag = 1;
					 $shipping = $order->getShippingAddress()->format('html'); 
				}
				if (empty($getBillingAddress)) {
					 $bill_flag = 0;
				} else {
					 $bill_flag = 1;
					 $billing = $order->getBillingAddress()->format('html');	 
				}
				
				$response['billing_info'] = $billing;
				$response['shipping_info'] = $shipping;
				$orderSubtotal = $order->getSubtotal();
				$ordershippingAmount = $order->getShippingAmount();
				$orderTax = $order->getTaxAmount();
				$orderDiscount = $order->getDiscountAmount();
				$orderStatus = $order->getStatus();
				$response['subtotal'] = '<span class="price">'.number_format($orderSubtotal,2).'</span>';
				$response['shipping'] = '<span class="price">'.number_format($ordershippingAmount,2).'</span>';
				$response['tax'] = '<span class="price">'.number_format($orderTax,2).'</span>';
				$response['discount'] = '<span class="price">'.number_format($orderDiscount,2).'</span>';
				$grandTotal = $order['grand_total'];;
				$response['grandtotal'] = '<span class="price">'.number_format($grandTotal,2).'</span>';
				$response['order_number'] = $params['order_number'];
				$response['order_email'] = $params['order_email'];
				$response['status'] = $orderStatus;
				$shipping_method = $order->getShippingDescription();
				$response['carriers'] = $shipping_method;
				
				$orderShipmentCollection = $order->getShipmentsCollection();
				$orderCreatedAt = $order->getCreatedAt();
				
				foreach($orderShipmentCollection as $shipment)
				{
					$shipmentCreatedAt = $shipment->getCreatedAt();
					$response['shipped_dates'] = date("M d, Y h:i:s A",strtotime($shipmentCreatedAt));
				}
				$response['billed_on'] = date("M d, Y h:i:s A",strtotime($orderCreatedAt));
				
				$this->loadLayout();
				$this->getLayout()
					 ->getBlock('offlineorder.tracking.track')
					 ->setTemplate('netsolutions/offlineordertrack/track.phtml');
				$block = $this->getLayout()
							  ->getBlock('offlineorder.tracking.track');
				$block->setData('order_number',$params['order_number']);
				$block->setData('billing_info',$response['billing_info']);
				$block->setData('shipping_info',$response['shipping_info']);
				$block->setData('subtotal',$response['subtotal']);
				$block->setData('shipping',$response['shipping']);
				$block->setData('tax',$response['tax']);
				$block->setData('discount',$response['discount']);
				$block->setData('grandtotal',$response['grandtotal']);
				$block->setData('status',$response['status']);
				$block->setData('billed_on',$response['billed_on']);
				$block->setData('carriers',$response['carriers']);
				$block->setData('shipped_dates',$response['shipped_dates']);
				$block->setData('orderdetail_header',$response['orderdetail_header']);
				$block->setData('orderdetail_html',$response['orderdetail_html']);
				$block->setData('order_number',$params['order_number']);
				$payment =  $order->getPayment()->getMethodInstance()->getTitle();
				$block->setData('payment',$payment);
				$this->renderLayout();
				
			}else{
				
				$this->loadLayout();
				$this->getLayout()
					 ->getBlock('offlineorder.tracking.track')
					 ->setTemplate('netsolutions/offlineordertrack/track.phtml');
				$block = $this->getLayout()
							  ->getBlock('offlineorder.tracking.track');
				$errormsg = 'Entered data is incorrect.Please try again.';
				$block->setData('error_status',$errormsg);
				$this->renderLayout();
			}
		}else{
				
				$this->loadLayout();
				$this->getLayout()
					 ->getBlock('offlineorder.tracking.track')
					 ->setTemplate('netsolutions/offlineordertrack/track.phtml');
				$block = $this->getLayout()
							  ->getBlock('offlineorder.tracking.track');
				$errormsg = 'Entered data is incorrect.Please try again.';
				$block->setData('error_status',$errormsg);
				$this->renderLayout();
		}
	}
	/**
	 * Retrieve Order Number or Id from email address
	 * 
	 * 
	 * @return collection order number
	 * */
	 public function forgotajaxAction()
	 {
		 
		$params = $this->getRequest()->getParams(); 
		$params['order_email']; 
		$response = array();
		
		if($params['order_email']!= ''){
			
			
			$response['order_email'] = $params['order_email'];
			
			$ordercustomername = Mage::getResourceModel('sales/order_collection')
									->addFieldToSelect('customer_firstname')
									->addFieldToSelect('customer_lastname')
									->addFieldToFilter('customer_email',$response['order_email'])
									->getFirstItem();
			if(count($ordercustomername)>0){
				$customername = $ordercustomername->getCustomerFirstname().' '.$ordercustomername->getCustomerLastname();
			}
												
			$ordertrack = Mage::getModel('offlineordertrack/ordertrack')->getCollection()
							->addFieldToSelect('ordertrack_id')
							->addFieldToSelect('emailtime')
							->addFieldToSelect('email')
							->addFieldToFilter('email',$response['order_email'])
							->getFirstItem();

			if($ordertrack['emailtime'] !='' && $ordertrack['email'] == $response['order_email']){ 
				//$date = Mage::getModel('core/date')->gmtDate();	
				$date = Mage::getModel('core/date')->date('Y-m-d H:i:s');

				$dateA = $ordertrack['emailtime']; 

				$dateB = $date; 

				$timediff = strtotime($dateB) - strtotime($dateA);

				if($timediff >= 86400){ 
				
					/** @var Mage_Core_Model_Template**/
					//$response['order_email'] = 'asha.rajputsai@gmail.com';
					$translate = Mage::getSingleton('core/translate');
					$translate->setTranslateInline(false);
					$storeId = Mage::app()->getStore()->getId();
						
					$mailTemplate = Mage::getModel('core/email_template');
					
					$mailTemplate->setDesignConfig(array('area'=>'frontend'))
								 ->sendTransactional(
									  Mage::getStoreConfig(self::XML_PATH_FORGOT_ORDERNUMER_EMAIL),
									  Mage::getStoreConfig(self::XML_PATH_FORGOT_ORDERNUMER_EMAIL_IDENTITY),
									  $response['order_email'],
									  array(
										'name'  => ucwords($customername),
										'email' => $response['order_email'],
									  )
								 ); 
					$translate->setTranslateInline(true);
					$ordertrack = Mage::getModel('offlineordertrack/ordertrack')->load($ordertrack['ordertrack_id']);
								//	->addFieldToFilter('email',$response['order_email']);
					//$date = Mage::getModel('core/date')->gmtDate();
					
					$date = Mage::getModel('core/date')->date('Y-m-d H:i:s');
					$data=array(
							'emailtime'=>$date,
							'email'=>$response['order_email'],
							);
					$ordertrack->setEmailtime($date);
					$ordertrack->save();
					$response['error_status'] = 'Order Numer with respect to email address is sent to your email address.';
				}
				else
				{
					//echo 'less than 24 hours';
					$response['error_status'] = 'Only one email can be send within 24hrs';
				}	
			}else{  
				$ordercollection = Mage::getResourceModel('sales/order_collection')
									->addFieldToSelect('*')
									->addFieldToFilter('customer_email',$response['order_email']);
									//print_r($ordercollection->getData()); die;
				$orderCollectionData = $ordercollection->getData();
				if(!empty($orderCollectionData))
				{
					/** @var Mage_Core_Model_Template**/
					//$response['order_email'] = 'asha.rajputsai@gmail.com';
				
					$translate = Mage::getSingleton('core/translate');
					$translate->setTranslateInline(false);
					$storeId = Mage::app()->getStore()->getId();
						
					$mailTemplate = Mage::getModel('core/email_template');
					
					$mailTemplate->setDesignConfig(array('area'=>'frontend'))
								 ->sendTransactional(
									  Mage::getStoreConfig(self::XML_PATH_FORGOT_ORDERNUMER_EMAIL),
									  Mage::getStoreConfig(self::XML_PATH_FORGOT_ORDERNUMER_EMAIL_IDENTITY),
									  $response['order_email'],
									  array(
										'name'  => ucwords($customername),
										'email' => $response['order_email'],
									  )
								 ); 
					$translate->setTranslateInline(true);
					
					//$date = Mage::getModel('core/date')->gmtDate();
					$date = Mage::getModel('core/date')->date('Y-m-d H:i:s');
					$data=array(
							'emailtime'=>$date,
							'email'=>$response['order_email'],
							);
					$model=Mage::getModel('offlineordertrack/ordertrack')->addData($data);
					$model->save();
					$response['error_status'] = 'Order Numer with respect to email address is sent to your email address.';
				}else{
					$response['error_status'] = 'No order place with this email address';	
				}				
			}				
		}
		$this->getResponse()->setBody(Mage::Helper('core')->jsonEncode($response));
	 }
}
