<?php


/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    ShareDisc
 * @subpackage ShareDisc/includes
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class ShareDisc_Deactivator {

	/**
	 * @since    1.0.0
	 */
	public static function deactivate() {
				
			
		delete_option('sharedisc_successfully_activated');
		
	}

}
