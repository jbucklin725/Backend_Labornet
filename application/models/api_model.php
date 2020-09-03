<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model {

    public function __construct() {
        
    }
	
	

    public function user_signup($params){
		$result = array();
		$data = array();
		$status = 0;
		$msg = '';
		if((int)$params['signup_mode'] == 2){
			$request_fields = array('user_type', 'user_facebook_id', 'user_name', 'user_full_name', 'user_company_code', 'photo_data', 'user_location', 'user_phone_num');
			$query = $this->db->get_where('ln_users', array('user_facebook_id'=>$params['user_facebook_id']));
			if($query->num_rows() == 0){
				$created_time = time();
				$image_path = config_item('path_media_users');				
				$image_name =$created_time  . '.jpg';
				$image_url = $image_path . $image_name;
				$binary = base64_decode($params['photo_data']);
				header('Content-Type: bitmap; charset=utf-8');
				$file = fopen($image_url, 'w');
				
				if($file) {
					fwrite($file, $binary);
				} else {
					$status = 3;
					$msg = 'File Upload failed';
				}
				fclose($file);
				$photo_url = $image_name;
				$user_signup_date = date('Y-m-d h:i:s');
				$data = array('user_type'=>$params['user_type'],
							'user_facebook_id'=>$params['user_facebook_id'],
							'user_name'=>$params['user_name'],
							'user_full_name'=>$params['user_full_name'],
							'user_company_code'=>$params['user_company_code'],
							'user_photo_url'=>$photo_url,
							'user_location_address'=>$params['user_location'],
							'user_phone_num'=>$params['user_phone_num'],
							'user_last_login_date'=>$user_signup_date,
							'user_signup_date'=>$user_signup_date,
							'user_closed'=>1);
				$this->db->insert('ln_users', $data);
				$insert_id = $this->db->insert_id();
				$result['user_id'] = $insert_id;
				$status = 1;
				$msg = 'Success your Sign Up!';
			}else{
				$status = 2;
				$msg = 'User is already registered.';
			}
		}elseif((int)$params['signup_mode'] == 1){
			$request_fields = array('user_type', 'user_email', 'user_password', 'user_name', 'user_full_name', 'user_company_code', 'photo_data', 'user_phone_num', 'user_location');
			$query_manual = $this->db->get_where('ln_users', array('user_email'=>$params['user_email']));
			if($query_manual->num_rows() == 0){
				$created_time = time();
				$image_path = config_item('path_media_users');				
				$image_name =$created_time  . '.jpg';
				$image_url = $image_path . $image_name;
				$binary = base64_decode($params['photo_data']);
				header('Content-Type: bitmap; charset=utf-8');
				$file = fopen($image_url, 'w');
				
				if($file) {
					fwrite($file, $binary);
				} else {
					$status = 3;
					$msg = 'File Upload failed';
				}
				fclose($file);
				$photo_url = $image_name;
				$user_signup_date = date('Y-m-d h:i:s');
				$data = array('user_type'=>$params['user_type'],
							'user_email'=>$params['user_email'],
							'user_password'=>$this->get_user_auth_salt($params['user_password']),
							'user_name'=>$params['user_name'],
							'user_full_name'=>$params['user_full_name'],
							'user_company_code'=>$params['user_company_code'],
							'user_photo_url'=>$photo_url,
							'user_location_address'=>$params['user_location'],
							'user_phone_num'=>$params['user_phone_num'],
							'user_last_login_date'=>$user_signup_date,
							'user_signup_date'=>$user_signup_date,
							'user_closed'=>1);
				$this->db->insert('ln_users', $data);
				$insert_id = $this->db->insert_id();
				$result['user_id'] = $insert_id;
				$status = 1;
				$msg = 'Success your Sign up!';
			}else{
				$status = 2;
				$msg = 'User is already registered.';
			}
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
     
    
	public function user_login($params){
		$result = array();
		$current_user = array();
		$status = 0;
		$msg = '';
		if((int)$params['signin_mode'] == 2){
			$request_fields = array('user_facebook_id', 'user_location');
			$query = $this->db->get_where('ln_users', array('user_facebook_id'=>$params['user_facebook_id']));
			if($query->num_rows() > 0){
				$user_login_date = date('Y-m-d h:i:s');
				$this->db->update('ln_users', array('user_last_login_date'=>$user_login_date, 'user_location_address'=>$params['user_location'], 'user_closed'=>1), array('user_facebook_id'=>$params['user_facebook_id']));
				$user_id = element('user_id', $query->row_array());
				$user_type = element('user_type', $query->row_array());
				$result['user_id'] = $user_id;
				$result['user_type'] = $user_type;
				$status = 1;
				$msg = 'Success!';
			}else{
				$status = 2;
				$msg = 'This User Not Registered!';
			}
		}elseif((int)$params['signin_mode'] == 1){
			$request_fields = array('user_email', 'user_password', 'user_location');
			$query = $this->db->get_where('ln_users', array('user_email'=>$params['user_email'], 'user_password'=>$this->get_user_auth_salt($params['user_password'])));
			if($query->num_rows() > 0){
				$user_login_date = date('Y-m-d h:i:s');
				$this->db->update('ln_users', array('user_last_login_date'=>$user_login_date, 'user_location_address'=>$params['user_location'], 'user_closed'=>1), array('user_email'=>$params['user_email']));
				$user_id = element('user_id', $query->row_array());
				$user_type = element('user_type', $query->row_array());
				$result['user_id'] = $user_id;
				$result['user_type'] = $user_type;
				$status = 1;
				$msg = 'Success!';
			}else{
				$status = 2;
				$msg = 'This User Not registered!';
			}
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	
	public function user_logout($params){
		$result = array();
		$status = 0;
		$msg = '';
		$request_fields = array('user_id');
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$user_closed = 0;
			$this->db->update('ln_users', array('user_closed'=>$user_closed), array('user_id'=>$params['user_id']));
			$status = 1;
			$msg = 'Success!';
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	public function driver_create_profile($params){
		$result = array();
		$status = 0;
		$msg = '';
		
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() == 0){
			$status = 2;
			$msg = 'This user not registered!';
		}elseif($params['user_type'] == 1){
			$created_time = time();
			$image_path = config_item('path_media_users');				
			$image_name =$created_time  . '.jpg';
			$image_url = $image_path . $image_name;
			$binary = base64_decode($params['user_photo_url']);
			header('Content-Type: bitmap; charset=utf-8');
			$file = fopen($image_url, 'w');
				
			if($file) {
				fwrite($file, $binary);
			} else {
				$status = 3;
				$msg = 'File Upload failed';
			}
			fclose($file);
			$photo_url = $image_name;
			$user_data = array(
							'user_first_name'=>$params['user_first_name'],
							'user_last_name'=>$params['user_last_name'],
							'user_full_name'=>$params['user_first_name'] . ' ' . $params['user_last_name'],
							'user_gender'=>$params['user_gender'],
							'user_age'=>$params['user_age'],
							'user_about_content'=>$params['user_about'],
							'user_photo_url'=>$photo_url,
							'user_location'=>$params['user_location'],
							'user_fav_store_ids'=>$params['user_fav_store_ids']
						);
			$this->db->update('ln_users', $user_data, array('user_id'=>$params['user_id']));
			$user_profile = array(
								'cp_user_id'=>$params['user_id'],
								'cp_cbs_id'=>$params['user_body_shape_id'],
								'cp_waist'=>$params['user_waist'],
								'cp_height'=>$params['user_height'],
								'cp_hair_color'=>$params['user_hair_color'],
								'cp_eye_color'=>$params['user_eye_color']
							);
			if($this->db->get_where('ln_driver_profiles', array('cp_user_id'=>$params['user_id']))->num_rows() == 0){
				$this->db->insert('ln_driver_profiles', $user_profile);
			}else{
				$this->db->update('ln_driver_profiles', $user_profile, array('cp_user_id'=>$params['user_id']));
			}
			$user_fashion_needs = array(
									'cfn_user_id'=>$params['user_id'],
									'cfn_budget_min'=>$params['user_budget_min'],
									'cfn_budget_max'=>$params['user_budget_max'],
									'cfn_fav_store_ids'=>$params['user_fav_store_ids']
								);
			if($this->db->get_where('ln_driver_fashion_needs', array('cfn_user_id'=>$params['user_id']))->num_rows() == 0){
				$this->db->insert('ln_driver_fashion_needs', $user_fashion_needs);
			}else{
				$this->db->update('ln_driver_fashion_needs', $user_fashion_needs, array('cfn_user_id'=>$params['user_id']));
			}
			$i = 0;
			$cfn_occasions = (string)element('cfn_occasions', $this->db->get_where('ln_driver_fashion_needs', array('cfn_user_id'=>$params['user_id']))->row_array());
			foreach($params['user_occasions'] as $user_occasion){
				if($i < count($params['user_occasions'])){
					$user_occasion = $params['user_occasions'][$i];
					if($cfn_occasions == ''){
						$cfn_occasions = $user_occasion[0] . ':' . $user_occasion[1];
					}else{
						$cfn_occasions .= ',' . $user_occasion[0] . ':' . $user_occasion[1];
					}
					$i ++;
				}
			}
			$this->db->update('ln_driver_fashion_needs', array('cfn_occasions'=>$cfn_occasions), array('cfn_user_id'=>$params['user_id']));
			$status = 1;
			$msg = 'Success!';
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	
	public function driver_create_about_me($params){
		$result = array();
		$status = 0;
		$msg = '';
		$request_fields = array('user_id', 'user_profile_photo', 'user_cover_photo', 'user_about_me', 
									array('move_photo_urls'), array('collection_photo_urls'), array('style_photo_urls'));
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			// profile photo upload
			$created_pp_time = time();
			$pp_image_path = config_item('path_media_users');				
			$pp_image_name =$created_pp_time  . '.jpg';
			$pp_image_url = $pp_image_path . $pp_image_name;
			$pp_binary = base64_decode($params['user_profile_photo']);
			header('Content-Type: bitmap; charset=utf-8');
			$pp_file = fopen($pp_image_url, 'w');
				
			if($pp_file) {
				fwrite($pp_file, $pp_binary);
			} else {
				$status = 2;
				$msg = 'Profile photo Upload failed';
			}
			fclose($pp_file);
			$profile_photo_url = $pp_image_name;
			
			// cover photo upload
			$created_cp_time = time();
			$cp_image_path = config_item('path_media_users');				
			$cp_image_name =$created_cp_time  . '_cover'. '.jpg';
			$cp_image_url = $cp_image_path . $cp_image_name;
			$cp_binary = base64_decode($params['user_cover_photo']);
			header('Content-Type: bitmap; charset=utf-8');
			$cp_file = fopen($cp_image_url, 'w');
				
			if($cp_file) {
				fwrite($cp_file, $cp_binary);
			} else {
				$status = 3;
				$msg = 'Cover photo Upload failed';
			}
			fclose($cp_file);
			$cover_photo_url = $cp_image_name;
			$user_data = array(
							'user_photo_url'=>$profile_photo_url,
							'user_cover_photo_url'=>$cover_photo_url,
							'user_about_content'=>$params['user_about_me']
						);
			$this->db->update('ln_users', $user_data, array('user_id'=>$params['user_id']));
			
			//update move photo
			$i = 0;
			$sp_created_time = time();
			$sp_photo_urls = array();
			foreach($params['move_photo_urls'] as $move_photo_url){
				if($i < count($params['move_photo_urls'])){
					$move_photo_url = $params['move_photo_urls'][$i];
					$sp_image_path = config_item('path_media_moves');				
					$sp_image_name =$sp_created_time . '_' . $i . '.jpg';
					$sp_image_url = $sp_image_path . $sp_image_name;
					$sp_binary = base64_decode($move_photo_url[1]);
					header('Content-Type: bitmap; charset=utf-8');
					$sp_file = fopen($sp_image_url, 'w');
				
					if($sp_file) {
						fwrite($sp_file, $sp_binary);
					} else {
						$status = 4;
						$msg = 'move photo Upload failed';
					}
					fclose($sp_file);
					$sp_photo_urls[$i] = $sp_image_name;
					$this->db->update('ln_moves', array('move_photo_url'=>$sp_photo_urls[$i]), array('move_id'=>$move_photo_url[0]));
					$i ++ ;
				}
			}   //  foreach($params['user_moves'] as $user_move)
			
			// insert collection photos
			$i = 0;
			$cop_created_time = time();
			$cop_photo_urls = array();
			foreach($params['collection_photo_urls'] as $collection_photo_url){
				$cop_image_path = config_item('path_media_collections');				
				$cop_image_name =$cop_created_time . '_' . $i . '.jpg';
				$cop_image_url = $cop_image_path . $cop_image_name;
				$cop_binary = base64_decode($collection_photo_url);
				header('Content-Type: bitmap; charset=utf-8');
				$cop_file = fopen($cop_image_url, 'w');
				
				if($cop_file) {
					fwrite($cop_file, $cop_binary);
				} else {
					$status = 5;
					$msg = 'Collection photo Upload failed';
				}
				fclose($cop_file);
				$cop_photo_urls[$i] = $cop_image_name;
				$collection_data = array(
										'cc_photo_url'=>$cop_photo_urls[$i],
										'cc_user_id'=>$params['user_id']
									);
				$this->db->insert('ln_driver_collections', $collection_data);
				$i ++ ;
			}  //  foreach($params['user_collections'] as $user_collection)
			
			// insert style photos
			$i = 0;
			$stp_created_time = time();
			$stp_photo_urls = array();
			foreach($params['style_photo_urls'] as $style_photo_url){
				$stp_image_path = config_item('path_media_styles');				
				$stp_image_name =$stp_created_time . '_' . $i . '.jpg';
				$stp_image_url = $stp_image_path . $stp_image_name;
				$stp_binary = base64_decode($style_photo_url);
				header('Content-Type: bitmap; charset=utf-8');
				$stp_file = fopen($stp_image_url, 'w');
				
				if($stp_file) {
					fwrite($stp_file, $stp_binary);
				} else {
					$status = 6;
					$msg = 'Style photo Upload failed';
				}
				fclose($stp_file);
				$stp_photo_urls[$i] = $stp_image_name;
				$style_data = array(
								'cs_photo_url'=>$stp_photo_urls[$i],
								'cs_user_id'=>$params['user_id']
							);
				$this->db->insert('ln_driver_styles', $style_data);
				$i ++;
			}  //   foreach($params['style_photo_urls'] as $style_photo_url)
			$status = 1;
			$msg = 'Success!';
		}   ///   if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	
	public function driver_moves($params){
		$result = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$moves = $this->db->get_where('ln_moves', array('move_driver_id'=>$params['user_id']));
			if($moves->num_rows() > 0){
				$i = 0;
				$driver_moves = array();
				foreach($moves as $move){
					if($i < $moves->num_rows()){
						$move = $moves->row_array($i);
						$driver_move = array(
											'move_id'=>$move['move_id'],
											//'move_status'=>$move['move_status'],
											'move_name'=>$move['move_title'],
											'move_photo_url'=>$move['move_photo_url'],
											'move_laborer_id'=>$move['move_user_id'],
											'move_rate'=>$move['move_rating'],
											'move_location_street'=>$move['move_location_street'],
											'move_location_city'=>$move['move_location_city'],
											'move_loaction_state'=>$move['move_location_state'],
											'move_location_zipcode'=>$move['move_location_zipcode'],
											'move_notes_content'=>$move['move_description'],
											'move_created_date'=>$move['move_created_date'],
											'move_expire_date'=>$move['move_expire_date'],
											'move_payment_subtotal'=>$move['move_payment_subtotal'],
											'move_payment_promo_code'=>$move['move_payment_promo_code'],
											'move_payment_credits'=>$move['move_payment_credits'],
											'move_payment_total'=>$move['move_payment_total']
										);
						$move_laborer = $this->db->get_where('ln_users', array('user_id'=>$move['move_user_id']))->row_array();
						$driver_move['move_laborer_name'] = (string)element('user_full_name', $move_laborer);
						$driver_move['move_laborer_photo_url'] = (string)element('user_photo_url', $move_laborer);
						
						
						$interval = (new DateTime(element('move_expire_date', $move)))->diff(new DateTime(date('Y-m-d h:i:s')));
						$left_time = $interval->format('%H hours %i minutes ');
						$driver_move['left_time'] = $left_time; 
						$move_start_datetime = explode(' ', element('move_created_date', $move));
						$move_end_datetime = explode(' ', element('move_expire_date', $move));
						$driver_move['move_date'] = $move_start_datetime[0];
						$driver_move['move_start_time'] = explode(':', $move_start_datetime[1])[0] . ':' . explode(':', $move_start_datetime[1])[1];
						$driver_move['move_end_time'] = explode(':', $move_end_datetime[1])[0] . ':' . explode(':', $move_end_datetime[1])[1];
						$current_time = new DateTime(date('Y-m-d h:i:s'));
						$move_expire_date = new DateTime(element('move_expire_date', $move));
						if($current_time < $move_expire_date){
							$driver_move['move_status'] = 1;
						}else{
							$driver_move['move_status'] = 2;
						}
						$this->db->update('ln_moves', array('move_status'=>$driver_move['move_status']), array('move_id'=>$move['move_id']));
						$driver_moves[] = $driver_move;
						$i ++ ;
					}	//	if($i < $moves->num_rows())
				}	//	foreach($sessoins as $move)
				$result['driver_moves'] = $driver_moves;
				$status = 1;
				$msg = 'Success!';
			}else{
				$status = 2;
				$msg = 'There is no moves!';
			}   //  if($moves->num_rows() > 0)
		}  // if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	public function driver_start_move($params){
		$result = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$move = $this->db->get_where('ln_moves', array('move_id'=>$params['move_id']));
			if($move->num_rows() > 0){
				$move_status = 1;
				$this->db->update('ln_moves', array('move_status'=>$move_status), array('move_id'=>$params['move_id']));
				$status = 1;
				$msg = 'Success!';
			}else{
				$status = 2;
				$msg = 'This move not found';
			}	//	if($move->num_rows() > 0)
		}	//	if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	
	public function driver_extend_move($params){
		$result = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$move = $this->db->get_where('ln_moves', array('move_id'=>$params['move_id']));
			if($move->num_rows() > 0){
				$move_status = element('move_status', $move->row_array());
				if($move_status == 1){
					//$move_expire_date = new DateTime(element('sessoin_expire_date', $move->row_array()));
					if((int)$params['extend_time'] == 1){
						/* $move_expire_date->add(new DateInterval('PT30M'));
						$move_expire_date_new = $move_expire_date->format('Y-m-d H:i'); */
						$move_expire_date_new = (new DateTime(element('sessoin_expire_date', $move->row_array())))->modify('+30 minutes')->format('Y-m-d H:i:s');
					}elseif((int)$params['extend_time'] == 2){
						$move_expire_date->add(new DateInterval(PT1H));
						$move_expire_date_new = $move_expire_date->format('Y-m-d h:i:s');						
					}elseif((int)$params['extend_time'] == 3){
						$move_expire_date->add(new DateInterval(PT2H));
						$move_expire_date_new = $move_expire_date->format('Y-m-d h:i:s');						
					}	//	if($params['extend_time'] == 1)
					$this->db->update('ln_moves', array('move_expire_date'=>$move_expire_date_new), array('move_id'=>$params['move_id']));
					$status = 1;
					$msg = 'Success!';
				}	//	if(element('move_status', $move->row_array()) == 1)
			}else{
				$status = 2;
				$msg = 'There is no move!';
			}	//if($move->num_rows() > 0)
		}	//	if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	
	public function driver_save_review($params){
		$result = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$move = $this->db->get_where('ln_moves', array('move_id'=>$params['move_id']));
			if($move->num_rows() > 0){
				$data = array(
							'move_rating'=>$params['move_rate'],
							'move_review_content'=>$params['move_review']
						);
				$this->db->update('ln_moves', $data, array('move_id'=>$params['move_id']));
				$status = 1;
				$msg = 'Success!';
			}else{
				$status = 2;
				$msg = 'This move not exist!';
			}   //  if($move->num_rows() > 0)
		}   //  if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return  $result;
	}
	
	public function driver_cancel_move($params){
		$result = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$move = $this->db->get_where('ln_moves', array('move_id'=>$params['move_id']));
			if($move->num_rows() > 0) {
				$this->db->delete('ln_moves', array('move_id'=>$params['move_id']));
				$status = 1;
				$msg = 'Success!';
			}else{
				$status = 2;
				$msg = 'This move not exist!';
			}	//	if($move->num_rows() > 0)
		}	//	if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	public function driver_create_move($params){
		$result = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$service = $this->db->get_where('ln_services', array('service_id'=>$params['service_id']));
			$service_title = element('service_title', $service->row_array());
			$service_description = element('service_description', $service->row_array());
			$move = array(
						'move_title'=>$service_title,
						'move_description'=>$service_description,
						'move_driver_id'=>$params['user_id'],
						'move_user_id'=>$params['laborer_id'],
						'move_created_date'=>$params['move_created_date'],
						'move_expire_date'=>$params['move_expire_date'],
						'move_day_period'=>$params['move_day_period'],
						'move_rating'=>$params['move_hourly_rate'],
						'move_location_street'=>$params['move_location_street'],
						'move_location_city'=>$params['move_location_city'],
						'move_location_state'=>$params['move_location_state'],
						'move_location_zipcode'=>$params['move_location_zipcode'],
						'move_payment_subtotal'=>$params['move_bud_subtotal'],
						'move_payment_promo_code'=>$params['move_bud_promocode'],
						'move_payment_credits'=>$params['move_bud_credits'],
						'move_payment_total'=>$params['move_bud_total'],
						
					);
			$this->db->insert('ln_moves', $move);
			$status = 1;
			$msg = 'Success!';
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	
	public function ln_laborers($params){
		$result = array();
		$ln_laborers = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$laborers = $this->db->get_where('ln_users', array('user_type'=>2));
			if($laborers->num_rows() > 0){
				$i = 0;
				$laborers_data = array();
				foreach($laborers as $laborer){
					if($i < $laborers->num_rows()){
						$laborer = $laborers->row_array($i);
						$laborer_data = array(
											'sp_id'=>element('sp_id', $this->db->get_where('ln_laborer_profiles', array('sp_user_id'=>$laborer['user_id']))->row_array()),
											'sp_user_id'=>$laborer['user_id'],
											'sp_name'=>$laborer['user_full_name'],
											'sp_reviews_count'=>element('sp_reviews_count', $this->db->get_where('ln_laborer_profiles', array('sp_user_id'=>$laborer['user_id']))->row_array()),
											'sp_fav_quote'=>(string)element('sp_tag_line', $this->db->get_where('ln_laborer_profiles', array('sp_user_id'=>$laborer['user_id']))->row_array()),
											'sp_profile_photo_url'=>$laborer['user_photo_url'],
											'sp_followers'=>explode(',', element('follow_ref_ids', $this->db->get_where('ln_follows', array('follow_user_id'=>$laborer['user_id'], 'follow_type'=>0))->row_array())),
											'sp_following'=>explode(',', element('follow_ref_ids', $this->db->get_where('ln_follows', array('follow_user_id'=>$laborer['user_id'], 'follow_type'=>1))->row_array())),
											'sp_about_me'=>(string)element('sp_description', $this->db->get_where('ln_laborer_profiles', array('sp_user_id'=>$laborer['user_id']))->row_array()),
											'sp_social_media'=>(string)element('sm_url', $this->db->get_where('ln_social_media', array('sm_laborer_id'=>$laborer['user_id']))->row_array()),
											'sp_location_latitude'=>$laborer['user_location_latitude'],
											'sp_location_latitude'=>$laborer['user_location_longitude'],
											'user_available'=>$laborer['user_available']
										);
						$laborer_photos = $this->db->get_where('ln_user_photos', array('up_user_id'=>$laborer['user_id'], 'up_is_main'=>0));
						$j = 0;
						$sp_photo_urls = array();
						foreach($laborer_photos as $laborer_photo){
							if($j < $laborer_photos->num_rows()){
								$laborer_photo = $laborer_photos->row_array($j);
								$sp_photo_urls[] = (string)element('up_photo_url', $laborer_photo);
								$j ++;
							}
						}
						$laborer_data['sp_photo_urls'] = $sp_photo_urls;
						$laborer_store_ids = explode(',', element('user_fav_store_ids', $this->db->get_where('ln_users', array('user_id'=>$laborer['user_id']))->row_array()));
						$j = 0;
						$sp_fav_stores_data = array();
						foreach($laborer_store_ids as $laborer_store_id){
							if($j < count($laborer_store_ids)){
								
								$user_store = $this->db->get_where('ln_stores', array('store_id'=>$laborer_store_id))->row_array();
								$sp_fav_store_data = array(
														'photo_url'=>(string)element('store_photo_url', $user_store),
														'store_name'=>(string)element('store_name', $user_store)
													);
								$sp_fav_stores_data[] = $sp_fav_store_data;
								$j ++;
							}	//	if($j < $laborer_stores->num_rows())
						}	//	foreach($laborer_stores as $laborer_store)
						$laborer_data['sp_fav_stores'] = $sp_fav_stores_data;
						$laborer_associations = $this->db->get_where('ln_laborer_associations', array('sa_laborer_id'=>$laborer['user_id']));
						$j = 0;
						$sp_associations = array();
						foreach($laborer_associations as $laborer_association){
							if($j < $laborer_associations->num_rows()){
								$laborer_association = $laborer_associations->row_array($j);
								$sp_associations[] = array(
													'ass_name'=>(string)element('sa_title', $laborer_association),
													'ass_start_year'=>(string)element('sa_start_year', $laborer_association),
													'ass_end_year'=>(string)element('sa_end_year', $laborer_association)
													);
								$j ++;
							}
						}
						$laborer_data['sp_associations'] = $sp_associations;
						$laborer_awards = $this->db->get_where('ln_laborer_awards', array('sw_laborer_id'=>$laborer['user_id']));
						$j = 0;
						$sp_awards = array();
						foreach($laborer_awards as $laborer_award){
							if($j < $laborer_awards->num_rows()){
								$laborer_award = $laborer_awards->row_array($j);
								$sp_awards[] = array(
												'awa_name'=>(string)element('sw_title', $laborer_award),
												'awa_start_year'=>(string)element('sw_start_year', $laborer_award),
												'awa_end_year'=>(string)element('sw_end_year', $laborer_award)
											);
								$j ++;
							}
						}
						$laborer_data['sp_awards'] = $sp_awards;
						$laborer_degrees = $this->db->get_where('ln_laborer_degrees', array('sd_laborer_id'=>$laborer['user_id']));
						$j = 0;
						$sp_degrees = array();
						foreach($laborer_degrees as $laborer_degree){
							if($j < $laborer_degrees->num_rows()){
								$laborer_degree = $laborer_degrees->row_array($j);
								$sp_degrees[] = array(
												'deg_name'=>(string)element('sd_title', $laborer_degree),
												'deg_start_year'=>(string)element('sd_start_year', $laborer_degree),
												'deg_end_year'=>(string)element('sd_end_year', $laborer_degree)
											);
								$j ++;
							}
						}
						$laborer_data['sp_degrees'] = $sp_degrees;
						$laborer_available_time_slots = $this->db->get_where('ln_service_time_slots', array('sts_laborer_id'=>$laborer['user_id']));
						$j = 0;
						$available_time_slots = array();
						foreach($laborer_available_time_slots as $laborer_available_time_slot){
							if($j < $laborer_available_time_slots->num_rows()){
								$laborer_available_time_slot = $laborer_available_time_slots->row_array($j);
								$available_time_slots[] = array(
															'available_date'=>element('sts_date', $laborer_available_time_slot),
															'available_date_period'=>element('sts_day_period', $laborer_available_time_slot),
															'available_start_time'=>element('sts_start_time', $laborer_available_time_slot),
															'available_end_time'=>element('sts_end_time', $laborer_available_time_slot)
														);
								$j ++;
							}
						}
						$laborer_data['available_time_slot'] = $available_time_slots;
						$laborer_services = $this->db->get_where('ln_laborer_available_services', array('sas_user_id'=>$laborer['user_id']));
						$j = 0;
						$sp_services = array();
						foreach($laborer_services as $laborer_service){
							if($j < $laborer_services->num_rows()){
								$laborer_service = $laborer_services->row_array($j);
								$sp_services[] = array(
													'service_id'=>element('sas_service_id', $laborer_service),
													'service_cost_rate'=>element('sas_cost_rate', $laborer_service),
													'service_duration_min'=>element('sas_duration_min', $laborer_service)
												);
								$j ++;
							}
						}
						$laborer_data['sp_services'] = $sp_services;
						$ln_laborers[] = $laborer_data;
						$i ++;
					}	//	if($i < $laborers->num_rows())
				}	//	foreach($laborers as $laborer)
				$result['ln_laborers'] = $ln_laborers;
				$status = 1;
				$msg = 'Success!';
			}	//	if($laborers->num_rows() > 0)
		}	//	if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	public function ln_driver($params){
		$result = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$driver = $this->db->get_where('ln_users', array('user_id'=>$params['driver_id']));
			if($driver->num_rows() > 0){
				$driver_profile = $this->db->get_where('ln_driver_profiles', array('cp_user_id'=>$params['driver_id']))->row_array();
				$driver_fashion_needs = $this->db->get_where('ln_driver_fashion_needs', array('cfn_user_id'=>$params['driver_id']))->row_array();
				$ln_driver = array(
								'driver_first_name'=>element('user_first_name', $driver->row_array()),
								'driver_last_name'=>element('user_last_name', $driver->row_array()),
								'driver_age'=>element('user_age', $driver->row_array()),
								'driver_gender'=>element('user_gender', $driver->row_array()),
								'driver_about'=>(string)element('user_about_content', $driver->row_array()),
								'driver_photo_url'=>(string)element('user_photo_url', $driver->row_array()),
								'driver_location'=>element('user_location_address', $driver->row_array()),
								'driver_body_shape_id'=>element('cp_cbs_id', $driver_profile),
								'driver_waist'=>(string)element('cp_waist', $driver_profile),
								'driver_height'=>(string)element('cp_height', $driver_profile),
								'driver_hair_color'=>(string)element('cp_hair_color', $driver_profile),
								'driver_eye_color'=>(string)element('cp_eye_color', $driver_profile),
								'driver_budget_min'=>element('cfn_budget_min', $driver_fashion_needs),
								'driver_budget_max'=>element('cfn_budget_max', $driver_fashion_needs),
								'driver_fav_store_ids'=>explode(',', element('cfn_fav_store_ids', $driver_fashion_needs))
							);
				$driver_occasions = array();
				//if((string)element('cfn_occasions', $driver_fashion_needs) != ''){
					$occasions = explode(',', element('cfn_occasions', $driver_fashion_needs));
					$i = 0;
					foreach($occasions as $occasion){
						if($i < count($occasions)){
							$occasion_ids = explode(':', $occasion);
							if((int)$occasion_ids[1] != 0){
								$driver_occasions[] = array(
														'co_name'=>(string)element('co_name', $this->db->get_where('ln_driver_occasions', array('co_id'=>$occasion_ids[0]))->row_array()),
														'co_photo_url'=>(string)element('co_photo_url', $this->db->get_where('ln_driver_occasions', array('co_id'=>$occasion_ids[0]))->row_array()),
														'cop_name'=>(string)element('cop_name', $this->db->get_where('ln_driver_occasion_periods', array('cop_id'=>$occasion_ids[1]))->row_array())
													);
							}else{
								$driver_occasions[] = array(
														'co_name'=>(string)element('co_name', $this->db->get_where('ln_driver_occasions', array('co_id'=>$occasion_ids[0]))->row_array()),
														'co_photo_url'=>(string)element('co_photo_url', $this->db->get_where('ln_driver_occasions', array('co_id'=>$occasion_ids[0]))->row_array()),
														'cop_name'=>''
													);
							}
							$i ++;
						}
					}
				//}
				$ln_driver['driver_occasions'] = $driver_occasions;
				$result['ln_driver'] = $ln_driver;
				$status = 1;
				$msg = 'Success!';
			}
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}

	public function laborer_create_profile($params){
		$resilt = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() == 0){
			$status = 2;
			$msg = 'This user not registered!';
		}else{
			$created_time = time();
			$image_path = config_item('path_media_laborers');				
			$image_name =$created_time  . '.jpg';
			$image_url = $image_path . $image_name;
			$binary = base64_decode($params['user_photo_url']);
			header('Content-Type: bitmap; charset=utf-8');
			$file = fopen($image_url, 'w');
				
			if($file) {
				fwrite($file, $binary);
			} else {
				$status = 3;
				$msg = 'File Upload failed';
			}
			fclose($file);
			$photo_url = $image_name;
			$user_data = array(
							'user_first_name'=>$params['user_first_name'],
							'user_last_name'=>$params['user_last_name'],
							'user_full_name'=>$params['user_first_name'] . ' ' . $params['user_last_name'],
							'user_photo_url'=>$photo_url,
							'user_gender'=>$params['user_gender'],
							'user_age'=>$params['user_age'],
							'user_location_address'=>$params['user_location'],
							'user_fav_store_ids'=>$params['user_fav_store_ids']
						);
			$this->db->update('ln_users', $user_data, array('user_id'=>$params['user_id']));
			$user_profile = array(
								'sp_user_id'=>$params['user_id'],
								'sp_tag_line'=>$params['user_tag_line'],
								'sp_description'=>$params['user_description'],
								'sp_travel_miles_min'=>$params['user_travel_miles_min'],
								'sp_travel_miles_max'=>$params['user_travel_miles_max'],
							);
			if($this->db->get_where('ln_laborer_profiles', array('sp_user_id'=>$params['user_id']))->num_rows() == 0){
				$this->db->insert('ln_laborer_profiles', $user_profile);
			}else{
				$this->db->update('ln_laborer_profiles', $user_profile, array('sp_user_id'=>$params['user_id']));
			}
			$user_social_media = array(
									'sm_laborer_id'=>$params['user_id'],
									'sm_url'=>$params['user_social_media']
								);
			$this->db->insert('ln_social_media', $user_social_media);
			$i = 0;
			$user_associations = array();
			foreach($params['user_associations'] as $user_association){
				if($i < count($params['user_associations'])){
					$user_association = $params['user_associations'][$i];
					$user_associations[$i] = array(
										'sa_laborer_id'=>$params['user_id'],
										'sa_title'=>$user_association[0],
										'sa_start_year'=>$user_association[1],
										'sa_end_year'=>$user_association[2]
									);
					$this->db->insert('ln_laborer_associations', $user_associations[$i]);
					$i ++;
				}	//	f($i < $params['user_awards']->num_rows())
			}	//	foreach($params['user_awards'] as $user_award)
			$i = 0;
			$user_awards = array();
			foreach($params['user_awards'] as $user_award){
				if($i < count($params['user_awards'])){
					$user_award = $params['user_awards'][$i];
					$user_awards[$i] = array(
										'sw_laborer_id'=>$params['user_id'],
										'sw_title'=>$user_award[0],
										'sw_start_year'=>$user_award[1],
										'sw_end_year'=>$user_award[2]
									);
					$this->db->insert('ln_laborer_awards', $user_awards[$i]);
					$i ++;
				}	//	f($i < $params['user_awards']->num_rows())
			}	//	foreach($params['user_awards'] as $user_award)
			$i = 0;
			$user_degrees = array();
			foreach($params['user_degrees'] as $user_degree){
				if($i < count($params['user_degrees'])){
					$user_degree = $params['user_degrees'][$i];
					$user_degrees[$i] = array(
										'sd_laborer_id'=>$params['user_id'],
										'sd_title'=>$user_degree[0],
										'sd_start_year'=>$user_degree[1],
										'sd_end_year'=>$user_degree[2]
									);
					$this->db->insert('ln_laborer_degrees', $user_degrees[$i]);
					$i ++;
				}	//	f($i < $params['user_degrees']->num_rows())
			}	//	foreach($params['user_degrees'] as $user_degree)
			$i = 0;
			$user_services = array();
			$sp_sm_ids = '';
			foreach($params['user_services'] as $user_service){
				if($i < count($params['user_services'])){
					$user_service = $params['user_services'][$i];
					$user_services[$i] = array(
											'sas_service_id'=>$user_service[0],
											'sas_user_id'=>$params['user_id'],
											'sas_cost_rate'=>$user_service[1],
											'sas_duration_min'=>$user_service[2]
										);
					$this->db->insert('ln_laborer_available_services', $user_services[$i]);
					$sp_sm_ids .=',' . $user_service[0];
					$i ++;
				}
			}
			$this->db->update('ln_laborer_profiles', array('sp_sm_ids'=>$sp_sm_ids), array('sp_user_id'=>$params['user_id']));
			$status = 1;
			$msg = 'Success!';
		}	//	if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	
	
	public function laborer_past_moves($params){
		$result = array();
		$status = 0;
		$msg = '';
		$query = $this->db->get_where('ln_users', array('user_id'=>$params['user_id']));
		if($query->num_rows() > 0){
			$moves = $this->db->get_where('ln_moves', array('move_user_id'=>$params['user_id'], 'move_status'=>2));
			if($moves->num_rows() > 0){
				$i = 0;
				$user_past_moves = array();
				foreach($moves as $move){
					if($i < $moves->num_rows()){
						$move = $moves->row_array($i);
						$user_past_move = array(
												'move_id'=>$move['move_id'],
												'move_name'=>$move['move_title'],
												'move_photo_url'=>$move['move_photo_url'],
												'move_driver_id'=>$move['move_driver_id'],
												'move_driver_name'=>element('user_full_name', $this->db->get_where('ln_users', array('user_id'=>$move['move_driver_id']))->row_array()),
												'move_driver_photo_url'=>(string)element('user_photo_url', $this->db->get_where('ln_users', array('user_id'=>$move['move_driver_id']))->row_array()),
												'move_rate'=>$move['move_rating'],
												'move_location_street'=>$move['move_location_street'],
												'move_location_city'=>$move['move_location_city'],
												'move_location_state'=>$move['move_location_state'],
												'move_location_zipcode'=>$move['move_location_zipcode'],
												'move_note'=>$move['move_notes_content'],
												//'move_created_date'=>$move['move_created_date'],
												//'move_expire_date'=>$move['move_expire_date'],
												'move_payment_subtotal'=>$move['move_payment_subtotal'],
												'move_payment_promo_code'=>$move['move_payment_promo_code'],
												'move_payment_credits'=>$move['move_payment_credits'],
												'move_payment_total'=>$move['move_payment_total']
											);
						$ps_start_datetime = explode(' ', element('move_created_date', $move));
						$ps_end_datetime = explode(' ', element('move_expire_date', $move));
						$user_past_move['move_date'] = $ps_start_datetime[0];
						$user_past_move['move_created_time'] = explode(':', $ps_start_datetime[1])[0] . ':' . explode(':', $ps_start_datetime[1])[1];
						$user_past_move['move_expire_time'] = explode(':', $ps_end_datetime[1])[0] . ':' . explode(':', $ps_end_datetime[1])[1];
						$user_past_moves[] = $user_past_move;
						$i ++;
					}	//	if($i < $moves->num_rows())
				}	//	foreach($moves as $move)
				$result['st_past_moves'] = $user_past_moves;
				$status = 1;
				$msg = 'Success!';
			}else{
				$status = 2;
				$msg = 'There is no past moves!';
			}	//	if($moves->num_rows() > 0)
		}	//	if($query->num_rows() > 0)
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	
	
	
	
//Utility Functions	
	
	public function get_user_auth_salt($password) {
    	return sha1(config_item('user_auth_salt') . md5($password));
    } 
	

    

    // Common Functions
	public function get_address($lat, $lng, $timeoutParam = 0) {
    	$timeout = (($timeoutParam == 0) ? config_item('http_timeout_default') : $timeoutParam);
    	$arContext['http']['timeout'] = $timeout;
    	$context = stream_context_create($arContext);
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng) . '&sensor=false';
        $json = @file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        if ($status == "OK")
            return $data->results[0]->formatted_address;
        else
            return false;
    }
    

    public function remove_characters($needle, $str) {
    	$s = smq_replace($needle, '', $str);
    	//$s = $this->clean($s);
    	return $s;
    }
	
	
	public function distance($lat1, $lon1, $lat2, $lon2) {
		$theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles;
    }
}
