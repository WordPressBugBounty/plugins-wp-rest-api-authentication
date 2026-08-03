<?php
/**
 * OAuth authentication methods registry and configuration reader.
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
 * Provides OAuth method definitions and configured-method lookup.
 */
class Mo_API_Authentication_OAuth_Methods {

	/**
	 * OAuth 2.0 grant-type method definitions.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function get_all_authentication_methods_definitions() {
		return array(
			'JWT Authentication' => array(
				'id'          => 'jwt_auth',
				'name'        => 'JWT Authentication',
				'category'    => 'oauth2',
				'grant_type'  => 'jwt',
				'token_type'  => 'Bearer',
				'encryption'  => 'HS256',
			),
			'Basic Authentication' => array(
				'id'          => 'basic_auth',
				'name'        => 'Basic Authentication',
				'category'    => 'oauth2',
				'grant_type'  => 'basic',
				'token_type'  => 'Basic',
				'encryption'  => 'base64',
			),
		);
	}

	/**
	 * Auth method IDs that can be configured on the free plan.
	 *
	 * @return array<int, string>
	 */
	public static function get_free_plan_configurable_method_ids() {
		return array( 'basic_auth', 'jwt_auth' );
	}

	/**
	 * Normalize a user-supplied auth method identifier to an internal method ID.
	 *
	 * @param string $auth_method Raw auth method value from user input.
	 * @return string|null Internal method ID, or null if not recognized.
	 */
	public static function normalize_auth_method_id( $auth_method ) {
		$auth_method = strtolower( trim( sanitize_text_field( (string) $auth_method ) ) );

		$aliases = array(
			'basic_auth' => 'basic_auth',
			'basic'      => 'basic_auth',
			'jwt_auth'   => 'jwt_auth',
			'jwt'        => 'jwt_auth',
		);

		return isset( $aliases[ $auth_method ] ) ? $aliases[ $auth_method ] : null;
	}

	/**
	 * Configure an authentication method for the site.
	 *
	 * @param string               $auth_method Auth method to configure.
	 * @param array<string, mixed> $args        Optional configuration arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function configure_authentication_method( $auth_method, $args = array() ) {
		$normalized_method = self::normalize_auth_method_id( $auth_method );

		if ( null === $normalized_method || ! in_array( $normalized_method, self::get_free_plan_configurable_method_ids(), true ) ) {
			return array(
				'status'           => 'error',
				'code'             => '403',
				'message'          => __( 'This method is not available with your current plan. Only Basic Authentication and JWT Authentication can be configured.', 'wp-rest-api-authentication' ),
				'requested_method' => sanitize_text_field( (string) $auth_method ),
				'allowed_methods'  => self::get_free_plan_configurable_method_ids(),
			);
		}

		if ( 'basic_auth' === $normalized_method ) {
			return self::configure_basic_authentication( $args );
		} elseif ( 'jwt_auth' === $normalized_method ) {
			return self::configure_jwt_authentication();
		}

		return array(
			'status'           => 'error',
			'code'             => '422',
			'message'          => __( 'Authentication method cannot be configured.', 'wp-rest-api-authentication' ),
			'requested_method' => sanitize_text_field( (string) $auth_method ),
			'allowed_methods'  => self::get_free_plan_configurable_method_ids(),
		);
	}

	/**
	 * Configure Basic Authentication.
	 *
	 * @param array<string, mixed> $args Optional configuration arguments.
	 * @return array<string, mixed>|\WP_Error
	 */
	private static function configure_basic_authentication( $args = array() ) {
		$credential_type = isset( $args['credential_type'] ) ? sanitize_text_field( (string) $args['credential_type'] ) : 'uname_pass';

		if ( 'cid_secret' === $credential_type ) {
			return array(
				'status'  => 'error',
				'code'    => '403',
				'message' => __( 'Client ID and Secret based Basic Authentication is not available with your current plan.', 'wp-rest-api-authentication' ),
			);
		}

		update_option( 'mo_api_authentication_selected_authentication_method', 'basic_auth' );
		update_option( 'mo_api_authentication_authentication_key', 'uname_pass' );
		delete_option( 'mo_rest_api_ajax_method_data' );

		return array(
			'status'          => 'success',
			'code'            => '200',
			'message'         => __( 'Basic Authentication Method is configured successfully.', 'wp-rest-api-authentication' ),
			'active_method'   => 'basic_auth',
			'credential_type' => 'uname_pass',
		);
	}

	/**
	 * Configure JWT Authentication.
	 *
	 * @return array<string, mixed>
	 */
	private static function configure_jwt_authentication() {
		update_option( 'mo_api_authentication_selected_authentication_method', 'jwt_auth' );

		if ( empty( get_option( 'mo_api_authentication_jwt_client_secret' ) ) ) {
			update_option( 'mo_api_authentication_jwt_client_secret', stripslashes( wp_generate_password( 32, false, false ) ) );
		}

		update_option( 'mo_api_authentication_jwt_signing_algorithm', 'HS256' );

		return array(
			'status'            => 'success',
			'code'              => '200',
			'message'           => __( 'JWT Authentication Method is configured successfully.', 'wp-rest-api-authentication' ),
			'active_method'     => 'jwt_auth',
			'signing_algorithm' => 'HS256',
		);
	}

	/**
	 * Return configured Authentication methods.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_configured_oauth_methods() {
		$selected_method = get_option( 'mo_api_authentication_selected_authentication_method', '' );

		$response = array(
			'status'                 => 'success',
			'code'                   => '200',
			'active_method'          => $selected_method,
		);

		return $response;
	}
}

/**
 * Return all configured OAuth authentication methods.
 *
 * @return array<string, mixed>
 */
function mo_api_authentication_get_configured_oauth_methods() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_OAuth_Methods::get_configured_oauth_methods();
}

/**
 * Return all available authentication method definitions.
 *
 * @return array<string, array<string, mixed>>
 */
function mo_api_authentication_get_all_authentication_methods_definitions() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_OAuth_Methods::get_all_authentication_methods_definitions();
}

/**
 * Configure an authentication method for the site.
 *
 * @param string               $auth_method Auth method to configure.
 * @param array<string, mixed> $args        Optional configuration arguments.
 * @return array<string, mixed>|\WP_Error
 */
function mo_api_authentication_configure_authentication_method( $auth_method, $args = array() ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_OAuth_Methods::configure_authentication_method( $auth_method, $args );
}
