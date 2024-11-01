<?php

/**
 * General Helper Class
 *
 * This class defines all helper functions for ShareDisc
 *
 * @since      1.0.0
 * @package    ShareDisc
 * @subpackage ShareDisc/includes
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class SDHelper{
					
				
			
		
	
	/**
	 * Base Share Key
	 *
	 * @since 1.0.0
	*/					
	public static $plugin_url = 'http://www.sharedisc.com/wc-api/shared_url/?key=';			
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * create_log_key
	 *
	 * @since 1.0.0
	*/		
	public static function create_log_key(){
		$share_key = hash( 'md5', date( 'U' ) . mt_rand() );
		return $share_key;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * available_themes
	 *
	 * @since 1.0.0
	*/		
	public static function available_themes(){
		$dir = str_replace('/includes/', '/themes/', plugin_dir_path( __FILE__ ));
		
		$available_themes = array();
		
		foreach ( glob( $dir.'*.css' ) as $file ) {
			$available_themes[basename($file)] = ucfirst(str_replace('-', ' ', str_replace('sd-theme-', '', basename($file, '.css')))); 	
		}	
		
		return $available_themes;
		
	}
	
	
	
	
	
	
	
	
	
	/**
	 * product_discount_on_offer
	 *
	 * @since 1.0.0
	*/		
	public static function product_discount_on_offer($prod_id, $cart_key, $price){
		$SD_PLATFORMS = self::locate_platforms();
		$percentage = 0;
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
						
				$PLATFORM = new $CLASS;
				if($PLATFORM->enabled):
					$PLATFORM->set_product_values($prod_id, $cart_key, $price);
					$percentage += intval($PLATFORM->product_discount);	
					
				endif;
				
			endforeach;
		endif;	
		
		echo $percentage;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * product_discount_avail
	 *
	 * @since 1.0.0
	*/		
	public static function product_discount_avail($prod_id, $cart_key, $price){
		$SD_PLATFORMS = self::locate_platforms();
		$percentage = 0;
		$earned = 0;
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
						
				$PLATFORM = new $CLASS;
				if($PLATFORM->enabled):
					$PLATFORM->set_product_values($prod_id, $cart_key, $price);
					$percentage += intval($PLATFORM->product_discount);	
					$earned += intval($PLATFORM->product_shared);	
					
									endif;
				
			endforeach;
		endif;	
		
		echo intval($percentage) - intval($earned);
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * create_share_url
	 *
	 * @since 1.0.0
	*/
	public static function create_share_url($key){
		$longurl = self::$plugin_url.$key;
		
		
		
		$bitly_settings = get_option('sharedisc_bitly_settings');
		if(isset($bitly_settings)){
			$bitly_settings = unserialize($bitly_settings);
		} 
		
		if($bitly_settings['is_bitly_enabled'] == 'on'){
				
			if($bitly_settings['sharedisc_bitly_login'] && $bitly_settings['sharedisc_bitly_key']):
				$connectURL = 'http://api.bit.ly/v3/shorten?login='.$bitly_settings['sharedisc_bitly_login'].'&apiKey='.$bitly_settings['sharedisc_bitly_key'].'&uri='.urlencode($longurl).'&format=txt';
			else:
				$connectURL = 'http://api.bit.ly/v3/shorten?login=o_114jqkbob&apiKey=R_01c52b641ac84692b163354177721630&uri='.urlencode($longurl).'&format=txt';
			endif;
			

			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch,CURLOPT_URL,$connectURL);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
			$data = curl_exec($ch);
			
			return $data;
			
		}else{
			
			return $longurl;
			
		}	
		
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * locate_platforms
	 *
	 * @since 1.0.0
	*/		
	public static function locate_platforms(){
		$classes = array();

		foreach( get_declared_classes() as $class ) {
		    if ( is_subclass_of($class, 'ShareDisc_Sharing_Platform') ){
		        array_push($classes, $class);
		    }
		}	
		
		return $classes;
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * platform_icons
	 *
	 * @since 1.0.0
	*/		
	public static function platform_icons(){
		$SD_PLATFORMS = self::locate_platforms();
		
		
		
		if(count($SD_PLATFORMS) > 0):
			
			$return = '';
		
			foreach($SD_PLATFORMS as $CLASS):
			
				$PLATFORM = new $CLASS;
				$return .= $PLATFORM->get_icon();
				
			endforeach;
			
			return $return;
			
		endif;
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * enable_platforms
	 *
	 * @since 1.0.0
	*/
	public static function enable_platforms(){
		$SD_PLATFORMS = self::locate_platforms();
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
			
				$PLATFORM = new $CLASS;
				$PLATFORM->enabled_option();
				
			endforeach;
		endif;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * admin_platforms
	 *
	 * @since 1.0.0
	*/
	public static function admin_platforms(){
		$SD_PLATFORMS = self::locate_platforms();
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
			
				$PLATFORM = new $CLASS;
				if($PLATFORM->enabled):
					$PLATFORM->admin_option();
				endif;
				
			endforeach;
		endif;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * product_platforms
	 *
	 * @since 1.0.0
	*/
	public static function product_platforms($prod_id, $cart_key, $price){
		$SD_PLATFORMS = self::locate_platforms();
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
			
				$PLATFORM = new $CLASS;
				if($PLATFORM->enabled):
					$PLATFORM->per_product_option($prod_id, $cart_key, $price);
				endif;
				
			endforeach;
		endif;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * product_platforms
	 *
	 * @since 1.0.0
	*/
	public static function cart_platforms($prod_id, $cart_key, $price){
		$SD_PLATFORMS = self::locate_platforms();
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
			
				$PLATFORM = new $CLASS;
				if($PLATFORM->enabled):
					$PLATFORM->per_cart_option($prod_id, $cart_key, $price);
				endif;
				
			endforeach;
		endif;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * product_platforms
	 *
	 * @since 1.0.0
	*/
	public static function show_cart_platforms(){
		$SD_PLATFORMS = self::locate_platforms();
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
			
				$PLATFORM = new $CLASS;
				if($PLATFORM->enabled):
					$PLATFORM->per_cart_option($prod_id, $cart_key, $price);
				endif;
				
			endforeach;
		endif;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * public_platforms
	 *
	 * @since 1.0.0
	*/
	public static function public_platforms($prod_id, $cart_key, $price){
		$SD_PLATFORMS = self::locate_platforms();
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
						
				$PLATFORM = new $CLASS;
				if($PLATFORM->enabled):
					$PLATFORM->public_option($prod_id, $cart_key, $price);
				endif;
				
			endforeach;
		endif;	
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * has_network_settings
	 *
	 * @since 1.0.0
	*/
	public static function has_network_settings(){
		$SD_PLATFORMS = self::locate_platforms();
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
			
				$PLATFORM = new $CLASS;
				
				if(method_exists($PLATFORM, 'network_setting')):
					if($PLATFORM->enabled):
						return true;
					endif;
				endif;
				
			endforeach;
		endif;	
		
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * network_settings
	 *
	 * @since 1.0.0
	*/
	public static function network_settings(){
		$SD_PLATFORMS = self::locate_platforms();
		
		if(count($SD_PLATFORMS) > 0):
			foreach($SD_PLATFORMS as $CLASS):
			
				$PLATFORM = new $CLASS;
				
				if(method_exists($PLATFORM, 'network_setting')):
					if($PLATFORM->enabled):
						$PLATFORM->network_setting();
					endif;
				endif;
				
			endforeach;
		endif;	
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * platform_colour
	 *
	 * @since 1.0.0
	*/
	public static function platform_colour($platform){
			
		switch($platform):
			case "facebook":
				echo "#385591";
				break;
			case "twitter":
				echo "#48a2d7";
				break;
			case "googleplus":
				echo "#e74b32";
				break;
			case "linkedin":
				echo "#0076a8";
				break;
		endswitch;
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * random_colour
	 *
	 * @since 1.0.0
	*/
	public static function random_colour($platform){
			
		$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    	$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
		echo $color;
	}
		
	
}



?>