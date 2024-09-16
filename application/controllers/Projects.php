<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }
	public function view() {
		$this->load->view("projects/project");
	}

    public function activity() {
		$this->load->view("projects/activity");
	}
}
