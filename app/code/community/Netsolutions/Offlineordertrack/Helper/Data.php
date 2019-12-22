<?php
class Netsolutions_Offlineordertrack_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_CONFIG_PATH = "netsolconfig/settings/";
	
	
	public function trackingUrl()
    {
        return Mage::getUrl('offlineordertrack/tracking/index');
    }
    /***
	 * retrieve the order track url 
	 * 
	 * @return url
	 * 
	 ****/
    public function resultUrl()
    {
		return Mage::getUrl('offlineordertrack/tracking/ordertrack');
	}
	/***
	 * retrieve store config flag for netsol group enable or disabled.
	 * 
	 * @return boolean.
	 * 
	 ****/
	public function getIsEnabled()
	{
		return Mage::getStoreConfigFlag('netsolconfig/netsol_group/netsol_enable');
	}
	/***
	 * 
	 * 
	 * */
	 public function forgotorderUrl()
    {
        return Mage::getUrl('offlineordertrack/tracking/forgotajax');
    }
}
