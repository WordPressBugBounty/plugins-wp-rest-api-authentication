<?php
/**
 * Protected REST API route management helpers.
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
 * Manages protect/unprotect operations for REST API routes.
 */
class Mo_API_Authentication_Protected_Routes {

	/**
	 * Option name that stores protected route patterns.
	 *
	 * @var string
	 */
	const PROTECTED_ROUTES_OPTION = 'mo_api_authentication_protectedrestapi_route_whitelist';

	/**
	 * Ensure protected routes are initialized like the admin UI does.
	 *
	 * @return void
	 */
	public static function ensure_protected_routes_initialized() {
		if ( ! function_exists( 'mo_api_authentication_sync_rest_route_protection' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'flow/mo-api-authentication-flow.php';
		}

		if ( get_option( 'mo_api_authentication_init_protected_apis' ) ) {
			$protected_routes = get_option( self::PROTECTED_ROUTES_OPTION, array() );
			if ( empty( $protected_routes ) || ! is_array( $protected_routes ) ) {
				mo_api_authentication_sync_rest_route_protection();
			}
			return;
		}

		mo_api_authentication_reset_api_protection();
		update_option( 'mo_api_authentication_init_protected_apis', 'true' );
	}

	/**
	 * Return all registered REST API route patterns.
	 *
	 * @return array<int, string>
	 */
	public static function get_all_rest_routes() {
		$wp_rest_server = rest_get_server();
		$all_routes     = array_keys( $wp_rest_server->get_routes() );

		return array_values( array_map( 'esc_html', $all_routes ) );
	}

	/**
	 * Return currently protected REST API route patterns.
	 *
	 * @return array<int, string>
	 */
	public static function get_protected_routes() {
		self::ensure_protected_routes_initialized();

		$protected_routes = get_option( self::PROTECTED_ROUTES_OPTION, array() );

		if ( ! is_array( $protected_routes ) ) {
			return array();
		}

		return array_values( array_map( 'esc_html', $protected_routes ) );
	}

	/**
	 * Check whether a route can be managed on the current plan.
	 *
	 * @param string $route REST API route pattern.
	 * @return bool
	 */
	public static function is_route_manageable_on_plan( $route ) {
		if ( ! class_exists( 'Mo_API_Authentication_ProtectedRestAPIs' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'advanced/class-mo-api-authentication-protectedrestapis.php';
		}

		return Mo_API_Authentication_ProtectedRestAPIs::check_route_is_wp_standard_or_not( $route ) || get_option( 'mo_rest_api_protect_migrate' );
	}

	/**
	 * Normalize and sanitize a route value.
	 *
	 * @param string $route REST API route pattern.
	 * @return string
	 */
	public static function normalize_route( $route ) {
		$route = trim( (string) $route );

		if ( '' === $route ) {
			return '';
		}

		if ( '/' !== substr( $route, 0, 1 ) ) {
			$route = '/' . $route;
		}

		return esc_html( $route );
	}

	/**
	 * Protect or unprotect one or more REST API routes.
	 *
	 * @param array<int, string> $routes Route patterns to update.
	 * @param string             $action Either protect or unprotect.
	 * @return array<string, mixed>|\WP_Error
	 */
	public static function update_rest_api_protection( $routes, $action ) {
		self::ensure_protected_routes_initialized();

		if ( ! is_array( $routes ) || empty( $routes ) ) {
			return new WP_Error(
				'missing_routes',
				__( 'At least one route must be provided.', 'wp-rest-api-authentication' )
			);
		}

		$action = strtolower( trim( sanitize_text_field( (string) $action ) ) );
		if ( ! in_array( $action, array( 'protect', 'unprotect' ), true ) ) {
			return new WP_Error(
				'invalid_action',
				__( 'The action field must be either protect or unprotect.', 'wp-rest-api-authentication' )
			);
		}

		$available_routes = self::get_all_rest_routes();
		$protected_routes = self::get_protected_routes();
		$updated_routes   = array();
		$invalid_routes   = array();
		$skipped_routes   = array();

		foreach ( $routes as $route ) {
			$route = self::normalize_route( $route );

			if ( '' === $route ) {
				continue;
			}

			if ( ! in_array( $route, $available_routes, true ) ) {
				$invalid_routes[] = $route;
				continue;
			}

			if ( ! self::is_route_manageable_on_plan( $route ) ) {
				$skipped_routes[] = $route;
				continue;
			}

			if ( 'protect' === $action ) {
				if ( in_array( $route, $protected_routes, true ) ) {
					continue;
				}

				$protected_routes[] = $route;
				$updated_routes[]   = $route;
				continue;
			}

			if ( ! in_array( $route, $protected_routes, true ) ) {
				continue;
			}

			$protected_routes = array_values(
				array_filter(
					$protected_routes,
					function ( $protected_route ) use ( $route ) {
						return $protected_route !== $route;
					}
				)
			);
			$updated_routes[] = $route;
		}

		if ( ! empty( $invalid_routes ) ) {
			return array(
				'status'           => 'error',
				'code'             => '422',
				'message'          => __( 'One or more routes are not registered REST API endpoints.', 'wp-rest-api-authentication' ),
				'action'           => $action,
				'updated_routes'   => $updated_routes,
				'invalid_routes'   => $invalid_routes,
				'skipped_routes'   => $skipped_routes,
				'protected_routes' => $protected_routes,
			);
		}

		if ( ! empty( $skipped_routes ) && empty( $updated_routes ) ) {
			return array(
				'status'           => 'error',
				'code'             => '403',
				'message'          => __( 'These routes cannot be managed on the free plan. Only default WordPress /wp/v2 endpoints are supported.', 'wp-rest-api-authentication' ),
				'action'           => $action,
				'updated_routes'   => $updated_routes,
				'invalid_routes'   => $invalid_routes,
				'skipped_routes'   => $skipped_routes,
				'protected_routes' => $protected_routes,
			);
		}

		update_option( self::PROTECTED_ROUTES_OPTION, array_values( array_unique( $protected_routes ) ) );

		$message = 'protect' === $action
			? __( 'Selected REST API routes are now protected.', 'wp-rest-api-authentication' )
			: __( 'Selected REST API routes are now publicly accessible.', 'wp-rest-api-authentication' );

		if ( ! empty( $skipped_routes ) ) {
			$message = __( 'Some routes were updated, but premium-only routes were skipped on the free plan.', 'wp-rest-api-authentication' );
		}

		return array(
			'status'           => 'success',
			'code'             => '200',
			'message'          => $message,
			'action'           => $action,
			'updated_routes'   => $updated_routes,
			'invalid_routes'   => $invalid_routes,
			'skipped_routes'   => $skipped_routes,
			'protected_routes' => self::get_protected_routes(),
		);
	}
}

/**
 * Protect or unprotect one or more REST API routes.
 *
 * @param array<int, string> $routes Route patterns to update.
 * @param string             $action Either protect or unprotect.
 * @return array<string, mixed>|\WP_Error
 */
function mo_api_authentication_update_rest_api_protection( $routes, $action ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_Protected_Routes::update_rest_api_protection( $routes, $action );
}
