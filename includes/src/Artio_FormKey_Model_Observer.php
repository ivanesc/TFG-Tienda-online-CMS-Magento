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
 * Observer.
 *
 * @category Artio
 * @package Artio_FormKey
 * @author Artio Magento Team <info@artio.net>
 */
class Artio_FormKey_Model_Observer
{

	/**
	 * @param Varien_Event_Observer $observer
	 */
	public function disableFormKey($observer)
	{
		$config = Mage::getSingleton('artio_formkey/config');

		if ($config->isFormKeyDisabled())
		{
			$actions = $config->getAffectedActionsAsArray();
			$route = $observer->getEvent()->getControllerAction()->getFullActionName();

			if (in_array($route, $actions))
			{
				$key = Mage::getSingleton('core/session')->getFormKey();
				Mage::app()->getRequest()->setParam('form_key', $key);
			}
		}
	}

}