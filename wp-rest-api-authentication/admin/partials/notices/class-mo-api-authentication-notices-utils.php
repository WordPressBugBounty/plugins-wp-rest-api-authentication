<?php
/**
 * This is a utility file for WordPress notices.
 *
 * @package    Miniorange_Api_Authentication
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Mo_API_Authentication_Notices_Utils
 *
 * Utility class for WordPress admin notices.
 */
class Mo_API_Authentication_Notices_Utils {

	/**
	 * Check if the notice time has not expired.
	 *
	 * @param int $close_time The time the notice was closed.
	 * @param int $time The current time to check against.
	 * @param int $unit The unit of time to check against.
	 *
	 * @return bool True if the notice time has not expired, false otherwise.
	 */
	public static function if_notice_time_remaining( $close_time, $time, $unit ) {
		return $close_time > 0 && ( time() - $close_time ) < ( $time * $unit );
	}
}