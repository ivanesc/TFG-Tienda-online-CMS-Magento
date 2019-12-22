<?php 
class Imedia_Quickview_IndexController extends Mage_Core_Controller_Front_Action{
    
	public function indexAction(){
        
		echo $this->getLayout()->createBlock('core/template')->setTemplate('imedia/quickview/view.phtml')->toHtml();  
    
	}
	
}
?>