(function( $ ) {
	'use strict';
	
	
	// http://www.codedevelopr.com/articles/get-social-network-share-counts/
	
	
	window.sd_linkedin = [];
	
	
	
	
	jQuery(window).load(function(){
		if(sd_platforms.last_opened){
			var $the_qty_item_in_the_row = jQuery('input[name="cart['+sd_platforms.last_opened+'][qty]"]');	
			var $the_row = $the_qty_item_in_the_row.closest('tr');
			$the_row.find('a.sharedisgrid').trigger('click');
		}
		
	});
	
	
	
	
		
	
	if(sd_platforms.sharedisc_platform_settings.sharedisc_facebook_app_id){
	
	/**
	 * @since 1.0.0
	*/
	window.fbAsyncInit = function() {
	    FB.init({
	      appId      : sd_platforms.sharedisc_platform_settings.sharedisc_facebook_app_id, 
	      status     : true,    
	      cookie     : true, 
	      xfbml      : true 
	    });
	    
	  };
	
	
	
	
	
	
	
	
	
	
		/**
		 * @since 1.0.0
		*/
	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "https://connect.facebook.net/en_US/all.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	
	
	}
	
	
	
	
	
	
	
	/**
	 * @since 1.0.0
	*/
	 window.twttr = (function (d,s,id) {
	  var t, js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return; js=d.createElement(s); js.id=id;
	  js.src="https://platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore(js, fjs);
	  return window.twttr || (t = { _e: [], ready: function(f){ t._e.push(f); } });
	}(document, "script", "twitter-wjs"));
	
	
	
	
	
	
	
	
	
	
	/**
	 * @since 1.0.0
	*/
	twttr.ready(function (twttr) {
	    twttr.events.bind('tweet', function(event){
	       if(event){
	       	       		
	       		var $sd_ID 					= event.target.dataset['productId'];
				var $sd_KEY 				= event.target.dataset['productKey'];
				var $sd_PATH 				= event.target.dataset['productUrl'];
				var $sd_title 				= event.target.dataset['title'];
				var $sd_url 				= event.target.dataset['shareurl'];
				var $sd_keyline 			= event.target.dataset['keyline'];
				var $sd_price 				= event.target.dataset['price'];
				var $sd_cart_key 			= event.target.dataset['sharedproductid'];
				var $sd_share_percentage 	= event.target.dataset['sharePercentage'];				
				var $sd_share_qty 			= jQuery('input[name="cart['+$sd_cart_key+'][qty]"]').val();
				var $sd_share_base 			= event.target.dataset['shareBase'];	
				
				jQuery('.woocommerce form').block({ message: 'Applying Twitter Discount', overlayCSS: { background: '#fff url(' + sd_platforms.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });
									       		
	       		sd_handle_twitter_share($sd_ID, $sd_KEY, $sd_PATH, $sd_title, $sd_keyline, $sd_url, $sd_price, $sd_cart_key, $sd_share_percentage, $sd_share_qty, $sd_share_base);
			} 
	    });
	});
	
	
	
	
	
	
	
	if(sd_platforms.sharedisc_platform_settings.sharedisc_linkedin_app_id){
	
		(function()
			{
				var e = document.createElement('script');
				e.type = 'text/javascript';
				e.async = false;
				e.src = 'http://platform.linkedin.com/in.js?async=true';
				e.onload = function(){
					IN.init({api_key: sd_platforms.sharedisc_platform_settings.sharedisc_linkedin_app_id, authorize: true, scope: 'w_share'});
				};			
		
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(e, s);
		})();
	}
		
	
	
  
  
 
  
  
  
  
 


	
	
	
	
	
	
	
	/**
	 * @since 1.0.0
	*/
   	jQuery('.share-link').live('click', function() {
		var $sd_ID = jQuery(this).attr('data-product-id');
		var $sd_KEY = jQuery(this).attr('data-product-key');
		var $sd_PATH = jQuery(this).attr('data-product-url');
		var $sd_title = jQuery(this).attr('data-title');
		var $sd_price = jQuery(this).attr('data-price');
		var $sd_image = jQuery(this).attr('data-image');
		var $sd_social = jQuery(this).attr('data-social');
		var $sd_url = jQuery(this).attr('data-shareurl');
		var $sd_keyline = jQuery(this).attr('data-keyline');
		var $sd_cart_key = jQuery(this).attr('data-sharedproductid');
		var $sd_share_percentage = jQuery(this).attr('data-share-percentage');				
		var $sd_share_qty = jQuery(this).closest('tr').prev().find('input[type="number"]').val();
		var $sd_share_base = jQuery(this).attr('data-share-base');
		
		
		switch($sd_social){
			case "facebook" :
				sd_handle_facebook_share($sd_image, $sd_ID, $sd_KEY, $sd_PATH, $sd_title, $sd_keyline, $sd_url, $sd_price, $sd_cart_key, $sd_share_percentage, $sd_share_qty, $sd_share_base);
			break;
			
			case "linkedin" :
				sd_handle_linkedin_share($sd_image, $sd_ID, $sd_KEY, $sd_PATH, $sd_title, $sd_keyline, $sd_url, $sd_price, $sd_cart_key, $sd_share_percentage, $sd_share_qty,$sd_share_base);
			break;
			
		}
	});
	
	
	
	
	
	/**
	 * @since 1.0.0
	*/
	function sd_handle_cart_totals(){
				
		//jQuery('input[name="update_cart"]').closest('form').submit();
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * @since 1.0.0
	*/
	function sd_handle_facebook_share($sd_image, $sd_ID, $sd_KEY, $sd_PATH, $sd_title, $sd_keyline, $sd_url, $sd_price, $sd_cart_key, $sd_share_percentage,$sd_share_qty, $sd_share_base){
		
		var data = {
					'action': 'sd_actions_log_share',
					'type': 'facebook',
					'product_id': $sd_ID,
					'product_key': $sd_KEY,
					'product_path': $sd_PATH,
					'product_title': $sd_title,
					'product_price': $sd_price,
					'cart_key': $sd_cart_key,
					'product_data': response.post_id,
					'share_percentage': $sd_share_percentage,
					'qty': $sd_share_qty,
					'base':$sd_share_base,
					'share_url' :  $sd_url					
				};
				
				console.log(data);
		
		
		/*
		
		FB.ui({
		  method: 'feed',
		  link: $sd_url,
		  caption: $sd_title,
		  description: $sd_keyline,
		  picture: $sd_image
		  
		}, function(response){
			
			if(response.post_id){
				
				jQuery('.woocommerce form').block({ message: 'Applying Facebook Discount', overlayCSS: { background: '#fff url(' + sd_platforms.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });
				
				var data = {
					'action': 'sd_actions_log_share',
					'type': 'facebook',
					'product_id': $sd_ID,
					'product_key': $sd_KEY,
					'product_path': $sd_PATH,
					'product_title': $sd_title,
					'product_price': $sd_price,
					'cart_key': $sd_cart_key,
					'product_data': response.post_id,
					'share_percentage': $sd_share_percentage,
					'qty': $sd_share_qty,
					'base':$sd_share_base,
					'share_url' :  $sd_url					
				};
				
				
				jQuery.post(sd_platforms.ajax_url, data, function(response){
					sd_handle_cart_totals();					
				});
			}
						
		});
		
		*/
		
	}
	
	
		function sd_handle_twitter_share($sd_ID, $sd_KEY, $sd_PATH, $sd_title, $sd_keyline, $sd_url, $sd_price, $sd_cart_key, $sd_share_percentage,$sd_share_qty, $sd_share_base){
		
			
			var data = {
				'action': 'sd_actions_log_share',
				'type': 'twitter',
				'product_id': $sd_ID,
				'product_key': $sd_KEY,
				'product_path': $sd_PATH,
				'product_title': $sd_title,
				'product_price': $sd_price,
				'cart_key': $sd_cart_key,
				'product_data': '-',
				'share_percentage': $sd_share_percentage,
				'qty': $sd_share_qty,
				'base':$sd_share_base 	 			,
				'share_url' :  $sd_url			
			};
			
			
			jQuery.post(sd_platforms.ajax_url, data, function(response){
				
				sd_handle_cart_totals();
				
			});
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * @since 1.0.0
	*/
	function sd_handle_linkedin_share($sd_image, $sd_ID, $sd_KEY, $sd_PATH, $sd_title, $sd_keyline, $sd_url, $sd_price, $sd_cart_key, $sd_share_percentage,$sd_share_qty, $sd_share_base){
				
		
		
		window.sd_linkedin.$sd_ID = $sd_ID;
		window.sd_linkedin.$sd_KEY = $sd_KEY;
		window.sd_linkedin.$sd_PATH = $sd_PATH;
		window.sd_linkedin.$sd_title = $sd_title;
		window.sd_linkedin.$sd_keyline = $sd_keyline;
		window.sd_linkedin.$sd_url = $sd_url;
		window.sd_linkedin.$sd_price = $sd_price;
		window.sd_linkedin.$sd_cart_key = $sd_cart_key;
		window.sd_linkedin.$sd_image = $sd_image;
		window.sd_linkedin.$sd_share_percentage = $sd_share_percentage;
		window.sd_linkedin.$sd_share_quantity = $sd_share_qty;
		window.sd_linkedin.$sd_share_base = $sd_share_base;
		window.sd_linkedin.$sd_url = $sd_url;
			
		
	   if(!IN.User.isAuthorized()){
	   	IN.User.authorize(sd_do_the_linkedin_share);
	   }else{
	    sd_do_the_linkedin_share();
	   }
		
	}
	
	function sd_do_the_linkedin_share(){

		if(IN.User.isAuthorized()){
		
		 IN.API.Raw("/people/~/shares")
		    .method("POST")
		    .body( JSON.stringify( {
		        "content": {
		          "submitted-url": window.sd_linkedin.$sd_url,
		          "title": window.sd_linkedin.$sd_title,
		          "description": window.sd_linkedin.$sd_keyline,
		          "submitted-image-url": window.sd_linkedin.$sd_image
		        },
		        "visibility": {
		          "code": "anyone"
		        }
		      })
		    )
		    .result(function(r) { 
		    	
		    jQuery('.woocommerce form').block({ message: 'Applying LinkedIn Discount', overlayCSS: { background: '#fff url(' + sd_platforms.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6 } });
		    	
		      var data = {
					'action': 'sd_actions_log_share',
					'type': 'linkedin',
					'product_id': window.sd_linkedin.$sd_ID,
					'product_key': window.sd_linkedin.$sd_KEY,
					'product_path': window.sd_linkedin.$sd_PATH,
					'product_title': window.sd_linkedin.$sd_title,
					'product_price': window.sd_linkedin.$sd_price,
					'cart_key': window.sd_linkedin.$sd_cart_key,
					'product_data': '-',
					'share_percentage': window.sd_linkedin.$sd_share_percentage,
					'qty': window.sd_linkedin.$sd_share_qty,
					'base':window.sd_linkedin.$sd_share_base	,
					'share_url' :  window.sd_linkedin.$sd_url	  				
				};
				
				
				jQuery.post(sd_platforms.ajax_url, data, function(response){
					
					sd_handle_cart_totals();
					
				});
				
				
		    });
		    
		    }	
	}
	
	
	
	
	


})( jQuery );
