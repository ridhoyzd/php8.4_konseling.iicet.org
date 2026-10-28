<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dass_process extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
        $this->load->library('pagination');
		$this->load->model(array('dass_process_model'));
	}

	function index()
	{
		if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
			$page_number 					= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $config['base_url'] 			= base_url('dass_process/page');
            $config['first_url']			= base_url('dass_process');
            $config['total_rows'] 			= $this->dass_process_model->all_dass_public(false,false,true)->num_rows();
            $config['use_page_numbers'] 	= TRUE; 
            $config['num_links'] 			= 2;
            $config['display_pages'] 		= TRUE;
            $config['per_page'] 			= 200;
            $config['full_tag_open'] 		= '<ul class="pagination">';
            $config['full_tag_close'] 		= '</ul>';
            $config['first_link'] 			= '&laquo; First';
            $config['next_link'] 			= 'Next &rsaquo; ';
            $config['prev_link'] 			= '&lsaquo; Prev';
            $config['last_link'] 			= 'Last &raquo;';
            $config['first_tag_open'] 		= '<li class="page-item">';
            $config['first_tag_close'] 		= '</li>';
            $config['last_tag_open'] 		= '<li class="page-item">';
            $config['last_tag_close'] 		= '</li>';
            $config['cur_tag_open'] 		= '<li class="page-item active"><a href="javascript:void(0);" class="page-link">';
            $config['cur_tag_close'] 		= '<span class="sr-only">(current)</span></a></li>';			
            $config['next_tag_open'] 		= '<li class="page-item">';
            $config['next_tag_close'] 		= '</li>';			
            $config['prev_tag_open'] 		= '<li class="page-item">';
            $config['prev_tag_close'] 		= '</li>';
            $config['num_tag_open'] 		= '<li class="page-item">';
            $config['num_tag_close'] 		= '</li>';
            $config['attributes'] 			= array('class' => 'page-link');
            $this->pagination->initialize($config);
            $offset 						= ($page_number  == NULL OR $page_number < 0 ) ? 0 : ($page_number * $config['per_page']) - $config['per_page'];
            $data['pagination']				= $this->pagination->create_links();
            $data['query']					= $this->dass_process_model->all_dass_public($config['per_page'], $offset); // with paging
            //$data['query']					= $this->dass_process_model->all_dass_public(false,false,true); // without paging
            $data['no']						= $offset;
            $data['parent_navlink']         = ''.site_url('dass_process').'';
            $data['view']			        = 'dass_process/index';
            $this->load->view($data['view'], $data);
		}
		else
		{
            redirect(''.site_url('login').'', 'refresh');
		}
	}
    
    function page()
    {
        $this->index();
    }

    function process()
    {
        if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
            if ($this->input->is_ajax_request() == true)
            {
                if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) 
                {
                    header('Allow: POST');
                    header('HTTP/1.1 405 Method Not Allowed');
                    header('Content-Type: text/plain');
                    exit('ERROR: Method Not Allowed !');
                }

                $client_id      = trim($this->input->post('client'));
                $test_id        = trim($this->input->post('test'));
                $input          = array();
                $check_data     = $this->dass_process_model->check_data($client_id,$test_id);
                $kategori_soal  = $this->dass_process_model->kategori_soal();
                if($check_data->num_rows() == 1)
                {
                    // kategori
                    if($kategori_soal->num_rows() > 0)
                    {
                        foreach ($kategori_soal->result() as $kategori)
                        {
                            if($this->dass_process_model->dass_by_group($test_id, $kategori->soal_kategori_id)->num_rows() > 0)
                            {
                                $jumlah = 0; 
                                foreach ($this->dass_process_model->dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
                                {
                                    $jumlah = $jumlah + $nilai->jawaban;
                                }
                                
                                if($kategori->soal_kategori_id == 1) 
                                {
                                    // Stress / Tekanan
                                    $data['point_stress']   = $jumlah;
                                    $data['stress']         = dass_result($kategori->soal_kategori_id, $jumlah);
                                    $this->dass_process_model->update_test_public($data,$client_id,$test_id);
                                }
                                else if($kategori->soal_kategori_id == 2)
                                {
                                    // Anxiety / Kebimbangan 
                                    $data['point_anxiety']   = $jumlah;
                                    $data['anxiety']         = dass_result($kategori->soal_kategori_id, $jumlah);
                                    $this->dass_process_model->update_test_public($data,$client_id,$test_id);
                                }
                                else if($kategori->soal_kategori_id == 3)
                                {
                                    // Depression / Kemurungan
                                    $data['point_depression']   = $jumlah;
                                    $data['depression']         = dass_result($kategori->soal_kategori_id, $jumlah);
                                    $this->dass_process_model->update_test_public($data,$client_id,$test_id);
                                }
                            }
                        }
                    }

                    $res['status']  = 1;
                    $res['message'] = 'Updated';
                    echo json_encode($res);
                }
                else
                {

                    $res['status']  = 0;
                    $res['message'] = 'not found';
                    echo json_encode($res);
                }

            }
        }
    }
}