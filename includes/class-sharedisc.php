<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 * @package    ShareDisc
 * @subpackage ShareDisc/includes
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class ShareDisc_Base {

	/**
	 * @since    1.0.0
	 */
	protected $loader;

	/**
	 * @since    1.0.0
	 */
	protected $plugin_name;

	/**
	 * @since    1.0.0
	 */
	protected $version;
	
		
	
	protected $credits;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'sharedisc';
		$this->version = '1.0.0';
		$this->credits = 0;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'platforms/class-sharedisc-sharing.php';	
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'platforms/sharedisc-platform-facebook.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'platforms/sharedisc-platform-twitter.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'platforms/sharedisc-platform-linkedin.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sharedisc-helper.php';		
				
		
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sharedisc-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sharedisc-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sharedisc-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sharedisc-public.php';

		$this->loader = new ShareDisc_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new ShareDisc_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_ShareDisc_API() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new ShareDisc_Admin( $this->get_plugin_ShareDisc_API(), $this->get_version() );
		
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'sd_plugin_sharedisc_admin_notice' );
		
		$this->loader->add_action( 'init', $plugin_admin, 'sd_plugin_sharedisc_update_share_qty' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'sd_plugin_admin_page' );
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'sd_plugin_dashboard_widget' );
						
		$this->loader->add_action( 'woocommerce_init', $plugin_admin, 'sd_plugin_log_for_conversion' );
		$this->loader->add_action( 'woocommerce_order_status_pending', $plugin_admin, 'sd_plugin_track_conversion_key' );
		$this->loader->add_action( 'woocommerce_order_status_on-hold', $plugin_admin, 'sd_plugin_track_conversion_key' );
		$this->loader->add_action( 'woocommerce_order_status_pending', $plugin_admin, 'sd_plugin_track_discount_amount' );
		$this->loader->add_action( 'woocommerce_order_status_on-hold', $plugin_admin, 'sd_plugin_track_discount_amount' );
		$this->loader->add_action( 'woocommerce_order_status_completed', $plugin_admin, 'sd_plugin_log_actual_conversion' );
		$this->loader->add_action( 'woocommerce_order_status_completed', $plugin_admin, 'sd_plugin_log_actual_discount' );
		
		$this->loader->add_action( 'woocommerce_product_write_panel_tabs', $plugin_admin, 'sd_plugin_options_tab' );
		$this->loader->add_action( 'woocommerce_product_write_panels', $plugin_admin, 'sd_plugin_tab_options' );
		$this->loader->add_action( 'woocommerce_product_write_panel_tabs', $plugin_admin, 'sd_plugin_shares_tab' );
		$this->loader->add_action( 'woocommerce_product_write_panels', $plugin_admin, 'sd_plugin_shares_tab_options' );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'sd_plugin_process_product_meta_sharedisc_tab' );
		
		
		$consumer_key = get_option('sd_consumer_key');
		if($consumer_key):
			$this->credits = $plugin_admin->sd_plugin_sharedisc_credit_count();
						
			if($this->credits > 0){
					
				$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_admin, 'sd_plugin_sharedisc_add_cart_discount' );
				$this->loader->add_action( 'wp_ajax_sd_actions_log_share', $plugin_admin, 'sd_plugin_sharedisc_log_share' );
				$this->loader->add_action( 'wp_ajax_nopriv_sd_actions_log_share', $plugin_admin, 'sd_plugin_sharedisc_log_share' );
				
			
			}
		endif;
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
				
		$sharedisc_messages = get_option('sharedisc_message_settings'); 
		if(isset($sharedisc_messages)){
			$sharedisc_messages = unserialize($sharedisc_messages);
		} 
		
		
		$sharedisc_settings = get_option('sharedisc_settings'); 
		if(isset($sharedisc_settings)){
			$sharedisc_settings = unserialize($sharedisc_settings);
		} 	
				

		$plugin_public = new ShareDisc_Public( $this->get_plugin_ShareDisc_API(), $this->get_version(), $this->credits );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			
		if($this->credits > 0){
				
			if($sharedisc_settings['sharing_type'] == 'product'){
				$this->loader->add_action( 'woocommerce_cart_item_subtotal', $plugin_public, 'sd_plugin_cart_item', 99, 3 );
			}else{
				$this->loader->add_action( 'woocommerce_after_cart_table', $plugin_public, 'sd_plugin_add_by_cart', 99);
				$this->loader->add_action( 'woocommerce_checkout_after_order_review', $plugin_public, 'sd_plugin_add_by_cart', 99);
			}	
				
			//$this->loader->add_action( 'wp_ajax_sd_display_share_grid', $plugin_public, 'sd_plugin_ajax_grid' );
			//$this->loader->add_action( 'wp_ajax_nopriv_sd_display_share_grid', $plugin_public, 'sd_plugin_ajax_grid' );	
		
			if($sharedisc_messages['are_archive_callouts_enabled'] == 'on'):	
				$this->loader->add_action('woocommerce_before_shop_loop', $plugin_public, 'sd_plugin_product_tag_loop', 25);
				$this->loader->add_action('woocommerce_before_shop_loop_item', $plugin_public, 'sd_plugin_product_tag_loop_item', 8);
			endif;
			
			if($sharedisc_messages['are_single_callouts_enabled'] == 'on'):	
				$this->loader->add_action('woocommerce_single_product_summary', $plugin_public, 'sd_plugin_product_single_info', 30);	
			endif;
		
		}	


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 */
	public function get_plugin_ShareDisc_API() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
