<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Model_System_Config_Source_Template {
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
		  'label'=> 'Grid Template' 
		   ),
		  array(
		  'value'=>'2',
		  'label'=> 'List Template' 
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
		$options = array('1'=>'Grid Template','2'=>'List Template');
		return $options;
	}
}