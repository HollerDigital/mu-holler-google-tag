<?php
/*
Plugin Name: Holler Google Tag Manager
Plugin URI: http://jamesmurgatroyd.com
Description: Declares a plugin that adds Google Tag Manager to a website. Don't forget to add your Container ID in the settings page
Version: 1.0
Author: James Murgatroyd Communications
Author URI: http://jamesmurgatroyd.com/
License: GPLv2
*/

function add_gtm_header() {
  $trackingID = get_option('holler_gtm_id') ;
  if ( !current_user_can( 'manage_options' ) ) {
  ?>
  <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $trackingID; ?>');</script>
<!-- End Google Tag Manager -->

<?php
  }
}


function add_gtm_body() {
  $trackingID = get_option('holler_gtm_id') ;
  if ( !current_user_can( 'manage_options' ) ) {
  ?>
 <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $trackingID; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php
  }
}

//register our settings
function register_holler_gtm_settings() {
  register_setting( 'holler-gtm-settings-group', 'holler_gtm_id' );
  register_setting( 'holler-gtm-settings-group', 'holler_gtm_enabled' );
}
add_action( 'admin_init', 'register_holler_gtm_settings' );

// Action Hook to add menu to Settings
add_action("admin_menu", "holler_gtm_options_submenu");

// Add Sub Menu to Settings in WP
function holler_gtm_options_submenu() {
  add_submenu_page(
        'options-general.php',
        'Holler Google Tag Manager',
        'Google Tag Manager',
        'administrator',
        'holler-gtm-options',
        'holler_gtm_settings_page' );
}
// Settings Page Code
function holler_gtm_settings_page()   {
    ob_start(); ?>
  <div class="wrap">
  <h1>Google Tag Manager</h1>
  
  <form method="post" action="options.php">
      <?php settings_fields( 'holler-gtm-settings-group' ); ?>
      <?php do_settings_sections( 'holler-gtm-settings-group' ); ?>
      <table class="form-table">
     
          <tr valign="top">
            <th scope="row">Tracking Enabled</th>
            <td> <input type="checkbox" name="holler_gtm_enabled" value="1" <?php checked(1, get_option('holler_gtm_enabled'), true); ?> /> </td>
          </tr>
                 
          <tr valign="top">
            <th scope="row">Tracking ID</th>
            <td><input type="text" name="holler_gtm_id" value="<?php echo esc_attr( get_option('holler_gtm_id') ); ?>" /></td>
          </tr>
      </table>
      <?php submit_button(); ?>
  </form>
  </div>
<?php
    echo ob_get_clean();
  }


$enabled = get_option('holler_gtm_enabled');
if( $enabled  == '1' ){
  // Google Tag Manager
  add_action('wp_head', 'add_gtm_header');
  add_action('wp_body_open', 'add_gtm_body');
}
