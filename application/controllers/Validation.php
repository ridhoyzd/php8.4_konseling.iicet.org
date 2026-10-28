<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validation extends CI_Controller {
	
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
		$this->load->model(array('validation_model'));
	}

    function index()
    {

    }

    function signature()
    {
        $code       = trim_input_value($this->input->get('code'));
        $test_id    = nuxencrypt($code,'show');

        if($test_id !='' && $this->validation_model->rekam_detail($test_id)->num_rows() == 1)
        {
            $row = $this->validation_model->rekam_detail($test_id)->row();
            $client_id                  = $row->client_id;
            $data['test_id']            = $row->test_id;
            $data['client_id']          = $row->client_id;
            $data['sesi']               = $row->sesi;
            $data['client_no']          = client_info($client_id,'client_no');
            $data['client_name']        = client_info($client_id,'client_name');
            $data['client_birthday']    = client_info($client_id,'client_birthday');
            $data['client_gender']      = client_info($client_id,'client_gender');
            $data['client_email']       = client_info($client_id,'client_email');
            $data['client_phone']       = client_info($client_id,'client_phone');
            $data['client_address']     = client_info($client_id,'client_address');
            $data['therapy_id']         = $row->therapy_id;
            $data['therapy_name']       = $row->therapy_name;
            $data['therapy_price']      = $row->therapy_price;
            $data['deskripsi_masalah']  = $row->deskripsi_masalah;
            $data['keterangan']         = $row->keterangan;
            $data['tindak_lanjut']   	= $row->tindak_lanjut;
            $data['konselor']   	    = $row->konselor;
            $data['counselor_id']       = $row->counselor_id;
            $data['counselor_name']     = $row->counselor_name;

            $data['stress']             = $row->stress;
            $data['anxiety']            = $row->anxiety;
            $data['depression']         = $row->depression;
            $data['tanggal_ditetapkan'] = $row->tanggal_ditetapkan;

            $data['view']               = 'validation/validation';
            $this->load->view($data['view'], $data);
        }
        else
        {
            $data['view']               = 'validation/validation_invalid';
            $this->load->view($data['view'], $data);
        }
    }
}