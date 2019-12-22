<?php
class Netsolutions_Offlineordertrack_Block_Tracking extends Mage_Core_Block_Template{
	/***
	 * Retrieve form url through helper
	 * 
	 * @return form url
	 * */
	public function getFormUrl()
	{
		return $this->helper('offlineordertrack')->resultUrl();
	}
	/***
	 * Retrieve order track url through helper
	 * 
	 * @return form url
	 * */
	public function ordertrackUrl()
	{
		return $this->helper('offlineordertrack')->trackingUrl();
	}
	/***
	 * Retrieve form url through helper
	 * @return forgot ordernumber form url
	 * */
	 public function getForgotorderurl(){
		 return $this->helper('offlineordertrack')->forgotorderUrl();
	 }
	
}
