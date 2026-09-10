<?php
/**
 * Handle API protection
 * This file will handle the Basic Authentication flow to protect the REST APIs.
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
 * [Handle Basic Authentication method for API protection]
 */
class Mo_API_Authentication_Basic_OAuth {

	/**
	 * Check if valid request
	 *
	 * @param mixed $headers API request headers.
	 * @return bool
	 */
	public static function mo_api_auth_is_valid_request( $headers ) {
		if ( ( isset( $headers['AUTHORIZATION'] ) && '' !== $headers['AUTHORIZATION'] ) || ( isset( $headers['AUTHORISATION'] ) && '' !== $headers['AUTHORISATION'] ) ) {
			if ( isset( $headers['AUTHORIZATION'] ) && '' !== $headers['AUTHORIZATION'] ) {
				$authorization_header = explode( ' ', $headers['AUTHORIZATION'] );
			} elseif ( isset( $headers['AUTHORISATION'] ) && '' !== $headers['AUTHORISATION'] ) {
				$authorization_header = explode( ' ', $headers['AUTHORISATION'] );
			}

			if ( isset( $authorization_header[0] ) && ( strcasecmp( $authorization_header[0], 'Basic' ) === 0 ) && isset( $authorization_header[1] ) && '' !== $authorization_header[1] ) {
				$encoded_creds       = $authorization_header[1];
				$decoded_cred_string = base64_decode( $encoded_creds ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Using base64 for verifying standard basic authentication method and ignoring sanitization because we are not storing this in database.
				$creds               = explode( ':', $decoded_cred_string, 2 );

				if ( isset( $creds[0] ) && isset( $creds[1] ) ) {
					if ( get_option( 'mo_api_authentication_authentication_key' ) === 'uname_pass' || ( function_exists( 'mo_api_auth_get_privileged_test_config' ) && 'basic_auth' === mo_api_auth_get_privileged_test_config() ) ) {
						// username and password.
						$uname = sanitize_user( $creds[0] );
						$pword = $creds[1];
						$user  = Mo_API_Authentication_Utils::verify_user_credentials( $uname, $pword );

						if ( $user ) {
							wp_set_current_user( $user->ID );
							if ( function_exists( 'mo_api_auth_mark_token_authenticated' ) ) {
								mo_api_auth_mark_token_authenticated( (int) $user->ID );
							}
							// The Protected API success request counter is increasing.
							Mo_API_Authentication_Utils::increment_success_counter( Mo_API_Authentication_Constants::PROTECTED_API );
							return true;
						}

						// An unknown username and an incorrect password take the same code path and
						// return the same response, so neither the message nor the response time
						// reveals whether the account exists.
						Mo_API_Authentication_Utils::increment_blocked_counter( Mo_API_Authentication_Constants::INVALID_CREDENTIALS );
						$response = array(
							'status'            => 'error',
							'error'             => 'INVALID_CREDENTIALS',
							'code'              => '400',
							'error_description' => 'Invalid username or password.',
						);
						wp_send_json( $response, 400 );
					} elseif ( get_option( 'mo_api_authentication_authentication_key' ) === 'cid_secret' ) {
						// client id and client secret.
						if ( get_option( 'mo_api_auth_clientid' ) === $creds[0] && get_option( 'mo_api_auth_clientsecret' ) === $creds[1] ) {
							if ( function_exists( 'mo_api_auth_mark_token_authenticated' ) ) {
								mo_api_auth_mark_token_authenticated();
							}
							// The Protected API success request counter is increasing.
							Mo_API_Authentication_Utils::increment_success_counter( Mo_API_Authentication_Constants::PROTECTED_API );
							return true;
						} else {
							// Invalid credentials counter is increasing.
							Mo_API_Authentication_Utils::increment_blocked_counter( Mo_API_Authentication_Constants::INVALID_CREDENTIALS );
							$response = array(
								'status'            => 'error',
								'error'             => 'INVALID_CLIENT_CREDENTIALS',
								'code'              => '400',
								'error_description' => 'Invalid client ID or client secret.',
							);
							wp_send_json( $response, 400 );
						}
					}
				} else {
					// Invalid credentials counter is increasing.
					Mo_API_Authentication_Utils::increment_blocked_counter( Mo_API_Authentication_Constants::INVALID_CREDENTIALS );
					$response = array(
						'status'            => 'error',
						'error'             => 'INVALID_TOKEN_FORMAT',
						'code'              => '401',
						'error_description' => 'Sorry, you are not using correct format to encode string.',
					);
					wp_send_json( $response, 401 );
				}
			} else {
				// Invalid credentials counter is increasing.
				Mo_API_Authentication_Utils::increment_blocked_counter( Mo_API_Authentication_Constants::INVALID_CREDENTIALS );
				$response = array(
					'status'            => 'error',
					'error'             => 'INVALID_AUTHORIZATION_HEADER_TOKEN_TYPE',
					'code'              => '401',
					'error_description' => 'Authorization header must be type of Basic Token.',
				);
				wp_send_json( $response, 401 );
			}
		}
		// Missing authorization header counter is increasing.
		Mo_API_Authentication_Utils::increment_blocked_counter( Mo_API_Authentication_Constants::MISSING_AUTHORIZATION_HEADER );

		$response = array(
			'status'            => 'error',
			'error'             => 'MISSING_AUTHORIZATION_HEADER',
			'code'              => '401',
			'error_description' => 'Authorization header not received. Either authorization header was not sent or it was removed by your server due to security reasons.',
		);
		wp_send_json( $response, 401 );
	}
}
