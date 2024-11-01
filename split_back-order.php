<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/*
	Plugin Name: Split Back Order
	Plugin URI: 
	Description: This plugin split order Split Back (sbow)
	Version: 1.0
	Author: SunArc
	Author URI: https://sunarctechnologies.com/
	Text Domain: woocommerce-split-order-category
	License: GPL2

*/

global $wpdb;
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} else {

    clearstatcache();
}


require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
define('sbow_sunarc_plugin_dir', dirname(__FILE__));

register_activation_hook(__FILE__, 'sbow_plugin_activate');

function sbow_plugin_activate() {
    update_option( 'enable_split_back_order', 'no' );
    update_option( 'split_back_order', 'no' );
    update_option( 'split_back_order_salable', 'no' );
    update_option( 'split_back_order_instock', 'no');
    update_option($option_name, $new_value);
	
}

// Deactivation Pluign 
function sbow_deactivation() {
     update_option( 'enable_split_back_order', '' );
    update_option( 'split_back_order', '' );
    update_option( 'split_back_order_salable', '' );
    update_option( 'split_back_order_instock', '');
}

register_deactivation_hook(__FILE__, 'sbow_deactivation');

// Uninstall Pluign 
function sbow_uninstall() {
  update_option( 'enable_split_back_order', '' );
    update_option( 'split_back_order', '' );
    update_option( 'split_back_order_salable', '' );
    update_option( 'split_back_order_instock', '');
    
}


$SUNARC_all_plugins = get_plugins();

$SUNARC_activate_all_plugins = apply_filters('active_plugins', get_option('active_plugins'));

if (array_key_exists('woocommerce/woocommerce.php', $SUNARC_all_plugins) && in_array('woocommerce/woocommerce.php', $SUNARC_activate_all_plugins)) {
      $enable_split_back_order = get_option('enable_split_back_order');
      $split_back_order = get_option('split_back_order');
    //  $split_back_order_salable = get_option('split_back_order_salable');
      $split_back_order_instock = get_option('split_back_order_instock');
     if ($enable_split_back_order == 'yes' && $split_back_order =='no' && $split_back_order_instock=='no') {
        require_once sbow_sunarc_plugin_dir . '/include/splitbackorder.php';
    } elseif($enable_split_back_order == 'yes' && $split_back_order_instock=='yes' && $split_back_order=='no')
	{
		  require_once sbow_sunarc_plugin_dir . '/include/splitbackorder-in-stock.php';
	}
	 elseif($enable_split_back_order == 'yes' && $split_back_order_instock=='yes' && $split_back_order=='yes')
	{
		  require_once sbow_sunarc_plugin_dir . '/include/splitbackorder-in-stock-out-stock.php';
	}
	 elseif($enable_split_back_order == 'yes' && $split_back_order_instock=='no' && $split_back_order=='yes')
	{
		  require_once sbow_sunarc_plugin_dir . '/include/splitbackorder-backorder.php';
	}
}


function sbow_register_my_custom_submenu_page() {
    add_submenu_page( 'woocommerce', 'Split By Back order', 'Split By Back Order', 'manage_options', 'split-back-order', 'sbow_my_custom_submenu_page_callback' ); 
}
function sbow_my_custom_submenu_page_callback() {
  
	 require_once sbow_sunarc_plugin_dir . '/include/setting.php';
}
add_action('admin_menu', 'sbow_register_my_custom_submenu_page',99);


add_action( 'woocommerce_email', 'sbow_remove_hooks' );

function sbow_remove_hooks( $email_class ) {
		remove_action( 'woocommerce_low_stock_notification', array( $email_class, 'low_stock' ) );
		remove_action( 'woocommerce_no_stock_notification', array( $email_class, 'no_stock' ) );
		remove_action( 'woocommerce_product_on_backorder_notification', array( $email_class, 'backorder' ) );
		
		// New order emails
		remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		remove_action( 'woocommerce_order_status_pending_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		remove_action( 'woocommerce_order_status_failed_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		remove_action( 'woocommerce_order_status_failed_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
		
		// Processing order emails
		remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
		remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
		
		// Completed order emails
		remove_action( 'woocommerce_order_status_completed_notification', array( $email_class->emails['WC_Email_Customer_Completed_Order'], 'trigger' ) );
			
		// Note emails
		remove_action( 'woocommerce_new_customer_note_notification', array( $email_class->emails['WC_Email_Customer_Note'], 'trigger' ) );
}


		function action_woocommerce_checkout_order_processed( $order_id, $posted_data, $order ) {
		  $enable_split_back_order = get_option('enable_split_back_order');
      $split_back_order = get_option('split_back_order');
			if($enable_split_back_order=='yes' && $split_back_order=='yes' ){	
		   update_post_meta($order_id,'_order_total',0);  
			}
		}; 
		add_action( 'woocommerce_checkout_order_processed', 'action_woocommerce_checkout_order_processed', 10, 3 ); 

add_filter( 'woocommerce_endpoint_order-received_title', 'sbow_sunarc_thank_you_title' );
 
function sbow_sunarc_thank_you_title( $old_title ){
  $enable_split_back_order = get_option('enable_split_back_order');
  $split_back_order = get_option('split_back_order');
if($enable_split_back_order=='yes'  ){	
  $order_id = wc_get_order_id_by_order_key( $_GET['key'] ); 
  update_post_meta($order_id,'_order_total',0);  
 	?>
	<?php
	 }
}
		

		function __construct() {
            add_action('admin_menu', array($this, 'wos_woocommerce_split_order_menu'));
            if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
                $this->frontend_css_js();
            }
        }

			function sbow_frontend_scripts() {
				$dir = plugin_dir_url(__FILE__);
				wp_enqueue_style('woocommerce_admin_styles');
				wp_enqueue_style('split-backorder-css', $dir . 'assets/css/custom_style.css', array(), '0.1.0', 'all');
				wp_enqueue_script('split-backorder-js', $dir . 'assets/js/custom.js', array(), '0.1.0', 'all');
		     }
		  add_action('admin_enqueue_scripts','sbow_frontend_scripts');
		  
		  
		  add_filter( 'manage_edit-shop_order_columns', 'sbow_bbloomer_add_new_order_admin_list_column' );
 
		function sbow_bbloomer_add_new_order_admin_list_column( $columns ) {
			$columns['billing_country'] = 'Split Back Order';
			return $columns;
		}
		 
		add_action( 'manage_shop_order_posts_custom_column', 'sbow_bbloomer_add_new_order_admin_list_column_content' );
		 
		function sbow_bbloomer_add_new_order_admin_list_column_content( $column ) {
		   
			global $post;
		 
			if ( 'billing_country' === $column ) {
				echo get_post_meta($post->ID,'in_stock_status',true);
				//$order = wc_get_order( $post->ID );
			//	echo $order->get_billing_country();
			  
			}
		}