<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->model(array('invoice_model','invoice_datatable'));
	}

	function index()
	{
		if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
			$data['parent_navlink']	= ''.site_url('invoice').'';
			$data['view']			= 'invoice/index';
			$this->load->view($data['view'], $data);
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	function datatable()
	{
		if ($this->session->userdata('logged_as_admin') == 1 AND $this->session->userdata('user_level') == 'admin')
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
				//$this->db->query('SET lc_time_names = "id_ID"');
				$list 	= $this->invoice_datatable->get_datatables();
				$data 	= array();
				$draw 	= isset($_POST['draw']) ? $_POST['draw'] : 0;
				$num 	= isset($_POST['start']) ? $_POST['start'] : 0;
				$no 	= $num+1;
				$i		= 1;
				foreach ($list as $post) 
				{
					$row = array();
					$row['no']					= $no++;
					$row['test_id'] 		  	= $post->test_id;
					$row['invoice_code'] 		= '<a href="'.site_url('invoice/detail/'.$post->test_id.'').'"><strong>'.$post->invoice_code.'</strong></a>';
					$row['invoice_status'] 		= $post->invoice_status;
					$row['tanggal_mengisi'] 	= indo_date(substr($post->tanggal_mengisi,0,10));
					$row['client_no'] 			= $post->client_no;
					$row['client_name'] 		= $post->client_name;
					$row['client_email'] 		= $post->client_email;
					$row['client_phone'] 		= $post->client_phone; 
					//$row['therapy_name'] 		= ($post->therapy_name != '') ? $post->therapy_name : '';
					$row['tagihan'] 		    = ($post->tagihan != '') ? number_format($post->tagihan,0,',','.') : '';
					$row['client_birthday'] 	= indo_date($post->client_birthday);
					$row['client_gender'] 	    = jenis_kelamin($post->client_gender);
					$actions = '<div class="d-grid"><a href="'.site_url('invoice/detail/'.$post->test_id.'').'" class="btn btn-sm btn-primary"><i class="far fa-file-alt"></i> Detail</a></div>';
					
					$row['actions'] 	= $actions;

					$i++;
					$data[] = $row;
				}
				$output = array('draw' => $draw,
								'recordsTotal' => $this->invoice_datatable->count_all(),
								'recordsFiltered' => $this->invoice_datatable->count_filtered(),
								'data' => $data);
				$result = json_encode($output, JSON_UNESCAPED_SLASHES);
				$clean_result = str_replace(array('\n','\t'), '', $result);
				echo $clean_result;
			}
		}
	}

	function detail($test_id=null)
	{
		if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
			if($test_id != '' && $this->invoice_model->one_test($test_id)->num_rows() == 1)
			{
				$row = $this->invoice_model->one_test($test_id)->row();
				$data['test_id']			= $row->test_id;
				$data['invoice_code']		= $row->invoice_code;
				$data['invoice_status']		= $row->invoice_status;
				$data['client_id']			= $row->client_id;
				$data['client_no']			= client_info($row->client_id, 'client_no');
				$data['client_name']		= client_info($row->client_id, 'client_name');
				$data['client_email']		= client_info($row->client_id, 'client_email');
				$data['client_phone']		= client_info($row->client_id, 'client_phone');
				$data['client_address']		= client_info($row->client_id, 'client_address');
				$data['tanggal_mengisi']	= indo_date(substr($row->tanggal_mengisi,0,10));
				$data['diskon']				= $row->diskon;
				$data['jumlah_diskon']		= $row->jumlah_diskon;
				$data['total_bayar']		= $row->total_bayar;
                $data['form_action']        = ''.site_url('invoice/setdiskon').'';
				/*
				$data['therapy_id']			= $row->therapy_id;
				$data['therapy_name']		= $row->therapy_name;
				$data['therapy_price']		= number_format($row->therapy_price,0,',','.');
				*/
				$data['therapies']          = $this->invoice_model->therapy_by_test($row->test_id);
				$data['parent_navlink']	    = ''.site_url('invoice').'';
				$data['view']				= 'invoice/detail';
				$this->load->view($data['view'], $data);
			}
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}
	function print($test_id=null)
	{
		if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
			if($test_id != '' && $this->invoice_model->one_test($test_id)->num_rows() == 1)
			{
				$row = $this->invoice_model->one_test($test_id)->row();
				$data['test_id']			= $row->test_id;
				$data['invoice_code']		= $row->invoice_code;
				$data['invoice_status']		= $row->invoice_status;
				$data['client_id']			= $row->client_id;
				$data['client_no']			= client_info($row->client_id, 'client_no');
				$data['client_name']		= client_info($row->client_id, 'client_name');
				$data['client_email']		= client_info($row->client_id, 'client_email');
				$data['client_phone']		= client_info($row->client_id, 'client_phone');
				$data['client_address']		= client_info($row->client_id, 'client_address');
				$data['tanggal_mengisi']	= indo_date(substr($row->tanggal_mengisi,0,10));
				$data['diskon']				= $row->diskon;
				$data['jumlah_diskon']		= $row->jumlah_diskon;
				$data['total_bayar']		= $row->total_bayar;
				$data['therapies']          = $this->invoice_model->therapy_by_test($row->test_id);
				$data['parent_navlink']	    = ''.site_url('invoice').'';
				$data['view']				= 'invoice/print_html';
				$this->load->view($data['view'], $data);
			}
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}
	function pdf($test_id=null)
	{
		if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
			if($test_id != '' && $this->invoice_model->one_test($test_id)->num_rows() == 1)
			{
				$row = $this->invoice_model->one_test($test_id)->row();
				$data['test_id']			= $row->test_id;
				$data['invoice_code']		= $row->invoice_code;
				$data['invoice_status']		= $row->invoice_status;
				$data['client_id']			= $row->client_id;
				$data['client_no']			= client_info($row->client_id, 'client_no');
				$data['client_name']		= client_info($row->client_id, 'client_name');
				$data['client_email']		= client_info($row->client_id, 'client_email');
				$data['client_phone']		= client_info($row->client_id, 'client_phone');
				$data['client_address']		= client_info($row->client_id, 'client_address');
				$data['tanggal_mengisi']	= indo_date(substr($row->tanggal_mengisi,0,10));
				$data['diskon']				= $row->diskon;
				$data['jumlah_diskon']		= $row->jumlah_diskon;
				$data['total_bayar']		= $row->total_bayar;
				$data['therapies']          = $this->invoice_model->therapy_by_test($row->test_id);
				$data['view']				= 'invoice/print_pdf';
				$this->load->view($data['view'], $data);
			}
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	function setdiskon()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
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

				$test_id    = trim($this->input->post('test_id'));
                $client_id  = trim($this->input->post('client_id'));

                if($this->invoice_model->test_check($test_id,$client_id)->num_rows() == 1)
                {
                    $this->form_validation->set_rules('test_id', 'ID', 'required');
                    $this->form_validation->set_rules('client_id', 'ClientID', 'required');
                    $this->form_validation->set_rules('diskon', 'diskon', 'integer');
                    $this->form_validation->set_rules('jumlah_diskon', 'Jumlah Diskon', 'integer');
                    $this->form_validation->set_rules('total_bayar', 'Total Bayar', 'required|integer');

                    if ($this->form_validation->run() == FALSE)
                    {
                        $res['status']      = 0;
                        $res['redirect']    = 0;
                        $res['type']        = 'warning';
                        $res['message']     = validation_errors();
                        echo json_encode($res);
                        return false;
                    }
                    else
                    {
						$data['diskon']				= trim($this->input->post('diskon'));
						$data['jumlah_diskon']		= trim($this->input->post('input_diskon'));
						$data['total_bayar']		= trim($this->input->post('total_bayar'));
						$data['total_invoice']		= trim($this->input->post('total_invoice'));
						if($this->invoice_model->test_update($data, $test_id))
						{
                            $res['status']      = 1;
                            $res['redirect']    = 1;
                            $res['link']        = ''.site_url('invoice/detail/'.$test_id.'').'';
                            $res['type']        = 'success';
                            $res['message']     = 'Diskon berhasil disimpan';
                            echo json_encode($res);
                            return false;
						}
					}
				}
			}
		}
	}

}