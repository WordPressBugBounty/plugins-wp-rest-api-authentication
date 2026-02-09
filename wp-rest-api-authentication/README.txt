=== JWT Authentication for WP REST APIs ===
Contributors: miniOrange
Tags: rest-api, api key, jwt authentication, REST, secure api, token, endpoints, json web token, oauth,
Requires at least: 3.0.1
Tested up to: 6.9
Stable tag: 4.3.0
Requires PHP: 5.6
License: Expat
License URI: https://plugins.miniorange.com/mit-license

Secure and protect WordPress REST API from unauthorized access using JWT token, Basic Authentication, API Key, OAuth 2, or external token.


== Description ==
**WordPress REST API endpoints** are **open and unsecured by default** which can be used to access your site data. Secure WordPress APIs from unauthorized users with our **[JWT Authentication for WP REST APIs plugin](https://plugins.miniorange.com/wordpress-rest-api-authentication)**.  

Our plugin offers below authentication methods to **Protect WP REST API endpoints**:
- [JWT Authentication](https://plugins.miniorange.com/wordpress-rest-api-jwt-authentication-method)
- [Basic Authentication](https://plugins.miniorange.com/wordpress-rest-api-basic-authentication-method)
- [API Key Authentication](https://plugins.miniorange.com/rest-api-key-authentication-method) 
- [OAuth 2.0 Authentication](https://plugins.miniorange.com/wordpress-rest-api-oauth-2-0-authentication-method)
- External Token based Authentication 2.0/OIDC/JWT/[Firebase](https://firebase.google.com/docs/auth/admin/create-custom-tokens) provider's token authentication methods. 


You can authenticate default WordPress endpoints and custom-developed REST endpoints and third-party plugin REST API endpoints like that of [Woocommerce](https://wordpress.org/plugins/woocommerce/), [Learndash](https://www.learndash.com/), [Buddypress](https://wordpress.org/plugins/buddypress/), [Gravity Forms](https://www.gravityforms.com/), [CoCart](https://wordpress.org/plugins/cart-rest-api-for-woocommerce/), etc.

[youtube https://www.youtube.com/watch?v=IsyKI7eEV-I&t=2s]

 
==WP REST API Authentication Methods in our plugin==
* [JWT Authentication](https://plugins.miniorange.com/wordpress-rest-api-jwt-authentication-method#step_a1)
Provides an endpoint where you can pass the user credentials, and it will generate a JWT (JSON Web Token), which you can use to access the WordPress REST APIs accordingly.
Additionally, to maintain a seamless user experience without frequent logins needed due to token expiry, you can use our *Refresh and Revoke token* mechanisms feature.
When the access token expires, instead of forcing the user to log in again, the client can request a new access token using a valid refresh token.
* [API Key Authentication](https://plugins.miniorange.com/rest-api-key-authentication-method#step_a)
* [Basic Authentication](https://plugins.miniorange.com/wordpress-rest-api-basic-authentication-method): 
           	- 1. **Username: Password** 
           	- 2. **Client-ID: Client-Secret**
* [OAuth 2.0 Authentication](https://plugins.miniorange.com/wordpress-rest-api-oauth-2-0-authentication-method#step_a)
           	- 1. **Password Grant**
                - 2. **Client Credentials Grant**
* [Third Party Provider Authentication](https://plugins.miniorange.com/wordpress-rest-api-authentication-using-third-party-provider#step_a)


==Following are some of the integrations that are possible with WP REST API Authentication:==
* Learndash API Authentication
* Custom Built REST API Endpoints Authentication
* BuddyPress API Authentication
* WooCommerce API Authentication
* Gravity Form API Authentication
* External/Third-party plugin API endpoints integration in WordPress
 
You can also disable the WP REST APIs with our plugin such that no one can make API calls to your WordPress REST API endpoints.Our plugin also provides **Refresh and Revoke Token** that can be used to improve the API security. 

## Benefits of Refresh Token ##

- Enhances security by keeping access tokens short-lived.
- Improves user experience with uninterrupted sessions.
- Reduces login frequency.

## Benefits of Revoke Token ##

- Protects against token misuse if a device is lost or compromised.
- Enables admin-triggered logouts or session control.
- Useful for complying with stricter session policies.

With this plugin, the user is allowed to access your site's resources only after successful WP REST API authentication. JWT Authentication for WP REST APIs plugin will make your **WordPress endpoints secure from unauthorized access.**

== Plugin Feature List ==
 ## FREE PLAN
* Authenticate only default core WordPress REST API endpoints.
* Basic Authentication with username and password.
* JWT Authentication (JSON Web Token Authentication).
* Enable Selective API protection.
* Restrict non-logged-in users to access REST API endpoints.
* Disable WP REST APIs

## PREMIUM PLAN
 
* Authenticate all REST API endpoints (Default WP, Custom APIs,Third-Party plugins)
* **JWT Token Authentication** (JSON Web Token Authentication)
* Login, Refresh and Revoke token endpoints for token management
* API Key Authentication
* Basic Authentication (username/password and email/password)
* OAuth 2.0 Authentication
* Universal API key and User-specific API key for authentication
* Selective API protection.
* Disable WP REST APIs
* Time-based token expiry
* Role-based WP REST API authentication
* Custom Header support rather than just _Authorization_ to increase security.
* Create users in WordPress based on third-party provider access tokens (JWT tokens) authentication.


== Installation ==
 
This section describes how to install the JWT Authentication for WP REST APIs plugin and get it working.
 
= From your WordPress dashboard =
 
1. Visit `Plugins > Add New`
2. Search for `JWT Authentication for WP REST APIs `. Find and Install the `JWT Authentication for WP REST APIs` plugin by miniOrange
3. Activate the plugin
 
= From WordPress.org =
 
1. Download JWT Authentication for WP REST APIs .
2. Unzip and upload the `wp-rest-api-authentication` directory to your `/wp-content/plugins/` directory.
3. Activate JWT Authentication for WP REST APIs from your Plugins page.
 
 
== Privacy ==
 
This plugin does not store any user data.

== Frequently Asked Questions ==

= What is the use of JWT Authentication for WP REST APIs  =
    JWT Authentication for WP REST APIs plugin prevents unauthorized access to your WordPress APIs. It reduces potential attack by securing the WP APIs.
	
= How can I authenticate the REST APIs using this plugin? =
	This plugin supports 5 methods: i) JWT Token based authentication, ii) authentication through user credentials passed as an encrypted token, iii) API Key authentication, iv) OAuth 2.0 Authentication protocol and v) authentication via JWT token obtained from the external OAuth/OpenId providers which include Google, Facebook, Azure, AWS Cognito, Apple etc and also from Firebase. 

= How does the JWT Authentication for WP REST APIs plugin work? =
	You just have to select your WP REST API Authentication Method in the plugin.
	Based on the method you have selected, you will get the authorization code/token after sending the token request.
	Access your REST API with the code/token you received in the previous step. 

= Does this plugin provide the Basic authentication method for WP REST API authentication? = 
	Yes, the plugin provides Basic authentication with the following 2 methods -
	a.) WP Username & Password b.) Client Credentials.
	The plugin provides you with more security for Basic auth token validation using a highly secure HMAC algorithm.

= Can I authenticate custom-built REST endpoints and Third-Party plugin APIs? = 
	Yes, the plugin supports the authentication for custom-built REST endpoints and Third-Party plugin APIs.

= Does this plugin disable REST APIs of WordPress? =
	Yes, this plugin by default disables all the WP REST APIs, which can only be accessed with allowed authentication and authorization, but it provides a feature where you can choose which particular endpoints you want to disable and which ones to make accessible publicly. 

= How do I log in and register WordPress users using the WordPress REST API endpoint? = 
	This plugin provides this HTTP POST endpoint `wp-json/api/v1/token,` also called as WordPress login API endpoint, in which you can pass the user's WordPress credentials and this endpoint will validate the user and return you with the appropriate response. 
	The plugin also supports the WP REST API authentication and authorization of WordPress users' register API.

= How do I authenticate WordPress REST API endpoints using an external JWT token or access token provided by OAuth/OIDC/Social Login providers? = 
     This plugin provides you with an WP REST API Authentication method called the 'Third Party Provider' authentication method, in which the JWT token or access token is obtained from external identities(OAuth/OIDC/JWT/JWKS providers) like Firebase, Okta, Azure, Keycloak, ADFS, AWS Cognito, Google, Facebook, Apple, etc., can be passed along with API request in the header, and the plugin validates that JWT / access token directly from these external sources/providers. 

= How do I access user-specific data for Woocommerce REST API without the need to pass actual Woocommerce API credentials? =
	This plugin provides a way to bypass Woocommerce security and instead authenticate APIs using the authentication methods, hence improving the security and preventing Woocommerce credentials from getting compromised. The authentication token passed in the API request will validate the user and result in user-specific data only. For more information, please contact us at apisupport@xecurify.com
	
= How to achieve auto-login between WordPress and external apps using a token or JWT token?
	To achieve the auto-login and session sharing, we have another plugin **[WordPress Login & Register using JWT](https://wordpress.org/plugins/login-register-using-jwt/)**   

= Does this plugin provide WordPress Forgot password or password reset functionality using REST API endpoint?
	Yes, with the premium plan, the plugin provides the REST API endpoint for the complete forgot password/password reset functionality securely.

== Screenshots ==

1. List of WP REST API Authentication Methods
2. List of Protected WP REST APIs
3. Basic Authentication method configuration
4. JWT Authentication method configuration
5. Advanced Settings
6. Custom API Integration
7. Postman Sample Settings
8. API Access Auditing analytics

== Changelog ==

= 4.3.0 =
* Bug fixes.

= 4.2.0 =
* Bug fixes.

= 4.1.0 =
* Bug fixes.

= 4.0.0 =
* Security enhancements.

= 3.9.0 =
* UI Improvements

= 3.8.0 =
* Plugin name changes
* UI Improvements

= 3.7.2 =
* Bug fix related to CORS response headers
* Optimization fixes related to repetitive database queries
* UI Improvements

= 3.7.1 =
* Bug fixes related to some icons not showing up correctly.

= 3.7.0 =
* Plugin name changes

= 3.6.5 =
* Compatibility with WordPress 6.8
* URL migration

= 3.6.4 =
* Bug fixes

= 3.6.3 =
* UI improvements related to the REST API Access analytics show in the dashboard

= 3.6.2 =
* Bug fixes for file includes

= 3.6.1 =
* Bug fixes

= 3.6.0 =
* Code improvements.
* Compatibility with WP 6.7.*

= 3.5.4 =
* Added analytics logs for logged-in users.
* Added fix for plugin not getting deactivated after clicking the Skip button.

= 3.5.3 =
* Minor Bug fix

= 3.5.2 =
* Major bug fix for 401 response on edit, update and delete API requests (Requires saving the "Protected REST APIs" Settings in the plugin again for changes to be in effect)
* Usability improvements for API Access analytics

= 3.5.1 =
* Bug fix for file includes

= 3.5.0 =
* Auditing and analytics for REST API access 
* Bug fixes for Basic Authentication
* UI Updates

= 3.4.0 =
* Compatibility with WordPress 6.6
* UI Updates

= 3.3.1 =
* Major Release with UI and UX improvements

= 3.3.0 =
* Major Release with UI and UX improvements

= 3.2.0 =
* Compatibility with WordPress 6.5
* Fix related to the CORS issue

= 3.1.0 =
* Minor UI Improvements

= 3.0.0 =
* Compatibility with WordPress 6.4

= 2.9.1 =
* Quick fix related to permalinks settings

= 2.9.0 =
* Usability improvements
* UI updates

= 2.8.0 =
* WordPress 6.3 compatibility
* Added support for the WordPress.com environment for API authentication
* UI Improvements

= 2.7.0 =
* WordPress 6.2 compatibility
* UI Changes

= 2.6.0 =
* Security Fixes
* UI Improvements & Fixes

= 2.5.1 =
* PHP Warning for incorrect JWT fixed 

= 2.5.0 =
* Security Fixes
* UI Improvements

= 2.4.2 = 
* Bug Fixes

= 2.4.1 = 
* WordPress 6.1 compatibility
* Added a JWT token endpoint for the JWT authentication method
* Security fixes

= 2.4.0 = 
* Minor Bug Fixes

= 2.3.0 = 
* WordPress 6.0 compatibility
* Improvised Test Configuration User experience
* Minor Bug Fixes

= 2.2.1 =
* Bug fixes for Test API Configuration
* Bug fixes for API key configuration
* UI fixes

= 2.2.0 = 
* UI improvements
* Introduced a feature for Test API Configuration
* Added the Third-party plugin integration section
* Bug fixes

= 2.1.0 =
* Major UI updates
* Usability improvements and bug fixes
* Compatibility with WordPress 5.9.1
* Compatibility with PHP 8+

= 1.6.7 = 
* Compatibility with WordPress 5.9

= 1.6.6 = 
* UI Updates

= 1.6.5 =
* WordPress 5.8.2 compatibility
* UI Changes

= 1.6.4 =
* Security Improvements

= 1.6.3 =
* WordPress 5.8.1 compatibility
* Readme Updates 

= 1.6.2 =
* WordPress 5.8 compatibility
* Bug Fixes
* Usability Improvements
* UI Updates

= 1.6.1 =
* Bug Fixes
* Modifications for Custom API auth capabilities

= 1.6.0 =
* Minor fixes
* UI updates
* Usability improvements

= 1.5.2 =
* Minor fixes
* Remove extra code

= 1.5.1 =
* Minor fixes
* Security fixes

= 1.5.0 =
* Minor fixes
* Security fixes

= 1.4.2 =
* UI updates

= 1.4.1 =
* UI updates
* Minor fixes

= 1.4.0 =
* WordPress 5.6 compatibility

= 1.3.10 =
* Allow all REST APIs to authenticate
* Added Postman samples
* Minor Bugfix

= 1.3.9 =
* Minor Bugfix

= 1.3.8 =
* Added compatibility for WP 5.5

= 1.3.7 =
* Bundle plan release
* Minor Bugfix

= 1.3.6 =
* Added compatibility for WP 5.4

= 1.3.5 =
* Minor Bugfix

= 1.3.4 =
* Minor Bugfix

= 1.3.2 =
* Minor Bugfix

= 1.3.1 =
* Minor Fixes

= 1.3.0 =
* Added UI Changes
* Updated plugin licensing
* Added New features
* Added compatibility for WP 5.3 & PHP7.4
* Minor UI & feature fixes

= 1.2.1 =
* Added fixes for undefined getallheaders()

= 1.2.0 =
* Added UI changes for Signing Algorithms and Role-Based Access
* Added Signature Validation
* Minor fixes

= 1.1.2 =
* Added JWT Authentication
* Fixed role-based access to REST APIs
* Fixed common class conflicts

= 1.1.1 =
* Fixes to Create, Posts, Update Publish Posts

= 1.1.0 =
* Updated UI and features
* Added compatibility for WordPress version 5.2.2
* Added support for accessing draft posts as per User's WordPress Role Capability
* Allowed Logged In Users to access posts through /wp-admin Dashboard

= 1.0.2 =
* Added Bug fixes  

= 1.0.0 =
* Updated UI and features
* Added compatibility for WordPress version 5.2.2

== Upgrade Notice ==

= 1.1.1 =
* Fixes to Create, Posts, Update Publish Posts

= 1.1.0 =
* Updated UI and features
* Added compatibility for WordPress version 5.2.2
* Added support for accessing draft posts as per User's WordPress Role Capability
* Allowed Logged In Users to access posts through /wp-admin Dashboard

= 1.0.2 =
* Added Bug fixes  

= 1.0.0 =
* Updated UI and features
* Added compatibility for WordPress version 5.2.2