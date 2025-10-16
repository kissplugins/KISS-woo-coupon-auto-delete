<?php
/**
 * Cron Manager Class
 *
 * Handles cron job scheduling and execution
 */

defined( 'ABSPATH' ) || exit;

class NCC_Cron_Manager {

	const CRON_HOOK = 'neochrome_cleanup_expired_coupons';

	/**
	 * Initialize cron hooks
	 */
	public static function init() {
		add_action( self::CRON_HOOK, array( __CLASS__, 'run_cleanup' ) );
		add_filter( 'cron_schedules', array( __CLASS__, 'add_minute_interval' ) );
	}

	/**
	 * Add custom cron interval (every minute)
	 *
	 * @param array $schedules Existing cron schedules
	 * @return array Modified schedules
	 */
	public static function add_minute_interval( $schedules ) {
		$schedules['every_minute'] = array(
			'interval' => 60,
			'display'  => __( 'Every Minute' )
		);
		return $schedules;
	}

	/**
	 * Schedule the cron job
	 */
	public static function schedule() {
		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time(), 'every_minute', self::CRON_HOOK );
		}
	}

	/**
	 * Unschedule the cron job
	 */
	public static function unschedule() {
		$timestamp = wp_next_scheduled( self::CRON_HOOK );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::CRON_HOOK );
		}
	}

	/**
	 * Run the cleanup process (called by cron)
	 */
	public static function run_cleanup() {
		$deleted_count = NCC_Coupon_Cleaner::run();

		if ( $deleted_count > 0 ) {
			NCC_Logger::log( sprintf( 'Batch complete: %d coupon(s) deleted', $deleted_count ) );
		}
	}
}
