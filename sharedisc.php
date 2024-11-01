<?php

/**
 *
 * @link              http://www.sharedisc.com
 * @since             1.0.0
 * @package           ShareDisc
 *
 * @wordpress-plugin
 * Plugin Name:       ShareDisc
 * Plugin URI:        http://www.sharedisc.com
 * Description:       <strong>The FREE Social Media Sharing Plugin.</strong> Let your customers share their cart and earn discounts while marketing your product directly to their friends on Social Media.
 * Version:           1.0.5
 * Author:            kri8it
 * Author URI:        http://www.kri8it.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sharedisc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}






/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/woocommerce-session-helper.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-sharedisc.php';


/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-sharedisc-activator.php';


/** This action is documented in includes/class-plugin-name-activator.php */
register_activation_hook( __FILE__, array( 'ShareDisc_Activator', 'activate' ) );


/* initiate Sharedisc */
function run_ShareDisc() {
		
	if(class_exists('Woocommerce')):

		$plugin = new ShareDisc_Base();
		$plugin->run();
	
	else:
	
		 add_action( 'admin_notices', 'sd_woocommerce_required' ); 
	
	endif;

}

add_action('plugins_loaded', 'run_ShareDisc', 999);

function sd_woocommerce_required(){
	echo '<div class="notice"><p><strong>ShareDisc</strong> requires Woocommerce To Be Enabled</p></div>';
}

?>