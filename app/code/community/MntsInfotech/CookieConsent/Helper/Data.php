<?php

/**
 * Optimiseweb CookieNotice Helper Data
 *
 * @package     Optimiseweb_CookieNotice
 * @author      Kathir Vel (sid@optimiseweb.co.uk)
 * @copyright   Copyright (c) 2014 Optimise Web
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MntsInfotech_CookieConsent_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
    * Name library directory.
    */
  
   /**
    * List files for include.
    *
    * @var array
    */
   protected $_files = array(
       'jquery-1.10.2.min',
	   'cookieconsent'
   );
   
   public function getFiles(){
       return $this->_files;
   }
   
   public function getJQueryPath($file){
       return "mntsinfotech/".$file.".js";
   }
}
