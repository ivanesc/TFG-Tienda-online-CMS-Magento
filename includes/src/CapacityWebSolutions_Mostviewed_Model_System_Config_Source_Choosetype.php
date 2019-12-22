<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Model_System_Config_Source_Choosetype {
	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
    {
		 $array=array(
		  array(
		  'value'=>'1',
		  'label'=> 'Display All Products' 
		   ),
		  array(
		  'value'=>'2',
		  'label'=>'Display Category Wise' 
		   ),
		  );
		return $array;
    }

	/**
	 * Get options in "key-value" format
	 *
	 * @return array
	 */
	public function toArray()
	{
		$options = array('1'=>'Display All Products','2'=>'Display Category Wise');
		return $options;
	}
}