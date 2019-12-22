<?php
$content = '<h2>Wait!</h2>
<h4>Before you leave, here is 1 offer for you....!!!</h4>
<h5 class="offer">Get Upto <span>70% Discount</span> & <span>5% Cash Back</span> on wide range of products. Hurry!</h5>
<p>Online Shopping for Men, Women & Kids. Shop online for Mobiles, Laptops, Appliances, Clothing, Footwear, Home Furnishing & more.</p>
<p><a class="button" href="#">Shop Now</a></p>
<small align=right>* T&C Apply</small>';

//if you want one general block for all the store viwes, uncomment the line below
$stores = array(0);
foreach ($stores as $store){
    $block = Mage::getModel('cms/block');
    $block->setTitle('Offer for you...!!!');
    $block->setIdentifier('vsourz-exit-screen-popup');
    $block->setStores(array($store));
    $block->setIsActive(1);
    $block->setContent($content);
    $block->save();
}