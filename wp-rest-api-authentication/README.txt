=== WordPress REST API Authentication ===
Contributors: miniOrange
Tags: api, rest-api, jwt auth, jwt, basic auth, REST, secure api, token, endpoints, json web token, oauth, api key auth
Requires at least: 3.0.1
Tested up to: 6.7
Stable tag: 3.6.3
Requires PHP: 5.6
License: MIT/Expat
License URI: https://docs.miniorange.com/mit-license

Secure and protect your WP REST API endpoints from unauthorized access. Authenticate  WordPress API using secure authentication methods. 


== Description ==
**WordPress REST API endpoints** are **open and unsecured by default** through which a hacker can access your site remotely. With our **[WordPress REST API Authentication plugin](https://plugins.miniorange.com/wordpress-rest-api-authentication)** secure your WordPress APIs from unauthorized users.  

**Protect WP REST API endpoints** from public access using [API Key Authentication](https://plugins.miniorange.com/rest-api-key-authentication-method) or [JWT Authentication](https://plugins.miniorange.com/wordpress-rest-api-jwt-authentication-method) or [Basic Authentication](https://plugins.miniorange.com/wordpress-rest-api-basic-authentication-method) or [OAuth 2.0 Authentication](https://plugins.miniorange.com/wordpress-rest-api-oauth-2-0-authentication-method) or third-party OAuth 2.0/OIDC/JWT/[Firebase](https://firebase.google.com/docs/auth/admin/create-custom-tokens) provider's token authentication methods. 

This plugin will make sure that only after the successful authentication, the user is allowed to access your site's resources. REST API Authentication will make your **WordPress endpoints secure from unauthorized access.**

Along with the default and standard WordPress REST API endpoints, With WP REST API Authentication you can authenticate custom-developed REST endpoints and third-party plugin REST API endpoints like that of [Woocommerce](https://wordpress.org/plugins/woocommerce/), [Learndash](https://www.learndash.com/), [Buddypress](https://wordpress.org/plugins/buddypress/), [Gravity forms](https://www.gravityforms.com/), [CoCart](https://wordpress.org/plugins/cart-rest-api-for-woocommerce/) etc.

[youtube https://www.youtube.com/watch?v=IsyKI7eEV-I&t=2s]

 
==WordPress REST API Authentication Methods in our WordPress plugin==
* [Basic Authentication](https://plugins.miniorange.com/wordpress-rest-api-basic-authentication-method): 
           	- 1. **Username: Password** 
           	- 2. **Client-ID: Client-Secret**
* [API Key Authentication](https://plugins.miniorange.com/rest-api-key-authentication-method#step_a)
* [JWT Authentication](https://plugins.miniorange.com/wordpress-rest-api-jwt-authentication-method#step_a1)
* [OAuth 2.0 Authentication](https://plugins.miniorange.com/wordpress-rest-api-oauth-2-0-authentication-method#step_a) _**[Most Secure]**_
           	- 1. **Password Grant**
                - 2. **Client Credentials Grant**
* [Third Party Provider Authentication](https://plugins.miniorange.com/wordpress-rest-api-authentication-using-third-party-provider#step_a)


==Following are some of the integrations that are possible with REST API Authentication:==
== WooCommerce API Authentication ==
* **Wordpress Rest API authentication** allows you to authenticate the WooCommerce store APIs with your mobile or desktop application & extend the features and functionality of your eCommerce store.
== BuddyPress API Authentication ==
* **Securely access BuddyPress REST API endpoints** via authentication using different authentication methods like JWT token (JSON Web Token), API Keys etc.
== Gravity Form API Authentication ==
* This plugin supports interaction with Gravity Forms from an external client application which can be your Android/iOS application. 
== Learndash API Authentication ==
* This plugin allows you to securely access Learndash user profiles, courses, groups & other Learndash API endpoints.
== Custom Built REST API Endpoints Authentication ==
* The plugin **supports authentication for your own built custom REST API routes/endpoints**. You can secure these API endpoints using the plugin’s highly secured authentication methods.
== External/Third-party plugin API endpoints integration in WordPress ==
* These integrations can be used to fetch/update the data from the third-party side into the WordPress that can be used to display it on the WordPress site as well and this data can be processed further to use with any other plugin or WordPress events.
 
== FEATURES ==
 
FREE PLAN
 
* Basic Authentication with username and password.
* JWT Authentication (JSON Web Token Authentication).
* Authenticate default WordPress REST API endpoints.
* Selective API protection.
* Restrict non-logged-in users to access REST API endpoints.
 
PREMIUM PLANS
 
* Authenticate standard WP REST APIs and custom/third-party plugin REST API endpoints.
* Basic Authentication (username/password and email/password)
* JWT Token Authentication (JSON Web Token Authentication)
* API Key Authentication
* OAuth 2.0 Authentication
* Third-Party OAuth 2.0/OIDC/JWT Provider's Token
* Selective API protection.
* Universal API key and User-specific API key for authentication
* Time-based token expiry
* Role-based authentication
* Custom Header support rather than just _Authorization_ to increase security.
* Create users in WordPress based on third-party provider access tokens (JWT tokens) authentication.

== Our Other Popular REST API Integrations ==

* [Custom API for WP plugin](https://wordpress.org/plugins/custom-api-for-wp/) to create and connect external APIs to your WordPress site.

* [Sync products to WooCommerce | Import WooCommerce products using API](https://plugins.miniorange.com/woocommerce-api-product-sync-with-woocommerce-rest-apis) to connect to your Supplier, Inventory, ERP, and CRM APIs to sync the products to your [WooCommerce](https://wordpress.org/plugins/woocommerce/) store with all the product data automatically.

* [Sync Custom Posts using External API](https://plugins.miniorange.com/wordpress-sync-posts-from-api) to automatically sync the data to custom posts in WordPress from the external REST API data. 

* [WordPress JWT Single Sign-On (SSO) Auto login](https://wordpress.org/plugins/login-register-using-jwt/) to sync user sessions or auto-login to WordPress and other connected sites

== Installation ==
 
This section describes how to install the WordPress REST API Authentication and get it working.
 
= From your WordPress dashboard =
 
1. Visit `Plugins > Add New`
2. Search for `REST API Authentication`. Find and Install the `api authentication` plugin by miniOrange
3. Activate the plugin
 
= From WordPress.org =
 
1. Download WordPress REST API Authentication.
2. Unzip and upload the `wp-rest-api-authentication` directory to your `/wp-content/plugins/` directory.
3. Activate WordPress REST API Authentication from your Plugins page.
 
 
== Privacy ==
 
This plugin does not store any user data.

== Frequently Asked Questions ==

= What is the use of API Authentication =
    The REST API authentication prevents unauthorized access to your WordPress APIs. It reduces potential attack factors.
	
= How can I authenticate the REST APIs using this plugin? =
	This plugin supports 5 methods: i) authentication through API key or token, ii) authentication through user credentials passed as an encrypted token, iii) authentication through JWT (JSON Web token), iv) authentication through OAuth 2.0 protocol and v) authentication via JWT token obtained from the external OAuth/OpenId providers which include Google, Facebook, Azure, AWS Cognito, Apple etc and also from Firebase. 

= Does this plugin allow authentication through JWT (JSON Web Tokens)? =
	Yes, this plugin supports REST API authentication through JWT (JSON Web token). The JWT is validated every time an API request is made, only the requested resource for the API call is allowed to access if the JWT validation is successful.

= How does the REST API Authentication plugin work? =
	You just have to select your Authentication Method in the plugin.
	Based on the method you have selected you will get the authorization code/token after sending the token request.
	Access your REST API with the code/token you received in the previous step. 

= Does this plugin provide the Basic authentication method for API authentication? = 
	Yes, the plugin provides the Basic authentication with the following 2 methods -
	a.) WP Username & Password b.) Client Credentials.
	The plugin provides you with more security of Basic auth token validation using a highly secure HMAC algorithm.

= Does this plugin enable the authentication for my custom-built REST endpoints? = 
	Yes, the plugin supports the authentication for custom-built REST endpoints using rest_api_init or register_rest_route.

= Does this plugin disable REST APIs of WordPress? =
	Yes, this plugin by default disables all the WP REST APIs which can only be accessed with allowed authentication and authorization but it provides a feature where you can choose which particular endpoints you want to disable and which one to make accessible publicly. 

= How do log in and register WordPress users using the WordPress REST API endpoint? = 
	This plugin provides this HTTP POST endpoint `wp-json/api/v1/token` also called as WordPress login API endpoint in which you can pass the user's WordPress credentials and this endpoint will validate the user and return you with the appropriate response. 
	The plugin also supports the authentication and authorization of WordPress users' register REST API.

= How to access draft posts? =
	You can access draft posts using Basic Auth, OAuth 2.0(using Username: Password), JWT authentication, and API Key auth(using Universal Key) methods. Pages/posts need to access with the status. The default status used in the request is 'Publish' and any user can access the Published post. 
	To access the pages/posts stored in the draft, you need to append the ?status=draft to the page/post request.
	For Example:
	You need to use below URL format while sending a request to access different types of posts
	1. Access draft posts only
		https://<domain>/wp-json/wp/v2/posts?status=draft
	2. Access all types of posts
		https://<domain>/wp-json/wp/v2/posts?status=any
	You just have to change the status(draft, pending, any, publish) as per your requirement. You do not have to pass the status parameter to access Published posts.

= How do I authenticate WordPress REST API endpoints using an external JWT token or access token provided by OAuth/OIDC/Social Login providers? = 
     This plugin provides you with an authentication method called the 'Third Party Provider' authentication method in which the JWT token or access token is obtained from external identities(OAuth/OIDC/JWT/JWKS providers) like Firebase, Okta, Azure, Keycloak, ADFS, AWS Cognito, Google, Facebook, Apple etc. can be passed along with API request in the header and the plugin validates that JWT / access token directly from these external sources/providers. 

= How do I access user-specific data for Woocommerce REST API without the need to pass actual Woocommerce API credentials? =
	This plugin provides a way to bypass Woocommerce security and instead authenticate APIs using the authentication methods, hence improvising the security and hence no chance of Woocommerce credentials getting compromised. The authentication token passed in the API request will validate the user and result in user-specific data only. For more information, please contact us at apisupport@xecurify.com

= How to enable API access in WooCommerce?
    You can enable API access in WooCommerce using our WP REST API Authentication plugin. Please reach out to us at apisupport@xecurify.com for more information.
	
= How to achieve auto-login between WordPress and external apps using a token or JWT token?
	To achieve the auto-login and session sharing, we have another plugin **[WordPress Login & Register using JWT](https://wordpress.org/plugins/login-register-using-jwt/)**   

= Does this plugin provide WordPress Forgot password or password reset functionality using REST API endpoint?
	Yes, with the premium plan, the plugin provides the REST API endpoint for the complete forgot password/password reset functionality securely.

== Screenshots ==

1. List of API Authentication Methods
2. List of Protected WP REST APIs
3. Basic Authentication method configuration
4. JWT Authentication method configuration
5. Advanced Settings
6. Custom API Integration
7. Postman Sample Settings
8. API Access Auditing analytics

== Changelog ==

= 3.6.3 =
* UI improvements related to the REST API Access analytics show in the dashboard

= 3.6.2 =
* Bug fixes for file includes

= 3.6.1 =
* Bug fixes

= 3.6.0 =
* Code improvments.
* Compatibility with WP 6.7.*

= 3.5.4 =
* Added analytics logs for logged in user.
* Added fix for plugin not getting deactivated after clicking Skip button.

= 3.5.3 =
* Minor Bug fix

= 3.5.2 =
* Major bug fix for 401 response on edit, update and delete API requests (Requires saving the "Protected REST APIs" Settings in plugin again for changes to be in effect)
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
* Introduced feature for Test API Configuration
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
* Added postman samples
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