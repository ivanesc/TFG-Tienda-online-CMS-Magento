<?php
/***************************************************************************
	@extension	: Dynamic Bestseller Products.
	@Version	: 1.0.0.1
	@package	: Dzinehub_Bestseller	
	@class		: app/code/community/Dzinehub/Bestseller/Block/Product/Bestsellers.php	
	@author		: arun@dzine-hub.com
	@support	: http://www.dzine-hub.com/contact-us/		
***************************************************************************/

class Dzinehub_Bestseller_Block_Product_Bestsellers extends Mage_Catalog_Block_Product_Abstract
{
	function getBestsellerProduct()
		{
		$extensionenableflag = Mage::getStoreConfig('dzinehub_bestseller/general/active',Mage::app()->getStore());
		if($extensionenableflag==1)
			{
			$productCount = 50; // Cache
			$storeId = Mage::app()->getStore()->getId();

			/****** Get the list of best selling product ******/

			$_productCollection = Mage::getResourceModel('reports/product_collection')
				->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
				->addAttributeToSelect('image')
				->addAttributeToSelect('name')
				->addAttributeToSelect('url')
				->addAttributeToSelect('price')
				->setPageSize($productCount)
				->addOrderedQty()
				->setOrder('ordered_qty', 'desc');
			
			return $_productCollection;
			}
		}
}