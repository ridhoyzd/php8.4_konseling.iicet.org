<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Therapy extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
		$this->load->model(array('therapy_model'));
	}

	function index()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			$data['query']			= $this->therapy_model->fetch_data();
			$data['view']			= 'therapy/index';
			$this->load->view($data['view'], $data);
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	function add()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			$data['parent_navlink']			= site_url('therapy');
			$data['form_action']			= site_url('therapy/save');
			$data['view']					= 'therapy/add';
			$this->load->view($data['view'], $data);
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	function edit($id = null)
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			if($id != '' && $this->therapy_model->one_data($id)->num_rows() == 1)
			{
				$row = $this->therapy_model->one_data($id)->row();
				$data['therapy_id']				= $row->therapy_id;
				$data['therapy_name']			= $row->therapy_name;
				$data['therapy_price']			= $row->therapy_price;

				$data['parent_navlink']			= site_url('therapy');
				$data['form_action']			= site_url('therapy/update');
				$data['view']					= 'therapy/edit';
				$this->load->view($data['view'], $data);
			}
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	function save()
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
				$this->form_validation->set_rules('therapy_name', 'Name', 'required');
				$this->form_validation->set_rules('therapy_price', 'Price', 'required|integer');

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
					$data['therapy_name']				= trim_input_value($this->input->post('therapy_name',true));
					$data['therapy_price']				= trim_input_value($this->input->post('therapy_price',true));
					$data['created']					= date('Y-m-d H:i:s');

					if($this->therapy_model->save_data($data))
					{
						$res['status']      = 1;
						$res['redirect']    = 1;
						$res['link']    	= ''.site_url('therapy').'';
						$res['type']        = 'success';
						$res['message']     = 'Type of therapy added.';
						echo json_encode($res);
						return false;
					}
				}
			}
		}
		else
		{
			$res['status']      = 0;
			$res['redirect']    = 0;
			$res['type']        = 'error';
			$res['message']     = 'Action not allowed !';
			echo json_encode($res);
			return false;
		}
	}

	function update()
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

				$this->form_validation->set_rules('therapy_id', 'ID', 'required');
				$this->form_validation->set_rules('therapy_name', 'Name', 'required');
				$this->form_validation->set_rules('therapy_price', 'Price', 'required|integer');
                
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
					$id 								= trim_input_value($this->input->post('therapy_id',true));
					$data['therapy_name']				= trim_input_value($this->input->post('therapy_name',true));
					$data['therapy_price']				= trim_input_value($this->input->post('therapy_price',true));
					
                    if($this->therapy_model->update_data($data, $id))
                    {
                        $res['status']      = 1;
                        $res['redirect']    = 1;
                        $res['link']    	= ''.site_url('therapy').'';
                        $res['type']        = 'success';
                        $res['message']     = 'Data updated';
                        echo json_encode($res);
                        return false;
                    }
                }
            }
            else
            {
                $res['status']      = 0;
                $res['redirect']    = 0;
                $res['type']        = 'error';
                $res['message']     = 'Action not allowed !';
                echo json_encode($res);
                return false;
            }
        }
        else
        {
            $res['status']      = 0;
            $res['redirect']    = 0;
            $res['type']        = 'error';
            $res['message']     = 'Access denied !';
            echo json_encode($res);
            return false;
        }
    }
	
	function delete()
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

				$id = trim_input_value($this->input->post('id'));
				if($id != '' && $this->therapy_model->one_data($id)->num_rows() == 1)
				{
					// Delete
					if($this->therapy_model->delete_data($id))
					{
						$res['status']      = 1;
						$res['redirect']    = 1;
						$res['link']    	= ''.site_url('therapy').'';
						$res['type']        = 'success';
						$res['message']     = 'Data deleted';
						echo json_encode($res);
						return false;
					}
					else
					{
						$res['status']      = 1;
						$res['redirect']    = 0;
						$res['type']        = 'error';
						$res['message']     = 'Sorry, Deleting data is not allowed at the moment!';
						echo json_encode($res);
						return false;
					}
				}
				else
				{
					$res['status']      = 0;
					$res['redirect']    = 0;
					$res['type']        = 'error';
					$res['message']     = 'Data not found';
					echo json_encode($res);
					return false;
				}
			}
		}
	}

}