<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
    var $data;
    public function __construct() {
        parent::__construct();
        $valid = !(
        		empty($_SERVER['CONTENT_TYPE']) ||
        		$_SERVER['CONTENT_TYPE'] != 'application/json; charset=UTF-8' ||
        		!(isset($_SERVER['HTTP_API_KEY']) && $_SERVER['HTTP_API_KEY'] == config_item('api_key')));

        if($valid) {
        	$this->data = json_decode(file_get_contents('php://input'), TRUE);
            $valid = !!count($this->data);
        }
        if(!$valid) {
        	echo "Invalid Request";
        	exit;
        }
    }
/*     public function test() {
		$request_fields = array('user_id',array('like_id'));		
		$response = $this->api_model->test($this->data);
	} */
    	
	
	public function user_signup() {
		$request_fields = array('signup_mode');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->user_signup($this->data);
		}
		echo json_encode($response);
	}
    
	
	public function user_login() {
		$request_fields = array('signin_mode');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->user_login($this->data);
		}
		echo json_encode($response);
	}
	
	public function user_logout() {
		$request_fields = array('user_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->user_logout($this->data);
		}
		echo json_encode($response);
	}
	
	public function driver_create_profile() {
		$request_fields = array('user_id', 'user_type');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->driver_create_profile($this->data);
		}
		echo json_encode($response);
	}
	
	
	public function driver_create_about_me(){
		$request_fields = array('user_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->driver_create_about_me($this->data);
		}
		echo json_encode($response);
	}
	
	public function driver_moves(){
		$request_fields = array('user_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->driver_moves($this->data);
		}
		echo json_encode($response);
	}
	
	public function driver_start_move(){
		$request_fields = array('user_id', 'move_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->driver_start_move($this->data);
		}
		echo json_encode($response);
	}
	
	
	public function driver_extend_move(){
		$request_fields = array('user_id', 'move_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->driver_extend_move($this->data);
		}
		echo json_encode($response);
	}
	
	public function driver_save_review(){
		$request_fields = array('user_id', 'move_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->driver_save_review($this->data);
		}
		echo json_encode($response);
	}
	
	public function driver_cancel_move(){
		$request_fields = array('user_id', 'move_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->driver_cancel_move($this->data);
		}
		echo json_encode($response);
	}
	
	public function driver_create_move(){
		$request_fields = array('user_id', 'service_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->driver_create_move($this->data);
		}
		echo json_encode($response);
	}
	
	
	
	
	public function ln_driver(){
		$request_fields = array('user_id', 'driver_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->ln_driver($this->data);
		}
		echo json_encode($response);
	}
	
	public function ln_laborers(){
		$request_fields = array('user_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->ln_laborers($this->data);
		}
		echo json_encode($response);
	}
	
	public function laborer_create_profile(){
		$request_fields = array('user_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->laborer_create_profile($this->data);
		}
		echo json_encode($response);
	}
	
	public function laborer_appointments(){
		$request_fields = array('user_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->laborer_appointments($this->data);
		}
		echo json_encode($response);
	}
	
	public function laborer_past_moves(){
		$request_fields = array('user_id');
		$request_form_success = true;
		foreach($request_fields as $request_field){
			if(!isset($this->data[$request_field])) {
				$request_form_success = false;
				break;
			}
		}
		if(!$request_form_success){
			$reponse['status'] = 0;
			$response['msg'] = config_item('msg_fill_form');
		}else{
			$response = $this->api_model->laborer_past_moves($this->data);
		}
		echo json_encode($response);
	}
	
	

}
