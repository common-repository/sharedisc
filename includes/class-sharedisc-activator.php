<?php


/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    ShareDisc
 * @subpackage ShareDisc/includes
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class ShareDisc_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {
							
						
					
				
			
							
		/*
		* Check if update necessary and update main Sharedisc settings.
		*/
		if(get_option('sharedis_settings')):
			
			$sharedis_settings = get_option('sharedis_settings');		
			$sharedis_settings = maybe_unserialize($sharedis_settings);
						
			$sharedisc_settings = array();
			
			foreach($sharedis_settings as $K=>$V):
				$u_key = str_replace('sharedis_', 'sharedisc_', $K); 
				$u_val = str_replace('sharedis_', 'sharedisc_', $V); 
				$sharedisc_settings[$u_key] = $u_val;
			endforeach;
			
			delete_option('sharedis_settings');
		
		else:
		
			$sharedisc_settings = array(
				'general_keyline' => 'WOW.  Check this out! I shared and got discount!',
				'general_discount_threshold' => '10',
				'are_sale_items_disabled' => 'on',
				'product_icon_location' => 'right',
				'sharing_type' => 'product'
			);
		
		endif;	
					
		if(!get_option('sharedisc_settings')):
			update_option('sharedisc_settings', serialize($sharedisc_settings));
		endif;
							
						
					
				
			
							
		/*
		* Check if update necessary and update Sharedisc message settings.
		*/	
		if(get_option('sharedis_message_settings')):
		
			$sharedis_message_settings = get_option('sharedis_message_settings');		
			$sharedis_message_settings = maybe_unserialize($sharedis_message_settings);
						
			$sharedisc_message_settings = array();
			
			foreach($sharedis_message_settings as $K=>$V):
				$u_key = str_replace('sharedis_', 'sharedisc_', $K); 
				$u_val = str_replace('sharedis_', 'sharedisc_', $V); 
				$sharedisc_message_settings[$u_key] = $u_val;
			endforeach;
			
			delete_option('sharedis_message_settings');
		
		else:
		
			$sharedisc_message_settings = array(
				'are_archive_callouts_enabled' => 'on',
				'are_single_callouts_enabled' => 'on',
				'product_single_callout' => 'Look for this item in your cart to claim your discounts!',
				'product_loop_callout' => 'Share this product in your cart to earn discounts!',
				'shop_info_callout' => 'Share the products in your cart to earn discounts!'
			);		
			
		endif;		
		
		if(!get_option('sharedisc_message_settings')):
			update_option('sharedisc_message_settings', serialize($sharedisc_message_settings));
		endif;	
							
						
					
				
			
							
		/*
		* Check if update necessary and update Sharedisc styling settings.
		*/
		if(get_option('sharedis_styling_settings')):
		
			$sharedis_styling_settings = get_option('sharedis_styling_settings');		
			$sharedis_styling_settings = maybe_unserialize($sharedis_styling_settings);
						
			$sharedisc_styling_settings = array();
			
			foreach($sharedis_styling_settings as $K=>$V):
				$u_key = str_replace('sharedis_', 'sharedisc_', $K); 
				$u_val = str_replace('sharedis_', 'sharedisc_', $V); 
				$sharedisc_styling_settings[$u_key] = $u_val;
			endforeach;
			
			delete_option('sharedis_styling_settings');
		
		else:
		
			$sharedisc_styling_settings = array(		
				'sd_css_theme' => 'sd-theme-sharedisc-base.css',
				'sd_popup_style' => 'tooltipster-light',
				'sd_popup_animation' => 'grow',
				'sd_popup_location' => 'top',	
				'sd_popup_trigger' => 'hover'
			);
				
		endif;
		
		if(!get_option('sharedisc_styling_settings')):
			update_option('sharedisc_styling_settings', serialize($sharedisc_styling_settings));
		endif;	
							
						
					
				
			
							
		/*
		* Check if update necessary and update Sharedisc bitly settings.
		*/
		if(get_option('sharedis_bitly_settings')):
		
			$sharedis_bitly_settings = get_option('sharedis_bitly_settings');		
			$sharedis_bitly_settings = maybe_unserialize($sharedis_bitly_settings);
						
			$sharedisc_bitly_settings = array();
			
			foreach($sharedis_bitly_settings as $K=>$V):
				$u_key = str_replace('sharedis_', 'sharedisc_', $K); 
				$u_val = str_replace('sharedis_', 'sharedisc_', $V); 
				$sharedisc_bitly_settings[$u_key] = $u_val;
			endforeach;
			
			delete_option('sharedis_bitly_settings');
		
		else:
		
			$sharedisc_bitly_settings = array(
				'is_bitly_enabled' => 'on'
			);
		
		endif;
		
		if(!get_option('sharedisc_bitly_settings')):
			update_option('sharedisc_bitly_settings', serialize($sharedisc_bitly_settings));
		endif;	
							
						
					
				
			
							
		/*
		* Check if update necessary and update main Platform settings.
		*/	
		if(get_option('sharedis_platform_enabled')):
		
			$sharedis_platform_enabled = get_option('sharedis_platform_enabled');		
			$sharedis_platform_enabled = maybe_unserialize($sharedis_platform_enabled);
						
			$sharedisc_platform_enabled = array();
			
			foreach($sharedis_platform_enabled as $K=>$V):
				$u_key = str_replace('sharedis_', 'sharedisc_', $K); 
				$u_val = str_replace('sharedis_', 'sharedisc_', $V); 
				$sharedisc_platform_enabled[$u_key] = $u_val;
			endforeach;
			
			delete_option('sharedis_platform_enabled');
			
			if(!get_option('sharedisc_platform_enabled')):
				update_option('sharedisc_platform_enabled', serialize($sharedisc_platform_enabled));
			endif;	
					
		endif;
							
						
					
				
			
							
		/*
		* Check if update necessary and update main Platform settings.
		*/	
		if(get_option('sharedis_platform_settings')):
		
			$sharedis_platform_settings = get_option('sharedis_platform_settings');		
			$sharedis_platform_settings = maybe_unserialize($sharedis_platform_settings);
						
			$sharedisc_platform_settings = array();
			
			foreach($sharedis_platform_settings as $K=>$V):
				$u_key = str_replace('sharedis_', 'sharedisc_', $K); 
				$u_val = str_replace('sharedis_', 'sharedisc_', $V); 
				$sharedisc_platform_settings[$u_key] = $u_val;
			endforeach;
			
			delete_option('sharedis_platform_settings');
			
			if(!get_option('sharedisc_platform_settings')):
				update_option('sharedisc_platform_settings', serialize($sharedisc_platform_settings));
			endif;	
			
		endif;
							
		
	}

}
