<?php
/**
 * Form Key
 *
 *
 * @category    Artio
 * @package     Artio_FormKey
 * @copyright   Copyright (c) 2014 Artio (http://www.artio.net)
 */

/**
 * Configuration model.
 *
 * @category Artio
 * @package Artio_FormKey
 * @author Artio Magento Team <info@artio.net>
 */
class Artio_FormKey_Model_Config
{


	/**
	 * Determine whether form key should be disabled or not.
	 *
	 * @param int $store
	 * @return bool
	 */
	public function isFormKeyDisabled($store = null)
	{
		return (bool) $this->getVal('disable_formkey', $store);
	}


	/**
	 * Get list of actions that should be the form key disabled for.
	 *
	 * @param int $store
	 * @return array
	 */
	public function getAffectedActionsAsArray($store = null)
	{
		return $this->_explode($this->getVal('actions', $store), $delimiter = "\n");
	}


	/**
	 * Get configuration value.
	 *
	 * @param string $key
	 * @param int $store
	 * @return mixed
	 */
	public function getVal($key, $store = null)
	{
		return Mage::getStoreConfig("web/formkey/$key", $store);
	}


	/**
	 * Method explodes configuration string ($value) into array.
	 *
	 * Configuration string is trim before explode. Each
	 * item in array is also trim after explode.
	 *
	 * Configuration string is exploded by $delimiter.
	 *
	 * If $value is not defined method returns empty array.
	 * If $delimiter is not defined method log error and return empty array.
	 *
	 * @param string $value
	 * @param string $delimiter
	 * @return array
	 */
	protected function _explode($value, $delimiter = ',')
	{
		if (!$value)
			return array();

		if (!$delimiter)
		{
			Mage::log("Artio_FormKey: Delimiter cannot be empty!", Zend_Log::WARN);
			return array();
		}

		$value = trim($value);
		$value = explode($delimiter, $value);
		$value = array_map('trim', $value);

		return $value;
	}


}