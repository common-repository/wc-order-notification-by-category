<?php 
/* 
Plugin Name:    Order Notification By Category for WooCommerce
Description:    This will add more order notification recipients by order item categories. You can notify the different people on orders received on the base of the order item's categories.
Version:        1.0.0
Author:         LogicEverest 
Author URI:     http://logiceverest.com 
License:        GPLv2 or later
License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:    woo_category_alert
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LE_WOO_CATEGORY_ALERT_VERSION', '1.0.0' );

require plugin_dir_path(__FILE__) . 'class.woo-category-alert.php';

/**
 * @return LE_WooCategoryAlert
 */
function le_woo_category_alert() {
    return LE_WooCategoryAlert::instance();
}

le_woo_category_alert();