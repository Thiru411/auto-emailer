<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
      
 parent::__construct();   
		$this->load->library('common');     
        $this->load->model('CommonModel','',TRUE);
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper("jwt_helper");
		$this->CI = & get_instance();

    }
  
/*************** Common Methods ***********************/
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
        $data['profile_pic'] = $this->session->userdata("profile_pic");  
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
		$data=$this->common_data();
        $data["menu_open"]="";		
        $data["menu_active"]="dashboard";
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'user_id:All','user_status:All');

		$data_array=array();
		// echo apiendpoint;
		  $makecall = $this->common->callAPI('GET', apiendpoint.'users/users_details', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		
		$result = json_decode($makecall);
		$data['users_count']=count($result->data->user_details);

		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'customer_id:All','customer_status:All');

		$data_array=array();
		// echo apiendpoint;
		      $makecall = $this->common->callAPI('GET', apiendpoint.'customers/customer_details', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		 $data['customers_count']=0;
		$result = json_decode($makecall);
if($result->status){
		$data['customers_count']=count($result->data->user_details);
}

		$access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'project_id:All');

		$data_array=array();
		// echo apiendpoint;
		      $makecall = $this->common->callAPI('GET', apiendpoint.'projects/project_details', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		 $result = json_decode($makecall);
		 $data['projects_count']=0;
		 if($result->status){

		$j=0;
		 $data['projects_count']=count($result->data->project_details);
		 if($result->status){
			foreach($result->data->project_details as $info){
				if(date('Y-m-d',strtotime($info->created_at))==date('Y-m-d')){
					$j++;
				}
			}
		 }
		}
		 $access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'alert_id:All');

		$data_array=array();
		// echo apiendpoint;
		      $makecall = $this->common->callAPI('GET', apiendpoint.'client/alert_details', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		 $result = json_decode($makecall);
		 $access_token_header = array('Content-Type:application/json','Accesstoken:'.$data['admin_session_token'],'alert_id:All');

		 $data_array=array();
		 // echo apiendpoint;
			   $makecall = $this->common->callAPI('GET', apiendpoint.'client/alert_details', json_encode($data_array), $access_token_header);
		  // echo apiendpoint;
		  $result = json_decode($makecall);
		$j=0;
		if($result->status){
		 $data['alert_list_count']=count($result->data->alert_details);
		}else{
			$data['alert_list_count']=0;
		}
		 if($result->status){
			foreach($result->data->alert_details as $info){
				if(date('Y-m-d',strtotime($info->created_at))==date('Y-m-d')){
					$j++;
				}
			}
		 }
$data['today']=$j;
		$this->load->view("dashboard/index",$data);
	}
    public function login_check() {
		$access_token_header = array('Content-Type:application/json','Accesstoken:'.globalAccessToken);
		$_POST = json_decode(file_get_contents("php://input"),true);
        $email=$_POST['email'];
        $password=$_POST['password'];
		$data_array = array('email'=>$email,'password'=>$password);
		// echo apiendpoint;
		   $makecall = $this->common->callAPI('POST', apiendpoint.'Auth/signin', json_encode($data_array), $access_token_header);
		 // echo apiendpoint;
		
		$result = json_decode($makecall);
		$status=$result->status;
		$message=$result->message;
		 if($status==true)
		 {
			 if($result->data!=""){
				$name=$result->data->name;
				$email=$result->data->email;
				$userid=$result->data->userid;
				$session_token=$result->data->Accesstoken;
                $profile_pic=$result->data->profile_pic;
			 
					$sess_array = array(
						'sk_employee_id' =>$userid,
						'fullname' => $name,
						'email' => $email,
						'session_token'=>$session_token
						);
						$this->session->set_userdata("admin_session_id", $userid);
                        $this->session->set_userdata("profile_pic", $profile_pic);
						$this->session->set_userdata("admin_session_name", $name);
						$this->session->set_userdata("admin_session_email", $email);
						$this->session->set_userdata("admin_session_token", $session_token);
						$this->session->set_userdata('logged_in', $sess_array);
								
						} 
						echo json_encode(array('message' =>$status));
		}
		else
		{
            echo json_encode(array('message' =>$status));
		} 
	}
    
}
