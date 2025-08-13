 jQuery(document).ready(function($) {
    $('#mo_api_authentication_WCPS_loading').click(function() {
        var $btn = $(this);
        var currentText = $btn.text().trim();

        if (currentText === 'Install Now') {
            $btn.text('Installing...');
        } else if (currentText === 'Activate Now') {
            $btn.text('Activating...');
        } else {
            $btn.text('Processing...');
        }
    });

     $('#mo_api_authentication_CAW_loading').click(function() {
         var $btn = $(this);
         var currentText = $btn.text().trim();
         if (currentText === 'Install Now') {
            $btn.text('Installing...');
          } else if (currentText === 'Activate Now') {
             $btn.text('Activating...');
          } else {
             $btn.text('Processing...');
         }
     });
});

function mo_api_authentication_install_and_activate_caw_free( plugin_exists ) {
  var data = {
	  'action': 'install_and_activate_caw_free',
	  'nonce':moRestData.nonce_caw
	};

 if ( plugin_exists ) {
    jQuery('#mo_api_authentication_caw_loading').text('Opening...');
} else {
	jQuery('#mo_api_authentication_caw_loading').text('Installing...');			
  }
	jQuery.post(ajaxurl, data)
	.done(function(response) {
	   if ( response.success ) {
			window.location.href = response.data.redirect_url;
	 } 
})
	.fail(function(xhr, textStatus, errorThrown) {
        console.log('Error in AJAX request:', textStatus, errorThrown);
			jQuery('#mo_api_authentication_caw_loading').text('Activate Now');
			window.location.href = 'https://wordpress.org/plugins/custom-api-for-wp/';
		});
	}
function mo_api_authentication_install_and_activate_wcps_free( plugin_exists ) {
  var data = {
	  'action': 'install_and_activate_wcps_free',
	  'nonce': moRestData.nonce_wcps
	};

		if ( plugin_exists ) {
			jQuery('#mo_api_authentication_wcps_loading').text('Opening...');
		  } else {
			jQuery('#mo_api_authentication_wcps_loading').text('Installing...');			
		}
			jQuery.post(ajaxurl, data)
			.done(function(response) {
				if ( response.success ) {
					window.location.href = response.data.redirect_url;
          }
	  })
		.fail(function(xhr, textStatus, errorThrown) {
			jQuery('#mo_api_authentication_wcps_loading').text('Activate Now');
			window.location.href = 'https://wordpress.org/plugins/products-sync-for-woocommerce/';
		});
   }