<?php
/**
 * Protected REST APIs
 * This file will display the UI to protect WP REST API endpoints.
 *
 * @package    protected-rest-api
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Mo_API_Authentication_Auditing
 *
 * Handles the display of auditing information and pie charts for API access.
 */
class Mo_API_Authentication_Auditing {

	/**
	 * Display auditing pie charts and API access summary.
	 */
	public static function mo_api_authentication_display_auditing_pie_charts() {

		$counters = get_option( 'api_access_counters', array() );

		if ( ! is_array( $counters ) || empty( $counters ) ) {
			?>
			<div id="mo_api_authentication_password_setting_layout" class="border border-1 rounded-4 p-3 bg-white">
			<div class="d-flex align-items-center gap-3 mb-3">
				<h5 class="m-0">API Access Analytics</h5>
			</div>
			<p class="mb-4 fs-6">
				Utilize advanced auditing and analysis features to gain a clearer picture of API performance and security threats.
			</p>
			<div class="row bg-light p-3 mb-3 shadow-sm rounded w-100 mx-auto">
			<h6>No data available for auditing. It appears that there are no recorded API access attempts or errors at this time. Start accessing the APIs to begin tracking and see auditing data.</h6>
			</div>
			</div>
			<?php
			return;
		}

		// Success and Blocked counts.
		$success_counts = is_array( $counters ) ? ( $counters[ Mo_API_Authentication_Constants::SUCCESS ] ?? array() ) : array();
		$blocked_counts = is_array( $counters ) ? ( $counters[ Mo_API_Authentication_Constants::BLOCKED ] ?? array() ) : array();
		// Calculate API accesses.
		$open_api_access = is_array( $success_counts ) ? ( $success_counts[ Mo_API_Authentication_Constants::OPEN_API ] ?? 0 ) : 0;

		$protected_api_access = 0;

		foreach ( $success_counts as $key => $value ) {
			if ( Mo_API_Authentication_Constants::OPEN_API !== $key ) {
				$protected_api_access += $value;
			}
		}

		$total_success = $open_api_access + $protected_api_access;
		$total_blocked = array_sum( $blocked_counts );
		$total_apis    = $total_success + $total_blocked;

		?>
		<div id="mo_api_authentication_password_setting_layout" class="border border-1 rounded-4 p-3 bg-white">
			<div class="d-flex align-items-center gap-3 mb-3">
				<h5 class="m-0">API Access Analytics</h5>
			</div>
			<p class="mb-4 fs-6">
				Utilize advanced auditing and analysis features to gain a clearer picture of API performance and security threats.
			</p>
			<div class="row bg-light p-3 mb-3 shadow-sm rounded w-100 mx-auto">
				<!-- Total API Access -->
				<div class="col text-center">
					<h4 class="fw-bold"><?php echo esc_html( $total_apis ); ?></h4>
					<h6>Total API Access</h6>
				</div>
				<!-- Open API Access -->
				<div class="col text-center">
					<h4 class="fw-bold"><?php echo esc_html( $open_api_access ); ?></h4>
					<h6>Open API Access</h6>
				</div>
				<!-- Authorized API Access -->
				<div class="col text-center">
					<h4 class="fw-bold"><?php echo esc_html( $protected_api_access ); ?></h4>
					<h6>Authorized API Access</h6>
				</div>
				<!-- Blocked API Access -->
				<div class="col text-center position-relative">
					<h4 class="fw-bold"><?php echo esc_html( $total_blocked ); ?></h4>
					<h6>Blocked API Access</h6>
					<!-- Tooltip icon with Bootstrap classes -->
					<span class="badge bg-secondary position-absolute top-0 end-0 p-2" data-bs-toggle="tooltip" data-bs-html="true" title="
						<ul>
							<li><strong>Total API Access:</strong> Includes all API requests.</li>
							<li><strong>Open API Access:</strong> APIs accessible without miniOrange Authentication.</li>
							<li><strong>Authorized API Access:</strong> Valid credentials provided.</li>
							<li><strong>Blocked API Access:</strong> Requests blocked due to missing/invalid credentials.</li>
						</ul>
					">i</span>
				</div>
			</div>
			<div class="border border-1 rounded-3 p-3 mt-2">
				<h6>API Access Audit</h6>
				<div id="api-access-pie-chart" class="mb-4"></div>
				<hr class="my-3">
				<h6>Open API vs Protected API Access</h6>
				<div id="open-vs-protected-pie-chart"></div>
			</div>
		</div>
		<script>
			// Initialize Bootstrap tooltips
			document.addEventListener('DOMContentLoaded', function() {
				var mo_rest_api_tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
				var mo_rest_api_tooltipList = mo_rest_api_tooltipTriggerList.map(function(tooltipTriggerEl) {
					return new bootstrap.Tooltip(tooltipTriggerEl, {
						placement: 'right', // Ensure tooltip opens on the right side
						container: 'body' // Append to body to avoid overflow issues
					});
				});
			});
		</script>

		<script type="text/javascript">
			// Load the Visualization API and the piechart package.
			google.charts.load('current', {
				'packages': ['corechart']
			});

			// Set a callback to run when the Google Visualization API is loaded.
			google.charts.setOnLoadCallback(mo_api_auth_drawCharts);

			function mo_api_auth_drawCharts() {
				// Chart 1: All API Success and Blocked Events
				var mo_rest_api_apiAccessAuditChartData = google.visualization.arrayToDataTable([
					['API Type', 'Count'],
					['Authorized API Access', <?php echo isset( $success_counts[ Mo_API_Authentication_Constants::PROTECTED_API ] ) ? esc_js( $success_counts[ Mo_API_Authentication_Constants::PROTECTED_API ] ) : 0; ?>],
					['Open API Access', <?php echo esc_js( $open_api_access ); ?>],
					['Missing Authorization Header', <?php echo isset( $blocked_counts[ Mo_API_Authentication_Constants::MISSING_AUTHORIZATION_HEADER ] ) ? esc_js( $blocked_counts[ Mo_API_Authentication_Constants::MISSING_AUTHORIZATION_HEADER ] ) : 0; ?>],
					['Invalid Credentials', <?php echo isset( $blocked_counts[ Mo_API_Authentication_Constants::INVALID_CREDENTIALS ] ) ? esc_js( $blocked_counts[ Mo_API_Authentication_Constants::INVALID_CREDENTIALS ] ) : 0; ?>]
				]);
				
				var mo_rest_api_apiAccessAuditChartOptions = {
					height: 350,
					width: 635,
					colors: ['#ea7070', '#4dbedf', '#eeb646', '#e59572'],
					pieHole: 0.5,
					tooltip: {
						trigger: 'focus' // Change to 'focus' to avoid flicker on hover
					}
				};

				var mo_rest_api_apiAccessAuditChart = new google.visualization.PieChart(document.getElementById('api-access-pie-chart'));
				mo_rest_api_apiAccessAuditChart.draw(mo_rest_api_apiAccessAuditChartData, mo_rest_api_apiAccessAuditChartOptions);

				// Chart 2: Open API vs Protected API
				var mo_rest_api_openVsProtectedChartData = google.visualization.arrayToDataTable([
					['API Type', 'Count'],
					['Open API Access', <?php echo esc_js( $open_api_access ); ?>],
					['Protected API Access', <?php echo esc_js( $protected_api_access + $total_blocked ); ?>]
				]);

				var mo_rest_api_openVsProtectedChartOptions = {
					height: 350,
					width: 635,
					colors: ['#F61313', '#04AF70'],
					tooltip: {
						trigger: 'focus' // Change to 'focus' to avoid flicker on hover
					}
				};

				var mo_rest_api_openVsProtectedChart = new google.visualization.PieChart(document.getElementById('open-vs-protected-pie-chart'));
				mo_rest_api_openVsProtectedChart.draw(mo_rest_api_openVsProtectedChartData, mo_rest_api_openVsProtectedChartOptions);
			}
		</script>

		<?php
	}
}
?>