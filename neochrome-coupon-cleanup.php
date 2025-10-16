<?php
/**
 * Plugin Name: KISS Coupon Cleanup
 * Description: Automatically deletes expired WooCommerce coupons in batches via cron
 * Version: 1.0.0
 * Author: Neochrome
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 6.0
 * WC tested up to: 8.0
 */

defined( 'ABSPATH' ) || exit;

// Constants
define( 'NCC_VERSION', '1.0.0' );
define( 'NCC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NCC_BATCH_SIZE', 20 );              // Coupons per cron run
define( 'NCC_ENABLE_LOGGING', true );        // Enable/disable logs

/**
 * Check WooCommerce dependency and load plugin
 */
add_action( 'plugins_loaded', 'ncc_check_dependencies' );
function ncc_check_dependencies() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'ncc_wc_missing_notice' );
		return;
	}

	// Load classes
	require_once NCC_PLUGIN_DIR . 'includes/class-logger.php';
	require_once NCC_PLUGIN_DIR . 'includes/class-coupon-cleaner.php';
	require_once NCC_PLUGIN_DIR . 'includes/class-cron-manager.php';

	// Initialize
	NCC_Cron_Manager::init();
}

/**
 * Admin notice if WooCommerce is not active
 */
function ncc_wc_missing_notice() {
	echo '<div class="error"><p><strong>Neochrome Coupon Cleanup</strong> requires WooCommerce to be installed and active.</p></div>';
}

/**
 * Activation hook - schedule cron job
 */
register_activation_hook( __FILE__, 'ncc_activate' );
function ncc_activate() {
	// Check if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'Neochrome Coupon Cleanup requires WooCommerce to be installed and active.' );
	}

	// Load dependencies
	require_once NCC_PLUGIN_DIR . 'includes/class-logger.php';
	require_once NCC_PLUGIN_DIR . 'includes/class-cron-manager.php';

	// Schedule cron
	NCC_Cron_Manager::schedule();
	NCC_Logger::log( 'Plugin activated and cron scheduled' );
}

/**
 * Deactivation hook - clear scheduled cron job
 */
register_deactivation_hook( __FILE__, 'ncc_deactivate' );
function ncc_deactivate() {
	// Load dependencies
	require_once NCC_PLUGIN_DIR . 'includes/class-logger.php';
	require_once NCC_PLUGIN_DIR . 'includes/class-cron-manager.php';

	// Clear cron
	NCC_Cron_Manager::unschedule();
	NCC_Logger::log( 'Plugin deactivated and cron cleared' );
}
