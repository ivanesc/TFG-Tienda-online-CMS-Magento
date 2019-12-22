<?php
class MntsInfotech_CookieConsent_Model_Observer
{
    public function prepareLayoutBefore(Varien_Event_Observer $observer)
   {
       /*if (!Mage::helper('cookieconsent')->isEnabled()) {
           return $this;
       }*/
 
       /* @var $block Mage_Page_Block_Html_Head */
       $block = $observer->getEvent()->getBlock();
        
       if ("head" == $block->getNameInLayout()) {
           foreach (Mage::helper('cookieconsent')->getFiles() as $file) {
               $block->addJs(Mage::helper('cookieconsent')->getJQueryPath($file));
           }
       }
 
       return $this;
   }
}