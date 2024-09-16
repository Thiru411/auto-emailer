<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

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

	public function manage_client() {
		$this->load->view("client/manage_client");
	}
    public function manage_alert() {
        $data=$this->common_data();	 
		$data["menu_open"]="alert";		
		$data["menu_active"]="alerts";
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"alert_id:All");
		 $data['alert_details']=$output="";  $data['projects']=  $data['customer']='';
		     $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_details", array(), $login_access_token_header);
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->alert_details!="")
				{
					$i=1;
					foreach($result->data->alert_details as $info)
					{
						$to=$info->to;
                        $alert_id=$info->sk_alert_d;
                        $login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"alert_id:".$alert_id);
                         
                     $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_list", array(), $login_access_token_header);
                    $result1 = json_decode($makecall);
                    $status=$result1->status;  
                    $status_mail='';
                    if($status==true)
                        {
                            if($result1->data->alert_details_list!="")
                            {
                                foreach($result1->data->alert_details_list as $info1)
                                {
                                        if($info1->alertstopdate=='' || $info1->alertstopdate==null){
                                            $status_mail='Inprogress';
                                        }else{
                                            $status_mail='Completed';
                                        }
                                }
                            }
                        }else{
                            $status_mail='Yet To Send';
                        }
						$cc=$info->cc;
						 $subject=$info->subject;
						$customer=$info->customer;
						$project=$info->project;
                        $project_name=$info->project_name;
					   $frequency=$info->frequency;
					   $start_date=date('d M Y',strtotime($info->start_date));
                       $end_date=date('d M Y',strtotime($info->end_date));
                       $alert_message=$info->alert_message;
                       $pdf_file=$info->pdf_file;
					   $created_at=date('d M Y',strtotime($info->created_at));
					   $user_view=base_url().'client/view/'.base64_encode($alert_id);		
                       $customer_id=$info->customer_name;
                        $email_id=$info->email;
                       $phone_number=$info->phonenumner;
                       $address=$info->address;
                       $country_name=$info->country_name;
						$output=$output."<tr>
                        <!--begin::Checkbox-->
                        <td>
                           $i
                        </td>
                        <td>$customer_id</td>
                        <!--end::User=-->
                        <!--begin::Role=-->
                        <td>$project_name</td>
                        <td>$start_date</td>
                        <!--end::Role=-->
                        <!--begin::Last login=-->
                        <td>
                            $status_mail
                        </td>
                        <!--end::Last login=-->
                        <!--begin::Two step=-->
                        <!--end::Two step=-->
                        <!--begin::Joined-->
                        <!--begin::Joined-->
                        <!--begin::Action=-->
                        <td class='text-end'>
                        <div class='menu-item px-3'>
                        <a href='$user_view' class='menu-link px-3'>View</a>
                    </div>
                            <!--end::Menu-->
                        </td>
                        <!--end::Action=-->
                    </tr>";
						$i++;
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
            $data['customer']=$contry;
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
                       $email=$info->email;
                       $contry=$contry."<option value='$sk_project_id'>$project_name($email)</option>";
                   }
               }
           }
            $data['users']=$contry;
			$data['alert_details']=$output;
		$this->load->view("client/manage_alert",$data);
	}

    public function add_alerts(){
		$data=$this->common_data();
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
		$_POST = json_decode(file_get_contents("php://input"),true);
        $to=implode(',',$_POST['to']);
		$cc=implode(',',$_POST['cc']);
        $subject=$_POST['subject'];
        $customer=$_POST['customer'];
		$projects=$_POST['projects'];
        $frequency=$_POST['frequency'];
        $start_date=$_POST['start_date'];
		$end_date=$_POST['end_date'];
        $alert_message=$_POST['alert_message'];
        $pdfattach=$_POST['pdfattach'];
		$data_array = array(
			'to'=>$to,
			'cc'=>$cc,
			'subject'=>$subject,
			'customer'=>$customer,
			'projects'=>$projects,
			'frequency'=>$frequency,
            'start_date'=>$start_date,
			'end_date'=>$end_date,
			'alert_message'=>$alert_message,
			'pdf_file'=>$pdfattach
	);
		// echo apiendpoint;
		         $makecall = $this->common->callAPI('POST', apiendpoint.'client/alert_details', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		
		$result = json_decode($makecall);
		$status=$result->status;
		$message=$result->message;
		 if($status==true)
		 {
			 if($result->data!=""){

						
			 } 
			echo json_encode(array('message' =>$status));
		 }
		else
		{
            echo json_encode(array('message' =>$status));
		} 
	}




    public function manage_alert_list_view() {
        $data=$this->common_data();	 
		$data["menu_open"]="manage_alert_list_view";		
		$data["menu_active"]="alerts_list";
        $from=date("Y-m-1");
        $to=date("Y-m-d");
        $output='';
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'from:'.$from,'to:'.$to);
		 $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_list_by_date_list", array(), $login_access_token_header);
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->alert_details_list!="")
				{
					$i=1;
					foreach($result->data->alert_details_list as $info)
					{
						$projectName=$info->projectName;
                        $alert_for_days=$info->alert_for_days;
                        $alertstopdate=$info->alertstopdate;
                        $for_how_many_days=$info->for_how_many_days;
                        $sk_alert_list_id=$info->sk_alert_list_id;
                         $client_name=$info->client_name;
                        $datestart=$info->datestart;
                        if($alertstopdate==null || $alertstopdate==''){
                            $stopalerts=base_url().'client/alert_stop/'.$sk_alert_list_id.'/0';
                            $sss='Stop Trigger Mail';
                        }else{
                            $stopalerts=base_url().'client/alert_start/'.$sk_alert_list_id.'/1';
                            $sss='Start Trigger Mail';
                        }
                        if(strtotime($datestart)>strtotime(date('Y-m-d'))){
                            $status="Yet To Send";
                           }else{
                            $status="Completed";
                           }
                           if($alertstopdate!=null){
                            $status="Stopped";
                           }
						$output=$output."<tr>
                        <!--begin::Checkbox-->
                        <td>
                           $i
                        </td>
                        <td>$client_name</td>
                        <td>
                       $projectName
                        </td>
                        <td>$datestart</td>
                        <td>$status</td>
                        <td>
                            $alertstopdate
                        </td>
                        
                    </tr>";
						$i++;
                        
					} 
				}
			}
			
			$data['alert_list_details']=$output;
		$this->load->view("client/manage_alert_list_v1",$data);
	}







    public function manage_alert_list() {
        $data=$this->common_data();	 
		$data["menu_open"]="manage_alert_list";		
		$data["menu_active"]="alerts_list";
        $from=date("Y-m-1");
        $to=date("Y-m-d");
        $output='';
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'from:'.$from,'to:'.$to);
		           $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_list_by_date_list_v1", array(), $login_access_token_header);
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->alert_details_list!="")
				{
					$i=1;
					foreach($result->data->alert_details_list as $info)
					{
						$projectName=$info->projectName;
                        $alert_for_days=$info->alert_for_days;
                        $alertstopdate=$info->alertstopdate;
                        $alertstopdate=$info->alertstopdate;
                        $sk_alert_list_id=$info->sk_alert_list_id;
                        $start_date=$info->start_date;
                        $client_name=$info->client_name;
                        $alert_id=$info->alert_id;
                        if($alertstopdate==null || $alertstopdate==''){
                            $stopalerts=base_url().'client/alert_stop/'.$sk_alert_list_id.'/0';
                            $sss='Stop Trigger Mail';
                        }else{
                            $stopalerts=base_url().'client/alert_start/'.$sk_alert_list_id.'/1';
                            $sss='Start Trigger Mail';
                        }
                        $user_view=base_url().'client/view/'.base64_encode($alert_id);		
						$output=$output."<tr>
                        <!--begin::Checkbox-->
                        <td>
                           $i
                        </td>
                      <td>$client_name</td>
                        <td>
                        $projectName
                        </td>
                        <td>
                        <a href='$user_view' class='menu-link px-3'>View</a>
                    </td>
                    <td>$start_date</td>
                        <td>
                            $alertstopdate
                        </td>
                        <td class='text-end'>
                                    <a href='$stopalerts' class='menu-link px-3' style='border: 1px solid;
                                    border-radius: 16px;
                                    background: #009ef7;
                                    color: white;
                                    padding: 6px;'>$sss</a>
                        </td>
                    </tr>";
						$i++;
					} 
				}
			}
			$data['alert_list_details']=$output;
		$this->load->view("client/manage_alert_list",$data);
	}




    function alert_stop(){
    $data=$this->common_data();
    $alert_list_id=$this->uri->segment(3);
    $alert_status=$this->uri->segment(4);
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
		$_POST = json_decode(file_get_contents("php://input"),true);
		$data_array = array(
			'alert_list_id'=>$alert_list_id,
            'alert_status'=>$alert_status
	    );
		$makecall = $this->common->callAPI('PUT', apiendpoint.'client/alert_list', json_encode($data_array), $access_token_header);
		$result = json_decode($makecall);
		$status=$result->status;
		$message=$result->message;
		redirect('client/manage_alert_list');
    }


    function alert_start(){
        $data=$this->common_data();
        $alert_list_id=$this->uri->segment(3);
        $alert_status=$this->uri->segment(4);
            $access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token']);
            $_POST = json_decode(file_get_contents("php://input"),true);
            $data_array = array(
                'alert_list_id'=>$alert_list_id,
                'alert_status'=>$alert_status
            );
            $makecall = $this->common->callAPI('PUT', apiendpoint.'client/alert_list', json_encode($data_array), $access_token_header);
            $result = json_decode($makecall);
            $status=$result->status;
            $message=$result->message;
            redirect('client/manage_alert_list');
        }



    public function manage_alert_list_stopped() {
        $data=$this->common_data();	 
		$data["menu_open"]="alert";		
		$data["menu_active"]="alerts";
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"alert_id:All","alert_status:0");
		 $data['alert_details']=$output="";  
		     $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_list", array(), $login_access_token_header);
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->alert_details_list!="")
				{
					$i=1;
					foreach($result->data->alert_details_list as $info)
					{
						$projectName=$info->projectName;
                        $alert_for_days=$info->alert_for_days;
                        $alertstopdate=$info->alertstopdate;
                        $sk_alert_list_id=$info->sk_alert_list_id;
                        $stopalerts=base_url().'client/alert_stop/'.$sk_alert_list_id;
						$output=$output."<tr>
                        <!--begin::Checkbox-->
                        <td>
                           $i
                        </td>
                      
                        <td class='d-flex align-items-center'>
                        <span>$projectName</span>
                        </td>
                        <td>$alert_for_days</td>
                        <td>
                            $alertstopdate
                        </td>
                        <td class='text-end'>
                                  Triggered Stopped
                        </td>
                    </tr>";
						$i++;
					} 
				}
			}
			
			$data['alert_list_details']=$output;
		$this->load->view("client/manage_alert_list_triggered",$data);
	}


    public function manage_alert_list_by_date() {
        $data=$this->common_data();	 
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $output='';

		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'from:'.$from,'to:'.$to);
		        $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_list_by_date_list", array(), $login_access_token_header);
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->alert_details_list!="")
				{
					$i=1;
					foreach($result->data->alert_details_list as $info)
					{
						$projectName=$info->projectName;
                        $alert_for_days=$info->alert_for_days;
                        $alertstopdate=$info->alertstopdate;
                        $sk_alert_list_id=$info->sk_alert_list_id;
                        if($alertstopdate==null || $alertstopdate==''){
                            $stopalerts=base_url().'client/alert_stop/'.$sk_alert_list_id.'/0';
                            $sss='Stop Trigger Mail';
                        }else{
                            $stopalerts=base_url().'client/alert_start/'.$sk_alert_list_id.'/1';
                            $sss='Start Trigger Mail';
                        }
						$output=$output."<tr>
                        <!--begin::Checkbox-->
                        <td>
                           $i
                        </td>
                      
                        <td class='d-flex align-items-center'>
                        <span>$projectName</span>
                        </td>
                        <td>$alert_for_days</td>
                        <td>
                            $alertstopdate
                        </td>
                        <td class='text-end'>
                                    <a href='$stopalerts' class='menu-link px-3' style='border: 1px solid;
                                    border-radius: 16px;
                                    background: #009ef7;
                                    color: white;
                                    padding: 6px;'>$sss</a>
                        </td>
                    </tr>";
						$i++;
					} 
				}
			}
			
            if($output!=''){
                echo $output;
            }else{
                
echo "<tr class='odd'><td valign='top' colspan='6' class='dataTables_empty'>No data available in table</td></tr>";
            }	}








    public function manage_alert_list_by_date1() {
        $data=$this->common_data();	 
         $from=date('Y-m-d',strtotime($this->input->post('from')));
        $to=date('Y-m-d',strtotime($this->input->post('to')));
        $output='';
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'from:'.$from,'to:'.$to);
		 $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_list_by_date_list", array(), $login_access_token_header);
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->alert_details_list!="")
				{
					$i=1;
					foreach($result->data->alert_details_list as $info)
					{
						$projectName=$info->projectName;
                        $alert_for_days=$info->alert_for_days;
                        $alertstopdate=$info->alertstopdate;
                        $for_how_many_days=$info->for_how_many_days;
                        $sk_alert_list_id=$info->sk_alert_list_id;
                         $client_name=$info->client_name;
                        $datestart=$info->datestart;
                        if($alertstopdate==null || $alertstopdate==''){
                            $stopalerts=base_url().'client/alert_stop/'.$sk_alert_list_id.'/0';
                            $sss='Stop Trigger Mail';
                        }else{
                            $stopalerts=base_url().'client/alert_start/'.$sk_alert_list_id.'/1';
                            $sss='Start Trigger Mail';
                        }
                        if(strtotime($datestart)>strtotime(date('Y-m-d'))){
                            $status="Yet To Send";
                           }else{
                            $status="Completed";
                           }
                           if($alertstopdate!=null){
                            $status="Stopped";
                           }
						$output=$output."<tr>
                        <!--begin::Checkbox-->
                        <td>
                           $i
                        </td>
                        <td>$client_name</td>
                        <td>
                       $projectName
                        </td>
                        <td>$datestart</td>
                        <td>$status</td>
                        <td>
                            $alertstopdate
                        </td>
                        
                    </tr>";
						$i++;
                        
					} 
				}
			}
			
            if($output!=''){
                echo $output;
            }else{
            echo "<tr class='odd'><td valign='top' colspan='6' class='dataTables_empty'>No data available in table</td></tr>";
            }
	}



    public function manage_alert_list_by_date_v1() {
        $data=$this->common_data();	 
        $from=date('Y-m-d',strtotime($this->input->post('from')));
        $to=date('Y-m-d',strtotime($this->input->post('to')));
        $output='';

		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'from:'.$from,'to:'.$to);
		           $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_list_by_date_list_v1", array(), $login_access_token_header);
			 $result = json_decode($makecall);
			$status=$result->status;  
			if($status==true)
			{
				if($result->data->alert_details_list!="")
				{
					$i=1;
					foreach($result->data->alert_details_list as $info)
					{
						$projectName=$info->projectName;
                        $alert_for_days=$info->alert_for_days;
                        $alertstopdate=$info->alertstopdate;
                        $alertstopdate=$info->alertstopdate;
                        $sk_alert_list_id=$info->sk_alert_list_id;
                        $start_date=$info->start_date;
                        $client_name=$info->client_name;
                        $alert_id=$info->alert_id;
                        if($alertstopdate==null || $alertstopdate==''){
                            $stopalerts=base_url().'client/alert_stop/'.$sk_alert_list_id.'/0';
                            $sss='Stop Trigger Mail';
                        }else{
                            $stopalerts=base_url().'client/alert_start/'.$sk_alert_list_id.'/1';
                            $sss='Start Trigger Mail';
                        }
                        $user_view=base_url().'client/view/'.base64_encode($alert_id);		
						$output=$output."<tr>
                        <!--begin::Checkbox-->
                        <td>
                           $i
                        </td>
                      <td>$client_name</td>
                        <td>
                        $projectName
                        </td>
                        <td>
                        <a href='$user_view' class='menu-link px-3'>View</a>
                    </td>
                    <td>$start_date</td>
                        <td>
                            $alertstopdate
                        </td>
                        <td class='text-end'>
                                    <a href='$stopalerts' class='menu-link px-3' style='border: 1px solid;
                                    border-radius: 16px;
                                    background: #009ef7;
                                    color: white;
                                    padding: 6px;'>$sss</a>
                        </td>
                    </tr>";
						$i++;
					} 
				}
			}
			
            if($output!=''){
                echo $output;
            }else{
                
echo "<tr class='odd'><td valign='top' colspan='6' class='dataTables_empty'>No data available in table</td></tr>";
            }	}

    public function view(){
        $data=$this->common_data();
         $id=$this->uri->segment(3);
		$login_access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],"alert_id:".base64_decode($id));
         $makecall = $this->common->callAPI('GET', apiendpoint . "client/alert_list_view", array(), $login_access_token_header);
        $result = json_decode($makecall);
       $status=$result->status;  
       $output='';
       if($status==true)
       {
           if($result->data->alert_details_list!="")
           {
               $i=1;
               foreach($result->data->alert_details_list as $info)
               {
                   $projectName=$info->projectName;
                   $alert_for_days=$info->alert_for_days;
                   $for_how_many_days=$info->for_how_many_days;
                   $alertstopdate=$info->alertstopdate;
                   $datestart=$info->datestart;
                   $sk_alert_list_id=$info->sk_alert_list_id;
                   if(strtotime($datestart)>strtotime(date('Y-m-d'))){
                    $status="Yet To Send";
                   }else{
                    $status="Completed";
                   }
                   if($alertstopdate!=null){
                    $status="Stopped";
                   }
                    $output=$output."<tr>
                   <!--begin::Checkbox-->
                   <td>
                      $i
                   </td>
                 
                   <td>
                   $projectName
                   </td>
                   <td>$for_how_many_days</td>
                   <td>$datestart</td>

                   <td>
                       $alertstopdate
                   </td>
                   <td>
                        $status
                   </td>
               </tr>";
                  $i++;
                   } 
           }
       }
       
       $data['alert_list_details']=$output;

        $this->load->view("client/manage_alert_View",$data);
    }
 
}
