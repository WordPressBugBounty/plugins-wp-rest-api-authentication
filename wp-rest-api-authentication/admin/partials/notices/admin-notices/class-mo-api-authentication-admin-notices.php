<?php

/**
 * This file contains the Mo_API_Authentication_Admin_Notices class, which handles
 * displaying admin-notices in the WordPress admin dashboard.
 *
 * @package    Miniorange_Api_Authentication
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __DIR__ ) . 'class-mo-api-authentication-notices-utils.php';


/**
 * Class Mo_API_Authentication_Admin_Notices
 *
 * This class is responsible for generating the summary box
 * that displays the total api access details,
 * along with a button for viewing more details.
 *
 * @package Mo_API_Authentication
 */
class Mo_API_Authentication_Admin_Notices {

	/**
	 * Displays the API summary box on the admin dashboard.
	 *
	 * This function outputs the summary box, which shows the api access details,
	 *
	 * @return void
	 */
	public static function display_summary_box() {
		// Check if the summary box was closed within the last 7 days.
		$close_time = get_option( 'mo_api_auth_summary_box_close_time', 0 );
		if ( Mo_API_Authentication_Notices_Utils::if_notice_time_remaining( $close_time, 7, DAY_IN_SECONDS ) ) {
			return;
		}

		$current_url = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( strpos( $current_url, 'admin.php' ) !== false) {
			return;
		}

		$counters = get_option( 'api_access_counters', array() );

		if ( ! is_array( $counters ) || empty( $counters ) ) {
			return;
		}

		$success_counts        = is_array( $counters ) ? ( $counters[ Mo_API_Authentication_Constants::SUCCESS ] ?? array() ) : array();
		$blocked_counts        = is_array( $counters ) ? ( $counters[ Mo_API_Authentication_Constants::BLOCKED ] ?? array() ) : array();
		$total_success         = array_sum( $success_counts );
		$open_api_access       = is_array( $success_counts ) ? ( $success_counts[ Mo_API_Authentication_Constants::OPEN_API ] ?? 0 ) : 0;
		$authorized_api_access = is_array( $success_counts ) ? ( $success_counts[ Mo_API_Authentication_Constants::PROTECTED_API ] ?? 0 ) : 0;
		$total_blocked         = array_sum( $blocked_counts );

		$total_apis = $total_success + $total_blocked;

		?>
		<div class="notice summary_box" id="mo-api-summary-box">
				<div class="mo-api-summary-logo">
					<img src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) . '../images/miniorange-logo.png' ); ?>" class="api-logo">
				</div>
				<div class="mo-api-summary-info">
					<div class="mo-api-summary-heading">
						<h3>miniOrange API Authentication Analytics</h3>
					</div>
					<div class="mo-api-alert">
					<p>Alert: <?php echo esc_html( $open_api_access ); ?> unrestricted APIs accessed. Each one could be an open door to vulnerabilities, risking data breaches and unauthorized control. Check your unrestricted API's on this <a href="<?php echo esc_url(admin_url('admin.php?page=mo_api_authentication_settings&tab=protectedrestapis')); ?>">link</a>.</p>
					</div>
					<div class="mo-api-summary-table">
						<div class="mo-api-summary-info-row">
							<div class="info-title">Total API Access</div>
							<div class="info-title">Open API Access</div>
							<div class="info-title">Authorized API Access</div>
							<div class="info-title">Blocked API Access</div>
						</div>
						<div class="mo-api-summary-info-row">
							<div class="info-value"><?php echo esc_html( $total_apis  ); ?></div>
							<div class="info-value"><?php echo esc_html( $open_api_access ); ?></div>
							<div class="info-value"><?php echo esc_html( $authorized_api_access ); ?></div>
							<div class="info-value"><?php echo esc_html( $total_blocked ); ?></div>
						</div>
					</div>
				</div>
				<div class="mo-api-summary-box-button">
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=mo_api_authentication_settings&tab=auditing' ) ); ?>">View Details</a>
				</div>
		<span id="mo-api-summary-close">&times;</span>
		</div>
		<script>
			(function($) {
				$(document).ready(function() {
					$('#mo-api-summary-close').on('click', function(e) {
						e.preventDefault();
						var data = {
							action: 'mo_api_auth_close_admin_notices',
							trigger: 'mo_api_auth_summary_box_close_time'
						};

						$.post(ajaxurl, data, function(response) {
							if (response.success) {
								$('.mo-api-summary-box').hide();
								location.reload();
							}
						});
					});
				});
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * Displays the special edition plan notice on the admin dashboard.
	 *
	 * This function outputs the special edition plan notice, which shows the special edition plan details & countdown timer.
	 *
	 * @return void
	 */
	public static function display_special_edition_plan_notice() {
		$now = new DateTime();
		if ( $now > new DateTime( '2025-03-31T23:59:59.999Z' ) ) {
			return;
		}
		// Check if the notice was closed within the last 1 day.
		$close_time = get_option( 'mo_api_auth_special_plan_notice_close_time', 0 );
		if ( Mo_API_Authentication_Notices_Utils::if_notice_time_remaining( $close_time, 1, DAY_IN_SECONDS ) ) {
			return;
		}

		// Check if the user is on the licensing tab.
		$current_url = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		if ( strpos( $current_url, 'admin.php?page=mo_api_authentication_settings&tab=licensing' ) !== false ) {
			return;
		}
		?>
		<div id="mo-rest-api-new-plan-notice" class="notice is-dismissible">
			<button id="mo_rest-api-dismiss-button" type="button" class="notice-dismiss" style="z-index: 1;">
				<span class="screen-reader-text">Dismiss this notice.</span>
			</button>
			<div class="mo-rest-api-box">
				<div class="mo-rest-api-ribbon mo-rest-api-ribbon-top-left"><span>Limited Offer</span></div>
				<div class="mo-rest-api-notice-content">
					<div style="display: flex;margin-right: 30px;align-content: center;justify-content: space-between;align-items: center;margin-left: 10%;">

						<div class="mo-rest-api-content-right">
							<div class="mo-rest-api-parent-div" style="display: flex;align-items: center;">
								<h2><img src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) . '../images/miniorange-logo-removebg.png' ); ?>" class="mo_api_notice_logo" alt="miniOrange">
								</h2>
								<h2>
									miniOrange API Security
								</h2>

							</div>
							<p class="mo-rest-api-special-edition-outer-div">
								Get complete API security <span class="mo-rest-api-special-edition-aditional">@ </span><span class="mo-rest-api-special-edition-price">29 USD</span><a class="mo-rest-api-spcl-edition-plan-link" href="<?php echo esc_url(site_url()); ?>/wp-admin/admin.php?page=mo_api_authentication_settings&tab=licensing" target="_blank">View Offer Details</a>
							</p>
						</div>

						<div class="mo-rest-api-countdown-container" style="display: flex;align-items: center;">
							<div class="mo-rest-api-countdown-item">
								<div class="mo-rest-api-flip-unit">
									<div class="mo-rest-api-flip-card" id="days">
										<div class="mo-rest-api-flip-front">00</div>
										<div class="mo-rest-api-flip-back">00</div>
									</div>
									<span class="mo-rest-api-days-left-label">Days</span>
								</div>
							</div>
							<div class="mo-rest-api-countdown-item">
								<div class="mo-rest-api-flip-unit">
									<div class="mo-rest-api-flip-card" id="hours">
										<div class="mo-rest-api-flip-front">00</div>
										<div class="mo-rest-api-flip-back">00</div>
									</div>
									<span class="mo-rest-api-days-left-label">Hours</span>
								</div>
							</div>
							<div>
								<div class="mo-rest-api-flip-unit">
									<span class="mo-rest-api-days-left-label-colon">:</span>
								</div>
							</div>
							<div class="mo-rest-api-countdown-item">
								<div class="mo-rest-api-flip-unit">
									<div class="mo-rest-api-flip-card" id="minutes">
										<div class="mo-rest-api-flip-front">00</div>
										<div class="mo-rest-api-flip-back">00</div>
									</div>
									<span class="mo-rest-api-days-left-label">Mins</span>
								</div>
							</div>
							<div class="mo-rest-api-countdown-item">
								<div class="mo-rest-api-flip-unit">
									<div class="mo-rest-api-flip-card" id="seconds">
										<div class="mo-rest-api-flip-front">00</div>
										<div class="mo-rest-api-flip-back">00</div>
									</div>
									<span class="mo-rest-api-days-left-label">Secs</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				let endTime = new Date('2025-01-31T23:59:59.999Z');
				const elements = {
					days: document.querySelector('#days .mo-rest-api-flip-front'),
					hours: document.querySelector('#hours .mo-rest-api-flip-front'),
					minutes: document.querySelector('#minutes .mo-rest-api-flip-front'),
					seconds: document.querySelector('#seconds .mo-rest-api-flip-front')
				};

				function flipCard(element, newValue) {
					const flipCard = element.parentElement;
					const flipBack = flipCard.querySelector('.mo-rest-api-flip-back');

					if (element.textContent !== newValue) {
						flipBack.textContent = newValue;
						flipCard.classList.add('mo-rest-api-flipping');
						setTimeout(() => {
							element.textContent = newValue;
							flipCard.classList.remove('mo-rest-api-flipping');
						}, 1000);
					}
				}

				function waveFlipAll() {
					const flipCards = document.querySelectorAll('.mo-rest-api-flip-card');
					flipCards.forEach((card, index) => {
						setTimeout(() => {
							card.classList.add('mo-rest-api-flipping');
							setTimeout(() => card.classList.remove('mo-rest-api-flipping'), 1000);
						}, index * 200);
					});
				}

				function updateCountdown() {
					let now = new Date(); // Current date
					let endTime = new Date('2025-03-31T23:59:59.999Z');

					const timeLeft = endTime - now;

					if (timeLeft <= 0) {
						document.querySelector('.mo-rest-api-countdown-container').innerHTML = '<span>Offer Expired!</span>';
						return;
					}

					const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
					const hours = Math.floor((timeLeft / (1000 * 60 * 60)) % 24);
					const minutes = Math.floor((timeLeft / (1000 * 60)) % 60);
					const seconds = Math.floor((timeLeft / 1000) % 60);

					flipCard(elements.days, days.toString().padStart(2, '0'));
					flipCard(elements.hours, hours.toString().padStart(2, '0'));
					flipCard(elements.minutes, minutes.toString().padStart(2, '0'));
					flipCard(elements.seconds, seconds.toString().padStart(2, '0'));
				}

				setTimeout(waveFlipAll, 500);
				updateCountdown();
				setInterval(updateCountdown, 1000);
				setInterval(() => {
					updateCountdown();
					waveFlipAll();
				}, 60000);
			});

			(function($) {
				$(document).on('click', '#mo_rest-api-dismiss-button', function() {
					$('#mo-rest-api-new-plan-notice').hide();
					$.post(ajaxurl, {
						action: 'mo_api_auth_close_admin_notices',
						trigger: 'mo_api_auth_special_plan_notice_close_time'
					});
				});
			})(jQuery);
		</script>
		<?php
	}
}
