<?php


/**
 *
 * Base Class for Sharing Platform Functionality
 *
 * @since      1.0.0
 * @package    ShareDisc
 * @subpackage ShareDisc/platforms
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class ShareDisc_Sharing_Platform{
	var $platform_name;
	var $platform_title;
	var $platform_version;
	var $platform_options;
	var $platform_enabled;
	var $enabled;
	var $shop_name;
	var $shop_url;		
	var $options;
	var $share_urls;
	var $share_url_short;
	var $share_url_long;
	var $share_key;
	var $currency;
	var $cart_key;
	var $placeholder;
	var $product_id;
	var $product_permalink;
	var $product_title;
	var $product_image;
	var $product_enabled;
	var $product_keyline;
	var $product_discount;	
	var $product_base_price;
	var $product_base_after;
	var $product_before_price;
	var $product_after_price;	
	var $product_shared;	
	var $product_can_share;
	var $discount_earned;
	var $shared_types;
	var $shared_carts;
	var $total_shared;
	
	
	
	
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @since 1.0.0
	*/
	public function __construct() {
		$this->shop_name = get_bloginfo('name');
		$this->shop_url = get_bloginfo('url');
		$this->options = unserialize(get_option('sharedisc_settings'));
		$this->currency = get_woocommerce_currency_symbol();
		$this->placeholder = 'WOW.  Check this out! I shared this product and got discount!';
		$this->platform_options = unserialize(get_option('sharedisc_platform_settings'));
		$this->product_can_share = true;
		$this->platform_enabled = unserialize(get_option('sharedisc_platform_enabled'));
		
		$this->total_shared = 0;
		
		if($this->platform_enabled['is_'.$this->platform_name.'_enabled'] == 'on'):
			$this->enabled = true;
		else:
			$this->enabled = false;
		endif;
	}
	
	
	
	
	
	
	
	
	
	/**
	 * get_var
	 *
	 * @since 1.0.0
	*/
	public function get_var($var) {
		return $this->$var;
	}
	
	
	
	
	
	
	
	
	
	/**
	 * echo_var
	 *
	 * @since 1.0.0
	*/
	public function echo_var($var) {
		echo $this->$var;
	}
	
	
	
	
	
	
	
	
	
	/**
	 * get_icon
	 *
	 * @since 1.0.0
	*/
	public function get_icon() {
		return '<span class="socialicon '.$this->platform_name.'"></span>';
	}
		
	
	
	
	
	
	
	
	
	
	
	/**
	 * set_product_values
	 *
	 * @since 1.0.0
	*/
	public function set_product_values($prod_id, $cart_key, $price, $sd_type){
		global $woocommerce;
		
		$session_var = 'sd_prod_percentage_'.$cart_key;
		
		$this->product_shared = 0;	
		$this->discount_earned = 0;	
		
		
		if($sd_type == 'product'){
		
			if(WooCommerce_Session_Helper::_isset('sd_prods_shared')){
				$array_of_shared_prods = unserialize(WooCommerce_Session_Helper::_get('sd_prods_shared'));
				
				foreach ($array_of_shared_prods as $KEY=>$SHARES):
				
					foreach($SHARES as $DATA):
					
						if($DATA['key'] == $cart_key){
							$this->shared_types[] = $DATA['type'];
							if($this->platform_name == $DATA['type']){
								$this->product_shared = intval($DATA['percent']);		
								$this->discount_earned = floatval(floatval($DATA['base']) * floatval($DATA['qty']));
							}
							$this->total_shared += intval($DATA['percent']);
						}
						
						
					endforeach;
					
				endforeach;
				
			}
			
			if($this->total_shared >= intval($this->options['general_discount_threshold'])){
				$this->product_can_share = false;
			}	
		
		}else{
				
			if(WooCommerce_Session_Helper::_isset('sd_cart_shared')){
				$array_of_shared_platforms = unserialize(WooCommerce_Session_Helper::_get('sd_cart_shared'));
				
				
				$this->shared_carts = array_keys($array_of_shared_platforms);
				
				if(isset($array_of_shared_platforms[$this->platform_name])){
					$this->total_shared += $this->options['general_discount_'.$this->platform_name];
					$this->product_can_share = false;	
				}
			}
			
			
				
			if($this->total_shared >= intval($this->options['general_discount_threshold'])){
				$this->product_can_share = false;		
			}
				
			
		}
				
	
		
		
		
		
		
		
		
		
		
				
						
		$this->share_key = 	SDHelper::create_log_key();
		
		$this->share_urls = SDHelper::create_share_url($this->share_key);
		$this->share_url_short = trim($this->share_urls);
		$this->share_url_long = trim($this->share_urls);
				
		$this->cart_key = $cart_key;	
				
		$this->product_id = $prod_id;	
		$the_product = new WC_Product($this->product_id);		
		$this->product_permalink = get_permalink($this->product_id);	
		$this->product_title = get_the_title($this->product_id);	
		$this->product_before_price = str_replace($this->currency, '', str_replace('<span class="amount">', '', str_replace('</span>', '', $price)));
		$this->product_base_price = str_replace($this->currency, '', str_replace('<span class="amount">', '', str_replace('</span>', '', $the_product->get_price())));
		
		$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $prod_id ), '' );
		$this->product_image = $featured_image[0];
		
		
		
		
		
		
		
		
		if($sd_type == 'product'){
		
		
		
			$this->product_enabled = (get_post_meta($prod_id, 'sharedisc_product_enabled', true)  === 'no') ? false : true;	
				
			if($this->product_enabled){
				
				$prod_discount = str_replace('%', '', get_post_meta($prod_id, 'sharedisc_product_discount_'.$this->platform_name, true));
				$prod_keyline = get_post_meta($prod_id, 'sharedisc_product_keyline_'.$this->platform_name, true)	;		
				
				if($prod_discount != ''):
					$this->product_discount = $prod_discount;
				else:
					$this->product_discount = $this->options['general_discount_'.$this->platform_name];
				endif;
				
				if($prod_keyline != ''):
					$this->product_keyline = $prod_keyline;	
				else:
					$has_platform_line = false;
					$has_generic_line = false;
					
					if(isset($this->options['general_keyline_'.$this->platform_name]) && $this->options['general_keyline_'.$this->platform_name] != ''):
						$has_platform_line = true;
					endif;
					
					if(isset($this->options['general_keyline']) && $this->options['general_keyline'] != ''):
						$has_generic_line = true;
					endif;
					
					if($has_platform_line):
						$this->product_keyline 		= $this->options['general_keyline_'.$this->platform_name];		
					elseif($has_generic_line):
						$this->product_keyline 		= $this->options['general_keyline'];
					else:
						$this->product_keyline 		= $this->placeholder;	
					endif;
				endif;
							
				
			}else{
				
				$this->product_discount 	= $this->options['general_discount_'.$this->platform_name];
				
				$has_platform_line = false;
				$has_generic_line = false;
				
				if(isset($this->options['general_keyline_'.$this->platform_name]) && $this->options['general_keyline_'.$this->platform_name] != ''):
					$has_platform_line = true;
				endif;
				
				if(isset($this->options['general_keyline']) && $this->options['general_keyline'] != ''):
					$has_generic_line = true;
				endif;
				
				if($has_platform_line):
					$this->product_keyline 		= $this->options['general_keyline_'.$this->platform_name];		
				elseif($has_generic_line):
					$this->product_keyline 		= $this->options['general_keyline'];
				else:
					$this->product_keyline 		= $this->placeholder;	
				endif;
							
				
			}	
				
		}else{
				
			
			$this->product_keyline 			= $this->options['general_keyline_'.$this->platform_name];
			$this->product_discount 		= $this->options['general_discount_'.$this->platform_name];
			$this->product_before_price 	= number_format(WC()->cart->total, 2);	
			$this->product_base_price 		= number_format(WC()->cart->total, 2);	
			
			
			
		}
		
		if(!$this->product_discount){
			$this->product_discount = $this->options['general_discount_threshold'];
		}
		
		$this->product_after_price = $this->product_before_price*($this->product_discount/100);
		$this->product_base_after = $this->product_base_price*($this->product_discount/100);
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * enabled_option
	 *
	 * @since 1.0.0
	*/
	public function enabled_option(){		
		?>
		
		<p>			
			<div class="socialicon sd_<?= $this->platform_name; ?>"></div>
			
			<?php if($this->enabled): ?>
				<input type="checkbox" checked="checked" name="is_<?= $this->platform_name; ?>_enabled" > Enable <?php echo $this->platform_title; ?>			
			<?php else: ?>
				<input type="checkbox" name="is_<?= $this->platform_name; ?>_enabled" > Enable <?php echo $this->platform_title; ?>			
			<?php endif; ?>
				
		</p>
		
		<?php
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * admin_option
	 *
	 * @since 1.0.0
	*/
	public function admin_option(){
		
		if(!isset($this->options['general_keyline_'.$this->platform_name]) || $this->options['general_keyline_'.$this->platform_name] == ''){
			$this->options['general_keyline_'.$this->platform_name] = $this->options['general_keyline'];
		}
		
		if(!isset($this->options['general_discount_'.$this->platform_name]) || $this->options['general_discount_'.$this->platform_name] == ''){
			$this->options['general_discount_'.$this->platform_name] = $this->options['general_discount_threshold'];
		}
		
			
				
		?>
		
		<section class="admin_section">	
		<h3 style="color:<?php SDHelper::platform_colour($this->platform_name); ?>"><div class="socialicon sd_<?= $this->platform_name; ?>"></div> <?= $this->platform_title; ?></h3>
		<p>
			
			<input class="textfield percentage" type="text" name="general_discount_<?= $this->platform_name; ?>"  value="<?php echo $this->options['general_discount_'.$this->platform_name];?>"> % Discount you want to give away per share		
		</p>
		<p>
			If you would like a different keyline/slogan, for this network, type it in here:<br/>
			<textarea class="textareas" name="general_keyline_<?= $this->platform_name; ?>" ><?=$this->options['general_keyline_'.$this->platform_name];?></textarea>	
		</p>
		
		</section>
		<?php
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * product_option
	 *
	 * @since 1.0.0
	*/
	public function product_option(){
				
		?>
		
		<div class="options_group">
			<? woocommerce_wp_text_input( array( 
			'id' => 'sharedisc_product_discount_'.$this->platform_name, 
			'class' => 'wc_input_decimal short', 
			'label' => __('<span class="socialicon '.$this->platform_name.'"></span>'.$this->platform_title.' Discount', 'woothemes'),
			'placeholder'=> __('% per share', 'woothemes')
			 )); ?>
			<? woocommerce_wp_textarea_input( array( 
			'id' => 'sharedisc_product_keyline_'.$this->platform_name, 
			'class' => 'sharedisc_social_option', 
			'label' => __('Share message', 'woothemes'), 
			'placeholder' => $this->placeholder
			)); ?>
		</div>
		
		<?php
	}
	
	
	
}




?>