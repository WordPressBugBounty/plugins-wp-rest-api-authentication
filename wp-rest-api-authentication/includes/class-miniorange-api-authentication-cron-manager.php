<?php
/**
 * Fired during plugin deactivation
 *
 * @link       miniorange
 *
 * @package    Miniorange_Api_Authentication
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cron Manager Class for Miniorange API Authentication Plugin
 */
class Miniorange_Api_Authentication_Cron_Manager {
	/**
	 * Constructor for the Miniorange_Api_Authentication_Cron_Manager class.
	 *
	 * Schedules the daily cron job if it's not already scheduled.
	 */
	public function __construct() {
		if ( ! wp_next_scheduled( 'mo_api_auth_daily_cron_hook' ) ) {
			wp_schedule_event( time(), 'daily', 'mo_api_auth_daily_cron_hook' );
		}
		add_action( 'mo_api_auth_daily_cron_hook', 'daily_cron_function' );
	}

	/**
	 * Clear the scheduled cron job on plugin deactivation.
	 */
	public static function clear_daily_cron() {
		$timestamp = wp_next_scheduled( 'mo_api_auth_daily_cron_hook' );
		wp_unschedule_event( $timestamp, 'mo_api_auth_daily_cron_hook' );
	}

	/**
	 * Cron job function to check if the summary box should be shown again.
	 */
	public function daily_cron_function() {
		$close_time = get_option( 'mo_api_auth_summary_box_close_time', 0 );

		if ( $close_time > 0 && ( time() - $close_time ) >= ( 7 * DAY_IN_SECONDS ) ) {
			// 7 days have passed, reset the option to show the summary box again
			delete_option( 'mo_api_auth_summary_box_close_time' );
		}
	}
}
