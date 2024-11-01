<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://www.sharedisc.com
 * @since      1.0.0
 * @package    ShareDisc
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


delete_option('sharedisc_settings');
delete_option('sharedisc_message_settings');
delete_option('sharedisc_styling_settings');
delete_option('sharedisc_bitly_settings');
delete_option('sharedisc_platform_enabled');
delete_option('sharedisc_platform_settings');
