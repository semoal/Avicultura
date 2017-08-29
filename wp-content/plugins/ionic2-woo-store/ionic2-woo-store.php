<?php
/**
 * @package ionic2-woo-store
 * @version 1.0
 */
/*
Plugin Name: Ionic2-Woo-Store
Plugin URI: http://www.oddwolves.com/
Description: Plugin for Ionic2WooStore app.
Version: 1.0
Author URI: http://www.oddwolves.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

define('SOW_REST_API_PATH', plugin_dir_path(__FILE__));
define('SOW_REST_API_URL',plugin_dir_url(__FILE__));


if (isset($_POST['sow_basic_options_action']) && $_POST['sow_basic_options_action'] == 'Save Settings') {
	
	$shipping_array=array();
	if(strpos($_POST['method_array'],',')){
		$ids_array=explode(',',$_POST['method_array']);

		for ($i = 0; $i < count($ids_array); $i++)
		{
			$shipping_info_array=array(
				'title'=>$_POST['sow_method_title_'.$ids_array[$i]],
		'id'=>$_POST['sow_method_id_'.$ids_array[$i]],
		'cost'=>$_POST['sow_method_cost_'.$ids_array[$i]],
				);
			array_push($shipping_array,$shipping_info_array);
		}
	}else{
		array_push($shipping_array,array(
				'title'=>$_POST['sow_method_title_'.$_POST['method_array']],
		'id'=>$_POST['sow_method_id_'.$_POST['method_array']],
		'cost'=>$_POST['sow_method_cost_'.$_POST['method_array']],
				));
	}

	update_option('sow_rest_api_secret',$_POST['sow_secret']);
	update_option('sow_rest_api_shipping',$shipping_array);
?>
<div class="updated">
	<p>
		<?php _e( 'Setting Updated'); ?>
	</p>
</div>
<?php
}

if (isset($_POST['sow_slider_options_action']) && $_POST['sow_slider_options_action'] == 'Save Settings') {
	$slider_array=array();
	if(strpos($_POST['slider_array'],',')){
		$ids_array=explode(',',$_POST['slider_array']);

		for ($i = 0; $i < count($ids_array); $i++)
		{
			array_push($slider_array,$_POST['slider_'.$ids_array[$i]]);
		}
	}else{
		array_push($slider_array,$_POST['slider_'.$_POST['slider_array']]);
	}

	update_option('sow_rest_api_slider',$slider_array);
?>
<div class="updated">
	<p>
		<?php _e( 'Setting Updated'); ?>
	</p>
</div>
<?php
}

if( ! function_exists( 'install_woocommerce_admin_notice' ) ) {

    function install_woocommerce_admin_notice() { ?>
<div class="error">
	<p>
		<?php _e( 'ionic2-woo-store is enabled but not effective. It requires WooCommerce in order to work.'); ?>
	</p>
</div>
<?php
    }
}

if( ! function_exists( 'install_rest_api_admin_notice' ) ) {

    function install_rest_api_admin_notice() { ?>
<div class="error">
	<p>
		<?php _e( 'ionic2-woo-store is enabled but not effective. It requires Rest-api in order to work.'); ?>
	</p>
</div>
<?php
    }
}

add_action( 'admin_init', function(){
	global $wp_version;

	if($wp_version<4.7 && !is_plugin_active( 'rest-api/plugin.php' ) ) {
		add_action( 'admin_notices', 'install_rest_api_admin_notice' );
		return;
	}
});
	  add_action('plugins_loaded',function(){
		  if ( ! function_exists( 'WC' ) ) {
			  add_action( 'admin_notices', 'install_woocommerce_admin_notice' );
			  return;
		  }
		  
	  });
	  

	  require_once( SOW_REST_API_PATH . 'includes/class.sow-rest-api-controller.php' );
	  $test=new SOW_REST_API_Controller();

	  add_action("admin_menu", "register_sow_rest_api_page");
	  function register_sow_rest_api_page()
	  {
		  add_menu_page("Ionic2WooStore","Ionic2WooStore","manage_options","Ionic2WooStore","sow_rest_page");
	  }

	  function sow_rest_page()
	  {
		  
		  $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'basic_options';
?>
<h2 class="nav-tab-wrapper">
	<a href="?page=Ionic2WooStore&tab=basic_options" class="nav-tab <?php echo $active_tab == 'basic_options' ? 'nav-tab-active' : ''; ?>">Basic Options</a>
	<a href="?page=Ionic2WooStore&tab=slider_options" class="nav-tab <?php echo $active_tab == 'slider_options' ? 'nav-tab-active' : ''; ?>">Slide Options</a>
</h2>
<?php if($active_tab == 'basic_options'){
		  include_once( 'admins/html-basic-setting.php' );
	  }else if($active_tab == 'slider_options'){
		  include_once( 'admins/html-slider-setting.php' );
	  }
	  }

	  add_action( 'wp_ajax_get_sow_secret_key', 'get_sow_secret_key_action_callback' );

	  function get_sow_secret_key_action_callback() {
		  echo genrate_sow_secret_key();
		  wp_die();
	  }

	  function genrate_sow_secret_key(){
		  $str='';
		  if ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
			  $str=bin2hex( openssl_random_pseudo_bytes( 20 ) );
		  } else {
			  $str= sha1( wp_rand() );
		  }

		  $consumer_key    = 'sow_' . $str;
		  $consumer_key=hash_hmac( 'sha256', $consumer_key, 'sow-api' );
		  return $consumer_key;
	  }

?>