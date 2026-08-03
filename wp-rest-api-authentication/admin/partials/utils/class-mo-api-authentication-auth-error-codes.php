<?php
/**
 * Basic and JWT authentication error code definitions.
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
 * Provides error codes and troubleshooting explanations for auth methods.
 */
class Mo_API_Authentication_Auth_Error_Codes {

	/**
	 * Return error codes and explanations for Basic and JWT authentication.
	 *
	 * @param array<string, mixed> $args Optional filters.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function get_auth_error_codes( $args = array() ) {
		$auth_method = isset( $args['auth_method'] ) ? sanitize_key( (string) $args['auth_method'] ) : '';
		$error_code  = isset( $args['error'] ) ? strtoupper( sanitize_text_field( (string) $args['error'] ) ) : '';

		$data = array(
			'summary'    => __(
				'These error codes are returned by the plugin during Basic Authentication and JWT Authentication flows, including token generation, token validation, and protected REST API requests.',
				'wp-rest-api-authentication'
			),
			'basic_auth' => self::get_basic_auth_errors(),
			'jwt_auth'   => self::get_jwt_auth_errors(),
		);

		if ( in_array( $auth_method, array( 'basic_auth', 'jwt_auth' ), true ) ) {
			$filtered = array(
				'summary'     => $data['summary'],
				'auth_method' => $auth_method,
				'errors'      => $data[ $auth_method ],
			);

			if ( '' !== $error_code ) {
				$match = self::find_error_by_code( $filtered['errors'], $error_code );
				if ( null === $match ) {
					return new WP_Error(
						'unknown_error_code',
						__( 'The requested error code was not found for this authentication method.', 'wp-rest-api-authentication' ),
						array(
							'auth_method' => $auth_method,
							'error'       => $error_code,
						)
					);
				}

				return array(
					'auth_method' => $auth_method,
					'error'       => $match,
				);
			}

			return $filtered;
		}

		if ( '' !== $error_code ) {
			$match = self::find_error_by_code( array_merge( $data['basic_auth'], $data['jwt_auth'] ), $error_code );
			if ( null === $match ) {
				return new WP_Error(
					'unknown_error_code',
					__( 'The requested error code was not found.', 'wp-rest-api-authentication' ),
					array(
						'error' => $error_code,
					)
				);
			}

			return array(
				'error' => $match,
			);
		}

		return $data;
	}

	/**
	 * Find a single error definition by code.
	 *
	 * @param array<int, array<string, mixed>> $errors     Error definitions.
	 * @param string                           $error_code Error code slug.
	 * @return array<string, mixed>|null
	 */
	private static function find_error_by_code( $errors, $error_code ) {
		foreach ( $errors as $error ) {
			if ( isset( $error['error'] ) && $error_code === $error['error'] ) {
				return $error;
			}
		}

		return null;
	}

	/**
	 * Shared authorization header troubleshooting guidance.
	 *
	 * @return array<string, mixed>
	 */
	private static function get_missing_authorization_header_help() {
		return array(
			'explanation' => array(
				__( 'Verify that the Authorization header is included in the request.', 'wp-rest-api-authentication' ),
				__( 'Some servers strip the Authorization header before WordPress receives the request.', 'wp-rest-api-authentication' ),
			),
			'resolution_steps' => array(
				__( 'For Apache, add the Authorization rewrite rules to your .htaccess file.', 'wp-rest-api-authentication' ),
				__( 'For NGINX, add Authorization to Access-Control-Allow-Headers in your server config.', 'wp-rest-api-authentication' ),
			),
			'server_configuration' => array(
				'apache_htaccess' => "RewriteEngine On\nRewriteCond %{HTTP:Authorization} ^(.*)\nRewriteRule .* - [e=HTTP_AUTHORIZATION:%1]",
				'nginx_config'    => 'add_header Access-Control-Allow-Headers "Authorization";',
			),
		);
	}

	/**
	 * Basic Authentication error definitions.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private static function get_basic_auth_errors() {
		$missing_header_help = self::get_missing_authorization_header_help();

		return array(
			array(
				'error'             => 'INVALID_PASSWORD',
				'code'              => '400',
				'error_description' => 'Incorrect password.',
				'context'           => 'protected_api_request',
				'explanation'       => array(
					__( 'The supplied username exists, but the password did not match.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Check that the username and password are correct.', 'wp-rest-api-authentication' ),
					__( 'If the password is correct, try a password without special characters.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'INVALID_USERNAME',
				'code'              => '400',
				'error_description' => 'Username Does not exist.',
				'context'           => 'protected_api_request',
				'explanation'       => array(
					__( 'No WordPress user exists with the supplied username.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Check that the username exists and is spelled correctly.', 'wp-rest-api-authentication' ),
					__( 'Use the WordPress username, not the email address. Email-based Basic Authentication is available on the Premium plan only.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'INVALID_CLIENT_CREDENTIALS',
				'code'              => '400',
				'error_description' => 'Invalid client ID or client secret.',
				'context'           => 'protected_api_request',
				'explanation'       => array(
					__( 'The Basic Authentication request used client ID and client secret credentials that do not match the configured values.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Verify the client ID and client secret configured in the plugin.', 'wp-rest-api-authentication' ),
					__( 'Client ID and secret based Basic Authentication requires a Premium plan.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'INVALID_TOKEN_FORMAT',
				'code'              => '401',
				'error_description' => 'Sorry, you are not using correct format to encode string.',
				'context'           => 'protected_api_request',
				'explanation'       => array(
					__( 'The Authorization header is present, but the Base64-encoded Basic credentials are not in username:password format.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Encode credentials as Base64(username:password).', 'wp-rest-api-authentication' ),
					__( 'Send the header as Authorization: Basic {encoded_credentials}.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'INVALID_AUTHORIZATION_HEADER_TOKEN_TYPE',
				'code'              => '401',
				'error_description' => 'Authorization header must be type of Basic Token.',
				'context'           => 'protected_api_request',
				'explanation'       => array(
					__( 'The Authorization header was sent, but it is not a Basic token.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Use Authorization: Basic {encoded_credentials}.', 'wp-rest-api-authentication' ),
					__( 'Do not use Bearer for Basic Authentication requests.', 'wp-rest-api-authentication' ),
				),
			),
			array_merge(
				array(
					'error'             => 'MISSING_AUTHORIZATION_HEADER',
					'code'              => '401',
					'error_description' => 'Authorization header not received. Either authorization header was not sent or it was removed by your server due to security reasons.',
					'context'           => 'protected_api_request',
				),
				$missing_header_help
			),
		);
	}

	/**
	 * JWT Authentication error definitions.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private static function get_jwt_auth_errors() {
		$missing_header_help = self::get_missing_authorization_header_help();

		return array(
			array(
				'error'             => 'BAD_REQUEST',
				'code'              => '401',
				'error_description' => 'Sorry, client secret is required to make a request. Contact to your administrator.',
				'context'           => 'token_endpoint',
				'explanation'       => array(
					__( 'JWT signing secret is not configured on the site.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Configure JWT Authentication in the plugin so a client secret is generated.', 'wp-rest-api-authentication' ),
					__( 'Contact the site administrator if the secret is missing after configuration.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'INVALID_CREDENTIALS',
				'code'              => '400',
				'error_description' => 'Invalid username or password.',
				'context'           => 'token_endpoint',
				'explanation'       => array(
					__( 'The username and password sent to the token endpoint could not be validated.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Check that the username and password are correct.', 'wp-rest-api-authentication' ),
					__( 'If the password is correct, make sure it does not contain unsupported special characters.', 'wp-rest-api-authentication' ),
					__( 'Use the WordPress username, not the email address. Email-based JWT login is available on the Premium plan only.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'FORBIDDEN',
				'code'              => '403',
				'error_description' => 'Username and password are required.',
				'context'           => 'token_endpoint',
				'explanation'       => array(
					__( 'The token endpoint request did not include both username and password.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Send both username and password in the POST request to /api/v1/token.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'SEGMENT_FAULT',
				'code'              => '401',
				'error_description' => 'Incorrect JWT Format.',
				'context'           => 'protected_api_request',
				'explanation'       => array(
					__( 'The JWT is not split into the expected header.payload.signature segments.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Re-enter the JWT token without extra spaces or line breaks.', 'wp-rest-api-authentication' ),
					__( 'Generate a fresh token from the token endpoint and try again.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'INVALID_SIGNATURE',
				'code'              => '401',
				'error_description' => 'JWT Signature is invalid.',
				'context'           => 'protected_api_request',
				'explanation'       => array(
					__( 'The JWT signature does not match the configured signing secret or algorithm.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Generate a new JWT from the current site token endpoint.', 'wp-rest-api-authentication' ),
					__( 'Ensure the token was issued by this site and has not been modified.', 'wp-rest-api-authentication' ),
				),
			),
			array(
				'error'             => 'INVALID_AUTHORIZATION_HEADER_TOKEN_TYPE',
				'code'              => '401',
				'error_description' => 'Authorization header must be type of Bearer Token.',
				'context'           => 'protected_api_request',
				'explanation'       => array(
					__( 'The Authorization header is missing a Bearer JWT or uses the wrong token type.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'Send Authorization: Bearer {jwt_token}.', 'wp-rest-api-authentication' ),
					__( 'Verify the JWT token field is populated before calling protected APIs.', 'wp-rest-api-authentication' ),
				),
			),
			array_merge(
				array(
					'error'             => 'MISSING_AUTHORIZATION_HEADER',
					'code'              => '401',
					'error_description' => 'Authorization header not received. Either authorization header was not sent or it was removed by your server due to security reasons.',
					'context'           => 'protected_api_request',
				),
				$missing_header_help
			),
			array(
				'error'             => 'UNAUTHORIZED',
				'code'              => '401',
				'error_description' => 'Incorrect JWT Format.',
				'context'           => 'token_validate',
				'explanation'       => array(
					__( 'The JWT could not be validated successfully.', 'wp-rest-api-authentication' ),
				),
				'resolution_steps'  => array(
					__( 'The JWT may be expired or generated for a different authentication flow.', 'wp-rest-api-authentication' ),
					__( 'Regenerate the JWT token and copy it exactly into the Authorization header.', 'wp-rest-api-authentication' ),
				),
			),
		);
	}
}

/**
 * Return Basic and JWT authentication error codes and explanations.
 *
 * @param array<string, mixed> $args Optional filters.
 * @return array<string, mixed>|\WP_Error
 */
function mo_api_authentication_get_auth_error_codes( $args = array() ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_Auth_Error_Codes::get_auth_error_codes( $args );
}
