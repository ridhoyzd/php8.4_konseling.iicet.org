<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_public extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
		$this->load->model(array('group_model'));
	}

	function index()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            $data['query']          = $this->group_model->fetch_group();
            $data['form_action']    = ''.site_url('group_public/save').'';
			$data['view']			= 'group_public/index';
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
            $data['form_action']        = ''.site_url('group_public/save').'';
            $data['view']				= 'group_public/add';
            $this->load->view($data['view'], $data);
        }
    }

    function edit()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{ 
            $id = trim($this->input->post('id'));
            if($id != '' && $this->group_model->one_group($id)->num_rows() == 1)
            {
                $row = $this->group_model->one_group($id)->row();
                $data['group_id']           = $row->group_id;
                $data['group_name']         = $row->group_name;
                $data['group_description']  = $row->group_description;
                $data['group_active']       = $row->group_active;
                $data['form_action']        = ''.site_url('group_public/update').'';
                $data['view']				= 'group_public/edit';
                $this->load->view($data['view'], $data);
            }
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
				$this->form_validation->set_rules('name', 'Group Name', 'required|is_unique[group_public.group_name]',
												   array('is_unique' => '&quot;'.$this->input->post('name').'&quot; already used.'));
                $this->form_validation->set_rules('active', 'Group Status', 'required');
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
					$data['group_name']				= trim($this->input->post('name',true));
					$data['group_description']		= trim($this->input->post('desc',true));
                    $data['group_active']		    = trim($this->input->post('active',true));
					$data['group_created']		    = date('Y-m-d H:i:s');
					if($this->group_model->save_group($data))
					{ 
						$res['status']      = 1;
						$res['redirect']    = 1;
						$res['link']    	= ''.site_url('group_public').'';
						$res['type']        = 'success';
						$res['message']     = 'New group created.';
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
                
                $id = trim($this->input->post('id'));
                if($id != '' && $this->group_model->one_group($id)->num_rows() == 1)
                {
                    $this->form_validation->set_rules('name', 'Group Name', 'required|callback_name_validation');
                    $this->form_validation->set_rules('active', 'Group Status', 'required');
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
                        $data['group_name']				= trim($this->input->post('name',true));
                        $data['group_description']		= trim($this->input->post('desc',true));
                        $data['group_active']		    = trim($this->input->post('active',true));
                        if($this->group_model->update_group($data,$id))
                        { 
                            $res['status']      = 1;
                            $res['redirect']    = 1;
                            $res['link']    	= ''.site_url('group_public').'';
                            $res['type']        = 'success';
                            $res['message']     = 'Group updated.';
                            echo json_encode($res);
                            return false;
                        }
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
    
    function name_validation($name)
    {
      $id = $this->input->post('id');
        if ($name != '' && $this->group_model->name_validation($id, $name)->num_rows() > 0) 
        {
            $this->form_validation->set_message('name_validation', 'The name <b>'.$name.'</b> already used.');
            return false;
        } 
        else 
        {
            return true;
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
                if($id != '' && $this->group_model->one_group($id)->num_rows() == 1)
                {
                    // update client group


                    // remove group
                    if ($this->group_model->delete_group($id))
                    {
                        $res['status']      = 1;
                        $res['redirect']    = 1;
                        $res['link']        = ''.site_url('group_public').'';
                        $res['type']        = 'success';
                        $res['message']     = 'Group deleted';
                        echo json_encode($res);
                        return false;
                    }
                }
            }
        }
    }
}