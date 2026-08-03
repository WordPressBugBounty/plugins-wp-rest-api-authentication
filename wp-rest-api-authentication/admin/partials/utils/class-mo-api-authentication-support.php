<?php
/**
 * Support request helpers for the plugin.
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
 * Handles support query submissions for abilities and programmatic use.
 */
class Mo_API_Authentication_Support_Requests {

	/**
	 * Submit a support query using the logged-in user's email address.
	 *
	 * @param string $query Support message from the user.
	 * @param string $phone Optional contact phone number.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function submit_support_query( $query, $phone = '' ) {
		$query = trim( sanitize_textarea_field( (string) $query ) );
		$phone = sanitize_text_field( (string) $phone );

		if ( '' === $query ) {
			return new WP_Error(
				'missing_query',
				__( 'The query field is required.', 'wp-rest-api-authentication' )
			);
		}

		$current_user = wp_get_current_user();
		$email        = sanitize_email( $current_user->user_email );

		if ( empty( $email ) || ! is_email( $email ) ) {
			return new WP_Error(
				'missing_user_email',
				__( 'The logged-in user does not have a valid email address.', 'wp-rest-api-authentication' )
			);
		}

		if ( ! class_exists( 'Miniorange_API_Authentication_Customer' ) ) {
			require_once plugin_dir_path( __DIR__ ) . '../../class-miniorange-api-authentication-customer.php';
		}

		$version        = get_option( 'mo_api_authentication_current_plugin_version', defined( 'MINIORANGE_API_AUTHENTICATION_VERSION' ) ? MINIORANGE_API_AUTHENTICATION_VERSION : '' );
		$prefixed_query = '[REST API Authentication for WP plugin] version ' . $version . ' - ' . $query;

		$fields = array(
			'firstName' => $current_user->user_firstname,
			'lastName'  => $current_user->user_lastname,
			'company'   => ! empty( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '',
			'email'     => $email,
			'ccEmail'   => 'apisupport@xecurify.com',
			'phone'     => $phone,
			'query'     => $prefixed_query,
		);

		$url  = get_option( 'host_name', Miniorange_API_Authentication_Customer::HOST_NAME ) . '/moas/rest/customer/contact-us';
		$args = array(
			'method'      => 'POST',
			'body'        => wp_json_encode( $fields ),
			'timeout'     => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(
				'Content-Type'  => 'application/json; charset=utf-8',
				'Authorization' => 'Basic',
			),
		);

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'support_request_failed',
				__( 'Your query could not be submitted. Please try again.', 'wp-rest-api-authentication' ),
				array(
					'error' => $response->get_error_message(),
				)
			);
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( $response_code < 200 || $response_code >= 300 ) {
			return array(
				'status'  => 'error',
				'code'    => (string) $response_code,
				'message' => __( 'Your query could not be submitted. Please try again.', 'wp-rest-api-authentication' ),
				'email'   => $email,
			);
		}

		return array(
			'status'  => 'success',
			'code'    => '200',
			'message' => __( 'Thanks for getting in touch! We shall get back to you shortly.', 'wp-rest-api-authentication' ),
			'email'   => $email,
		);
	}
}

/**
 * Submit a support query using the logged-in user's email address.
 *
 * @param string $query Support message from the user.
 * @param string $phone Optional contact phone number.
 * @return array<string, mixed>|\WP_Error
 */
function mo_api_authentication_submit_support_query( $query, $phone = '' ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_Support_Requests::submit_support_query( $query, $phone );
}
