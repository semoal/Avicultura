<?php
if ( ! class_exists( 'SOW_REST_API_Controller' ) ) {
	class SOW_REST_API_Controller {
		public $version=1;

		public function __construct(){
			add_action( 'rest_api_init',array( $this,'register_routes'));

		}

		public function register_routes() {
			register_rest_route( 'sow/v1', '/user_login', array(
				'methods' => 'GET',
				'callback' =>array($this,'user_login')
			) );

			register_rest_route( 'sow/v1', '/home_slider', array(
				'methods' => 'GET',
				'callback' =>array($this,'get_home_slider')
			) );

			register_rest_route( 'sow/v1', '/shipping_method', array(
				'methods' => 'GET',
				'callback' =>array($this,'get_shipping_method')
			) );
		}

		public function user_login($request){

			if(!empty($request['password'])){
			    $pass=$this->sow_decrypt($request['password']);
			   	$test_user=wp_authenticate($request['email'],$pass);
			    
			    if($test_user==null || is_wp_error($test_user)){
			        return 'error';
			    }else{
					if(in_array( 'customer', $test_user->roles ) || in_array( 'administrator', $test_user->roles )){
						return array(
							'id'=>$test_user->ID,
							'email'=>$test_user->user_email,
							'first_name'=>$test_user->first_name,
							'last_name'=>$test_user->last_name,
							'username'=>$test_user->user_login,
							'nick_name'=>$test_user->user_nicename
						);
					}
			    }
			}
			return 'error';
		}

		public function get_home_slider($request){
			$result=get_option('sow_rest_api_slider');
			if($result){
				return $result;
			}
			return array();
		}

		public function get_shipping_method(){
			$result=get_option('sow_rest_api_shipping');
			if($result){
				return $result;
			}
			return array();

		}

		private function sow_decrypt($decrypt_str){
			$iv=substr(md5(get_option('sow_rest_api_secret')),0,16);
			$key = md5(get_option('sow_rest_api_secret'));
			$data = base64_decode($decrypt_str);
			$result = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
			return rtrim($result,"\x00..\x1F");
		}

	}
}