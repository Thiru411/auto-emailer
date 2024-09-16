<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

	public function manage_employee() {
		$this->load->view("employee/manage_employee");
	}
 
}
