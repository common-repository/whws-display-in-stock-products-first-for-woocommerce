<?php
/*
   Plugin Name: WHWS Display In Stock Products First For WooCommerce
   Version: 0.2
   Author: David G
   Author URI: https://skenniweb.com
   Donate link: https://www.buymeacoffee.com/Ll7myjoqA
   Description: Use this handy little plugin to change the default sort order and display in stock products first in your WooCommerce store.
   Text Domain: whws-display-in-stock-products-first-for-woocommerce
   License: GPLv3
*/
class whws_display_in_stock_products_first
{
	public function __construct()
	{
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			add_filter('posts_clauses', array($this, 'whws_display_in_stock_products_first_funct'), 900);
		}
	}
	public function whws_display_in_stock_products_first_funct($posts_clauses)
	{
		global $wpdb;
      
		if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())) {
			$posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta whwsstock ON ($wpdb->posts.ID = whwsstock.post_id) ";
			$posts_clauses['orderby'] = " whwsstock.meta_value ASC, " . $posts_clauses['orderby'];
			$posts_clauses['where'] = " AND whwsstock.meta_key = '_stock_status' AND whwsstock.meta_value <> '' " . $posts_clauses['where'];
		}
		return $posts_clauses;
	}
}
new whws_display_in_stock_products_first;

function whws_add_plugin_page_settings_link2( $links ) {
	$links[] = '<a target="_blank" href="https://www.buymeacoffee.com/Ll7myjoqA">' . __('Donate to this plugin') . '</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'whws_add_plugin_page_settings_link2');
