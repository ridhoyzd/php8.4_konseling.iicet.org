<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Counselor extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
		$this->load->model(array('counselor_model'));
	}

	function index()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			$data['query']			= $this->counselor_model->fetch_data();
			$data['view']			= 'counselor/index';
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
			$data['parent_navlink']			= site_url('counselor');
			$data['form_action']			= site_url('counselor/save');
			$data['view']					= 'counselor/add';
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
			if($id != '' && $this->counselor_model->one_data($id)->num_rows() == 1)
			{
				$row = $this->counselor_model->one_data($id)->row();
				$data['counselor_id']				= $row->counselor_id;
				$data['counselor_name']			= $row->counselor_name;

				$data['parent_navlink']			= site_url('counselor');
				$data['form_action']			= site_url('counselor/update');
				$data['view']					= 'counselor/edit';
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
				$this->form_validation->set_rules('counselor_name', 'Name', 'required');

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
					$data['counselor_name']				= trim($this->input->post('counselor_name',true));
					$data['created']					= date('Y-m-d H:i:s');

					if($this->counselor_model->save_data($data))
					{
						$res['status']      = 1;
						$res['redirect']    = 1;
						$res['link']    	= ''.site_url('counselor').'';
						$res['type']        = 'success';
						$res['message']     = 'Counselor added.';
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

				$this->form_validation->set_rules('counselor_id', 'ID', 'required');
				$this->form_validation->set_rules('counselor_name', 'Name', 'required');
                
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
					$id 								= trim($this->input->post('counselor_id',true));
					$data['counselor_name']				= trim($this->input->post('counselor_name',true));
					
                    if($this->counselor_model->update_data($data, $id))
                    {
                        $res['status']      = 1;
                        $res['redirect']    = 1;
                        $res['link']    	= ''.site_url('counselor').'';
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

				$id = trim($this->input->post('id'));
				if($id != '' && $this->counselor_model->one_data($id)->num_rows() == 1)
				{
					// Delete
					if($this->counselor_model->delete_data($id))
					{
						$res['status']      = 1;
						$res['redirect']    = 1;
						$res['link']    	= ''.site_url('counselor').'';
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