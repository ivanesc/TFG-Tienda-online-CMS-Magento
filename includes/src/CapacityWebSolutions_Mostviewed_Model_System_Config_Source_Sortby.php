<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Model_System_Config_Source_Sortby{
	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
    {
		 $array=array(
		  array(
		  'value'=>'name',
		  'label'=> 'Name' 
		   ),
		  array(
		  'value'=>'price',
		  'label'=> 'Price' 
		   ),
		  array(
		  'value'=>'position',
		  'label'=> 'Position' 
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
		$options = array('name'=>'Name','price'=>'Price','position'=>'Position');
		return $options;
	}
}