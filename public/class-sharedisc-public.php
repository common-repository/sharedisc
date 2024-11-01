<?php


/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    ShareDisc
 * @subpackage ShareDisc/public
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class ShareDisc_Public {

	/**
	 * @since    1.0.0
	 */
	private $plugin_name;

	/**
	 * @since    1.0.0
	 */
	private $version;

	/**
	 * @since    1.0.0
	 */	
	private $credits;	
	
	/**
	 * @since    1.0.0
	 */
	private $site_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $credits ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->credits = $credits;
		$this->site_url = 'http://www.sharedisc.com/';
		$this->button_url = '';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		 
		 
		

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sharedisc-public.css', array(), $this->version, 'all' );
		$styling = get_option('sharedisc_styling_settings');
		$styling = unserialize($styling);		
		$dir_theme = str_replace('/public/', '/themes/', plugin_dir_url( __FILE__ ));
		wp_enqueue_style( $styling['sd_css_theme'], $dir_theme . $styling['sd_css_theme'], array(), $this->version, 'all' );
		
		wp_enqueue_style( 'sd-tipster', plugin_dir_url( __FILE__ ) . 'css/tooltipster.css', array(), $this->version, 'all' );		
		wp_enqueue_style( 'sd-'.$styling['sd_popup_style'], plugin_dir_url( __FILE__ ) . 'css/'.$styling['sd_popup_style'].'.css', array(), $this->version, 'all' );
		
		$themefile = basename($styling['sd_css_theme'], '.css');		
		$this->button_url = $dir_theme.$themefile.'.png';
		
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		
		wp_enqueue_script( 'sd-tipster', plugin_dir_url( __FILE__ ) . 'js/jquery.tooltipster.min.js', array( 'jquery'), $this->version, false );
		
		if($this->credits > 0){
			
			$assets_path          = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/'; 
						
							
			$params = array(
				'ajax_url' => get_bloginfo('url').'/wp-admin/admin-ajax.php',
				'ajax_loader_url' => apply_filters( 'woocommerce_ajax_loader_url', $assets_path . 'images/ajax-loader@2x.gif' ),
				'share_credits' => $this->credits
			);
			
			
			$sharedisc_settings = get_option('sharedisc_settings');
			if(isset($sharedisc_settings)){
				$sharedisc_settings = unserialize($sharedisc_settings);
				$params['icon_location'] = $sharedisc_settings['product_icon_location'];
			} 
			
			$styling = get_option('sharedisc_styling_settings');
			if($styling){
				$styling = unserialize($styling);
				$params['popup_theme'] = $styling['sd_popup_style']; 
				$params['popup_animation'] = $styling['sd_popup_animation']; 
				$params['popup_trigger'] = $styling['sd_popup_trigger']; 
				$params['popup_location'] = $styling['sd_popup_location']; 
			}
			
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sharedisc-public.js', array( 'jquery', 'jquery-blockui' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'sd_params', $params );
			 
			 
			$params = array(
				'ajax_url' => get_bloginfo('url').'/wp-admin/admin-ajax.php',
				'sharedisc_platform_settings' => unserialize(get_option('sharedisc_platform_settings')),
				'ajax_loader_url' => apply_filters( 'woocommerce_ajax_loader_url', $assets_path . 'images/ajax-loader@2x.gif' ),
				'share_credits' => $this->credits
			);
			
			
			if(WooCommerce_Session_Helper::_isset('sd_last_opened')):			
				$params['last_opened'] = WooCommerce_Session_Helper::_get('sd_last_opened');
				WooCommerce_Session_Helper::_unset('sd_last_opened');
			endif;
			 
			 
			wp_enqueue_script( 'sd-platforms', plugin_dir_url( __FILE__ ) . 'js/sharedisc-platforms.js', array( 'jquery', 'jquery-blockui' ), $this->version, false );
			wp_localize_script( 'sd-platforms', 'sd_platforms', $params );
			
		
		}

	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_ajax_grid
	 *
	 * @since 1.0.0
	*/
	public function sd_plugin_ajax_grid(){
			
		$sharedisc_settings = get_option('sharedisc_settings');
		$sharedisc_settings = unserialize($sharedisc_settings);

		
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if($_POST['product'] === $cart_item_key){
						
				$_product      				= apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$_product_key				= $cart_item_key;
				$product_id    				= apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				$product_price 				= apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_price_subtotal 	= apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
				$objectToLike 				= $_product->get_permalink();	
								
			}	
			
		}
			
		
		ob_start();
		
		?>
		
			<?php if(isset($sharedisc_settings) && is_array($sharedisc_settings)): ?>	
			
			<table class="sd_grid_table">
			
			<?php SDHelper::product_platforms($product_id, $_product_key, $product_price_subtotal); ?>
			
			</table>
			
			<?php endif; ?>
		
		
		<?php
		
		echo ob_get_clean();
		die();
			
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_loader
	 *
	 * @since 1.0.0
	*/
	public function sd_plugin_loader() {
		global $product;
		
		$sharedisc_settings = get_option('sharedisc_settings');
		if(isset($sharedisc_settings)){
			$sharedisc_settings = unserialize($sharedisc_settings);
			$general_discount_threshold = $sharedisc_settings['general_discount_threshold'];
		} else {
			$general_discount_threshold = '0';
		}
		
		if($sharedisc_settings['are_sale_items_disabled'] == 'on'){
			$are_sale_items_disabled = true;
		}else{
			$are_sale_items_disabled = false;
		}
		
		$onsale= $product->is_on_sale();
				
		if(!$onsale || ($onsale && !$are_sale_items_disabled)):
	
			$product = new WC_Product( get_the_ID() );
			$price = $product->price; // 
			$discount = $general_discount_threshold;
			$discount_percent = $discount/100;
			$discount_total = round($price * $discount_percent, 2); 
			
			
			echo '<a id="sharedisc_popup_text" class="sharedisctextlink">Earn up to '.$discount.'% or '.get_woocommerce_currency_symbol().' '.$discount_total.' OFF with ShareDisc</a>';
			
		endif;
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_add_by_cart
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_add_by_cart(){
			
		$array_of_products_in_cart = array();					
		
		$expensive = 0;
		$the_key = '';
						
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					
			$_product = $cart_item['data'];
        	$product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();	
        	
        	if($product_price > $expensive){
        		$expensive = $product_price;
        		$the_key = $cart_item_key;
        	}
        	
        	//$array_of_products_in_cart[$cart_item_key] = $cart_item;
								
		}	
		
		//shuffle($array_of_products_in_cart);
		
		$the_cart = WC()->cart->get_cart();
		$cart_item_key = $the_key;
		$cart_item = $the_cart[$cart_item_key];
		
		//foreach($array_of_products_in_cart as $cart_item_key => $cart_item ){
			$_product      				= apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$_product_key				= $cart_item_key;
			$product_id    				= apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			$product_price 				= apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
			$product_price_subtotal 	= apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
			$objectToLike 				= $_product->get_permalink();	
			
			
			?>
			
			<div class="sd_share_cart_block">
				Share your Cart to earn discounts:
				<?php SDHelper::cart_platforms($product_id, $_product_key, $product_price_subtotal); ?>
				<div style="clear:both;"></div>
			</div>
			
			<?php
			
			
			
		//	break;
			
		//}
		
				
				
			
			
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_cart_item
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_cart_item( $subtotal, $cart_item, $cart_item_key ){		
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$sharedisc_messages = get_option('sharedisc_message_settings'); 
		if(isset($sharedisc_messages)){
			$sharedisc_messages = unserialize($sharedisc_messages);
		} 
		
		$sharedisc_settings = get_option('sharedisc_settings');
		
		if(isset($sharedisc_settings)){
			$sharedisc_settings = unserialize($sharedisc_settings);
		} 
		
		if($sharedisc_settings['are_sale_items_disabled'] == 'on'){
			$are_sale_items_disabled = true;
		}else{
			$are_sale_items_disabled = false;
		}
		
		$general_threshold = $sharedisc_settings['general_discount_threshold'];
		
		$onsale= $_product->is_on_sale();
				
		if(!$onsale || ($onsale && !$are_sale_items_disabled)):
			
			if(is_cart()){
					
				$product_id    				= apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
								
				ob_start();
				SDHelper::product_platforms($product_id, $cart_item_key, $subtotal);
				$the_icons = ob_get_clean(); 
				
				//$subtotal .= '<br/><a title="'.$sharedisc_messages['cart_btn_callout'].'" class=" sharedisctextlink sharediscgrid">CLICK HERE TO GET UP TO '.$general_threshold.'% OFF</a>';
				$subtotal .= $the_icons; 
			}
			
		endif;
			
		return $subtotal;
		
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_product_single_info
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_product_single_info(  ){
	
		$product = new WC_Product( get_the_ID() );
			
		$sharedisc_settings = get_option('sharedisc_settings');
		if(isset($sharedisc_settings)){
			$sharedisc_settings = unserialize($sharedisc_settings);
			$general_discount_threshold = $sharedisc_settings['general_discount_threshold'];
		} else {
			$general_discount_threshold = '0';
		}
		
		if($sharedisc_settings['are_sale_items_disabled'] == 'on'){
			$are_sale_items_disabled = true;
		}else{
			$are_sale_items_disabled = false;
		}
		
		$sharedisc_messages = get_option('sharedisc_message_settings'); 
		if(isset($sharedisc_messages)){
			$sharedisc_messages = unserialize($sharedisc_messages);
		} 
			
		
		$onsale= $product->is_on_sale();
		if(!$onsale || ($onsale && !$are_sale_items_disabled)):
	
			$product = new WC_Product( get_the_ID() );
			$price = $product->price; // 
			$discount = $general_discount_threshold;
			$discount_percent = $discount/100;
			$discount_total = round($price * $discount_percent, 2); 
			$after = $price-$discount_total;
			
			
			echo '<p class="sd_single_prod_info price"><img title="'.$sharedisc_messages['product_single_callout'].'" class="sd_tooltip" src="'.$this->button_url.'" />'.get_woocommerce_currency_symbol().$discount_total.' Discount Available! <del>'.get_woocommerce_currency_symbol().$price.'</del> <ins>'.get_woocommerce_currency_symbol().$after.'</ins></p><div style="clear:both;"></div>';
			
		endif;
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_product_tag_loop
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_product_tag_loop(  ){
		global $product;
		
		
		$sharedisc_messages = get_option('sharedisc_message_settings'); 
		if(isset($sharedisc_messages)){
			$sharedisc_messages = unserialize($sharedisc_messages);
		} 
		
		
		echo '<span id="sd_append_to_results" title="'.$sharedisc_messages['shop_info_callout'].'" class="sd_earn_callout sd_tooltip"><img src="'.$this->button_url.'" /> Earn Discounts</span>';
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_product_tag_loop_item
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_product_tag_loop_item(  ){
		global $product;
		
		$sharedisc_settings = get_option('sharedisc_settings');
		
		if(isset($sharedisc_settings)){
			$sharedisc_settings = unserialize($sharedisc_settings);
		} 
		
		if($sharedisc_settings['are_sale_items_disabled'] == 'on'){
			$are_sale_items_disabled = true;
		}else{
			$are_sale_items_disabled = false;
		}
		
		
		$sharedisc_messages = get_option('sharedisc_message_settings'); 
		if(isset($sharedisc_messages)){
			$sharedisc_messages = unserialize($sharedisc_messages);
		} 
		
		$onsale= $product->is_on_sale();	
		
		if(!$onsale || ($onsale && !$are_sale_items_disabled)):
			echo '<span title="'.$sharedisc_messages['product_loop_callout'].'" class="sd_earn_callout_loop sd_tooltip"><img src="'.$this->button_url.'" /></span>';
		endif;
		
	}
	

}
