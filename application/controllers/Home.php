<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->model('home_model');
	}

	function index()
	{
		if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
			$data['view']			= 'home/index';
			$this->load->view($data['view'], $data);
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

}