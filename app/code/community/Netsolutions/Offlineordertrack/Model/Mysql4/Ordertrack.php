<?php
class Netsolutions_Offlineordertrack_Model_Mysql4_Ordertrack extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{   
		$this->_init('offlineordertrack/ordertrack', 'ordertrack_id');
	}
}
