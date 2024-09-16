<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {

    public function __construct() {
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
		date_default_timezone_set("Asia/Calcutta");

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
	public function getting_started() {
		$data=$this->common_data();	 
		$this->load->view("customers/getting-started");
	}

    public function list() {
		$data=$this->common_data();	
		$data=$this->common_data();	 
		$data["menu_open"]="users";		
		$data["menu_active"]="users";
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"customer_id:All");
		 //var_dump($login_access_token_header);exit();
		$data['output']="";
		$output='';
		 $photo=$name=$user_role=$address2=$userid=$email=$address=$user_role=$country='';
		 $language=$description=$town=$state=$post_code='';
			    $makecall = $this->common->callAPI('GET', apiendpoint . "customers/customer_details", array(), $login_access_token_header);
			 //$category_id=$category_name=$category_image="";
			 $result = json_decode($makecall);
			$status=$result->status;  
			$company='';$payment_method='';
			if($status==true)
			{
				if($result->data->user_details!="")
				{
					$i=1;
					foreach($result->data->user_details as $info)
					{
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
					} 
				}
			}
			$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"table:mst_country");
			$makecall = $this->common->callAPI('GET', apiendpoint . "auth/common_details", array(), $login_access_token_header);
			//$category_id=$category_name=$category_image="";
			$result = json_decode($makecall);
		   $status=$result->status; 
		   $contry=''; 

		   if($status==true)
		   {
			   if($result->data->common_details!="")
			   {
				   $i=1;
				   foreach($result->data->common_details as $info)
				   {
						if($country==$info->sk_id){$selected="selected";}else{$selected="";}
                        $contry=$contry."<option value='$info->sk_id' $selected>$info->sk_name</option>";
                	}
				}
			}
			$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"table:mst_language");
			 $makecall = $this->common->callAPI('GET', apiendpoint . "auth/common_details", array(), $login_access_token_header);
			//$category_id=$category_name=$category_image="";
			$result = json_decode($makecall);
		   $status=$result->status; 
		   $language=''; 
		   if($status==true)
		   {
			   if($result->data->common_details!="")
			   {
				   $i=1;
				   foreach($result->data->common_details as $info)
				   {
						if($language==$info->sk_id){$selected="selected";}else{$selected="";}
                        $language=$language."<option value='$info->sk_id' $selected>$info->sk_name</option>";
                	}
				}
			}
			$data['contry']=$contry; 

			$contry='';
            $login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"user_status:All","user_id:All");

             $makecall = $this->common->callAPI('GET', apiendpoint . "users/users_details", array(), $login_access_token_header);
            //$category_id=$category_name=$category_image="";
            $contry='';
            $result = json_decode($makecall);
           $status=$result->status;
           $contry=$contry."<option value=''>Select a users</option>";
 
           if($status==true)
           {
               if($result->data->user_details!="")
               {
                   $i=1;
                   foreach($result->data->user_details as $info)
                   {
                       $project_name=$info->name;
                       $sk_project_id=$info->userid;
                       $email=$info->email;
                       $contry=$contry."<option value='$sk_project_id'>$email($project_name)</option>";
                   }
               }
           }
            $data['users']=$contry;
			$data['output']=$output;
			$data['language']=$language;
		$this->load->view("customers/list",$data);
	}

    public function view() {
		
		$data=$this->common_data();	 
		$this->load->view("customers/view");
	}

	public function add_customer() {
		$data=$this->common_data();
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
		$_POST = json_decode(file_get_contents("php://input"),true);
        $user_name=$_POST['user_name'];
		$poc=$_POST['poc'];
        $pocnumber=$_POST['pocnumber'];
        $customer_email=implode(',',$_POST['customer_email']);
		$customer_num=$_POST['customer_num'];
        $customer_address=$_POST['customer_address'];
        $country_name=$_POST['country_name'];
        $address1=$_POST['address1'];
		$address2=$_POST['address2'];
		$owner=$_POST['owner'];
        $pocnumber=$_POST['pocnumber'];
        $city=$_POST['city'];
		$state=$_POST['state'];
        $postcode=$_POST['postcode'];
        $country=$_POST['country'];
        $billing=$_POST['billing'];
		$data_array = array(
			'fullname'=>$user_name,
			'poc'=>$poc,
			'owner'=>$owner,
			'pocnumber'=>$pocnumber,
			'email'=>$customer_email,
			'address_1'=>$address1,
			'address_2'=>$address2,
			'city'=>$city,
			'state'=>$state,
			'postalcode'=>$postcode,
			'country'=>$country_name,
			'cust_country'=>$country,
			'address_full'=>$customer_address,
			'billing'=>$billing,
			'phonenumner'=>$customer_num
	);
		// echo apiendpoint;
		         $makecall = $this->common->callAPI('POST', apiendpoint.'customers/customer_details', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		
		$result = json_decode($makecall);
		$status=$result->status;
		$message=$result->message;
		 if($status==true)
		 {
			 if($result->data!=""){
				$email=$result->data->email;
				//$userid=JWT::encode($result->data->Accesstoken,pkey);
						
			 } 
			echo json_encode(array('message' =>$status));
		 }
		else
		{
            echo json_encode(array('message' =>$status));
		} 
	}


	function actinact(){
		$data=$this->common_data();
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
		 $id=$this->input->post('id');
		$status=$this->input->post('status');
							// echo apiendpoint;
			$data_array=array('status'=>$status,
							'id'=>$id,
						'table'=>'mst_customer');
							
			     $makecall = $this->common->callAPI('PUT', apiendpoint.'users/user_details_update', json_encode($data_array), $access_token_header);
		// echo apiendpoint;
		
		$result = json_decode($makecall);
		$status=$result->status;
		$message=$result->message;
		if($status==true)
		{
			if($result->data!=""){
				$email=$result->data->email;
				//$userid=JWT::encode($result->data->Accesstoken,pkey);
						
			} 
			echo json_encode(array('message' =>$status));
		}
		else
		{
			echo json_encode(array('message' =>$status));
		} 
	}
}
