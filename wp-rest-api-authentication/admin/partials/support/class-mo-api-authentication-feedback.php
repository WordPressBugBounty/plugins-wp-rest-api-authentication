<?php
/**
 * Feedback
 * Handle user feedback upon deactivation.
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
 * Handle customer deactivation feedback.
 */
class Mo_API_Authentication_Feedback {

	/**
	 * Display both modals and handle their logic.
	 *
	 * @return void
	 */
	public static function mo_api_authentication_display_feedback() {
		if ( ! empty( $_SERVER['PHP_SELF'] ) && 'plugins.php' !== basename( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) ) {
			return;
		}

		$deactivate_reasons = array( "Does not have the features I'm looking for", 'Do not want to upgrade to Premium version', 'Confusing Interface', 'Bugs in the plugin', 'Unable to register', 'Other Reasons' );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'utils' );
		wp_enqueue_style( 'mo_api_admin_settings_style', plugin_dir_url( dirname( dirname( __DIR__ ) ) ) . 'css/style_settings.min.css', MINIORANGE_API_AUTHENTICATION_VERSION, array(), false, false );
		$counters = get_option( 'api_access_counters', array() );

		$success_counts = is_array( $counters ) ? ( $counters[ Mo_API_Authentication_Constants::SUCCESS ] ?? array() ) : array();
		$blocked_counts = is_array( $counters ) ? ( $counters[ Mo_API_Authentication_Constants::BLOCKED ] ?? array() ) : array();
		$total_success  = array_sum( $success_counts );
		$total_blocked  = array_sum( $blocked_counts );

		$total_apis = $total_success + $total_blocked;
		?>
		<!-- Plugin Details Modal -->
		<div id="mo_api_plugin_details_modal" class="mo_api_auth_modal">
			<div class="mo_api_auth_modal-content" style="margin-left: auto">
				<div class="mo_api_auth_modal-header">
					<h3>miniOrange REST API Authentication Plugin </h3>
					<span class="mo_api_auth_close" id="mo_api_details_close">&times;</span>
				</div>
				<hr>
				<div class="mo_api_auth_modal-body">
					<h2>Are you sure you want to deactivate?</h2>
					<div style="text-align: left; margin:25px">
						<p><strong>Please read this before deactivating the plugin:</strong></p>
						<ul>
							<li>Deactivating this plugin will leave your site's APIs unprotected, potentially exposing sensitive data and compromising your site's security.</li>
							<li>Since the plugin was activated, <b id="total_protected_apis"><?php echo $total_apis; ?></b> REST APIs access have been protected.</li>
							<li>For more detailed information on security report, please <a href="admin.php?page=mo_api_authentication_settings&tab=auditing" id="mo-api_auth-more_details_link">click here</a>.</li>
						</ul>
					</div>
				</div>
				<div class="mo_api_auth_modal-footer">
					<button id="mo_api_skip_details" class="button-primary" >Back to Safety</button>
					<button id="mo_api_continue_feedback" class="button button-primary mo-api-auth-btn-right">Continue with Risk</button>
				</div>
			</div>
		</div>

		<!-- Feedback Form Modal -->
		<div id="mo_api_feedback_modal" class="mo_api_auth_modal">
			<div class="mo_api_auth_modal-content" style="margin-left: auto; width:40%">
				<div class="mo_api_auth_modal-header">
					<h3>Your Feedback</h3>
					<span class="mo_api_auth_close" id="mo_api_feedback_close">&times;</span>
				</div>
				<hr>
				<form name="f" method="post" action="" id="mo_api_authentication_feedback">
					<?php wp_nonce_field( 'mo_api_authentication_feedback_form', 'mo_api_authentication_feedback_fields' ); ?>
					<input type="hidden" name="mo_api_authentication_feedback" value="true"/>
					<div class="mo_api_auth_modal-body" style="width: 80%; margin:auto">
						<h4>We would like your opinion to improve our plugin.</h4>
						<div id="smi_rate">
							<input type="radio" name="rate" id="angry" value="1"/>
							<label for="angry"><img class="sm" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ) . 'images/angry.png'; ?>" /></label>
							<input type="radio" name="rate" id="sad" value="2"/>
							<label for="sad"><img class="sm" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ) . 'images/sad.png'; ?>" /></label>
							<input type="radio" name="rate" id="neutral" value="3"/>
							<label for="neutral"><img class="sm" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ) . 'images/normal.png'; ?>" /></label>
							<input type="radio" name="rate" id="smile" value="4"/>
							<label for="smile"><img class="sm" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ) . 'images/smile.png'; ?>" /></label>
							<input type="radio" name="rate" id="happy" value="5" checked/>
							<label for="happy"><img class="sm" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ) . 'images/happy.png'; ?>" /></label>
						</div>
						<h4>Tell us what happened?</h4>
						<select name="deactivate_reason_select" id="deactivate_reason_select" required>
							<option value="">Please select your reason</option>
							<?php
							foreach ( $deactivate_reasons as $deactivate_reason ) {
								echo '<option value="' . esc_attr( $deactivate_reason ) . '">' . esc_html( $deactivate_reason ) . '</option>';
							}
							?>
						</select>
						<textarea id="mo_api_auth_query_feedback" name="mo_api_auth_query_feedback" rows="4" placeholder="Write your query here.."></textarea>
						<?php
						$email = get_option( 'mo_api_authentication_admin_email' );
						if ( empty( $email ) ) {
							$user  = wp_get_current_user();
							$email = $user->user_email;
						}
						?>
						<input type="email" id="mo_api_auth_query_mail" name="mo_api_auth_query_mail" placeholder="your email address" required value="<?php echo esc_attr( $email ); ?>" readonly="readonly"/>
						<i class="fa fa-pencil" onclick="mo_rest_api_editName()"></i>
						<div style="text-align: center;">
						
						</div>
					</div>
					<div class="mo_api_auth_modal-footer">
						<input id="mo_skip_feedback" type="button" name="miniorange_feedback_skip" class="button" value="Skip and deactivate" style="background-color: transparent;color:#0073aa;"/>
						<input type="submit" name="miniorange_feedback_submit" class=" button-primary " style="margin-left: auto;"  value="Submit"/>
					</div>
				</form>
				<form name="f" method="post" action="" id="mo_api_feedback_form_close">
					<?php wp_nonce_field( 'mo_api_authentication_skip_feedback_form', 'mo_api_authentication_skip_feedback_form_fields' ); ?>
					<input type="hidden" name="option" value="mo_api_authentication_skip_feedback"/>
				</form>
			</div>
		</div>

		<script>
			function mo_rest_api_editName(){
				document.querySelector('#mo_api_auth_query_mail').removeAttribute('readonly');
				document.querySelector('#mo_api_auth_query_mail').focus();
				return false;
			}

			jQuery(document).ready(function() {
				var mo_api_details_modal = document.getElementById('mo_api_plugin_details_modal');
				var mo_api_feedback_modal = document.getElementById('mo_api_feedback_modal');
				var mo_details_close = document.getElementById('mo_api_details_close');
				var mo_feedback_close = document.getElementById('mo_api_feedback_close');
				var mo_continue_feedback = document.getElementById('mo_api_continue_feedback');
				var mo_skip_details = document.getElementById('mo_api_skip_details');
				var mo_skip_feedback = document.getElementById('mo_skip_feedback');

				jQuery('a[aria-label="Deactivate WordPress REST API Authentication"]').click(function () {
					mo_api_details_modal.style.display = "block";
					return false;
				});

				mo_details_close.onclick = function () {
					mo_api_details_modal.style.display = "none";
				}

				mo_skip_details.onclick = function () {
					mo_api_details_modal.style.display = "none";
				}

				mo_continue_feedback.onclick = function () {
					mo_api_details_modal.style.display = "none";
					mo_api_feedback_modal.style.display = "block";
				}

				mo_feedback_close.onclick = function () {
					mo_api_feedback_modal.style.display = "none";
				}

				mo_skip_feedback.onclick = function () {
					mo_api_feedback_modal.style.display = "none";
					jQuery('#mo_api_feedback_form_close').submit();
				}

				window.onclick = function (event) {
					if (event.target == mo_api_details_modal) {
						mo_api_details_modal.style.display = "none";
						jQuery('#mo_api_feedback_form_close').submit();
					}
					if (event.target == mo_api_feedback_modal) {
						mo_api_feedback_modal.style.display = "none";
						jQuery('#mo_api_feedback_form_close').submit();
					}
				}
			});
		</script>
		<?php
	}
}