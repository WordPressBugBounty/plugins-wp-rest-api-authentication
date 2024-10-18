<?php
/**
 * This file contains the Mo_API_Summary_Box class, which handles
 * displaying a summary box for API statistics in the WordPress admin dashboard.
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
 * Class Mo_API_Summary_Box
 *
 * This class is responsible for generating the summary box
 * that displays the total api access details,
 * along with a button for viewing more details.
 *
 * @package Mo_API_Authentication
 */
class Mo_API_Summary_Box {

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
		if ( $close_time > 0 && ( time() - $close_time ) < ( 7 * DAY_IN_SECONDS ) ) {
			return;
		}

		$current_url = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( strpos( $current_url, 'admin.php' ) !== false ) {
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
		<div class="mo-api-summary-box" id="mo-api-summary-box">
			<div class="mo-api-summary-logo">
				<img src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) . 'images/miniorange-logo.png' ); ?>" class="api-logo">
			</div>
			<div class="mo-api-summary-info">
				<div class="mo-api-summary-heading">
					<h3>miniOrange API Authentication Analytics</h3>
				</div>
				<div class="mo-api-summary-table">
					<div class="mo-api-summary-info-row">
						<div class="info-title">Total API Access</div>
						<div class="info-title">Open API Access</div>
						<div class="info-title">Authorized API Access</div>
						<div class="info-title">Blocked API Access</div>
					</div>
					<div class="mo-api-summary-info-row">
						<div class="info-value"><?php echo esc_html( $total_apis ); ?></div>
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
							action: 'mo_api_auth_close_summary_box'
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
}
