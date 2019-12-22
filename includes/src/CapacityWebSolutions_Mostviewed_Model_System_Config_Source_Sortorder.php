<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Model_System_Config_Source_Sortorder {
	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
    {
		 $array=array(
		  array(
		  'value'=>'asc',
		  'label'=> 'Ascending' 
		   ),
		  array(
		  'value'=>'desc',
		  'label'=> 'Descending' 
		   ),
		  array(
		  'value'=>'rand',
		  'label'=> 'Random' 
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
		$options = array('1'=>'Auto','2'=>'Manually','3'=>'Random');
		return $options;
	}
}