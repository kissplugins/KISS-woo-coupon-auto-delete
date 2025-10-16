<?php
/**
 * Logger Class
 *
 * Handles logging of coupon deletion events using WooCommerce logger
 */

defined( 'ABSPATH' ) || exit;

class NCC_Logger {

	/**
	 * Get WooCommerce logger instance
	 *
	 * @return WC_Logger
	 */
	private static function get_logger() {
		return wc_get_logger();
	}

	/**
	 * Log a message
	 *
	 * @param string $message Message to log
	 * @param string $level Log level (info, error, warning, etc.)
	 */
	public static function log( $message, $level = 'info' ) {
		if ( ! NCC_ENABLE_LOGGING ) {
			return;
		}

		$logger = self::get_logger();
		$logger->log( $level, $message, array( 'source' => 'neochrome-coupon-cleanup' ) );
	}

	/**
	 * Log a coupon deletion
	 *
	 * @param string $coupon_code Coupon code that was deleted
	 * @param int $expiry_timestamp Unix timestamp when coupon expired
	 */
	public static function log_deletion( $coupon_code, $expiry_timestamp ) {
		if ( ! NCC_ENABLE_LOGGING ) {
			return;
		}

		$message = sprintf(
			'Deleted expired coupon: %s (expired: %s)',
			$coupon_code,
			date( 'Y-m-d H:i:s', $expiry_timestamp )
		);

		self::log( $message, 'info' );
	}
}
