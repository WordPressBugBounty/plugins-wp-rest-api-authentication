<?php
/**
 * Free vs Premium plan feature definitions for the plugin.
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
 * Provides structured Free vs Premium feature information.
 */
class Mo_API_Authentication_Plan_Features {

	/**
	 * Return a structured comparison of Free and Premium plugin features.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_free_vs_pro_features() {
		return array(
			'summary'            => __(
				'The free plan secures default WordPress REST API endpoints using Basic Authentication (username/password) or JWT Authentication with selective /wp/v2 API protection. Premium plans unlock additional authentication methods, advanced security controls, and support for custom and third-party plugin REST APIs.',
				'wp-rest-api-authentication'
			),
			'free_plan'          => array(
				'label'       => __( 'Free Plan', 'wp-rest-api-authentication' ),
				'description' => __( 'Available without a paid license.', 'wp-rest-api-authentication' ),
				'features'    => array(
					__( 'Authenticate default core WordPress REST API endpoints only (/wp/v2).', 'wp-rest-api-authentication' ),
					__( 'Basic Authentication with WordPress username and password.', 'wp-rest-api-authentication' ),
					__( 'JWT Authentication (JSON Web Token Authentication).', 'wp-rest-api-authentication' ),
					__( 'Selective API protection for default WordPress endpoints.', 'wp-rest-api-authentication' ),
					__( 'Restrict non-logged-in users from accessing REST API endpoints.', 'wp-rest-api-authentication' ),
					__( 'Disable WordPress REST APIs by default and allow public access only for selected routes.', 'wp-rest-api-authentication' ),
					__( 'API access analytics and auditing dashboard.', 'wp-rest-api-authentication' ),
				),
			),
			'premium_overview'   => array(
				'label'       => __( 'Premium Plans', 'wp-rest-api-authentication' ),
				'description' => __(
					'Paid plans add broader endpoint coverage, more authentication methods, and advanced security controls. Premium is offered in Essential, Advanced, and All-Inclusive tiers.',
					'wp-rest-api-authentication'
				),
				'features'    => array(
					__( 'Authenticate all REST API endpoints, including custom-built APIs and third-party plugin APIs.', 'wp-rest-api-authentication' ),
					__( 'API Key Authentication with universal and user-specific API keys.', 'wp-rest-api-authentication' ),
					__( 'Basic Authentication with username/password, email/password, and client credentials.', 'wp-rest-api-authentication' ),
					__( 'OAuth 2.0 Authentication.', 'wp-rest-api-authentication' ),
					__( 'Third Party Provider authentication using tokens from Firebase, Azure, Google, Okta, and other OAuth/OIDC providers.', 'wp-rest-api-authentication' ),
					__( 'Login, refresh, and revoke token endpoints for token management.', 'wp-rest-api-authentication' ),
					__( 'Time-based token expiry configuration.', 'wp-rest-api-authentication' ),
					__( 'Role-based REST API access restriction.', 'wp-rest-api-authentication' ),
					__( 'Custom header support instead of only the Authorization header.', 'wp-rest-api-authentication' ),
					__( 'Create WordPress users from third-party provider access tokens.', 'wp-rest-api-authentication' ),
					__( 'Forgot password and password reset REST API endpoints.', 'wp-rest-api-authentication' ),
					__( 'Advanced API authentication settings.', 'wp-rest-api-authentication' ),
				),
			),
			'premium_tiers'      => array(
				'essential'      => array(
					'label'       => __( 'Essential', 'wp-rest-api-authentication' ),
					'description' => __( 'Basic, API Key, and JWT authentication for default WordPress APIs.', 'wp-rest-api-authentication' ),
					'features'    => array(
						__( 'Protect default WordPress APIs with Basic Authentication, API Key Authentication, and JWT Authentication.', 'wp-rest-api-authentication' ),
						__( 'Configure a single authentication method.', 'wp-rest-api-authentication' ),
						__( 'Role-based access to APIs.', 'wp-rest-api-authentication' ),
						__( 'Configurable API protection.', 'wp-rest-api-authentication' ),
						__( 'Custom token expiry.', 'wp-rest-api-authentication' ),
						__( 'HSA and RSA signature validation.', 'wp-rest-api-authentication' ),
					),
				),
				'advanced'       => array(
					'label'       => __( 'Advanced', 'wp-rest-api-authentication' ),
					'description' => __( 'Essential features plus OAuth 2.0 and external identity provider token support.', 'wp-rest-api-authentication' ),
					'features'    => array(
						__( 'All Essential plan features.', 'wp-rest-api-authentication' ),
						__( 'OAuth 2.0 Authentication for default WordPress APIs.', 'wp-rest-api-authentication' ),
						__( 'Token authentication from external identity providers such as Firebase, Azure, Google, Okta, and any OAuth/OIDC provider.', 'wp-rest-api-authentication' ),
					),
				),
				'all_inclusive'  => array(
					'label'       => __( 'All-Inclusive', 'wp-rest-api-authentication' ),
					'description' => __( 'Complete API security for default, custom, and third-party plugin endpoints.', 'wp-rest-api-authentication' ),
					'features'    => array(
						__( 'All Advanced plan features.', 'wp-rest-api-authentication' ),
						__( 'Authenticate custom-developed REST API endpoints.', 'wp-rest-api-authentication' ),
						__( 'Authenticate third-party plugin REST APIs such as WooCommerce, LearnDash, BuddyBoss, CoCart, and Gravity Forms.', 'wp-rest-api-authentication' ),
						__( 'Configure multiple authentication methods.', 'wp-rest-api-authentication' ),
						__( 'Manage protection for custom and third-party plugin endpoints in the Protected REST APIs tab.', 'wp-rest-api-authentication' ),
					),
				),
			),
			'feature_comparison' => self::get_feature_comparison(),
			'upgrade_url'        => 'https://plugins.miniorange.com/wordpress-rest-api-authentication#Pricingplan',
		);
	}

	/**
	 * Return feature-by-feature Free vs Premium comparison rows.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private static function get_feature_comparison() {
		return array(
			array(
				'feature'       => __( 'Default WordPress REST API authentication (/wp/v2)', 'wp-rest-api-authentication' ),
				'free'          => true,
				'premium'       => true,
				'minimum_plan'  => 'free',
				'description'   => __( 'Available on the free plan.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Custom REST API authentication', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'all_inclusive',
				'description'   => __( 'Requires the All-Inclusive plan.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Third-party plugin REST API authentication', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'all_inclusive',
				'description'   => __( 'Requires the All-Inclusive plan for endpoints such as WooCommerce, LearnDash, BuddyBoss, CoCart, and Gravity Forms.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Basic Authentication', 'wp-rest-api-authentication' ),
				'free'          => 'partial',
				'premium'       => true,
				'minimum_plan'  => 'free',
				'description'   => __( 'Free plan supports WordPress username and password only. Premium adds email/password and client credentials.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'JWT Authentication', 'wp-rest-api-authentication' ),
				'free'          => true,
				'premium'       => true,
				'minimum_plan'  => 'free',
				'description'   => __( 'Available on the free plan. Premium adds email/password login and advanced token controls.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'API Key Authentication', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'essential',
				'description'   => __( 'Premium only.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'OAuth 2.0 Authentication', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'advanced',
				'description'   => __( 'Available from the Advanced plan onward.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Third Party Provider authentication', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'advanced',
				'description'   => __( 'Validate tokens from external OAuth/OIDC/JWT providers such as Firebase, Azure, Google, and Okta.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Selective API protection', 'wp-rest-api-authentication' ),
				'free'          => 'partial',
				'premium'       => true,
				'minimum_plan'  => 'free',
				'description'   => __( 'Free plan can protect or unprotect default /wp/v2 endpoints only. Premium extends this to custom and third-party endpoints.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Disable WordPress REST APIs', 'wp-rest-api-authentication' ),
				'free'          => true,
				'premium'       => true,
				'minimum_plan'  => 'free',
				'description'   => __( 'Available on both free and premium plans.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Role-based API access', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'essential',
				'description'   => __( 'Premium only.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Custom token expiry', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'essential',
				'description'   => __( 'Configure access and refresh token expiry times.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Custom authentication header', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'essential',
				'description'   => __( 'Use a custom header instead of Authorization.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Login, refresh, and revoke token endpoints', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'essential',
				'description'   => __( 'Token lifecycle management endpoints.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Multiple authentication methods', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'all_inclusive',
				'description'   => __( 'Configure more than one active authentication method.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'Password reset REST API endpoints', 'wp-rest-api-authentication' ),
				'free'          => false,
				'premium'       => true,
				'minimum_plan'  => 'essential',
				'description'   => __( 'Forgot password and password reset via REST API.', 'wp-rest-api-authentication' ),
			),
			array(
				'feature'       => __( 'API access analytics', 'wp-rest-api-authentication' ),
				'free'          => true,
				'premium'       => true,
				'minimum_plan'  => 'free',
				'description'   => __( 'Track total, open, authorized, and blocked API access.', 'wp-rest-api-authentication' ),
			),
		);
	}
}

/**
 * Return structured Free vs Premium feature information.
 *
 * @return array<string, mixed>
 */
function mo_api_authentication_get_free_vs_pro_features() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Function is prefixed with mo_api_authentication_.
	return Mo_API_Authentication_Plan_Features::get_free_vs_pro_features();
}
