<?php
/**
 * JWT authentication endpoint details and curl examples.
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
 * Provides JWT token and token validation endpoint information.
 */
class Mo_API_Authentication_JWT_Endpoints {

	/**
	 * Return JWT token and token validation endpoints with curl examples.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_jwt_auth_endpoints() {
		$token_url    = get_rest_url( null, 'api/v1/token' );
		$validate_url = get_rest_url( null, 'api/v1/token-validate' );
		$is_configured = 'jwt_auth' === get_option( 'mo_api_authentication_selected_authentication_method', '' );

		return array(
			'auth_method'       => 'jwt_auth',
			'jwt_configured'    => $is_configured,
			'signing_algorithm' => get_option( 'mo_api_authentication_jwt_signing_algorithm', 'HS256' ),
			'token_type'        => 'Bearer',
			'endpoints'         => array(
				'token'          => self::get_token_endpoint( $token_url ),
				'token_validate' => self::get_token_validate_endpoint( $validate_url ),
			),
			'usage_notes'       => array(
				__( 'Use the token endpoint to exchange a WordPress username and password for a JWT.', 'wp-rest-api-authentication' ),
				__( 'Use the token validation endpoint to verify that a JWT is valid before calling protected REST APIs.', 'wp-rest-api-authentication' ),
				__( 'Pass the JWT in the Authorization header as Bearer {jwt_token} when accessing protected endpoints.', 'wp-rest-api-authentication' ),
			),
		);
	}

	/**
	 * Token endpoint details.
	 *
	 * @param string $url Full REST URL for the token endpoint.
	 * @return array<string, mixed>
	 */
	private static function get_token_endpoint( $url ) {
		return array(
			'name'        => __( 'Get JWT Token', 'wp-rest-api-authentication' ),
			'route'       => '/api/v1/token',
			'url'         => $url,
			'method'      => 'POST',
			'description' => __( 'Generates a JWT using WordPress username and password credentials.', 'wp-rest-api-authentication' ),
			'request'     => array(
				'body' => array(
					'username' => __( 'WordPress username', 'wp-rest-api-authentication' ),
					'password' => __( 'WordPress password', 'wp-rest-api-authentication' ),
				),
			),
			'example_response' => array(
				'token_type' => 'Bearer',
				'iat'        => 1710000000,
				'expires_in' => 1867680000,
				'jwt_token'  => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...',
			),
			'curl_examples' => array(
				'form_data' => self::build_curl_command(
					'POST',
					$url,
					array(
						"--form 'username=\"your_username\"'",
						"--form 'password=\"your_password\"'",
					)
				),
				'json'      => self::build_curl_command(
					'POST',
					$url,
					array(
						"--header 'Content-Type: application/json'",
						"--data-raw '{\"username\":\"your_username\",\"password\":\"your_password\"}'",
					)
				),
			),
		);
	}

	/**
	 * Token validation endpoint details.
	 *
	 * @param string $url Full REST URL for the token validation endpoint.
	 * @return array<string, mixed>
	 */
	private static function get_token_validate_endpoint( $url ) {
		return array(
			'name'        => __( 'Validate JWT Token', 'wp-rest-api-authentication' ),
			'route'       => '/api/v1/token-validate',
			'url'         => $url,
			'method'      => 'GET',
			'description' => __( 'Validates a JWT sent in the Authorization header.', 'wp-rest-api-authentication' ),
			'request'     => array(
				'headers' => array(
					'Authorization' => 'Bearer YOUR_JWT_TOKEN',
				),
			),
			'example_response' => array(
				'status'  => 'TRUE',
				'message' => 'VALID_TOKEN',
				'code'    => '200',
			),
			'curl_examples' => array(
				'bearer_token' => self::build_curl_command(
					'GET',
					$url,
					array(
						"--header 'Authorization: Bearer YOUR_JWT_TOKEN'",
					)
				),
			),
		);
	}

	/**
	 * Build a multiline curl command string.
	 *
	 * @param string               $method  HTTP method.
	 * @param string               $url     Request URL.
	 * @param array<int, string>   $options Additional curl options.
	 * @return string
	 */
	private static function build_curl_command( $method, $url, $options ) {
		$lines   = array(
			"curl --location --request {$method} '{$url}' \\",
		);
		$options = array_values( $options );

		foreach ( $options as $index => $option ) {
			$suffix    = ( $index === count( $options ) - 1 ) ? '' : ' \\';
			$lines[] = "\t{$option}{$suffix}";
		}

		return implode( "\n", $lines );
	}
}

/**
 * Return JWT token and token validation endpoints with curl examples.
 *
 * @return array<string, mixed>
 */
function mo_api_authentication_get_jwt_auth_endpoints() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_JWT_Endpoints::get_jwt_auth_endpoints();
}
