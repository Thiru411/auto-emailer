<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organization extends CI_Controller {

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

	public function manage_organizations() {
		$this->load->view("organizations/manage_organizations");
	} 

	public function manage_projects() {
		$data=$this->common_data();	 
		$data["menu_open"]="projects";		
		$data["menu_active"]="projects";
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"project_status:1","project_id:All");
		 //var_dump($login_access_token_header);exit();
		 $data['project_details']=$output="";
			    $makecall = $this->common->callAPI('GET', apiendpoint . "projects/project_details", array(), $login_access_token_header);
			 //$category_id=$category_name=$category_image="";
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->project_details!="")
				{
					$i=1;
					foreach($result->data->project_details as $info)
					{
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
			$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"table:mst_country");
			 $makecall = $this->common->callAPI('GET', apiendpoint . "auth/common_details", array(), $login_access_token_header);
			//$category_id=$category_name=$category_image="";
			$result = json_decode($makecall);
		   $status=$result->status; 
		   $contry=''; 
		   $contry=$contry."<option value=''>Select a County</option>";
		   if($status==true)
		   {
			   if($result->data->common_details!="")
			   {
				   $i=1;
				   foreach($result->data->common_details as $info)
				   {
						
                        $contry=$contry."<option value='$info->sk_id'>$info->sk_name</option>";
                	}
				}
			}
			$data['contry']=$contry;
			$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"customer_id:All");
			  $makecall = $this->common->callAPI('GET', apiendpoint . "customers/customer_details", array(), $login_access_token_header);
			//$category_id=$category_name=$category_image="";
			$result = json_decode($makecall);
		   $status=$result->status; 
		   $contry=''; 
		   $contry=$contry."<option value=''>Select a Customer</option>";
		   if($status==true)
		   {
			   if($result->data->user_details!="")
			   {
				   $i=1;
				   foreach($result->data->user_details as $info)
				   {
						
                        $contry=$contry."<option value='$info->userid'>$info->name</option>";
                	}
				}
			}
            $data['customer']=$contry;
			$contry='';
            $login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"user_status:All","user_id:All");

             $makecall = $this->common->callAPI('GET', apiendpoint . "users/users_details", array(), $login_access_token_header);
            //$category_id=$category_name=$category_image="";
            $contry='';
            $result = json_decode($makecall);
           $status=$result->status;
           $contry=$contry."<option value=''>Select Project Owners</option>";
 
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
                       $contry=$contry."<option value='$sk_project_id'>$project_name</option>";
                   }
               }
           }
            $data['users']=$contry;
			$data['project_details']=$output;
		$this->load->view("projects/manage_projects",$data);
	} 



	public function add_projects(){
		$data=$this->common_data();
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
		$_POST = json_decode(file_get_contents("php://input"),true);
        $project_name=$_POST['project_name'];
		$code=$_POST['code'];
        $project_email=implode(',',$_POST['project_email']);
        $format=$_POST['format'];
		$country=$_POST['country'];
        $project_contact_num=$_POST['project_contact_num'];
		$pocontact_num=$_POST['pocontact_num'];
		$owner=$_POST['owner'];
		$client=$_POST['client'];
		$pname=$_POST['pname'];
        $project_contac_alternative=$_POST['project_contac_alternative'];

		//var_dump($explode_data_sub);
		$data_array = array(
			'project_name'=>$project_name,
			'code'=>$code,
			'owner'=>$owner,
			'pname'=>$pname,
			'project_email'=>$project_email,
			'format'=>$format,
			'country'=>$country,
			'client'=>$client,
			'project_contact_num'=>$project_contact_num,
			'project_contac_alternative'=>$project_contac_alternative,
			'pocontact_num'=>$pocontact_num
	);
		// echo apiendpoint;
		     $makecall = $this->common->callAPI('POST', apiendpoint.'projects/project_details', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		
		$result = json_decode($makecall);
		$status=$result->status;
		$message=$result->message;
		 if($status==true)
		 {
			 if($result->data!=""){
				//$email=$result->data->email;
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
						'table'=>'mst_projects');
							
			    echo  $makecall = $this->common->callAPI('PUT', apiendpoint.'users/user_details_update', json_encode($data_array), $access_token_header);
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
