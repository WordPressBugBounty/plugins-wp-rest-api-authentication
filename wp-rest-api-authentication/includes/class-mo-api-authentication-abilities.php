<?php
/**
 * WordPress Abilities API integration for the plugin.
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
 * Registers plugin abilities with the WordPress Abilities API (WP 6.9+).
 */
class Mo_API_Authentication_Abilities {

	/**
	 * Ability category slug used for all plugin abilities.
	 *
	 * @var string
	 */
	const CATEGORY_SLUG = 'mo-api-auth';

	/**
	 * Register an ability category for REST API authentication abilities.
	 *
	 * @return void
	 */
	public function register_categories() {
		if ( ! function_exists( 'wp_register_ability_category' ) ) {
			return;
		}

		wp_register_ability_category(
			self::CATEGORY_SLUG,
			array(
				'label'       => __( 'REST API Authentication', 'wp-rest-api-authentication' ),
				'description' => __( 'Abilities for inspecting JWT Authentication for WP REST APIs configuration.', 'wp-rest-api-authentication' ),
			)
		);
	}

	/**
	 * Register plugin abilities.
	 *
	 * @return void
	 */
	public function register_abilities() {
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		wp_register_ability(
			'mo-api-auth/get-configured-oauth-methods',
			array(
				'label'               => __( 'Get Configured Auth Methods', 'wp-rest-api-authentication' ),
				'description'         => __( 'Returns the currently configured Auth methods for this site.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'execute_callback'    => array( $this, 'execute_get_configured_oauth_methods' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'status'                 => array(
							'type' => 'string',
						),
						'code'                   => array(
							'type' => 'string',
						),
						'active_method'          => array(
							'type' => 'string',
						),
					),
				),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/get-authentication-methods-definitions',
			array(
				'label'               => __( 'Get Auth Method Definitions', 'wp-rest-api-authentication' ),
				'description'         => __( 'Returns all authentication methods that can be configured for this site.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'execute_callback'    => array( $this, 'execute_get_authentication_methods_definitions' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'output_schema'       => array(
					'type'                   => 'object',
					'additionalProperties' => array(
						'type'       => 'object',
						'properties' => array(
							'id'         => array(
								'type' => 'string',
							),
							'name'       => array(
								'type' => 'string',
							),
							'category'   => array(
								'type' => 'string',
							),
							'grant_type' => array(
								'type' => 'string',
							),
							'token_type' => array(
								'type' => 'string',
							),
							'encryption' => array(
								'type' => 'string',
							),
						),
					),
				),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/get-api-access-analytics',
			array(
				'label'               => __( 'Get API Access Analytics', 'wp-rest-api-authentication' ),
				'description'         => __( 'Returns API access analytics including total, open, authorized, and blocked API access counts.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'execute_callback'    => array( $this, 'execute_get_api_access_analytics' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'total_api_access'      => array(
							'type' => 'integer',
						),
						'open_api_access'       => array(
							'type' => 'integer',
						),
						'authorized_api_access' => array(
							'type' => 'integer',
						),
						'blocked_api_access'    => array(
							'type' => 'integer',
						),
					),
				),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/get-free-vs-pro-features',
			array(
				'label'               => __( 'Get Free vs Pro Features', 'wp-rest-api-authentication' ),
				'description'         => __( 'Explains all Free and Premium plugin features, including plan tiers and a feature-by-feature comparison.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'execute_callback'    => array( $this, 'execute_get_free_vs_pro_features' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'summary'           => array(
							'type' => 'string',
						),
						'free_plan'         => array(
							'type'       => 'object',
							'properties' => array(
								'label'       => array(
									'type' => 'string',
								),
								'description' => array(
									'type' => 'string',
								),
								'features'    => array(
									'type'  => 'array',
									'items' => array(
										'type' => 'string',
									),
								),
							),
						),
						'premium_overview'  => array(
							'type'       => 'object',
							'properties' => array(
								'label'       => array(
									'type' => 'string',
								),
								'description' => array(
									'type' => 'string',
								),
								'features'    => array(
									'type'  => 'array',
									'items' => array(
										'type' => 'string',
									),
								),
							),
						),
						'premium_tiers'     => array(
							'type'       => 'object',
							'properties' => array(
								'essential'     => array(
									'type' => 'object',
								),
								'advanced'      => array(
									'type' => 'object',
								),
								'all_inclusive' => array(
									'type' => 'object',
								),
							),
						),
						'feature_comparison' => array(
							'type'  => 'array',
							'items' => array(
								'type'       => 'object',
								'properties' => array(
									'feature'      => array(
										'type' => 'string',
									),
									'free'         => array(
										'type' => array( 'boolean', 'string' ),
									),
									'premium'      => array(
										'type' => 'boolean',
									),
									'minimum_plan' => array(
										'type' => 'string',
									),
									'description'  => array(
										'type' => 'string',
									),
								),
							),
						),
						'upgrade_url'       => array(
							'type'   => 'string',
							'format' => 'uri',
						),
					),
				),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/get-jwt-auth-endpoints',
			array(
				'label'               => __( 'Get JWT Auth Endpoints', 'wp-rest-api-authentication' ),
				'description'         => __( 'Returns JWT token and token validation endpoints with curl request examples.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'execute_callback'    => array( $this, 'execute_get_jwt_auth_endpoints' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'auth_method'       => array(
							'type' => 'string',
						),
						'jwt_configured'    => array(
							'type' => 'boolean',
						),
						'signing_algorithm' => array(
							'type' => 'string',
						),
						'token_type'        => array(
							'type' => 'string',
						),
						'endpoints'         => array(
							'type'       => 'object',
							'properties' => array(
								'token'          => array(
									'type' => 'object',
								),
								'token_validate' => array(
									'type' => 'object',
								),
							),
						),
						'usage_notes'       => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
					),
				),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/get-auth-error-codes',
			array(
				'label'               => __( 'Get Auth Error Codes', 'wp-rest-api-authentication' ),
				'description'         => __( 'Returns error codes and troubleshooting explanations for Basic Authentication and JWT Authentication.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'auth_method' => array(
							'type'        => 'string',
							'description' => __( 'Optional filter for basic_auth or jwt_auth.', 'wp-rest-api-authentication' ),
							'enum'        => array( 'basic_auth', 'jwt_auth' ),
						),
						'error'       => array(
							'type'        => 'string',
							'description' => __( 'Optional specific error code to look up, such as INVALID_CREDENTIALS.', 'wp-rest-api-authentication' ),
						),
					),
					'additionalProperties' => false,
				),
				'execute_callback'    => array( $this, 'execute_get_auth_error_codes' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'output_schema'       => array(
					'type'                 => 'object',
					'additionalProperties' => true,
				),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => true,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/submit-support-query',
			array(
				'label'               => __( 'Submit Support Query', 'wp-rest-api-authentication' ),
				'description'         => __( 'Sends a support request to miniOrange using the logged-in user email address and the provided query.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'query' => array(
							'type'        => 'string',
							'description' => __( 'Support question or message.', 'wp-rest-api-authentication' ),
						),
						'phone' => array(
							'type'        => 'string',
							'description' => __( 'Optional contact phone number.', 'wp-rest-api-authentication' ),
						),
					),
					'required'             => array( 'query' ),
					'additionalProperties' => false,
				),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'status'  => array(
							'type' => 'string',
						),
						'code'    => array(
							'type' => 'string',
						),
						'message' => array(
							'type' => 'string',
						),
						'email'   => array(
							'type'   => 'string',
							'format' => 'email',
						),
					),
				),
				'execute_callback'    => array( $this, 'execute_submit_support_query' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => false,
						'destructive' => false,
						'idempotent'  => false,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/submit-trial-request',
			array(
				'label'               => __( 'Submit Trial Request', 'wp-rest-api-authentication' ),
				'description'         => __( 'Raises a premium plan demo/trial request using the logged-in user email address, matching the Demo/Trial Request form.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'usecase'       => array(
							'type'        => 'string',
							'description' => __( 'Business use case and requirements for the trial.', 'wp-rest-api-authentication' ),
						),
						'plan'          => array(
							'type'        => 'string',
							'description' => __( 'Premium plan to trial. Defaults to all_inclusive.', 'wp-rest-api-authentication' ),
							'enum'        => array( 'all_inclusive', 'not_sure' ),
						),
						'auth_methods'  => array(
							'type'        => 'array',
							'description' => __( 'Authentication methods you want to evaluate during the trial.', 'wp-rest-api-authentication' ),
							'items'       => array(
								'type' => 'string',
								'enum' => array(
									'basic_auth',
									'jwt_auth',
									'apikey_auth',
									'oauth_auth',
									'thirdparty_auth',
								),
							),
						),
						'endpoints'     => array(
							'type'        => 'array',
							'description' => __( 'REST API endpoint types you want to protect during the trial.', 'wp-rest-api-authentication' ),
							'items'       => array(
								'type' => 'string',
								'enum' => array(
									'wp_rest_api',
									'custom_api',
								),
							),
						),
					),
					'required'             => array( 'usecase' ),
					'additionalProperties' => false,
				),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'status'  => array(
							'type' => 'string',
						),
						'code'    => array(
							'type' => 'string',
						),
						'message' => array(
							'type' => 'string',
						),
						'email'   => array(
							'type'   => 'string',
							'format' => 'email',
						),
						'plan'    => array(
							'type' => 'string',
						),
					),
				),
				'execute_callback'    => array( $this, 'execute_submit_trial_request' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => false,
						'destructive' => false,
						'idempotent'  => false,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/update-rest-api-protection',
			array(
				'label'               => __( 'Update REST API Protection', 'wp-rest-api-authentication' ),
				'description'         => __( 'Protects or unprotects one or more REST API routes, matching the Protected REST APIs tab checkbox behavior.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'routes' => array(
							'type'        => 'array',
							'description' => __( 'REST API route paths to protect or unprotect (for example, /wp/v2/posts).', 'wp-rest-api-authentication' ),
							'items'       => array(
								'type' => 'string',
							),
							'minItems'    => 1,
						),
						'action' => array(
							'type'        => 'string',
							'description' => __( 'Use protect to require authentication, or unprotect to allow public access.', 'wp-rest-api-authentication' ),
							'enum'        => array( 'protect', 'unprotect' ),
						),
					),
					'required'             => array( 'routes', 'action' ),
					'additionalProperties' => false,
				),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'status'           => array(
							'type' => 'string',
						),
						'code'             => array(
							'type' => 'string',
						),
						'message'          => array(
							'type' => 'string',
						),
						'action'           => array(
							'type' => 'string',
						),
						'updated_routes'   => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
						'invalid_routes'   => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
						'skipped_routes'   => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
						'protected_routes' => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
					),
				),
				'execute_callback'    => array( $this, 'execute_update_rest_api_protection' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => false,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);

		wp_register_ability(
			'mo-api-auth/configure-authentication-method',
			array(
				'label'               => __( 'Configure Authentication Method', 'wp-rest-api-authentication' ),
				'description'         => __( 'Configures Basic Authentication or JWT Authentication for REST API protection. Other methods such as API Key, OAuth 2.0, and Third Party Provider require a premium plan.', 'wp-rest-api-authentication' ),
				'category'            => self::CATEGORY_SLUG,
				'input_schema'        => array(
					'type'       => 'object',
					'properties' => array(
						'auth_method' => array(
							'type'        => 'string',
							'description' => __( 'Authentication method to configure. Allowed values: basic_auth, basic, jwt_auth, jwt.', 'wp-rest-api-authentication' ),
						),
						'credential_type' => array(
							'type'        => 'string',
							'description' => __( 'Optional. Basic auth credential type. Only uname_pass is supported on the free plan.', 'wp-rest-api-authentication' ),
							'enum'        => array( 'uname_pass' ),
						),
					),
					'required'             => array( 'auth_method' ),
					'additionalProperties' => false,
				),
				'output_schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'status'           => array(
							'type' => 'string',
						),
						'code'             => array(
							'type' => 'string',
						),
						'message'          => array(
							'type' => 'string',
						),
						'active_method'    => array(
							'type' => 'string',
						),
						'requested_method' => array(
							'type' => 'string',
						),
						'allowed_methods'  => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
					),
				),
				'execute_callback'    => array( $this, 'execute_configure_authentication_method' ),
				'permission_callback' => array( $this, 'permission_manage_options' ),
				'meta'                => array(
					'annotations'  => array(
						'readonly'    => false,
						'destructive' => false,
						'idempotent'  => true,
					),
					'show_in_rest' => true,
				),
			)
		);
	}

	/**
	 * Permission check: only site administrators may run configuration abilities.
	 *
	 * @return bool
	 */
	public function permission_manage_options() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Enforce per-IP rate limiting for ability API requests.
	 *
	 * @return \WP_Error|null WP_Error when limit exceeded, otherwise null.
	 */
	private function enforce_ability_rate_limit() {
		if ( ! function_exists( 'mo_api_auth_check_rate_limit' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/flow/mo-token-api-flow.php';
		}

		if ( ! mo_api_auth_check_rate_limit() ) {
			return new WP_Error(
				'too_many_requests',
				__( 'Too many requests. Please try again later.', 'wp-rest-api-authentication' ),
				array(
					'status'            => 429,
					'error'             => 'TOO_MANY_REQUESTS',
					'code'              => '429',
					'error_description' => __( 'Too many requests. Please try again later.', 'wp-rest-api-authentication' ),
				)
			);
		}

		mo_api_auth_increment_rate_limit();

		return null;
	}

	/**
	 * Execute callback for the get-configured-oauth-methods ability.
	 *
	 * @return array<string, mixed>
	 */
	public function execute_get_configured_oauth_methods() {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_get_configured_oauth_methods' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-oauth-methods.php';
		}

		return mo_api_authentication_get_configured_oauth_methods();
	}

	/**
	 * Execute callback for the get-authentication-methods-definitions ability.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public function execute_get_authentication_methods_definitions() {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_get_all_authentication_methods_definitions' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-oauth-methods.php';
		}

		return mo_api_authentication_get_all_authentication_methods_definitions();
	}

	/**
	 * Execute callback for the get-api-access-analytics ability.
	 *
	 * @return array<string, int>
	 */
	public function execute_get_api_access_analytics() {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_get_api_access_analytics' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-utils.php';
		}

		return mo_api_authentication_get_api_access_analytics();
	}

	/**
	 * Execute callback for the get-free-vs-pro-features ability.
	 *
	 * @return array<string, mixed>
	 */
	public function execute_get_free_vs_pro_features() {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_get_free_vs_pro_features' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-plan-features.php';
		}

		return mo_api_authentication_get_free_vs_pro_features();
	}

	/**
	 * Execute callback for the get-jwt-auth-endpoints ability.
	 *
	 * @return array<string, mixed>
	 */
	public function execute_get_jwt_auth_endpoints() {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_get_jwt_auth_endpoints' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-jwt-endpoints.php';
		}

		return mo_api_authentication_get_jwt_auth_endpoints();
	}

	/**
	 * Execute callback for the get-auth-error-codes ability.
	 *
	 * @param array<string, mixed> $input Ability input payload.
	 * @return array<string, mixed>|\WP_Error
	 */
	public function execute_get_auth_error_codes( $input = array() ) {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_get_auth_error_codes' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-auth-error-codes.php';
		}

		$args = array();

		if ( ! empty( $input['auth_method'] ) ) {
			$args['auth_method'] = $input['auth_method'];
		}

		if ( ! empty( $input['error'] ) ) {
			$args['error'] = $input['error'];
		}

		return mo_api_authentication_get_auth_error_codes( $args );
	}

	/**
	 * Execute callback for the submit-support-query ability.
	 *
	 * @param array<string, mixed> $input Ability input payload.
	 * @return array<string, mixed>|\WP_Error
	 */
	public function execute_submit_support_query( $input ) {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_submit_support_query' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-support.php';
		}

		if ( empty( $input['query'] ) ) {
			return new WP_Error(
				'missing_query',
				__( 'The query field is required.', 'wp-rest-api-authentication' )
			);
		}

		$phone = ! empty( $input['phone'] ) ? $input['phone'] : '';

		return mo_api_authentication_submit_support_query( $input['query'], $phone );
	}

	/**
	 * Execute callback for the submit-trial-request ability.
	 *
	 * @param array<string, mixed> $input Ability input payload.
	 * @return array<string, mixed>|\WP_Error
	 */
	public function execute_submit_trial_request( $input ) {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_submit_trial_request' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-trial-request.php';
		}

		if ( empty( $input['usecase'] ) ) {
			return new WP_Error(
				'missing_usecase',
				__( 'The usecase field is required.', 'wp-rest-api-authentication' )
			);
		}

		return mo_api_authentication_submit_trial_request( $input );
	}

	/**
	 * Execute callback for the update-rest-api-protection ability.
	 *
	 * @param array<string, mixed> $input Ability input payload.
	 * @return array<string, mixed>|\WP_Error
	 */
	public function execute_update_rest_api_protection( $input ) {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_update_rest_api_protection' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-protected-routes.php';
		}

		if ( empty( $input['routes'] ) || ! is_array( $input['routes'] ) ) {
			return new WP_Error(
				'missing_routes',
				__( 'The routes field is required and must be a non-empty array.', 'wp-rest-api-authentication' )
			);
		}

		if ( empty( $input['action'] ) ) {
			return new WP_Error(
				'missing_action',
				__( 'The action field is required.', 'wp-rest-api-authentication' )
			);
		}

		return mo_api_authentication_update_rest_api_protection( $input['routes'], $input['action'] );
	}

	/**
	 * Execute callback for the configure-authentication-method ability.
	 *
	 * @param array<string, mixed> $input Ability input payload.
	 * @return array<string, mixed>|\WP_Error
	 */
	public function execute_configure_authentication_method( $input ) {
		$rate_limit_error = $this->enforce_ability_rate_limit();
		if ( is_wp_error( $rate_limit_error ) ) {
			return $rate_limit_error;
		}

		if ( ! function_exists( 'mo_api_authentication_configure_authentication_method' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'admin/partials/utils/class-mo-api-authentication-oauth-methods.php';
		}

		if ( empty( $input['auth_method'] ) ) {
			return new WP_Error(
				'missing_auth_method',
				__( 'The auth_method field is required.', 'wp-rest-api-authentication' )
			);
		}

		$args = array();
		if ( ! empty( $input['credential_type'] ) ) {
			$args['credential_type'] = $input['credential_type'];
		}

		return mo_api_authentication_configure_authentication_method( $input['auth_method'], $args );
	}
}
