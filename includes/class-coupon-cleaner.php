<?php
/**
 * Coupon Cleaner Class
 *
 * Handles the core logic for finding and deleting expired coupons
 */

defined( 'ABSPATH' ) || exit;

class NCC_Coupon_Cleaner {

	/**
	 * Run the cleanup process
	 * Finds expired coupons and deletes them in batches
	 *
	 * @return int Number of coupons deleted
	 */
	public static function run() {
		// Query expired coupons
		$args = array(
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'posts_per_page' => NCC_BATCH_SIZE,
			'meta_query'     => array(
				array(
					'key'     => 'date_expires',
					'value'   => time(),
					'compare' => '<',
					'type'    => 'NUMERIC'
				)
			),
			'fields' => 'ids'
		);

		$coupon_ids = get_posts( $args );

		if ( empty( $coupon_ids ) ) {
			return 0; // No expired coupons found
		}

		$deleted_count = 0;

		foreach ( $coupon_ids as $coupon_id ) {
			$coupon = new WC_Coupon( $coupon_id );

			// Safety check: verify expiration
			$expiry_date = $coupon->get_date_expires();

			if ( ! $expiry_date ) {
				continue; // Skip if no expiry date
			}

			if ( time() > $expiry_date->getTimestamp() ) {
				// Delete coupon
				$coupon_code = $coupon->get_code();
				$expiry_time = $expiry_date->getTimestamp();

				$deleted = wp_delete_post( $coupon_id, true );

				if ( $deleted ) {
					$deleted_count++;
					NCC_Logger::log_deletion( $coupon_code, $expiry_time );
				}
			}
		}

		return $deleted_count;
	}
}
