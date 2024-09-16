<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->library('common');     
        $this->load->model('CommonModel','',TRUE);
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper("jwt_helper");
		$this->CI = & get_instance();
    }
    public function common_data()
    {
        $post_date = date('Y-m-d');
        $timestamp = date("Y-m-d H:i:s");
		$post_time = date("H:i:s");
        $data["post_date"] = $post_date;
		$data["post_time"] = $post_time;
        $data["timestamp"] = $timestamp;

		$data['access_token_header']=""; 

		$access_token_header = array('Content-Type:application/json','Accesstoken:'.globalAccessToken);
		$data['access_token_header']=$access_token_header;

       
        $data['admin_session_id']="1";
        $data['admin_session_email']= "";
        $data['admin_session_name'] = "";  $data['admin_session_token'] = ""; 
        
       if($this->session->userdata("admin_session_id")=="" || $this->session->userdata("admin_session_id")==null){
            redirect("/");
        }
	
        $data['admin_session_id']= $this->session->userdata("admin_session_id");
		$data['admin_session_token']= $this->session->userdata("admin_session_token");
		$data['admin_session_email'] = $this->session->userdata("admin_session_email");
		$data['admin_session_name'] = $this->session->userdata("admin_session_name");  
		$data['login_access_token_header']="";
		if($data['admin_session_token']!="")
		{
			$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
			$data['login_access_token_header']=$login_access_token_header;
		}
        return $data;
	}
	public function encryption($payload) {
    	return $encryptedId = JWT::encode($payload,pkey);
    }
    public function decryption($cipher) {
    	return $encryptedId = JWT::decode($cipher,pkey);
    }
	public function index() {
		$this->load->view("auth/sign-in");
	}

    public function logout() { 
		$this->session->sess_destroy();
         $this->session->unset_userdata('logged_in');		
         redirect(base_url());
    }
    public function new_password() {
        $this->load->view("auth/new-password");
    }
    public function forgot_password_link(){
        $data='';

		$access_token_header = array('Content-Type:application/json','Accesstoken:'.globalAccessToken);
		$_POST = json_decode(file_get_contents("php://input"),true);
        $email=$_POST['email'];
		$data_array = array('email'=>$email);
		// echo apiendpoint;
		  $makecall = $this->common->callAPI('GET', apiendpoint.'Auth/password_operations', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		
		$result = json_decode($makecall);
		$status=$result->status;
		$message=$result->message;
		 if($status==true)
		 {
			 if($result->data!=""){
				$email=$result->data->email;
				$userid=JWT::encode($result->data->Accesstoken,pkey);
						
			 } 
			echo json_encode(array('message' =>$status));
		 }
		else
		{
            echo json_encode(array('message' =>$status));
		} 
    }
    public function forgot_password() {
        $this->load->view("auth/forgot-password");
    }

    public function check_new_password() {

        $data='';

		$access_token_header = array('Content-Type:application/json','Accesstoken:'.globalAccessToken);
		$_POST = json_decode(file_get_contents("php://input"),true);
        $email=$_POST['email'];
        $confirm_password=$_POST['confirm_password'];
        $password=$_POST['password'];

		$data_array = array('email'=>$email,'new_password'=>$password,'old_password'=>$confirm_password);
		// echo apiendpoint;
		  $makecall = $this->common->callAPI('PUT', apiendpoint.'Auth/password_operations', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		
		$result = json_decode($makecall);
		$status=$result->status;
		$message=$result->message;
		 if($status==true)
		 {
			 if($result->data!=""){
				$email=$result->data->email;
				$userid=JWT::encode($result->data->Accesstoken,pkey);
						
			 } 
			echo json_encode(array('message' =>$status));
		 }
		else
		{
            echo json_encode(array('message' =>$status));
		} 
    }

    public function new_password_confirmation() {
        $this->load->view("auth/new-password-confirmation");
    }


	public function loaddata()
				{
					$data=$this->common_data();
					$user_status=$this->input->post('user_status');
					$table=$this->input->post('table');
					$fieldName=$this->input->post('fieldName');
					$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'table:'.$table,'user_status:'.$user_status,'field_name:'.$fieldName);
					  $makecall = $this->common->callAPI('GET', apiendpoint.'auth/commonMethod', json_encode(array()), $access_token_header);					
					$result = json_decode($makecall);
					$status=$result->status;
					$message=$result->message;
					$output='';
					 if($status==true)
					 {
						 if($result->data!=""){
							//$userid=JWT::encode($result->data->Accesstoken,pkey);
							$i=1;
							foreach($result->data->common_details as $info){
								if($table=='mst_users'){
							$name=$info->name;
							$userid=$info->userid;
							 $email=$info->email;
							$photo=$info->photo;
							$last_login=$info->last_login;
							$dayscount='';
							if($last_login!=''){
							$dayscount=$this->convert_seconds($last_login);
							}
						   $created_at=date('d M Y',strtotime($info->created_at));
						   $user_view=base_url().'users/view/'.base64_encode($userid);	
						   $red='';
						   if($info->user_status==1){
							$red='';
							$user_delete1="<a href='#' class='menu-link px-3'  onclick='updatestatus($userid,0)'>Inactive</a>";																																											
						   }else{
							$red='style="color:red"';
							$user_delete1="<a href='#' class='menu-link px-3'  onclick='updatestatus($userid,1)'>Active</a>";																						
						   }	
							$output=$output."<tr $red>
							<td>
							$i								
							</td>
							<td class='d-flex align-items-center'>
								$name
						</td>
						<td>$email</td>
						<td>
						<div class='badge badge-light fw-bold'>$dayscount</div>
						</td>
						<td>$created_at</td>
						<td class='text-end'>
								<a href='#' class='btn btn-sm btn-light btn-active-light-primary' data-kt-menu-trigger='click' data-kt-menu-placement='bottom-end'>Actions
								<!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
								<span class='svg-icon svg-icon-5 m-0'>
									<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
										<path d='M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z' fill='currentColor' />
									</svg>
								</span>
								<!--end::Svg Icon--></a>
								<!--begin::Menu-->
								<div class='menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4' data-kt-menu='true'>
									<!--begin::Menu item-->
									<div class='menu-item px-3'>
								<a href='$user_view'class='menu-link px-3'>Edit</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class='menu-item px-3'>
							$user_delete1
						</div>
								<!--end::Menu item-->
							</div>
							<!--end::Menu-->
						</td>					
					</tr>";
							$i++;
									
					}else if($table=='mst_customer'){
							$name=$info->name;
						$customer_id=$info->userid;
						 $email=$info->email;
						 $phonenumber=$info->phonenumber;
						 $cust_country=$info->cust_country;
						$payment_method='';
						
						if($info->customer_status==1){
							$red='';
							$user_delete1="<a href='#' class='menu-link px-3'  onclick='updatestatus($customer_id,0)'>Inactive</a>";																																											
						   }else{
							$red='style="color:red"';
							$user_delete1="<a href='#' class='menu-link px-3'  onclick='updatestatus($customer_id,1)'>Active</a>";																						
						   }	
					   $created_at=date('d M Y',strtotime($info->created_at));
						$output=$output."<tr $red>
						<!--begin::Checkbox-->
						<td>
							$i
						</td>
						
						<td>
							$name
						</td>
						
						<td>
							$email
						</td>
						<!--end::Email=-->
						<!--begin::Company=-->
						<td>$phonenumber</td>
						<!--end::Company=-->
						<!--begin::Payment method=-->
						<td>$cust_country
						</td>
						<!--end::Payment method=-->
						<!--begin::Date=-->
						<td>$created_at</td>
						<!--end::Date=-->
						<!--begin::Action=-->
						<td class='text-end'>
							<a href='#' class='btn btn-sm btn-light btn-active-light-primary' data-kt-menu-trigger='click' data-kt-menu-placement='bottom-end'>Actions
							<!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
							<span class='svg-icon svg-icon-5 m-0'>
								<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
									<path d='M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z' fill='currentColor' />
								</svg>
							</span>
							<!--end::Svg Icon--></a>
							<!--begin::Menu-->
							<div class='menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4' data-kt-menu='true'>
								<!--begin::Menu item-->
								
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class='menu-item px-3'>
							$user_delete1
						</div>
								<!--end::Menu item-->
							</div>
							<!--end::Menu-->
						</td>
						<!--end::Action=-->
					</tr>";
						$i++;
						 }else{
							$project_name=$info->project_name;
						$sk_project_id=$info->sk_project_id;
						 $code=$info->code;
						 $client=$info->client;

						$email=$info->email;
						$selected_format=$info->selected_format;
					   $country_name=$info->country_name;
					   $created_date=date('d M Y',strtotime($info->created_at));
					   $user_view=base_url().'projects/edit/'.base64_encode($sk_project_id);		
					   $user_delete=base_url().'projects/delete/'.base64_encode($sk_project_id);
					   if($info->project_status=='1'){
						$red='';
						$user_delete1="<a href='#' class='menu-link px-3'  onclick='updatestatus($sk_project_id,0)'>Inactive</a>";																																											
					   }else{
						$red='style="color:red"';
						$user_delete1="<a href='#' class='menu-link px-3'  onclick='updatestatus($sk_project_id,1)'>Active</a>";																						
					   }																							
						$output=$output."<tr $red>
						<!--begin::Checkbox-->
						<td>
							$i
						</td>
						<!--end::Checkbox-->
						<!--begin::User=-->
						<td>$client</td>
						<td>
							
								$project_name
						
						</td>
						<!--end::User=-->
						<!--begin::Role=-->
						<!--end::Role=-->
						<!--begin::Last login=-->
						<td>
							$email
						</td>
						<!--end::Last login=-->
						<!--begin::Two step=-->
						<td>$country_name</td>
						<!--end::Two step=-->
						<!--begin::Joined-->
						<td>$created_date</td>
						<td class=''>
						<a href='#' class='btn btn-sm btn-light btn-active-light-primary' data-kt-menu-trigger='click' data-kt-menu-placement='bottom-end'>Actions
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
						<span class='svg-icon svg-icon-5 m-0'>
							<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
								<path d='M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z' fill='currentColor' />
							</svg>
						</span>
						<!--end::Svg Icon--></a>
						<!--begin::Menu-->
						<div class='menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4' data-kt-menu='true'>
							<!--begin::Menu item-->
							
							<!--end::Menu item-->
							<!--begin::Menu item-->
							<div class='menu-item px-3'>
						$user_delete1
					</div>
							<!--end::Menu item-->
						</div>
						<!--end::Menu-->
					</td>
					<!--end::Action=-->						
					<!--begin::Joined-->
						<!--begin::Action=-->
						
						<!--end::Action=-->
					</tr>	
												
				</tr>";
						$i++;
						 } 
						}
						}
					}	
					echo $output;
				}




				function convert_seconds($secondsval) 
				{  
				   $strtoday=strtotime(date('Y-m-d H:i:s'));  
				   $secondsval=$strtoday-strtotime(date('Y-m-d H:i:s',strtotime($secondsval)));                                       
				  $dt1 = new DateTime("@0");
				  $dt2 = new DateTime("@$secondsval");

				 if($dt1->diff($dt2)->format('%a days, %h hours') == 0){
				   return $dt1->diff($dt2)->format('%h  hours ago');
				 }else{
				   return $dt1->diff($dt2)->format('%a days, %h hours ago');
				 }
				
				 }   
}
