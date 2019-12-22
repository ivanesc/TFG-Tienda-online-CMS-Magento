function valueChanged(){
	if(jQuery('#order_forgotordernumber').is(":checked")) { 
		jQuery('#track-order-form').hide(1000);
		jQuery('#track-order-forgotorder').show(1000); 
		jQuery('#order-result').hide(1000);
		jQuery('#error_status').hide();
	}else{
		jQuery('#track-order-form').show(1000);
		jQuery('#track-order-forgotorder').hide(1000);
		jQuery('#error_status').hide();
	}
}
if ( typeof jQuery !== 'undefined')
{
jQuery.noConflict();
jQuery(document).ready(function() {
    /****
		jQuery ajax for retrieving all order number for specfic email 
		address and sending to customer email***/

	jQuery("#track-order-forgotorder").submit(function(){
		var url = jQuery("#track-order-forgotorder").attr('action');
		var data = jQuery('#track-order-forgotorder').serialize();
		//data += '&isAjax=1';
		
		jQuery.ajax({
			url: url,
			type: "POST",
			data: data,
			dataType: 'json',
			success:function(data){
				console.log('succes');
				if(data.error_status){
					jQuery('#error_status').html(data.error_status);
					jQuery('#error_status').show();
					//jQuery('#order-result').hide();
				}else{
					//jQuery('#order-result').show('slow');
					jQuery('#error_status').hide();
				}
				
			},
			statusCode: {
				404: function() { alert('Could not contact server.');
				},
				500: function() { alert('A server-side error has occurred.. 1');
				}
			},
			error: function(error) { 
				console.log(error);
				return false;
			},
		});
	
		return false;
	});
	/****
	Jquery ajax for tracking order detials
	**/
	jQuery("#track-order-form").submit(function()
	{
	jQuery('#div-loading').show();
	jQuery('#order-result-false').hide('slow');
	//jQuery('#order-result').hide('slow');
	//jQuery('#rs-carrier').show();
	jQuery('#rs-shipped').show();
	//jQuery('#rs-traking').show();
	//var ajax = '<?php echo $ajaxEnabled; ?>';
	var url = baseurl+'offlineordertrack/tracking/ajax/';
	var data = jQuery('#track-order-form').serialize();
	data += '&isAjax=1';
	if(ajax == 0)
	{
		 jQuery('#div-loading').hide();
		 var orderstatus = '<?php echo $this->status; ?>';
		 if (this.validator.validate()) {
			document.trackorderform.submit();
			if (orderstatus === 'canceled') {
				jQuery('#status2-value').text('Canceled');
			} else if (orderstatus === 'closed') {
				jQuery('#status2-value').text('Closed');
			} else if (orderstatus === 'complete') {
				jQuery('#status2-value').text('Complete');
			} else if (orderstatus === 'fraud') {
				jQuery('#status2-value').text('Suspected Fraud');
			} else if (orderstatus === 'holded') {
				jQuery('#status2-value').text('On Hold');
			} else if (orderstatus === 'payment_review') {
				jQuery('#status2-value').text('Payment Review');
			} else if (orderstatus === 'pending') {
				jQuery('#status2-value').text('Pending');
			} else if (orderstatus === 'pending_payment') {
				jQuery('#status2-value').text('Pending Payment');
			} else if (orderstatus === 'pending_paypal') {
				jQuery('#status2-value').text('Pending PayPal');
			} else {
				jQuery('#status2-value').text('Processing');
			}
			if (orderstatus === 'pending' || orderstatus === 'pending_payment' || orderstatus === 'pending_paypal' || orderstatus === 'holded') {
					removeActiveClass();
				jQuery('#status1-pending').addClass('active');
			} else if (orderstatus === 'processing' || orderstatus === 'payment_review' || orderstatus === 'fraud') {
					removeActiveClass();
				jQuery('#status1-processing').addClass('active');
			} else if (orderstatus === 'complete') {
					removeActiveClass();
				jQuery('#status1-completed').addClass('active');
			} else if (orderstatus === 'canceled') {
					removeActiveClass();
				jQuery('#status1-cancel').addClass('active');
			} else if (orderstatus === 'closed') {
					removeActiveClass();
				jQuery('#status1-closed').addClass('active');
			}
		 }
	}else{ 
		jQuery.ajax({
			url: url,
			type: "POST",
			data: data,
			dataType: 'json',
			success: function(data)
			{
				jQuery('#div-loading').hide();
				if (data.order_number !== '') {
					if(data.error_status){
						jQuery('#error_status').html(data.error_status);
						jQuery('#error_status').show();
						jQuery('#order-result').hide();
					}else{
						jQuery('#order-result').show('slow');
						jQuery('#error_status').hide();
					}
					
					jQuery('#no-value').text(data.order_number);
					jQuery('#track-order-number').text(data.order_number);
					jQuery('#billed-value').text(data.billed_on);
					jQuery('#status2-value').text(data.status);
					jQuery('#s-subtotal').html(data.subtotal);
					jQuery('#s-shipping').html(data.shipping);
					jQuery('#s-tax').html(data.tax);
					jQuery('#s-tax-discount').html(data.discount);
					jQuery('#s-total').html(data.grandtotal);
					jQuery('#s-payment').text('(' + data.payment + ')');
					jQuery('#gift-html').html(data.gifthtml);
					jQuery('#billing-info').html(data.billing_info);
					jQuery('#shipping-info').html(data.shipping_info);
					jQuery('#orderdetail-header').html(data.orderdetail_header);
					jQuery('#orderdetail-html').html(data.orderdetail_html);
					
					
					if (data.status === 'canceled') {
						jQuery('#status2-value').text('Canceled');
					} else if (data.status === 'closed') {
						jQuery('#status2-value').text('Closed');
					} else if (data.status === 'complete') {
						jQuery('#status2-value').text('Complete');
					} else if (data.status === 'fraud') {
						jQuery('#status2-value').text('Suspected Fraud');
					} else if (data.status === 'holded') {
						jQuery('#status2-value').text('On Hold');
					} else if (data.status === 'payment_review') {
						jQuery('#status2-value').text('Payment Review');
					} else if (data.status === 'pending') {
						jQuery('#status2-value').text('Pending');
					} else if (data.status === 'pending_payment') {
						jQuery('#status2-value').text('Pending Payment');
					} else if (data.status === 'pending_paypal') {
						jQuery('#status2-value').text('Pending PayPal');
					} else {
						jQuery('#status2-value').text('Processing');
					}
					jQuery('#number-after').val(data.order_number);
					jQuery('#email-after').val(data.order_email);
					
					var carrier_string = '';
					if(data.carriers)
					{
						for (var i = 0; i < data.carriers.length; i++)
						{
							if (data.carriers.length - i === 1) {
								carrier_string += data.carriers[i];
							} else {
								carrier_string += data.carriers[i] + ' ';
							}
						}
					}
					if(data.shipped_dates){
						jQuery('#shipped-value').text(data.shipped_dates);
					}else{
						jQuery('#rs-shipped').hide();
					}
					jQuery('#carrier-value').text(carrier_string);
					if (data.shipped_dates === '') {
						jQuery('#rs-carrier').hide();
						jQuery('#rs-shipped').hide();
						jQuery('#rs-traking').hide();
					}
					
					if (data.status === 'pending' || data.status === 'pending_payment' || data.status === 'pending_paypal' || data.status === 'holded') {
							removeActiveClass();
						jQuery('#status1-pending').addClass('active');
					} else if (data.status === 'processing' || data.status === 'payment_review' || data.status === 'fraud') {
							removeActiveClass();
						jQuery('#status1-processing').addClass('active');
					} else if (data.status === 'complete') {
							removeActiveClass();
						jQuery('#status1-completed').addClass('active');
					} else if (data.status === 'canceled') {
							removeActiveClass();
						jQuery('#status1-cancel').addClass('active');
					} else if (data.status === 'closed') {
							removeActiveClass();
						jQuery('#status1-closed').addClass('active');
					}
				}else {
					jQuery('#order-result-false').show('slow');
					jQuery('#order-result-false').text('Order Number or Email Adress invaild!');
				}
			},
			error: function(error)
			{
				console.log(error.status);
			}
		});
	} 
		return false;
	});
});
}
function removeActiveClass() {
	jQuery('#status1-pending').removeClass();
	jQuery('#status1-processing').removeClass();
	jQuery('#status1-completed').removeClass();
	jQuery('#status1-cancel').removeClass();
	jQuery('#status1-closed').removeClass();
}
