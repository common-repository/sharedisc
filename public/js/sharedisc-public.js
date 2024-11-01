(function( $ ) {
	'use strict';
	
	
	
	

		
		
		/**
		 * @since 1.0.0
		*/
		jQuery(document).ready(function(){
			
			
			
						
			/*
			 * Append the message at the top of the cart to the results count.  
			 */
			if(jQuery('#sd_append_to_results').length){
				
				jQuery('#sd_append_to_results').appendTo(jQuery('.woocommerce-result-count'));
				
			}
			
			
			
			
						
			/*
			 * Append the messages in the loop to the price p
			 */
			jQuery('.sd_earn_callout_loop').each(function(){
				
				if(sd_params.icon_location == 'left'){
					jQuery(this).css('left', '6px');
				}else{
					jQuery(this).css('right', '6px');
				}
				
				//var $_THIS = jQuery(this);
				//var $_THAT = jQuery(this).next();
				
				//$_THIS.appendTo($_THAT);
				
				
			});
			
			
			
			
			
			
						
			/*
			 * Instantiate all the hovers
			 */
			jQuery('.sd_tooltip').tooltipster({
				trigger: sd_params.popup_trigger,
				animation: sd_params.popup_animation,
				theme: sd_params.popup_theme,
				position: sd_params.popup_location,
				contentAsHTML: true
			});
			
			
			
			
			
						
			/*
			 * The callouts to open the sharing grid on the cart.
			 
			jQuery('.cart_item').each(function(item){
				
				if(jQuery(this).find('.sharediscgrid')){
				
					jQuery(this).after('<tr class="cart_item has_grid" style="display:none;"><td style="padding:0px" class="cart_grid" colspan="6"></td></tr>');
				
				}
			});
			*/
			
			
			
			
			
			
			/*
			 * Adding fee class to cart collatorals
			 
			if(jQuery('.cart-collaterals .cart_totals .fee').length){
				
				jQuery('.cart-collaterals .cart_totals .fee').each(function(){
					
					if(jQuery(this).find('th:contains(Discount)')){
						jQuery(this).closest('.fee').addClass("sd_fee_item");
					}
					
				});
				
			}
			*/
			
			
			
			
			
			
			/*
			 * Adding fee class to cart collatorals
			 
			if(jQuery('.woocommerce-checkout-review-order-table .fee').length){
				
				jQuery('.woocommerce-checkout-review-order-table .fee').each(function(){
					
					if(jQuery(this).find('th:contains(Discount)')){
						jQuery(this).closest('.fee').addClass("sd_fee_item");
					}
					
				});
				
			}		
			
			
			
			
			jQuery( 'body' ).bind( 'updated_checkout', function() {
				
				jQuery('.woocommerce-checkout-review-order-table .fee').each(function(){
					
					if(jQuery(this).find('th:contains(Discount)')){
						jQuery(this).closest('.fee').addClass("sd_fee_item");
					}
					
				});
				
			});
			*/			
					
			
			
			
		});
		
		
		
		
		
		
		
		
		
		
		/**
		 * @since 1.0.0
		
		jQuery('.sharediscgrid').live('click', function(){
			
			
			if(jQuery(this).closest('tr').next('.has_grid').is(':visible')){
				
				jQuery('.has_grid').hide();
				
			}else{
				
				jQuery('.has_grid').hide();
				var url = jQuery(this).parent().parent().find('.remove').attr('href');
				var nodeid = getURLParameter(url, 'remove_item');	
				var gridElement = jQuery(this).parent().parent().nextAll('.has_grid').first();
				var gridItself =  jQuery(this).parent().parent().nextAll('.has_grid').first().find('.cart_grid');
				jQuery(gridElement).toggle();
				var data = {
					'action': 'sd_display_share_grid',
					'product': nodeid
				};
				
				
				jQuery('.woocommerce form').block({ message: null, overlayCSS: { background: '#fff url(' + sd_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });
				
				jQuery.post(sd_params.ajax_url, data, function(response){
					jQuery(gridItself).html(response);
					jQuery('.woocommerce form').unblock();
				});
				
			}	
			

		});
		
		*/
		
		
		
		
		
		
		
		/**
		 * @since 1.0.0
		*/
		function getURLParameter(url, name) {
		    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
		}
	

})( jQuery );
