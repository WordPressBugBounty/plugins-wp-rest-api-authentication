<?php
/**
 * Trial request helpers for the plugin.
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
 * Handles premium plan trial request submissions.
 */
class Mo_API_Authentication_Trial_Request {

	/**
	 * Supported premium plan options.
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function get_plan_options() {
		return array(
			'all_inclusive' => array(
				'label' => __( 'WP API Authentication All-Inclusive Plan', 'wp-rest-api-authentication' ),
				'value' => 'miniorange-api-authentication-plugin@40.1.0',
			),
			'not_sure'      => array(
				'label' => __( 'Not Sure', 'wp-rest-api-authentication' ),
				'value' => 'Not Sure',
			),
		);
	}

	/**
	 * Supported authentication method labels keyed by input slug.
	 *
	 * @return array<string, string>
	 */
	public static function get_auth_method_labels() {
		return array(
			'basic_auth'      => 'Basic Authentication',
			'jwt_auth'        => 'JWT Authentication',
			'apikey_auth'     => 'API Key Authentication',
			'oauth_auth'      => 'OAuth 2.0 Authentication',
			'thirdparty_auth' => 'Third Party Authentication',
		);
	}

	/**
	 * Supported endpoint labels keyed by input slug.
	 *
	 * @return array<string, string>
	 */
	public static function get_endpoint_labels() {
		return array(
			'wp_rest_api' => 'WP REST APIs',
			'custom_api'  => 'WP Third Party/Custom APIs',
		);
	}

	/**
	 * Submit a premium plan trial request using the logged-in user's email address.
	 *
	 * @param array<string, mixed> $args Trial request payload.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function submit_trial_request( $args ) {
		if ( ! in_array( 'curl', get_loaded_extensions(), true ) ) {
			return new WP_Error(
				'curl_not_installed',
				__( 'PHP CURL extension is not installed or disabled. Please enable it to continue.', 'wp-rest-api-authentication' )
			);
		}

		$usecase = trim( sanitize_textarea_field( (string) ( $args['usecase'] ?? '' ) ) );

		if ( '' === $usecase ) {
			return new WP_Error(
				'missing_usecase',
				__( 'The usecase field is required.', 'wp-rest-api-authentication' )
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

		$plan_key    = sanitize_key( (string) ( $args['plan'] ?? 'all_inclusive' ) );
		$plan_options = self::get_plan_options();

		if ( ! isset( $plan_options[ $plan_key ] ) ) {
			return new WP_Error(
				'invalid_plan',
				__( 'The plan field must be either all_inclusive or not_sure.', 'wp-rest-api-authentication' )
			);
		}

		$auth_methods_selected = self::format_selected_labels(
			isset( $args['auth_methods'] ) && is_array( $args['auth_methods'] ) ? $args['auth_methods'] : array(),
			self::get_auth_method_labels()
		);

		$endpoints_selected = self::format_selected_labels(
			isset( $args['endpoints'] ) && is_array( $args['endpoints'] ) ? $args['endpoints'] : array(),
			self::get_endpoint_labels()
		);

		$message  = $usecase;
		$message .= '<br /><b> Premium Plan: </b>' . esc_html( $plan_options[ $plan_key ]['label'] );
		$message .= '<br /><b> Auth Methods: </b>' . esc_html( $auth_methods_selected );
		$message .= '<br /><b> Endpoints Selected: </b>' . esc_html( $endpoints_selected );

		$subject = 'REST API Authentication for WP Trial Request - ' . $email;
		$result  = self::send_trial_mail( $email, $message, $subject );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		if ( 'WRONG_FORMAT' === $result ) {
			return array(
				'status'  => 'error',
				'code'    => '422',
				'message' => __( 'Trial request could not be submitted because of an invalid request format.', 'wp-rest-api-authentication' ),
				'email'   => $email,
				'plan'    => $plan_key,
			);
		}

		return array(
			'status'  => 'success',
			'code'    => '200',
			'message' => __( 'Hang tight! We\'ll get back to you within 24hrs.', 'wp-rest-api-authentication' ),
			'email'   => $email,
			'plan'    => $plan_key,
		);
	}

	/**
	 * Convert selected slugs to a comma-separated label string.
	 *
	 * @param array<int, string>  $selected Selected item slugs.
	 * @param array<string, string> $labels   Allowed slug-to-label map.
	 * @return string
	 */
	private static function format_selected_labels( $selected, $labels ) {
		$formatted = array();

		foreach ( $selected as $item ) {
			$item = sanitize_key( (string) $item );
			if ( isset( $labels[ $item ] ) ) {
				$formatted[] = $labels[ $item ];
			}
		}

		return ! empty( $formatted ) ? implode( ', ', $formatted ) : __( 'None selected', 'wp-rest-api-authentication' );
	}

	/**
	 * Send the trial request email to miniOrange.
	 *
	 * @param string $email   Sender email address.
	 * @param string $message Trial request message body.
	 * @param string $subject Email subject.
	 * @return true|string|\WP_Error
	 */
	private static function send_trial_mail( $email, $message, $subject ) {
		if ( ! class_exists( 'Mo_API_Authentication_Demo' ) ) {
			require_once plugin_dir_path( __DIR__ ) . '../demo/class-mo-api-authentication-demo.php';
		}

		if ( ! class_exists( 'Miniorange_API_Authentication_Customer' ) ) {
			require_once plugin_dir_path( __DIR__ ) . '../../class-miniorange-api-authentication-customer.php';
		}

		$url                    = get_option( 'host_name', Mo_API_Authentication_Demo::HOST_NAME ) . '/moas/api/notify/send';
		$default_customer_key   = '16555';
		$default_api_key        = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';
		$current_time_in_millis = Miniorange_API_Authentication_Customer::get_timestamp();
		$string_to_hash         = $default_customer_key . $current_time_in_millis . $default_api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );

		$content = '<div >Hello, </a><br><br><b>Email :</b><a href="mailto:' . esc_attr( $email ) . '" target="_blank">' . esc_html( $email ) . '</a><br><br><b>Requirements (Usecase) :</b> ' . $message . '</div>';

		$fields = array(
			'customerKey' => $default_customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $default_customer_key,
				'fromEmail'   => $email,
				'bccEmail'    => 'apisupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'apisupport@xecurify.com',
				'toName'      => 'apisupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);

		$response = wp_remote_post(
			$url,
			array(
				'method'      => 'POST',
				'body'        => wp_json_encode( $fields ),
				'timeout'     => 15,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(
					'Content-Type'  => 'application/json',
					'Customer-Key'  => $default_customer_key,
					'Timestamp'     => $current_time_in_millis,
					'Authorization' => $hash_value,
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'trial_request_failed',
				__( 'Trial request could not be submitted. Please try again.', 'wp-rest-api-authentication' ),
				array(
					'error' => $response->get_error_message(),
				)
			);
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( isset( $body['status'] ) && 'ERROR' === $body['status'] ) {
			return 'WRONG_FORMAT';
		}

		return true;
	}
}

/**
 * Submit a premium plan trial request using the logged-in user's email address.
 *
 * @param array<string, mixed> $args Trial request payload.
 * @return array<string, mixed>|\WP_Error
 */
function mo_api_authentication_submit_trial_request( $args ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_Trial_Request::submit_trial_request( $args );
}
