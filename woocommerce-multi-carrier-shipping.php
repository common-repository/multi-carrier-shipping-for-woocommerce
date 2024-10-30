<?php
/*
	Plugin Name: Multi-Carrier Shipping Plugin for WooCommerce Basic
	Plugin URI: https://www.xadapter.com/product/multiple-carrier-shipping-plugin-woocommerce/
	Description: Intuitive Rule Based Multi-Carrier Shipping Plugin for WooCommerce. Set shipping rates based on rules based by Country, State, Post Code, Product Category,Shipping Class ,Weight , Shipping Company and Shipping Service.
	Version: 1.2.14
	Author: PluginHive
	Author URI: https://www.pluginhive.com/
	Copyright: 2017-2018 PluginHive.
	WC requires at least: 2.6.0
	WC tested up to: 3.4
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Plugin activation check
register_activation_hook( __FILE__, function() {
    //check if premium version is there
	if ( is_plugin_active('woocommerce-multi-carrier-shipping/woocommerce-multi-carrier-shipping.php') ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __("Is everything fine? You already have the Premium version installed in your website. For any issues, kindly raise a ticket via <a target='_blank' href='https://support.xadapter.com/'>support.xadapter.com</a>", "eha_multi_carrier_shipping" ), "", array('back_link' => 1 ));
	}
});

$GLOBALS['eha_API_URL']="http://shippingapi.storepep.com";	
//$GLOBALS['eha_API_URL']="http://localhost:3000";	
load_plugin_textdomain( 'eha_multi_carrier_shipping', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

if( ! function_exists('check_if_woocommerce_active') ) {
	function check_if_woocommerce_active(){
		$act=get_option( 'active_plugins' );
		foreach($act as $pname){
			if (strpos($pname, 'woocommerce.php') !== false){
				return true;
			}
		}
		return false;
	}
}

if (check_if_woocommerce_active()===true) {	
//include_once ( 'includes/class-eha-shipping-ups-admin.php' );
	include( 'eha-multi-carrier-shipping-common.php' );
	
	if (!function_exists('wf_plugin_configuration_mcp')){
		function wf_plugin_configuration_mcp(){
			return array(
			'id' => 'wf_multi_carrier_shipping',
			'method_title' => __('Multi Carrier Shipping', 'eha_multi_carrier_shipping' ),
			'method_description' => __('<strong>*Note: These fields are mandatory - Email ID, API Key, Shipper Settings, Carrier Settings</strong>', 'eha_multi_carrier_shipping' ));		
		}
	}


	add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ), 'plugin_action_links_mcp' );
	if( !function_exists('plugin_action_links_mcp') ){
		function plugin_action_links_mcp( $links ) {
		    $plugin_links = array(
		        '<a href="' . admin_url( 'admin.php?page=' . wf_get_settings_url_mcp() . '&tab=shipping&section=wf_multi_carrier_shipping' ) . '">' . __( 'Settings', 'eha_multi_carrier_shipping' ) . '</a>',
		        '<a href="' . admin_url( 'admin.php?page=' . wf_get_settings_url_mcp() . '&tab=shipping&section=wf_multi_carrier_shipping_area' ) . '">' . __( 'Shipping Area', 'eha_multi_carrier_shipping' ) . '</a>',
		        '<a href="https://www.xadapter.com/category/documentation/multiple-carrier-shipping-plugin-for-woocommerce/" target="_blank">' . __('Documentation', 'eha_multi_carrier_shipping') . '</a>',
		        '<a href="https://www.xadapter.com/product/multiple-carrier-shipping-plugin-woocommerce/" target="_blank">' . __('Premium Upgrade', 'wf-shipping-fedex') . '</a>',
		        '<a href="https://www.xadapter.com/support/forum/multiple-carrier-shipping-plugin-woocommerce/" target="_blank">' . __('Support', 'eha_multi_carrier_shipping') . '</a>'
		    );
		    return array_merge( $plugin_links, $links );
		}
	}

}