<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    ShareDisc
 * @subpackage ShareDisc/admin
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class ShareDisc_Admin {

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
	private $site_url;
		
	/**
	 * @since    1.0.0
	 */
	private $api_url;

	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->site_url = 'http://www.sharedisc.com/';
		$this->api_url = 'http://www.sharedisc.com/wc-api/';

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sharedisc-admin.css', array(), $this->version, 'all' );
			
		wp_enqueue_style( 'sd-tipster', plugins_url() . '/sharedisc/public/css/tooltipster.css', array(), $this->version, 'all' );
		

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'sd-tipster', plugins_url() . '/sharedisc/public/js/jquery.tooltipster.min.js', array( 'jquery'), $this->version, false ); 
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sharedisc-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_admin_notice
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_admin_notice(){
			
		 if (is_plugin_active('sharedisc/sharedisc.php') && !get_option('sharedisc_successfully_activated')) {
	        echo "<div class='sharedisc_activated'>Sharedisc has successfully been activated! Click <a href='".admin_url( 'admin.php?page=sharedisc_admin_page' )."'>HERE</a> to set it up and start sharing!</div>";
	        update_option('sharedisc_successfully_activated', 'true');
	    }	
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_dashboard_widget
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_dashboard_widget(){
		
		wp_add_dashboard_widget('sharedisc_api_widget_main', 'ShareDisc', array(&$this,'sd_plugin_widget_display' ));		
		
		$consumer_key = get_option('sd_consumer_key');
		if($consumer_key):				
			wp_add_dashboard_widget('sharedisc_api_widget_shares', 'Latest Product Shares', array(&$this,'sd_plugin_widget_share_display' ));	
			wp_add_dashboard_widget('sharedisc_api_widget_stats', 'ShareDisc Stats', array(&$this,'sd_plugin_widget_stats_display' ));
		
		endif;	
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_widget_display
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_widget_display(){
		$consumer_key = get_option('sd_consumer_key');
		if($consumer_key):
			$share_credits = $this->sd_plugin_sharedisc_credit_count();
			echo '<p>You have <strong>'.$share_credits.'</strong> share credits available</p>';
			echo '<p><a href="" class="sd_admin_linker_input">Buy More Credits</a></p>';
		else:
			echo '<p>Register now and start sharing!</p>';
			echo '<a target="_blank" href="'.$this->site_url.'my-account/" class="sd_admin_linker_input">Sign Me Up!</a>';
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_widget_share_display
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_widget_share_display(){
		$consumer_key = get_option('sd_consumer_key');
		if($consumer_key):			
			$share_latest = $this->sd_plugin_sharedisc_latest_shares();
			
			if($share_latest):
				?>
				
				<table class="widefat fixed" cellspacing="0">
				    <thead>
				    <tr>
				
				         <th style="width:40%;" id="col-date" class="manage-date column-date " scope="col">Date</th>
				         <th style="width:50%;"  id="col-product-name" class="manage-product-name column-product-name" scope="col">Product</th>
				         <th style="width:10%;"  id="col-share-type" class="manage-share-type column-share-type " scope="col">Type</th>
				
				    </tr>
				    </thead>
				    
				    <tbody>
				    	
				    	<?php foreach($share_latest as $S): ?>
				    		<tr>
					
					            <td><?php echo date('Y-m-d', strtotime($S->date)); ?></th>
					            <td><?php edit_post_link( get_the_title($S->product_id), NULL, NULL, $S->product_id ); ?> </th>
					            <td><div class="socialicon sd_<?php echo $S->share_type; ?>"></div></th>
					
					    </tr>
				    	<?php endforeach; ?>			    
				    	
				    </tbody>
				    
				  </table>
				  <a href="admin.php?page=sharedisc_admin_shares" class="sd_admin_linker_input">View All</a>
				<?php
			else:
				echo '<p>You do not have any shares yet.</p>';
			endif;			
			
		else:
			echo '<p>Register now and start sharing!</p>';
			
			echo '<a target="_blank" href="'.$this->site_url.'my-account/" class="sd_admin_linker_input">Sign Me Up!</a>';
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_widget_stats_display
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_widget_stats_display(){
		$shares = $this->sd_plugin_sharedisc_request_shares();	
		
		$total_shares_used = 0;
		$total_conversions = 0;
		$total_products = 0;
		$total_value = 0;
		$total_clicks = 0;
		$total_discount = 0;
		$share_return = 0;	    	
		
		if($shares):
			foreach($shares as $S): 
				$the_orders = unserialize($S->order_ids); 
				
				$total_conversions+= intval($S->conversions_gen);
				$total_clicks+= intval($S->clicks);
				$total_discount += floatval($S->discount); 
				$total_shares_used+= 1;
				
				if($the_orders):
					foreach($the_orders as $_O => $V):
									
						$this_order = new WC_Order($_O);
					
						$total_products += $this_order->get_item_count();
						$total_value += $this_order->get_total();
					
					endforeach;
				endif;
				
			endforeach;
		endif;
		
		$share_return = floatval($total_value) - floatval($total_discount);
		
		if($total_conversions != 0):
			$average_conversion_sale_amount 	= $total_value/$total_conversions;
			if(!$average_conversion_sale_amount): $average_conversion_sale_amount = 0; endif;
		else:
			$average_conversion_sale_amount = 0;
		endif;
		
		if($total_conversions != 0):
			$average_products_in_conversion 	= $total_products/$total_conversions;
			if(!$average_products_in_conversion): $average_products_in_conversion = 0; endif;
		else:
			$average_products_in_conversion = 0;
		endif;
		
		if($total_conversions != 0):
			$average_link_clicks_conversion 	= $total_clicks/$total_conversions;
			if(!$average_link_clicks_conversion): $average_link_clicks_conversion = 0; endif;
		else:
			$average_link_clicks_conversion = 0;
		endif;
		
		if($total_conversions != 0):
			$average_share_spend_conversion 	= $total_shares_used/$total_conversions;
			if(!$average_share_spend_conversion): $average_share_spend_conversion = 0; endif;
		else:
			$average_share_spend_conversion = 0;
		endif;
		
		if($total_shares_used != 0):
			$share_return_value 	= $share_return_value/$total_shares_used;
			if(!$share_return_value): $share_return_value = 0; endif;
		else:
			$share_return_value = 0;
		endif;
		
		?>
		
		
		
		<div id="sharedisc_admin_page">
			<div class="sharedisc_stats_section">					
				<div class="sharedisc_stats_item">Each conversion on average results in a return of <strong><?php echo get_woocommerce_currency_symbol().number_format($average_conversion_sale_amount, 2, '.', ' '); ?></strong>.</div>
				<div class="sharedisc_stats_item">There is an average of <strong><?php echo $average_products_in_conversion; ?></strong> product(s) in each conversion.</div>
				<div class="sharedisc_stats_item">A conversion happens on average every <strong><?php echo $average_link_clicks_conversion; ?></strong> times a share is clicked</div>
				<div class="sharedisc_stats_item"><strong><?php echo $average_share_spend_conversion; ?></strong> share(s) are used per conversion.</div>
				<div class="sharedisc_stats_item"><strong><?php echo get_woocommerce_currency_symbol().number_format($share_return_value, 2, '.', ' '); ?></strong> return per share.</div>				
			</div>			
		</div>
		<a href="admin.php?page=sharedisc_admin_stats" class="sd_admin_linker_input">View More Stats</a>
		
		
		
		<?php
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_admin_page
	 *
	 * @since 1.0.0
	*/
	public function sd_plugin_admin_page(){
						
					
				
			
		
		$consumer_key = get_option('sd_consumer_key');
		add_menu_page('ShareDisc','ShareDisc','delete_users', 'sharedisc_admin_page', array(&$this, 'sd_plugin_sharedisc_admin_page'), plugin_dir_url( __FILE__ ).'/images/sd_button.png'); 
		
		
		if($consumer_key):	
			add_submenu_page( 'sharedisc_admin_page', 'ShareDisc - Messages', 'Messages', 'delete_users', 'sharedisc_admin_messages', array(&$this, 'sd_plugin_sharedisc_admin_messages_page') );
			add_submenu_page( 'sharedisc_admin_page', 'ShareDisc - Shares', 'Shares', 'delete_users', 'sharedisc_admin_shares', array(&$this, 'sd_plugin_sharedisc_admin_shares_page') );
			add_submenu_page( 'sharedisc_admin_page', 'ShareDisc - Statistics', 'Statistics', 'delete_users', 'sharedisc_admin_stats', array(&$this, 'sd_plugin_sharedisc_admin_stats_page') );
			add_submenu_page( 'sharedisc_admin_page', 'ShareDisc - Styling', 'Styling', 'delete_users', 'sharedisc_admin_styling', array(&$this, 'sd_plugin_sharedisc_admin_styling_page') );
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_admin_page
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_admin_page(){
		global $current_user;
		$shopname = get_bloginfo('name');
		$shopurl = get_bloginfo('url');
		
		if(isset($_POST['sharedisc_save'])){
			$this->sd_plugin_sharedisc_save($_POST);
		}
		
		if(isset($_POST['sharedisc_api'])){
			$return = $this->sd_plugin_sharedisc_account($_POST);	
			if($return != 'activated'){
				$activation_error = $return;	
			}
		}
		
		if(isset($_POST['sharedisc_enabled'])){
			$this->sd_plugin_sharedisc_enabled($_POST);
		}
		
		if(isset($_POST['sharedisc_network'])){
			$this->sd_plugin_sharedisc_network($_POST);
		}		
		
		if(isset($_POST['sharedisc_bitly'])){
			$this->sd_plugin_sharedisc_bitly($_POST);
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
		
		$bitly_settings = get_option('sharedisc_bitly_settings');
		if(isset($bitly_settings)){
			$bitly_settings = unserialize($bitly_settings);
		} 
		
		if($bitly_settings['is_bitly_enabled'] == 'on'){
			$is_bitly_enabled = true;
		}else{
			$is_bitly_enabled = false;
		}
						
		
		$consumer_key = get_option('sd_consumer_key');
		$consumer_email =  get_option('sd_consumer_email');
		
		if($consumer_key):
			$share_credits = $this->sd_plugin_sharedisc_credit_count();
			$share_breakdown = $this->sd_plugin_sharedisc_credit_breakdown();			
			@ksort($share_breakdown);
		endif;
		
		
		?>
		
		
		<div class="wrap">
		<div id="sharedisc_main_admin_page" class="sharedisc_admin_content_holder">
		<div id="sharedisc_admin_header">
			
			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/sharedisclogo.png" />
			
		</div>
		
		<div class="sharedisc_page_information">
			On this page you can control the core functionality of your Sharedisc plugin.
		</div>
		
		<div class="sharedisc_colset">
			
			<div class="sharedisc_col">
				
				<div class="sharedisc_admin_block">
					<h1>ShareDisc Account</h1>
					<div id="sharedisc_admin_page">
						<form method="POST">
							
							<p>
								Enter your ShareDisc account email: </br>
								<input class="textfieldwide" type="text" name="sharedisc_api_email" value="<?=$consumer_email;?>">
							</p>
							
							<input type="submit" value="Save Account Settings" class="sd_admin_linker_input sd_save_post">		
							<input type="hidden" name="sharedisc_api" value="yes" />
						</form>
						
						<div>
							
							<?php if($activation_error){
								echo $activation_error;
							}elseif($consumer_key){
								echo '<p>Your account is activated</p>';
								echo '<p>You have <strong>'.$share_credits.'</strong> share credits available</p>';
							}else{
								echo '<p>your account is not yet activated.</p>';
							} ?>
							
						</div>
						
					</div>
				</div>
				<?php if($consumer_key && $share_breakdown): ?>
				
				<div class="sharedisc_admin_block">
					<h1>Credit Breakdown</h1>	
					<div id="sharedisc_admin_page">
						
						<?php foreach($share_breakdown as $E=>$V): ?>
							
								<?php 
									
								$today = date('Y-m-d');
								$diff = abs(strtotime($E) - strtotime($today));
								
								$years = floor($diff / (365*60*60*24));
								$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
								$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
								
								?>
							
							
								<div class="share_count_block">
									<strong><?php echo $V; ?></strong> shares expire on <em><?php echo $E; ?></em> ( That is in <?php printf("%d years, %d months, %d days\n", $years, $months, $days); ?>)
									
									
								</div>
						<?php endforeach; ?>
						
					</div>
				</div>	
				<div class="sharedisc_admin_block">
					<h1>Enable Networks</h1>	
					<div id="sharedisc_admin_page">
						<form method="POST" action="">
							<?php SDHelper::enable_platforms(); ?>	
							
							<input type="submit" value="Save Settings" class="sd_admin_linker_input sd_save_post">				
							<input type="hidden" name="sharedisc_enabled" value="yes" />
						</form>	
					</div>
				</div>
				
				
				<?php if(SDHelper::has_network_settings()): ?>
				<div class="sharedisc_admin_block">
					<h1>Network Settings</h1>	
					<div id="sharedisc_admin_page">
						<form method="POST" action="">
							<?php SDHelper::network_settings(); ?>	
							
							<input type="submit" value="Save Network Settings" class="sd_admin_linker_input sd_save_post">				
							<input type="hidden" name="sharedisc_network" value="yes" />
						</form>	
					</div>
				</div>
				<?php endif; ?>
				
					
				<div class="sharedisc_admin_block">
					<h1>ShareDisc Settings</h1>
					<div id="sharedisc_admin_page">
						<form method="POST" action="">
							
							<section class="admin_section">			
							<p>Would you like Cart Sharing or Per Product Sharing?</p>
							<p>
							<select name="sharing_type">
									<option <?php echo selected($sharedisc_settings['sharing_type'], 'product'); ?> value="product"> Per Product Sharing</option>
									<option <?php echo selected($sharedisc_settings['sharing_type'], 'cart'); ?> value="cart"> Cart Sharing</option>
								</select>
							</p>
							</section>
							
							
							<section class="admin_section">			
							<p>Enter your default keyline/slogan here. This will be added to all shares unless specified in the text boxes next to each social network.</p>
							<p>
								<textarea name="general_keyline" class="textareas" placeholder="I just saved on <?=$shopname;?>! Earn discounts on your favourite products, visit: <?=$shopurl;?>"><?=$sharedisc_settings['general_keyline'];?></textarea>
							</p>
							</section>
							
							<section class="admin_section">	
							<p class="main_setting_checkbox">
								<input class="textfield percentage" type="text" name="general_discount_threshold" placeholder="% value" value="<?=$sharedisc_settings['general_discount_threshold'];?>"> % Maximum Discount Allowed per item
							</p>
							</section>
							
							<section class="admin_section">	
							<p class="main_setting_checkbox">								
								<?php if($are_sale_items_disabled): ?>
									<input type="checkbox" checked="checked" name="are_sale_items_disabled" > Disable sharing on Sale Items	
								<?php else: ?>
									<input type="checkbox" name="are_sale_items_disabled" > Disable sharing on Sale Items
								<?php endif; ?>
									
							</p>
							</section>
														
							<section class="admin_section">	
							<p class="main_setting_checkbox">		
								Product Icon Location: 						
								<select name="product_icon_location">
									<option <?php echo selected($sharedisc_settings['product_icon_location'], 'right'); ?> value="right"> Product Icon is on the right (default)</option>
									<option <?php echo selected($sharedisc_settings['product_icon_location'], 'left'); ?> value="left"> Product Icon is on the left</option>
								</select>
									
							</p>
							</section>
											
							
							<?php SDHelper::admin_platforms(); ?>		
							
							<p>
								<input type="submit" value="Save Settings" class="sd_admin_linker_input sd_save_post sd_save_post" >			
							<input type="hidden" name="sharedisc_save" value="yes" />
							</p>
						</form>
					</div>	
				</div>
				
				<?php endif; ?>
				
				
				<div class="sharedisc_admin_block">
				<h1>Bit.ly Settings</h1>
				<div id="sharedisc_admin_page">
					<form method="POST" action="">						
						<section class="admin_section">			
						<p>Bitly is a url shortening service. We are going to enable it for you by default, but if you notice that it is slowing down the experience, please disable it.</p>
						<p>
							<?php if($is_bitly_enabled): ?>
								<input type="checkbox" checked="checked" name="is_bitly_enabled" > Enable Bit.ly
							<?php else: ?>
								<input type="checkbox" name="is_bitly_enabled" > Enable Bit.ly
							<?php endif; ?>	
						</p>
						</section>
						
						<?php if($is_bitly_enabled): ?>
						<p>If you have your own Bit.ly credentials for your business, you can paste them here, otherwise leave the below blank.</p>
						
						<p>Bit.ly Login: </br>
							<input class="textfieldwide" type="text" name="sharedisc_bitly_login" value="<?= $bitly_settings['sharedisc_bitly_login']; ?>"></p>
						
						<p>Bit.ly API Key: </br>
							<input class="textfieldwide" type="text" name="sharedisc_bitly_key" value="<?= $bitly_settings['sharedisc_bitly_key']; ?>"></p>	
						<?php endif; ?>
						
						<p>
							<input type="submit" value="Save Settings" class="sd_admin_linker_input" name="sharedisc_bitly" />		
							<input type="hidden" name="sharedisc_bitly" value="yes"  />
						</p>
					</form>
				</div>
			</div>
				
				
				
			</div>
					
			<div class="sharedisc_col">
				<?php $this->sd_plugin_handle_sharedisc_callouts(); ?>
			</div>
				
		<div style="clear:both;"></div>
		</div>
		</div>
		</div>
	<? }
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_admin_messages_page
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_admin_messages_page(){ ?>
		
		<?php if(isset($_POST['sharedisc_messages'])){
			$this->sd_plugin_sharedisc_messages($_POST);
		} ?>
		
		<?php $sharedisc_messages = get_option('sharedisc_message_settings'); 
		if(isset($sharedisc_messages)){
			$sharedisc_messages = unserialize($sharedisc_messages);
		}
		
		if($sharedisc_messages['are_archive_callouts_enabled'] == 'on'){
			$are_archive_callouts_enabled = true;
		}else{
			$are_archive_callouts_enabled = false;
		} 
		
		if($sharedisc_messages['are_single_callouts_enabled'] == 'on'){
			$are_single_callouts_enabled = true;
		}else{
			$are_single_callouts_enabled = false;
		} 
		
		$cart_page_message = false;
		$product_page_message = false;	
		
		
		?>
		<div class="wrap">
		<div id="sharedisc_admin_header">
			
			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/sharedisclogo.png" />
			
		</div>
		
		<div class="sharedisc_page_information">
			On this page you can control the text for the hover callouts.
		</div>
		
		<div class="sharedisc_admin_block" id="sharedisc_admin_page">
			<form method="POST" action="">
				
				<section class="admin_section">			
					<h3>Enable Callouts</h3>
					<p>Enable the Sharedisc callouts on the Product archive pages</p>
					<p>
						<?php if($are_archive_callouts_enabled): ?>
							<input type="checkbox" checked="checked" name="are_archive_callouts_enabled" > Yes
						<?php else: ?>
							<input type="checkbox" name="are_archive_callouts_enabled" > Yes
						<?php endif; ?>	
					</p>
					
					
					<p>Enable the Sharedisc callouts on Single product pages</p>
					<p>
						<?php if($are_single_callouts_enabled): ?>
							<input type="checkbox" checked="checked" name="are_single_callouts_enabled" > Yes
						<?php else: ?>
							<input type="checkbox" name="are_single_callouts_enabled" > Yes
						<?php endif; ?>	
					</p>
					
					
				</section>
				
				<section class="admin_section">
				<h3>Product Callouts</h3>
				<p>Enter the hover text here for the callout on a single product:</p>
				<p>
					<input class="textfieldwide" type="text" name="product_single_callout" value="<?=$sharedisc_messages['product_single_callout'];?>"/>
				</p>
				<p>Enter the hover text here for the callout on a product in the loop:</p>
				<p>
					<input class="textfieldwide" type="text" name="product_loop_callout" value="<?=$sharedisc_messages['product_loop_callout'];?>"/>
				</p>
				</section>
				
				<section class="admin_section">
				<h3>Shop Callouts</h3>
				<p>Enter the hover text here for the callout on the shop pages next to the results count.</p>
				<p>
					<input class="textfieldwide" type="text"  name="shop_info_callout" value="<?=$sharedisc_messages['shop_info_callout'];?>" />
				</p>	
				</section>
				
				<section class="admin_section">
					
				<p>
					<input type="submit" value="Save Settings" class="sd_admin_linker_input sd_save_post">
							<input type="hidden" name="sharedisc_messages" value="yes"  />
				</p>
				</section>
			</form>
		</div>
		</div>
		
	<?php }
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_handle_sharedisc_callouts
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_handle_sharedisc_callouts(){
		$consumer_key = get_option('sd_consumer_key');
						
		if(!$consumer_key):
			?>
			<div class="sharedisc_callout">
				<h2>Not registered yet?</h2>
				<a target="_blank" href="<?php echo $this->site_url; ?>my-account" target="_blank" class="sd_admin_linker_input">Register Now!</a>
				
			</div>	
			<?php
		else:
		
			$api_string = $this->api_url."sharedisc_callouts";
			$response = wp_remote_post($api_string);
			$response = json_decode($response['body']);	
		
			if($response->error):
				echo $response->error;	
			else:
				$callouts = $response->callouts;
				foreach ($callouts as $callout){				
					?>				
				
					<div class="sharedisc_callout">
						
						<?php if($callout->image_only): ?>
							<?php if($callout->link): ?>
								<a class="callout_with_image" href="<?php echo $callout->link; ?>" target="_blank">
							<?php endif; ?>
							
								<img alt="<?php echo $callout->title; ?>" src="<?php echo $callout->image; ?>" />
							
							<?php if($callout->link): ?>
								</a>
							<?php endif; ?>
						
						<?php else: ?>	
						
							<h2><?php echo $callout->title; ?></h2>
							
							<?php if($callout->image): ?>
								<img alt="<?php echo $callout->title; ?>" src="<?php echo $callout->image; ?>" />	
							<?php endif; ?>
							
							<?php if($callout->copy): ?>
								
								<?php echo $callout->copy; ?>
								
							<?php endif; ?>
							
							<?php if($callout->link): ?>
								<a href="<?php echo $callout->link; ?>" target="_blank" class="sd_admin_linker_input"><?php echo $callout->link_text; ?></a>
							<?php endif; ?>	
							
						<?php endif; ?>
						
						
						
						
						
					</div>
					
					<?php
				}
				
				
			endif;	
			
		endif;	
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_admin_shares_page
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_admin_shares_page(){
			
		add_thickbox();
				
		$filters = $this->sd_plugin_sharedisc_request_shares_filters();
				
		$shares = $this->sd_plugin_sharedisc_request_shares($filters);
		
		$num_filters = 0;		
		$total_shares_used = 0;
		$total_conversions_gen = 0;
		$total_conversions_prod = 0;
		$total_discount = 0;
		$total_return = 0;
		?>

		<div class="wrap">				
		<div id="sharedisc_admin_header">
			
			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/sharedisclogo.png" />
			
		</div>
		
		<div class="sharedisc_page_information">
			This page lists all of your shares with information pertaining to each of them.
		</div>
		
		<div class="sharedisc_page_extra">
			Clicking on the <strong>product name</strong> will take you to that product in your Woocommerce admin area. Clicking on the <strong>'view all'</strong> link under a shares orders, will present you will all the closures for that share.
		</div>
		
		
		<?php if(count($filters) > 0): ?>
			
			<div class="sharedisc_admin_block">
				<h2>Filter Shares</h2>
								
				<?php foreach($filters as $NAME=>$OPTS): ?>
					<?php $num_filters++; ?>
				<?php endforeach; ?>
				
				<form id="sd_share_filter_form" method="get">
					<input type="hidden" name="page" value="sharedisc_admin_shares" />
				<?php foreach($filters as $NAME=>$OPTS): ?>
					
					<div class="sd_admin_share_filter_item" style="width:<?php echo (100/$num_filters); ?>%;">
					<label><?php echo $NAME; ?></label>
					<select class="sd_admin_share_filter" id="sd_share_filter_<?php echo $NAME; ?>" name="sd_share_filter_<?php echo $NAME; ?>">
						
						<option value="-">Select a <?php echo $NAME; ?></option>
						
						<?php foreach ($OPTS as $opt): ?>
							<option <?php selected($_GET['sd_share_filter_'.$NAME], $opt->val); ?> value="<?php echo $opt->val; ?>"><?php echo $opt->title; ?></option>
						<?php endforeach; ?>
						
					</select>
					</div>
					
				<?php endforeach; ?>
					<div style="clear:both;"></div>
				</form>
			</div>
		
		<?php endif; ?>
		
		<div class="sharedisc_admin_block">
			
			
			<?php if(!count($shares) > 0): ?>
				
				<p>You have no shares yet.</p>
				
			<?php else: ?>
					
			<div id="sharedisc_admin_page">
			
			
			
			<table class="widefat fixed" cellspacing="0">
			    <thead>
			    <tr>
			
			            <th id="col-network" class="manage-network column-network" scope="col">Network</th>
			            <th id="col-share-type" class="manage-share-type column-share-type" scope="col">Type</th>
			            <th id="col-date" class="manage-date column-date " scope="col">Share Date</th>
			            <th id="col-product-name" class="manage-product-name column-product-name" scope="col">Product</th>
			            <th id="col-url-disc" class="manage-url-disc column-url-disc" scope="col">Discount Offered</th>
			            <th id="col-url-conversions-in-total" class="manage-url-conversions-in-total column-url-conversions-in-total" scope="col">Closures</th>
			            <th id="col-url-value" class="manage-url-value column-url-value" scope="col">Closure Value</th>
			            <th id="col-url-return" class="manage-url-orders column-url-return" scope="col">Share Return</th>
			            <th id="col-url-orders" class="manage-url-orders column-url-orders" scope="col">Orders</th>
			
			    </tr>
			    </thead>
			    
			    <tbody>
			    	
			    	<?php $total_value = 0; ?>
			    	
			    	<?php foreach($shares as $S): ?>
			    		
			    		<?php $share_return = 0; ?>
			    		
			    		<?php $the_orders = unserialize($S->order_ids); ?>		
			    		
			    			<?php $item_shared_count = 0; ?>
							<?php $item_shared_total = 0; ?>
							
							<?php if(!empty($the_orders)): ?>
							<?php foreach($the_orders as $_O=>$V): ?>
							
							<?php $this_order = new WC_Order($_O); ?>	
							
							
							<?php $item_shared_count += $this_order->get_item_count(); ?>
							<?php $item_shared_total += $this_order->get_total(); ?>
						
							<?php endforeach; ?>	
							<?php endif; ?>
							
							<?php $share_return = floatval($item_shared_total) - floatval($S->discount); ?>
							<?php $total_return += $share_return; ?>
							<?php $total_value += $item_shared_total; ?>
			    		
			    		<tr>
				            <td><div class="socialicon sd_<?php echo $S->share_type; ?>"></div></td>
				            <td><?php echo $S->shared_via; ?></td>
				            <td><?php echo $S->date; ?></td>
				            <td><?php edit_post_link( get_the_title($S->product_id), NULL, NULL, $S->product_id ); ?> </td>
				            <td><?php echo get_woocommerce_currency_symbol().number_format($S->discount, 2, '.', ' '); ?> </td>
							<td><?php echo $S->conversions_gen; ?></td>
							<td><?php echo get_woocommerce_currency_symbol().number_format($item_shared_total, 2, '.', ' '); ?></td>
							<td><?php echo get_woocommerce_currency_symbol().number_format($share_return, 2, '.', ' '); ?></td>
							<?php if(count($the_orders) > 0): ?>
							
							<td><a class="thickbox" href="#TB_inline?width=600&height=550&inlineId=<?php echo $S->share_key; ?>">view all</a>
								
								
								<div style="display:none" id="<?php echo $S->share_key; ?>">
									<h2>Orders from Conversions</h2> 
									<p>key: <?php echo $S->share_key; ?></p>
									
													
									
									<div class="orders">
										<?php if(!is_array($the_orders)): ?>
											<p>This share does not have any conversions as of yet</p>
										<?php else: ?>
											<table class="widefat fixed" cellspacing="0">
												
											 <thead>
											    <tr>
											
											            <th id="col-order-id" class="manage-order-id column-order-id" scope="col">Order</th>
											            <th id="col-order-date" class="manage-date column-order-date " scope="col">Date</th>
											            <th id="col-products" class="manage-date column-products " scope="col">Products</th>
											            <th id="col-products" class="manage-date column-products " scope="col">Total</th>
											
											    </tr>
											</thead>	
											
											<tbody>	
												
												<?php foreach($the_orders as $_O => $V): ?>
												
												<?php $this_order = new WC_Order($_O); ?>	
											
												<tr>
													<td><a href="<?php echo get_edit_post_link($_O); ?>"><?php echo $this_order->get_order_number(); ?></a></td>
													<td><?php echo date('Y-m-d', strtotime($this_order->__get('completed_date'))); ?></td>
													<td><?php echo $this_order->get_item_count(); ?></td>
													<td><?php echo $this_order->get_formatted_order_total(); ?></td>
												</tr>
												<?php endforeach; ?>
											</tbody>
											
											<tfoot>
												
												<tr><th></th><th></th><th><?php echo $item_shared_count; ?></th><th><?php echo get_woocommerce_currency_symbol().number_format($item_shared_total, 2, '.', ' '); ?></th></tr>
												
											</tfoot>
											
											</table>
											
										<?php endif; ?>	
									</div>	
								</div>
							</td>
							<?php endif; ?>
				    	</tr>
				    	
				    	<?php $total_shares_used+= intval($S->clicks); ?>
				    	<?php $total_conversions_prod+= intval($S->conversions_prod); ?>
				    	<?php $total_conversions_gen+= intval($S->conversions_gen); ?>
				    	<?php $total_discount += floatval($S->discount); ?>
				    	
			    	<?php endforeach; ?>			    
			    	
			    </tbody>
			    
			    <tfoot>
			    	<tr>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th><?php echo get_woocommerce_currency_symbol().number_format($total_discount, 2, '.', ' '); ?></th>
			    		<th><?php echo $total_conversions_gen; ?></th>
			    		<th><?php echo get_woocommerce_currency_symbol().number_format($total_value, 2, '.', ' '); ?></th>
			    		<th><?php echo get_woocommerce_currency_symbol().number_format($total_return, 2, '.', ' '); ?></th><th></th></tr>
			    </tfoot>
			    
			  </table>
			
			
				
			</div>
			
			<div id="sd_share_stats_box" style="display:none"></div>
			
			<?php endif; ?>
			
		</div>
		</div>
		
		
		<?php		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_admin_stats_page
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_admin_stats_page(){		
			
		
		$discounts = $this->sd_plugin_sharedisc_request_discounts();	
		$shares = $this->sd_plugin_sharedisc_request_shares();	

		
		$total_shares_used = 0;
		$total_conversions = 0;
		$total_products = 0;
		$total_value = 0;
		$total_clicks = 0;
		$total_discount = 0;
		$share_return = 0;	    	
		
		foreach($shares as $S): 
			$the_orders = unserialize($S->order_ids); 
			
			$total_conversions+= intval($S->conversions_gen);
			$total_clicks+= intval($S->clicks);
			$total_discount += floatval($S->discount); 
			$total_shares_used+= 1;
			
			foreach($the_orders as $_O => $V):
							
				$this_order = new WC_Order($_O);
			
				$total_products += $this_order->get_item_count();
				$total_value += $this_order->get_total();
			
			endforeach;
			
		endforeach;
		
		
		$share_return = floatval($total_value) - floatval($total_discount);
		
		?>
		
		<div class="wrap">		
		<div id="sharedisc_admin_header">
			
			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/sharedisclogo.png" />
			
		</div>
		
		<div class="sharedisc_page_information">
			This page breaks down all the statistics of how your shares are performing across the various networks as well as the monetary values thereof.
		</div>
		
		
		
		<?php if(!count($shares) > 0): ?>
			<div class="sharedisc_admin_block">				
			
				<div id="sharedisc_admin_page">
					<p>You have no shares yet.</p>
				</div>
			
			</div>
		<?php else: ?>
		<div class="sharedisc_admin_block">				
			
			<div id="sharedisc_admin_page">
				
				<div class="sharedisc_stats_section_main">
					<strong>Total Shares Used</strong>
					<?php echo $total_shares_used; ?>
				</div>
				
				<div class="sharedisc_stats_section_main">
					<strong>Total Closures</strong>
					<?php echo $total_conversions; ?>
				</div>
				
				
				<div class="sharedisc_stats_section_main">
					<strong>Total Products Sold</strong>
					<?php echo $total_products; ?>
				</div>
				
				
				<div class="sharedisc_stats_section_main">
					<strong>Total Url Clicks From Shares</strong>
					<?php echo $total_clicks; ?>
				</div>
				
				
				<div class="sharedisc_stats_section_main">
					<strong>Money Generated from Shares</strong>
					<?php echo get_woocommerce_currency_symbol().number_format($total_value, 2, '.', ' '); ?>
				</div>
				
				<div class="sharedisc_stats_section_main">
					<strong>Total Discount Given</strong>
					<?php echo get_woocommerce_currency_symbol().number_format($total_discount, 2, '.', ' '); ?>
				</div>
				
				
				<div class="sharedisc_stats_section_main">
					<strong>Return On Investment</strong>
					<?php echo get_woocommerce_currency_symbol().number_format($share_return, 2, '.', ' '); ?>
				</div>
				
				
			</div>
		</div>
		
		
		
		<?php
		
		
		$share_return = floatval($total_value) - floatval($total_discount);
		
		if($total_conversions != 0):
			$average_conversion_sale_amount 	= $total_value/$total_conversions;
			if(!$average_conversion_sale_amount): $average_conversion_sale_amount = 0; endif;
		else:
			$average_conversion_sale_amount = 0;
		endif;
		
		if($total_conversions != 0):
			$average_products_in_conversion 	= $total_products/$total_conversions;
			if(!$average_products_in_conversion): $average_products_in_conversion = 0; endif;
		else:
			$average_products_in_conversion = 0;
		endif;
		
		if($total_conversions != 0):
			$average_link_clicks_conversion 	= $total_clicks/$total_conversions;
			if(!$average_link_clicks_conversion): $average_link_clicks_conversion = 0; endif;
		else:
			$average_link_clicks_conversion = 0;
		endif;
		
		if($total_conversions != 0):
			$average_share_spend_conversion 	= $total_shares_used/$total_conversions;
			if(!$average_share_spend_conversion): $average_share_spend_conversion = 0; endif;
		else:
			$average_share_spend_conversion = 0;
		endif;
		
		if($total_shares_used != 0):
			$share_return_value 	= $share_return_value/$total_shares_used;
			if(!$share_return_value): $share_return_value = 0; endif;
		else:
			$share_return_value = 0;
		endif;
		?>
		
		<div class="sharedisc_admin_block">
			<div id="sharedisc_admin_page">
				<div class="sharedisc_stats_section">					
					<div class="sharedisc_stats_item">Each conversion on average results in a return of <strong><?php echo get_woocommerce_currency_symbol().number_format($average_conversion_sale_amount, 2, '.', ' '); ?></strong>.</div>
					<div class="sharedisc_stats_item">There is an average of <strong><?php echo $average_products_in_conversion; ?></strong> product(s) in each conversion.</div>
					<div class="sharedisc_stats_item">A conversion happens on average every <strong><?php echo $average_link_clicks_conversion; ?></strong> times a share is clicked</div>
					<div class="sharedisc_stats_item"><strong><?php echo $average_share_spend_conversion; ?></strong> share(s) are used per conversion.</div>
					<div class="sharedisc_stats_item"><strong><?php echo get_woocommerce_currency_symbol().number_format($share_return_value, 2, '.', ' '); ?></strong> return per share.</div>						
				</div>
			</div>
		</div>
		
		
		<div class="sharedisc_admin_block">				
			
			<div id="sharedisc_admin_page">
				<h2>Platform Performance </h2>
				
				<?php
				
				$total_closure = 0;
				$total_discount = 0;
				$total_return = 0;
				
				
				?>
								
					<table class="widefat fixed" cellspacing="0">
			    	<thead>
			    	<tr>
			
			            <th id="col-disc-info" class="manage-col-disc-info column-col-disc-info" scope="col">Type</th>
			            <th id="col-disc-info" class="manage-col-disc-info column-col-disc-info" scope="col">Share Count</th>
			            <th id="col-disc-info" class="manage-col-disc-info column-col-disc-info" scope="col">Closures</th>
			            <th id="col-disc-info" class="manage-col-disc-info column-col-disc-info" scope="col">Closure Value</th>
			            <th id="col-disc-info" class="manage-col-disc-info column-col-disc-info" scope="col">Discount Given</th>
			            <th id="col-disc-info" class="manage-col-disc-info column-col-disc-info" scope="col">Return</th>
			
			    	</tr>
			    	</thead>
			    	
			    	<tbody>
			    		
						<?php foreach($discounts as $type => $data): ?>
							
						<?php 
						
						$total_closure += $data->closure_value;
						$total_discount += $data->discount;
						$total_return += $data->return;
						
						?>
							
						<tr>
							<td style="color:<?php SDHelper::platform_colour($type); ?>"><?php echo $type; ?></td>
							<td><?php echo $data->count; ?></td>
							<td><?php echo $data->closures; ?></td>	
							<td><?php echo get_woocommerce_currency_symbol().number_format($data->closure_value, 2, '.', ' '); ?></td>	
							<td><?php echo get_woocommerce_currency_symbol().number_format($data->discount, 2, '.', ' '); ?></td>	
							<td><?php echo get_woocommerce_currency_symbol().number_format($data->return, 2, '.', ' '); ?></td>	
						</tr>							
						<?php endforeach; ?>
					</tbody>
					
					
					<tfoot>
						
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th><?php echo get_woocommerce_currency_symbol().number_format($total_closure, 2, '.', ' '); ?></th>
							<th><?php echo get_woocommerce_currency_symbol().number_format($total_discount, 2, '.', ' '); ?></th>
							<th><?php echo get_woocommerce_currency_symbol().number_format($total_return, 2, '.', ' '); ?></th>
						</tr>
						
					</tfoot>
					
					</table>
					
			</div>
			
		</div>
		
		
		<div class="sharedisc_admin_block">	
			
			<script type="text/javascript">
		
			jQuery(window).ready(function(){					  	
			  
				
			  jQuery("#doughnutChart_shares").drawDoughnutChart([	
			  	<?php foreach($discounts as $type => $s): ?>
			  		{ title: "<?php echo ucfirst($type); ?>",  value : <?php echo intval($s->count); ?>, color: "<?php SDHelper::platform_colour($type); ?>" },
			  	<?php endforeach; ?>
			  ]);
			  
			  jQuery("#doughnutChart_conversions").drawDoughnutChart([	
			  	<?php foreach($discounts as $type => $s): ?>
			  		{ title: "<?php echo ucfirst($type); ?>",  value : <?php echo intval($s->closures); ?>, color: "<?php SDHelper::platform_colour($type); ?>" },
			  	<?php endforeach; ?>
			  ]);
			  
			  jQuery("#doughnutChart_discount").drawDoughnutChart([	
			  	<?php foreach($discounts as $type => $s): ?>
			  		{ title: "<?php echo ucfirst($type); ?>",  value : <?php echo $s->discount; ?>, color: "<?php SDHelper::platform_colour($type); ?>" },
			  	<?php endforeach; ?>
			  ]);
			  
			  jQuery("#doughnutChart_return").drawDoughnutChart([	
			  	<?php foreach($discounts as $type => $s): ?>
			  		
			  		<?php if($s->return > 0): ?>
			  	
			  		{ title: "<?php echo ucfirst($type); ?>",  value : <?php echo $s->return; ?>, color: "<?php SDHelper::platform_colour($type); ?>" },
			  		
			  		<?php endif; ?>
			  		
			  	<?php endforeach; ?>
			  ]);
				
			});
		
		
		
		</script>
			
			<div class="sharedisc_admin_graph">
				<h3>Shares</h3>
				<p></p>
				<div id="doughnutChart_shares" class="chart chart_shares"></div>
			</div>	
			
			<div class="sharedisc_admin_graph">
				<h3>Closures</h3>
				<p></p>
				<div id="doughnutChart_conversions" class="chart chart_conversions"></div>
			</div>	
			
			<div class="sharedisc_admin_graph">
				<h3>Discounts</h3>
				<p></p>
				<div id="doughnutChart_discount" class="chart chart_discount"></div>
			</div>	
			
			<div class="sharedisc_admin_graph">
				<h3>Returns</h3>
				<p></p>
				<div id="doughnutChart_return" class="chart chart_return"></div>
			</div>	
			
			<div style="clear:both;"></div>				
			
			
		</div>
		
		<?php
		endif; ?>
		
		
		</div>
		
		<?php
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_admin_styling_page
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_admin_styling_page(){
						
		if(isset($_POST['sharedisc_styling'])){
			$this->sd_plugin_sharedisc_styling($_POST);
			
		}	
		
		$sharedisc_styling = get_option('sharedisc_styling_settings'); 
		if(isset($sharedisc_styling)){
			$sharedisc_styling = unserialize($sharedisc_styling);
		}		
				
		$available_themes = SDHelper::available_themes();
				
			
		$dir_theme = str_replace('/admin/', '/themes/', plugin_dir_url( __FILE__ ));
		
		?>	
		
		<style>
			
			@import "<?php echo $dir_theme . $sharedisc_styling['sd_css_theme']; ?>";
			@import "<?php echo plugins_url(  ) . '/sharedisc/public/css/'.$sharedisc_styling['sd_popup_style'].'.css'; ?>";
		
		</style>
		
		<script type="text/javascript">
		
			jQuery(document).ready(function(){
				
					/*
					 * Instantiate all the hovers
					 */
					jQuery('.sd_tooltip').tooltipster({
						trigger: '<?php echo $sharedisc_styling['sd_popup_trigger']; ?>',
						animation: '<?php echo $sharedisc_styling['sd_popup_animation']; ?>',
						theme: '<?php echo $sharedisc_styling['sd_popup_style']; ?>',
						position: '<?php echo $sharedisc_styling['sd_popup_location']; ?>',
						contentAsHTML: true
					});
				
			});
		
		</script>
		
		
		<div class="wrap">		
		<div id="sharedisc_admin_header">
			
			<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/sharedisclogo.png" />
			
		</div>
		
		<div class="sharedisc_page_information">
			On this page you will be able to pick the plugins general front-facing style, as well as changing the popup styling to best suit your site.
		</div>
		
		<form method="POST" action="">
			
		
		<div class="sharedisc_admin_block">
			<div id="sharedisc_admin_page">
				<h2>ShareDisc Theme</h2>
				<section class="admin_section">		
				<select name="sd_css_theme">
					<?php foreach($available_themes as $key=>$name): ?>
						
						<option <?php selected($sharedisc_styling['sd_css_theme'], $key); ?> value="<?php echo $key; ?>"><?php echo $name; ?></option>
						
					<?php endforeach; ?>
				</select>
			
			</section>
			<section class="admin_section">				
				<a id="sharedisc_popup_text" class="sharedisctextlink">This is what your selected theme looks like.</a>
			</section>
				
			</div>
		</div>
		
		<!--	
		<div class="sharedisc_page_extra">
			If you would like to create your own theme, check out the tutorial here: <a>How to theme my Sharedisc plugin</a>
		</div>
		-->
		
		<div class="sharedisc_admin_block">
			
			<div id="sharedisc_admin_page">
				<h2>Popup Styling</h2>
			</div>
							
			
			<div id="sharedisc_admin_page">
					
					<section class="admin_section">
					<label>Trigger On</label>
					<select name="sd_popup_trigger">
						
						<option <?php selected($sharedisc_styling['sd_popup_trigger'], 'hover'); ?> value="hover">Hover</option>
						<option <?php selected($sharedisc_styling['sd_popup_trigger'], 'click'); ?> value="click">Click</option>
						
					</select>
				
					<label>Theme</label>
					<select name="sd_popup_style">
						
						<option <?php selected($sharedisc_styling['sd_popup_style'], 'tooltipster-light'); ?> value="tooltipster-light">Light</option>
						<option <?php selected($sharedisc_styling['sd_popup_style'], 'tooltipster-noir'); ?> value="tooltipster-noir">Noir</option>
						<option <?php selected($sharedisc_styling['sd_popup_style'], 'tooltipster-punk'); ?> value="tooltipster-punk">Punk</option>
						<option <?php selected($sharedisc_styling['sd_popup_style'], 'tooltipster-shadow'); ?> value="tooltipster-shadow">Shadow</option>
						
					</select>
					
					<label>Animation</label>
					<select name="sd_popup_animation">
						
						<option <?php selected($sharedisc_styling['sd_popup_animation'], 'fade'); ?> value="fade">fade</option>
						<option <?php selected($sharedisc_styling['sd_popup_animation'], 'grow'); ?> value="grow">grow</option>
						<option <?php selected($sharedisc_styling['sd_popup_animation'], 'swing'); ?> value="swing">swing</option>
						<option <?php selected($sharedisc_styling['sd_popup_animation'], 'slide'); ?> value="slide">slide</option>
						<option <?php selected($sharedisc_styling['sd_popup_animation'], 'fall'); ?> value="fall">fall</option>
						
					</select>
				
					<label>Location</label>
					<select name="sd_popup_location">
						
						<option <?php selected($sharedisc_styling['sd_popup_location'], 'right'); ?> value="right">right</option>
						<option <?php selected($sharedisc_styling['sd_popup_location'], 'left'); ?> value="left">left</option>
						<option <?php selected($sharedisc_styling['sd_popup_location'], 'top'); ?> value="top">top</option>
						<option <?php selected($sharedisc_styling['sd_popup_location'], 'top-right'); ?> value="top-right">top-right</option>
						<option <?php selected($sharedisc_styling['sd_popup_location'], 'top-left'); ?> value="top-left">top-left</option>
						<option <?php selected($sharedisc_styling['sd_popup_location'], 'bottom'); ?> value="bottom">bottom</option>
						<option <?php selected($sharedisc_styling['sd_popup_location'], 'bottom-right'); ?> value="bottom-right">bottom-right</option>
						<option <?php selected($sharedisc_styling['sd_popup_location'], 'bottom-left'); ?> value="bottom-left">bottom-left</option>
						
					</select>
				
					
					</section>
					<section class="admin_section">
					<span class="sd_tooltip sd_example_link" title="this is the example <strong><?php echo strtoupper($sharedisc_styling['sd_popup_trigger']); ?></strong> text"><strong><?php echo strtoupper($sharedisc_styling['sd_popup_trigger']); ?></strong> here to view an example of your settings.</span>
					
					</section>
				
			</div>
			
		</div>
		
		<div class="sharedisc_admin_block">
			<em>*please save your changes before previewing.</em>
		</div>
		
		<div class="sharedisc_admin_block">		
			<input type="submit" value="Save Settings" class="sd_admin_linker_input sd_save_post">
			<input type="hidden" name="sharedisc_styling" value="yes"  />
		</div>
		
		</form>
		
		</div>	
		<?php
	}
				
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_options_tab
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_options_tab() { ?>
		<li class="sharedisc_tab"><a href="#sharedisc_tab_data"><?php _e('ShareDisc', 'woothemes'); ?></a></li>
	<?php }
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_tab_options
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_tab_options() {
		global $post;
		$shopname = get_bloginfo('name');
		$shopurl = get_bloginfo('url');
	?>
		<div id="sharedisc_tab_data" class="panel woocommerce_options_panel">		
			
			<div class="options_group">
			<? woocommerce_wp_checkbox( array( 'id' => 'sharedisc_product_enabled', 'label' => __('Use Product Settings?', 'woothemes') ) ); ?>
			</div>
						
			<?php SDHelper::product_platforms(); ?>
						
		</div>
	<?php
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_process_product_meta_sharedisc_tab
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_process_product_meta_sharedisc_tab( $post_id ) {
		$posted_array = $_POST;
		foreach($posted_array as $sharedisc_key => $sharedisc_value){				
			if( strpos($sharedisc_key, 'sharedisc_product_') !== false ) {
				if($sharedisc_key != 'sharedisc_product_enabled') {
					update_post_meta( $post_id, $sharedisc_key, $sharedisc_value);
				}
			}
			$enabled = ( isset($_POST['sharedisc_product_enabled']) && $_POST['sharedisc_product_enabled'] ) ? 'yes' : 'no';			
			update_post_meta( $post_id, 'sharedisc_product_enabled', $enabled );
		};
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_shares_tab
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_shares_tab() { ?>
		<li class="sharedisc_tab"><a href="#sharedisc_share_data"><?php _e('Product Shares', 'woothemes'); ?></a></li>
	<?php }
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_shares_tab_options
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_shares_tab_options(){
			
		$shares = $this->sd_plugin_sharedisc_product_shares($_REQUEST['post']);
		
		?>
		<div id="sharedisc_share_data" class="panel woocommerce_options_panel">
		
		<table class="widefat fixed" cellspacing="0">
			    <thead>
			    <tr>
			            <th id="col-date-time" class="manage-date-time column-date-time " scope="col">Share Date</th>
			            <th id="col-share-type" class="manage-share-type column-share-type " scope="col">Type</th>
			            <th id="col-url-clicks" class="manage-url-clicks column-url-clicks " scope="col">URL Clicks</th>
			            <th id="col-url-closures" class="manage-url-closures column-url-closures " scope="col">Closures</th>
			            <th id="col-url-discount" class="manage-url-closures column-url-discount " scope="col">Discount</th>
			            <th id="col-url-revenue" class="manage-url-revenue column-url-revenue " scope="col">Revenue</th>
			            <th id="col-url-return" class="manage-url-return column-url-return " scope="col">Return</th>
			
			    </tr>
			    </thead>
			    
			    <tbody>
			    	
			    	<?php foreach($shares as $S): ?>
			    		
			    		<?php $the_orders = unserialize($S->order_ids); ?>		

							<?php $item_shared_total = 0; ?>
							
							<?php foreach($the_orders as $_O=>$V): ?>
							
							<?php $this_order = new WC_Order($_O); ?>	
								<?php $item_shared_total += $this_order->get_total(); ?>						
							<?php endforeach; ?>	


							<?php $share_return = floatval($item_shared_total) - floatval($S->discount); ?>
			    		
			    		
			    		
			    		
			    		<tr>
				
				            <td><?php echo $S->date; ?></td>
				            <td><div class="socialicon sd_<?php echo $S->share_type; ?>"></div></td>
				            <td><?php echo $S->clicks; ?></td>
				            <td><?php echo $S->conversions_gen; ?></td>
				            <td><?php echo get_woocommerce_currency_symbol().number_format($S->discount, 2, '.', ' '); ?></td>
				            <td><?php echo get_woocommerce_currency_symbol().number_format($item_shared_total, 2, '.', ' '); ?></td>
				            <td><?php echo get_woocommerce_currency_symbol().number_format($share_return, 2, '.', ' '); ?></td>
				
				   	 	</tr>
			    	<?php endforeach; ?>			    
			    	
			    </tbody>
			    
			  </table>
		
		
		
		
		</div>
		<?php
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_save
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_save($POST){
		unset($POST['sharedisc_save']);
		update_option('sharedisc_settings', serialize($POST));
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_enabled
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_enabled($POST){
		unset($POST['sharedisc_enabled']);
		update_option('sharedisc_platform_enabled', serialize($POST));
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_network
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_network($POST){
		unset($POST['sharedisc_network']);
		update_option('sharedisc_platform_settings', serialize($POST));
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_messages
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_messages($POST){
		unset($POST['sharedisc_messages']);
		update_option('sharedisc_message_settings', serialize($POST));
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_bitly
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_bitly($POST){
		unset($POST['sharedisc_bitly']);
		update_option('sharedisc_bitly_settings', serialize($POST));
	}
	
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_account
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_account($POST){
		
		global $current_user;
		unset($POST['sharedisc_api']);
		
		if(trim($POST['sharedisc_api_email']) == ''){
			
			delete_option('sd_consumer_key');
			delete_option('sd_consumer_email');
			return 'de-activated';
			
		}else{
			$store_currency = get_woocommerce_currency_symbol();
					
			$api_string = $this->api_url."sharedisc_key/?sd_email=".$POST['sharedisc_api_email']."&sd_currency=".urlencode($store_currency)."&sd_domain=".get_bloginfo('siteurl')."&sd_title=".urlencode(get_bloginfo('name'));
			$response = wp_remote_post($api_string);
			$response = json_decode($response['body']);				
						
			
			if($response->error):
				return $response->error;	
			elseif($response->sd_consumer_key):
				update_option('sd_consumer_key', $response->sd_consumer_key);
				update_option('sd_consumer_email', $POST['sharedisc_api_email']);
				return 'activated';
			endif;
			
		}
			
			
		
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_styling
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_styling($POST){
		unset($POST['sharedisc_styling']);
		update_option('sharedisc_styling_settings', serialize($POST));
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_request_discounts
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_request_discounts(){
		$api_string = $this->api_url."sharedisc_all_discount/?sd_ck=".get_option('sd_consumer_key');
		
		$response = wp_remote_post($api_string);
		$response = json_decode($response['body']);	
		if($response->error):
			return $response->error;	
		else:
			return $response->sd_discount;
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_request_shares
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_request_shares($filters = null){
			
		$selected = array();			
		foreach($filters as $NAME=>$opts):
			$name = 'sd_share_filter_'.$NAME;
			if($_GET[$name] && $_GET[$name] != '-'):
				$selected[] = $name.'='.$_GET[$name];
			endif;
			
		endforeach; 
		
		if(count($selected) > 0){
			$selected = '&'.implode("&", $selected);
		}
		
		if($selected):
			$api_string = $this->api_url."sharedisc_all_shares/?sd_ck=".get_option('sd_consumer_key').$selected;
		else:
			$api_string = $this->api_url."sharedisc_all_shares/?sd_ck=".get_option('sd_consumer_key');
		endif;	
		
			
		$response = wp_remote_post($api_string);
		
		$response = json_decode($response['body']);	
		if($response->error):
			return $response->error;	
		else:
			return $response->sd_shares;
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_request_shares_filters
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_request_shares_filters(){
		$api_string = $this->api_url."sharedisc_all_shares_filters/?sd_ck=".get_option('sd_consumer_key');
		$response = wp_remote_post($api_string);
		
		$response = json_decode($response['body']);	
		if($response->error):
			return $response->error;	
		else:
			return $response->sd_share_filters;
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_credit_count
	 *
	 * @since 1.0.0
	*/
	public function sd_plugin_sharedisc_credit_count(){
		$api_string = $this->api_url."sharedisc_credits/?sd_ck=".get_option('sd_consumer_key');
		$response = wp_remote_post($api_string);
		
		$response = json_decode($response['body']);	
		if($response->sd_share_credits):
			return $response->sd_share_credits;	
		else:
			return $response->error;
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_credit_breakdown
	 *
	 * @since 1.0.0
	*/
	public function sd_plugin_sharedisc_credit_breakdown(){
		$api_string = $this->api_url."sharedisc_breakdown/?sd_ck=".get_option('sd_consumer_key');
		$response = wp_remote_post($api_string);
		
		$response = json_decode($response['body']);	
		if($response->sd_share_credit_breakdown):
			return $response->sd_share_credit_breakdown;	
		else:
			return $response->error;
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_latest_shares
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_latest_shares(){
		$api_string = $this->api_url."sharedisc_latest_shares/?sd_ck=".get_option('sd_consumer_key');
		$response = wp_remote_post($api_string);
		
		$response = json_decode($response['body']);	
		if($response->error):
			return $response->error;	
		else:
			return $response->sd_shares;
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_product_shares
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_product_shares($ID){
		$api_string = $this->api_url."sharedisc_product_shares/?sd_ck=".get_option('sd_consumer_key').'&sd_prod_id='.$ID;
		$response = wp_remote_post($api_string);
		
		$response = json_decode($response['body']);	
		if($response->error):
			return $response->error;	
		else:
			return $response->sd_shares;
		endif;
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_log_share
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_update_share_qty(){
		
		/* PRODUCT SHARING */			
		if(WooCommerce_Session_Helper::_isset('sd_prods_shared')):	
			$array_of_shared_prods = unserialize(WooCommerce_Session_Helper::_get('sd_prods_shared'));
			
			$cart_totals  = isset( $_POST['cart'] ) ? $_POST['cart'] : '';
			
			$keys_in_cart = array();
			
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ):
				$keys_in_cart[] = $cart_item_key;		
			endforeach;
						
			if ( sizeof( WC()->cart->get_cart() ) > 0 && is_array( $cart_totals ) ) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
	
					foreach($array_of_shared_prods as &$_SP):
						
						foreach($_SP as &$DATA):
						
						if($cart_item_key == $DATA['key']){
									
							if ( ! isset( $cart_totals[ $cart_item_key ] ) || ! isset( $cart_totals[ $cart_item_key ]['qty'] ) ) {
								continue;
							}	
															
							$DATA['qty'] = apply_filters( 'woocommerce_stock_amount_cart_item', wc_stock_amount( preg_replace( "/[^0-9\.]/", '', $cart_totals[ $cart_item_key ]['qty'] ) ), $cart_item_key );
							
						}
						
						
						endforeach;
					
									
						
					
					endforeach;	
					
				}			
				
			}
			
			foreach($array_of_shared_prods as $key=>$values):
				if(!in_array($key, $keys_in_cart)):
					unset($array_of_shared_prods[$key]);
				endif;
			
			endforeach;		
			
			WooCommerce_Session_Helper::_set('sd_prods_shared', serialize($array_of_shared_prods));
		
		endif;
		
		
		
		/* CART SHARING */		
		if(WooCommerce_Session_Helper::_isset('sd_cart_shared')):	
		
			$sd_options = unserialize(get_option('sharedisc_settings'));
		
			$array_of_shared_platforms = unserialize(WooCommerce_Session_Helper::_get('sd_cart_shared'));
			
			$cart_total = 0;
			
			
						
			if ( sizeof( WC()->cart->get_cart() ) > 0) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
													
					$cart_total += $values['line_total'];	
							
				}			
				
			}
			
			foreach($array_of_shared_platforms as $type=>&$data){			
				$type_discount = $sd_options['general_discount_'.$type];	
						
				
				$cart_amt = $cart_total*($type_discount/100);
				
				$data['base'] = $cart_amt;
			}						
			
			WooCommerce_Session_Helper::_set('sd_cart_shared', serialize($array_of_shared_platforms));
			
		endif;
		
		
		
	}
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_log_share
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_log_share(){
		global $woocommerce;
		
				
		$share_type			= $_POST['type'];
		$product_id 		= $_POST['product_id'];
		$product_key 		= $_POST['product_key'];
		$product_path 		= $_POST['product_path'];
		$product_title 		= $_POST['product_title'];
		$product_data 		= $_POST['product_data'];
		$product_price 		= $_POST['product_price'];
		$cart_key 			= $_POST['cart_key'];
		$share_percentage 	= $_POST['share_percentage'];
		$qty 				= $_POST['qty'];
		$base 				= $_POST['base'];
		$URL 				= $_POST['share_url'];
		$how 				= $_POST['how'];
		
		
		$the_product_site = get_bloginfo('siteurl');
		$the_product_title = get_the_title($product_id);
				
		$api_string = $this->api_url."sharedisc_log_share/?sd_ck=".get_option('sd_consumer_key')."&sd_type=".$share_type."&sd_prod_id=".$product_id."&sd_product_key=".$product_key."&sd_product_title=".urlencode($the_product_title)."&sd_product_site=".$the_product_site."&sd_product_path=".$product_path."&sd_share_url=".urlencode($URL)."&sd_how=".$how."&sd_product_data=".$product_data;
		
		wp_remote_post($api_string);		
		
		if($how == 'product'):
		
		
			if(WooCommerce_Session_Helper::_isset('sd_prods_shared')):
				$array_of_shared_prods = unserialize(WooCommerce_Session_Helper::_get('sd_prods_shared'));
			else:
				$array_of_shared_prods = array();
			endif;
			
			$share_row = array(
				'type' 		=> $share_type,
				'key' 		=> $cart_key,
				'qty' 		=> $qty,
				'base' 		=> $base,
				'percent' 	=> $share_percentage,
				'share_key'	=> $product_key
			);
			
			$array_of_shared_prods[$cart_key][] = $share_row;
			
			$session_var = 'sd_prod_percentage_'.$cart_key;
			
			if(isset($woocommerce->session->$session_var)){
				$percentage = intval($woocommerce->session->$session_var);
				$percentage += intval($share_percentage); 
			}else{
				$percentage = intval($share_percentage);
			}		
			
				
			WooCommerce_Session_Helper::_set($session_var, $percentage);
			WooCommerce_Session_Helper::_set('sd_prod_'.$share_type.'_'.$cart_key, serialize($_POST));
			WooCommerce_Session_Helper::_set('sd_prods_shared', serialize($array_of_shared_prods));
			//WooCommerce_Session_Helper::_set('sd_last_opened', $cart_key);
		
		else:
		
		
			if(WooCommerce_Session_Helper::_isset('sd_cart_shared')):
				$array_of_shared_platforms = unserialize(WooCommerce_Session_Helper::_get('sd_cart_shared'));
			else:
				$array_of_shared_platforms = array();
			endif;
			
			$share_row = array(
				'qty' 		=> 1,
				'base' 		=> $base,
				'percent' 	=> $share_percentage,
				'share_key'	=> $product_key
			);
			
			$array_of_shared_platforms[$share_type] = $share_row;
			WooCommerce_Session_Helper::_set('sd_cart_'.$share_type, serialize($_POST));
			WooCommerce_Session_Helper::_set('sd_cart_shared', serialize($array_of_shared_platforms));
		
		
		endif;
		
		
		
		
		die();
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_sharedisc_add_cart_discount
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_sharedisc_add_cart_discount(){
		session_start();
		global $woocommerce;	
		
		$count = 1;
						
		if(WooCommerce_Session_Helper::_isset('sd_prods_shared')){
			$array_of_shared_prods = unserialize(WooCommerce_Session_Helper::_get('sd_prods_shared'));
									
			foreach($array_of_shared_prods as $KEY=>$SHARES):
				
				foreach($SHARES as $DATA):		
				
					$prod_info = unserialize(WooCommerce_Session_Helper::_get('sd_prod_'.$DATA['type'].'_'.$DATA['key']));
					
					$fee = floatval(floatval($DATA['base']) * floatval($DATA['qty']))*-1;
					
					$woocommerce->cart->add_fee( 'Discount #'.$count.': Shared '.$prod_info['product_title'].' on '.ucfirst($prod_info['type']), $fee, true, 'standard'  );
					
					$count++;
					
					$_SESSION['sd_shared_disc'][$DATA['share_key']] = floatval(floatval($DATA['base']) * floatval($DATA['qty']));
				
				endforeach;
				
			endforeach;
		}
		
		$count = 1;
		
						
		if(WooCommerce_Session_Helper::_isset('sd_cart_shared')){
			$array_of_shared_platforms = unserialize(WooCommerce_Session_Helper::_get('sd_cart_shared'));
			
									
			foreach($array_of_shared_platforms as $KEY=>$SHARES):
					
					
					$fee = floatval(floatval($SHARES['base']) * floatval($SHARES['qty']))*-1;
					
					$woocommerce->cart->add_fee( 'Discount #'.$count.': Shared cart on '.ucfirst($KEY), $fee, true, 'standard'  );
					
					$count++;
					
					$_SESSION['sd_shared_disc'][$SHARES['share_key']] = floatval(floatval($SHARES['base']) * floatval($SHARES['qty']));
				
				
			endforeach;
		}
		
		
    	
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_track_discount_amount
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_track_discount_amount($order_id){
		session_start();
		global $woocommerce;
			
		if(isset($_SESSION['sd_shared_disc'])):
			$disc = serialize($_SESSION['sd_shared_disc']);
			update_post_meta($order_id, '_sd_shared_disc', $disc);			
		endif;	
				
		WooCommerce_Session_Helper::_unset('sd_prods_shared');
		WooCommerce_Session_Helper::_unset('sd_cart_shared');
		unset($_SESSION['sd_shared_disc']);
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_log_for_conversion
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_log_for_conversion(){
		session_start();
		global $woocommerce;
		
				
		if($_REQUEST['sd_log_product_id'] && $_REQUEST['sd_log_conversion_key']):
			
			if(isset($_SESSION['sd_prods_conversion'])):
			
				$array_of_conversion_prods = unserialize($_SESSION['sd_prods_conversion']);
				
				if(!is_array($array_of_conversion_prods)):
				
					$array_of_conversion_prods = array();
						
				endif;
				
			else:
			
				$array_of_conversion_prods = array();	
				
			endif;
			
			if(@!in_array($_REQUEST['sd_log_product_id'], $array_of_conversion_prods)):
			
				$array_of_conversion_prods[$_REQUEST['sd_log_conversion_key']] = $_REQUEST['sd_log_product_id'];
				
				$this->sd_plugin_log_key_click($_REQUEST['sd_log_conversion_key']);
				
			endif;
			
			$_SESSION['sd_prods_conversion'] = serialize($array_of_conversion_prods);
						
			
		endif;
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_track_conversion_key
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_track_conversion_key($order_id){
		session_start();
		global $woocommerce;
			
		if(!isset($_SESSION['sd_prods_conversion'])):
		
			return;	
			
		endif;	
		
		$order = new WC_Order($order_id);
		$items = $order->get_items();
		
		$logged_items = unserialize($_SESSION['sd_prods_conversion']);
						
		$tracked_items = array();
		$all_keys = array();
		
		foreach($logged_items as $share_key => $id):
		
			$all_keys[]= $share_key;
			
		endforeach;
		
		foreach ( $items as $item ) {
			
		    $product_id = intval($item['product_id']);
		    
		    foreach($logged_items as $share_key => $id):
		    	if(intval($id) == $product_id):
		    		$tracked_items[]= $share_key;
				endif;				
			endforeach;
		    
		}				
		
		if(count($all_keys) > 0):
		
			update_post_meta($order_id, '_sd_conversion_general', $all_keys);
			
		endif;
		
		if(count($tracked_items) > 0):
		
			update_post_meta($order_id, '_sd_conversion_prod', $tracked_items);
			
		endif;
		
		unset($_SESSION['sd_prods_conversion']);
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_log_actual_conversion
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_log_actual_conversion($order_id){
			
		$logged_items_prod = get_post_meta($order_id, '_sd_conversion_prod', true);
		$logged_items_gen = get_post_meta($order_id, '_sd_conversion_general', true);
		
		if($logged_items_prod){
			
			foreach($logged_items_prod as $ID=>$SK){
				$this->sd_plugin_handle_the_conversion($SK);
		    	unset($logged_items[$ID]);
			}
			
			if(count($logged_items_prod) > 0):
				update_post_meta($order_id, '_sd_conversion_prod', $logged_items_prod);
			else:
				delete_post_meta($order_id, '_sd_conversion_prod');
			endif;
			
		}
		
		
		if($logged_items_gen){
			
			foreach($logged_items_gen as $ID=>$SK){
				$this->sd_plugin_handle_the_gen_conversion($SK, $order_id);
		    	unset($logged_items_gen[$ID]);
			}
			
			if(count($logged_items_gen) > 0):
				update_post_meta($order_id, '_sd_conversion_general', $logged_items_gen);
			else:
				delete_post_meta($order_id, '_sd_conversion_general');
			endif;
			
		}
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_log_actual_conversion
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_log_actual_discount($order_id){
			
		$discount = get_post_meta($order_id, '_sd_shared_disc', true);
		$discount = maybe_unserialize($discount);
		if($discount){
				
			foreach($discount as $KEY=>$AMT){
				$this->sd_plugin_handle_the_discount($KEY, $AMT);	
			}	
			
		}
		
		delete_post_meta($order_id, '_sd_shared_disc');
		
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_handle_the_conversion
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_handle_the_discount($KEY, $AMT){
		$the_product_site = get_bloginfo('siteurl');
		$api_string = $this->api_url."sharedisc_log_discount/?sd_ck=".get_option('sd_consumer_key').'&sd_key='.$KEY.'&sd_site='.get_bloginfo('siteurl').'&sd_amount='.$AMT;
		wp_remote_post($api_string);
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_handle_the_conversion
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_handle_the_conversion($share_key){
		$api_string = $this->api_url."sharedisc_log_conversion_product/?sd_ck=".get_option('sd_consumer_key').'&sd_share_key='.$share_key;
		wp_remote_post($api_string);
	}
	
	
	
	
		/**
	 * sd_plugin_handle_the_gen_conversion
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_handle_the_gen_conversion($share_key, $order){
			
		$the_order = new WC_Order($order);
		
		$products = $the_order->get_item_count();
		
		//EXCLUDE SHIPPING
		$the_order_value = $the_order->get_subtotal();
		
		//INCLUDE SHIPPING
		$the_order_value = $the_order->get_total();
		
		$api_string = $this->api_url."sharedisc_log_conversion_general/?sd_ck=".get_option('sd_consumer_key').'&sd_share_key='.$share_key.'&sd_order='.$order.'&sd_prods='.$products.'&sd_value='.$the_order_value;
		wp_remote_post($api_string);
	}
	
	
	
	
	
	
	
	
	
			
	/**
	 * sd_plugin_log_key_click
	 *
	 * @since 1.0.0
	*/
	function sd_plugin_log_key_click($share_key){
		$api_string = $this->api_url."sharedisc_log_click/?sd_ck=".get_option('sd_consumer_key').'&sd_share_key='.$share_key;
		wp_remote_post($api_string);
	}
		

}
