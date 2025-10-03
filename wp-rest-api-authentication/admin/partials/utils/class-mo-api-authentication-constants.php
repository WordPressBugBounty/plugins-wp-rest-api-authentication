<?php
/**
 * Constants for API Authentication Plugin
 *
 * This file contains the class Mo_API_Authentication_Constants
 * which holds constants that are used throughout the plugin.
 *
 * @package    Mo_API_Authentication_Plugin
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Mo_API_Authentication_Constants
 *
 * Holds constants for common values used throughout the plugin.
 */
class Mo_API_Authentication_Constants {
	const MISSING_AUTHORIZATION_HEADER = 'MISSING_AUTHORIZATION_HEADER';
	const INVALID_CREDENTIALS          = 'INVALID_CREDENTIALS';
	const PROTECTED_API                = 'PROTECTED_API';
	const OPEN_API                     = 'OPEN_API';
	const SUCCESS                      = 'SUCCESS';
	const BLOCKED                      = 'BLOCKED';
}
