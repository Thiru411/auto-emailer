<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

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

	public function permissions() {
		$data=$this->common_data();	 
		$data["menu_open"]="users";		
		$data["menu_active"]="users";
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"permission_id:All");
		 //var_dump($login_access_token_header);exit();
		 $data['permision_details']=$output="";
			      $makecall = $this->common->callAPI('GET', apiendpoint . "permissions/permission_details", array(), $login_access_token_header);
			 //$category_id=$category_name=$category_image="";
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->permision_details!="")
				{
					$i=1;
					foreach($result->data->permision_details as $info)
					{
						$name=$info->full_name;
						 $client=$info->customer_name;
						$projects=$info->project_name;
					  $output=$output."<tr>
														<!--begin::Name=-->
														<td>$name</td>
														<!--end::Name=-->
														<td>$client</td>
														<td>$projects</td>
														<td class='text-end'>
															<!--begin::Update-->
															<button class='btn btn-icon btn-active-light-primary w-30px h-30px me-3' data-bs-toggle='modal' data-bs-target='#kt_modal_update_permission'>
																<!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
																<span class='svg-icon svg-icon-3'>
																	<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
																		<path d='M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z' fill='currentColor' />
																		<path opacity='0.3' d='M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z' fill='currentColor' />
																	</svg>
																</span>
																<!--end::Svg Icon-->
															</button>
															<!--end::Update-->
															<!--begin::Delete-->
															<button class='btn btn-icon btn-active-light-primary w-30px h-30px' data-kt-permissions-table-filter='delete_row'>
																<!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
																<span class='svg-icon svg-icon-3'>
																	<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
																		<path d='M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z' fill='currentColor' />
																		<path opacity='0.5' d='M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z' fill='currentColor' />
																		<path opacity='0.5' d='M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z' fill='currentColor' />
																	</svg>
																</span>
																<!--end::Svg Icon-->
															</button>
															<!--end::Delete-->
														</td>
													</tr>";

					}
				}
			}
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
            $data['client']=$contry;
            $login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"project_status:1","project_id:All");

             $makecall = $this->common->callAPI('GET', apiendpoint . "projects/project_details", array(), $login_access_token_header);
			 //$category_id=$category_name=$category_image="";
             $contry='';
			 $result = json_decode($makecall);
			$status=$result->status;
            $contry=$contry."<option value=''>Select a Project</option>";
  
			if($status==true)
			{
				if($result->data->project_details!="")
				{
					$i=1;
					foreach($result->data->project_details as $info)
					{
						$project_name=$info->project_name;
						$sk_project_id=$info->sk_project_id;
                        $contry=$contry."<option value='$sk_project_id'>$project_name</option>";
                	}
				}
			}
            $data['projects']=$contry;

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
                       $contry=$contry."<option value='$sk_project_id'>$project_name</option>";
                   }
               }
           }
            $data['users']=$contry;
			$data['output']=$output;

		$this->load->view("user-management/permissions",$data);
	}

    public function list() {
		$data=$this->common_data();	 
		$data["menu_open"]="users";		
		$data["menu_active"]="users";
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"user_status:All","user_id:All");
		 //var_dump($login_access_token_header);exit();
		 $data['inventory_details']=$output="";
			  $makecall = $this->common->callAPI('GET', apiendpoint . "users/users_details", array(), $login_access_token_header);
			 //$category_id=$category_name=$category_image="";
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->user_details!="")
				{
					$i=1;
					foreach($result->data->user_details as $info)
					{
						$name=$info->name;
						$userid=$info->userid;
						 $email=$info->email;
						$photo=$info->photo;
						$last_login=$info->last_login;
						$dayscount='';
						if($last_login!=''){
						$dayscount=$this->convert_seconds($last_login);
						}
					   $user_role=$info->user_role;
					   $created_at=date('d M Y',strtotime($info->created_at));
					   $organization=$info->organization;	
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
			$data['user_details']=$output;

		$this->load->view("user-management/users/list",$data);
	}

    public function view() {
		$data=$this->common_data();	 
		$data["menu_open"]="users";		
		$data["menu_active"]="users";
		$encode_user_id=$this->uri->segment(3);
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"user_status:1","user_id:".base64_decode($encode_user_id));
		 //var_dump($login_access_token_header);exit();
		 $output='';
		 $photo=$name=$user_role=$address2=$userid=$email=$address=$user_role=$country='';
		 $language=$description=$town=$state=$post_code='';
			 $makecall = $this->common->callAPI('GET', apiendpoint . "users/users_details", array(), $login_access_token_header);
			 //$category_id=$category_name=$category_image="";
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->user_details!="")
				{
					$i=1;
					foreach($result->data->user_details as $info)
					{
						$name=$info->name;
						$userid=$info->userid;
						 $email=$info->email;
						$photo=$info->photo;
						$address=$info->address;
						$country=$info->country;
					   $user_role=$info->user_role;
					   if($info->last_login!=""){
						$last_login=date('d M Y, h:i a',strtotime($info->last_login));
					   }else{
						$last_login='';
					   }
					  // $last_login=date('d M Y, h:i a',strtotime($info->last_login));
					   $address2=$info->address2;
						$language=$info->language;
						 $description=$info->description;
						$town=$info->town;
						$state=$info->state;
						$post_code=$info->post_code;
					   $created_at=date('d M Y, h:i a',strtotime($info->created_at));
					   $organization=$info->organization;	
						$output=$output."<div class='fw-bold mt-5'>Account ID</div>
						<div class='text-gray-600'>ID-$userid</div>
						<div class='fw-bold mt-5'>Email</div>
						<div class='text-gray-600'>
							<a href='#' class='text-gray-600 text-hover-primary'>$email</a>
						</div>
						<div class='fw-bold mt-5'>Address</div>
						<div class='text-gray-600'>$address,
						<br />$address2
						<br />$country</div>
						<div class='fw-bold mt-5'>Language</div>
						<div class='text-gray-600'>$language</div>
						<div class='fw-bold mt-5'>Last Login</div>
						<div class='text-gray-600'>$last_login</div>";
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
			$data['userid']=$userid;
			$data['email']=$email;
			$data['photo']=$photo;
			$data['address']=$address;
			$data['country']=$country;
			$data['user_role']=$user_role;
			$data['address2']=$address2;
			$data['language']=$language;
			$data['description']=$description;
			$data['town']=$town;
			$data['state']=$state;
			$data['post_code']=$post_code;
			$data['created_at']=date('d M Y, h:i a',strtotime($created_at));
			$data['organization']=$organization;				
			$data['user_des']=$output;
			$data['photo']=$photo;
			$data['name']=$name;
			$data['user_role']=$user_role;
			$data['contry']=$contry;
		$this->load->view("user-management/users/view",$data);
	}

    public function list_roles() {
		$this->load->view("user-management/roles/list");
	}

    public function view_roles() {
		$this->load->view("user-management/roles/view");
	}


	public function add_user(){
		$data=$this->common_data();
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
		$_POST = json_decode(file_get_contents("php://input"),true);
        $user_name=$_POST['user_name'];
		$avatar=$_POST['avatar'];
        $user_email=$_POST['user_email'];
        $user_mobile=$_POST['user_mobile'];
		$user_address=$_POST['user_address'];
        $user_country=$_POST['user_country'];
        $role=$_POST['role'];
		
		//var_dump($explode_data_sub);
		$data_array = array(
			'fullname'=>$user_name,
			'avatar'=>'',
			'phonenumber'=>$user_mobile,
			'address'=>$user_address,
			'email'=>$user_email,
			'country'=>$user_country,
			'role'=>$role
	);
		// echo apiendpoint;
		     $makecall = $this->common->callAPI('POST', apiendpoint.'users/users_details', json_encode($data_array), $access_token_header);
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


	public function update_user(){
		$data=$this->common_data();
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
		$_POST = json_decode(file_get_contents("php://input"),true);
        $user_name=$_POST['user_name'];
		$avatar=$_POST['avatar'];
        $user_email=$_POST['user_email'];
		$user_address=$_POST['user_address'];
        $user_country=$_POST['user_country'];
		$description=$_POST['user_description'];
		$language=$_POST['user_language'];
        $user_town=$_POST['user_town'];
        $user_state=$_POST['user_state'];
		$user_post=$_POST['user_post'];
        $user_address2=$_POST['user_address2'];
        $id_decode=$_POST['user_id'];
		$var='url("';
		$explode_data=explode($var,$avatar);
		$explode_data_sub=explode('")',$explode_data[1]);
		$data_array = array(
			'user_id'=>$id_decode,
			'fullname'=>$user_name,
			'avatar'=>$explode_data_sub[0],
			'address'=>$user_address,
			'email'=>$user_email,
			'country'=>$user_country,
			'address2'=>$user_address2,
			'description'=>$description,
			'language'=>$language,
			'town'=>$user_town,
			'state'=>$user_state,
			'post_code'=>$user_post
	);
		// echo apiendpoint;
		       $makecall = $this->common->callAPI('PUT', apiendpoint.'users/users_details', json_encode($data_array), $access_token_header);
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
				  
				  


				  public function add_permissions(){
					$data=$this->common_data();
					$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
					$_POST = json_decode(file_get_contents("php://input"),true);
					$user_name=$_POST['user_id'];
					$customer_id=$_POST['customer_id'];
					$project_id=$_POST['project_id'];
				
					
					$customer_id=implode(',',$customer_id);
					$project_id=implode(',',$project_id);

					$data_array = array(
						'user_id'=>$user_name,
						'clients'=>$customer_id,
						'projects'=>$project_id
				);
					// echo apiendpoint;
						    $makecall = $this->common->callAPI('POST', apiendpoint.'permissions/permission_details', json_encode($data_array), $access_token_header);
					 // echo apiendpoint;
					
					$result = json_decode($makecall);
					$status=$result->status;
					$message=$result->message;
					 if($status==true)
					 {
						 if($result->data!=""){
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
									'table'=>'mst_users');	
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