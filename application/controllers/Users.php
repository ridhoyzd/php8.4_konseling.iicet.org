<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
		$this->load->model(array('user_datatable','user_model'));
	}

	function index()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			$data['view']			= 'users/index';
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
				$list 	= $this->user_datatable->get_datatables();
				$data 	= array();
				$draw 	= isset($_POST['draw']) ? $_POST['draw'] : 0;
				$num 	= isset($_POST['start']) ? $_POST['start'] : 0;
				$no 	= $num+1;
				$i		= 1;
				foreach ($list as $post) 
				{
					$row = array();
					$row['no']				= $no++;
					$row['user_id'] 		= $post->user_id;
					$row['user_fullname'] 	= $post->user_fullname;
					$row['user_email'] 		= $post->user_email;
					$row['user_phone'] 		= $post->user_phone; 
					$row['user_active'] 	= $post->user_active;
					if($post->user_id != 1)
					{
						$actions = ' <div class="dropdown dropdown-action">
													<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="ActionMenu'.$i.'" data-bs-toggle="dropdown" aria-expanded="false">Menu</button>
													<ul class="dropdown-menu" aria-labelledby="ActionMenu'.$i.'">
														<li><a class="dropdown-item" href="'.base_url('users/edit/'.$post->user_id.'').'"><i class="fas fa-edit"></i> Edit</a></li>
														<li><a class="dropdown-item" href="'.base_url('users/password/'.$post->user_id.'').'"><i class="fas fa-lock"></i> Reset Password</a></li>
														<div class="dropdown-divider"></div>
														<li><a class="dropdown-item datatable-delete" href="javascript:void(0);" onclick="row_delete(\''.$post->user_id.'\',\''.site_url('users/delete').'\')"><i class="fa fa-times"></i> Delete</a></li>
													</ul>
											</div>';
					}
					else
					{
						$actions = ' <div class="dropdown dropdown-action">
								<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="ActionMenu'.$i.'" data-bs-toggle="dropdown" aria-expanded="false">Menu</button>
								<ul class="dropdown-menu" aria-labelledby="ActionMenu'.$i.'">
									<li><a class="dropdown-item" href="'.base_url('users/edit/'.$post->user_id.'').'"><i class="fas fa-edit"></i> Edit</a></li>
									<li><a class="dropdown-item" href="'.base_url('users/password/'.$post->user_id.'').'"><i class="fas fa-lock"></i> Reset Password</a></li>
								</ul>
						</div>';
					}
					$row['actions'] 	= $actions;

					$i++;
					$data[] = $row;
					
				}
				
				$output = array('draw' => $draw,
								'recordsTotal' => $this->user_datatable->count_all(),
								'recordsFiltered' => $this->user_datatable->count_filtered(),
								'data' => $data);
				$result = json_encode($output, JSON_UNESCAPED_SLASHES);
				
				$clean_result = str_replace(array('\n','\t'), '', $result);
				echo $clean_result;
			}
		}
	}

	function add()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			$data['parent_navlink']			= site_url('users');
			$data['form_action']			= site_url('users/save');
			$data['view']					= 'users/add';
			$this->load->view($data['view'], $data);
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	function edit($user_id = null)
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			if($user_id != '' && $this->user_model->one_user($user_id)->num_rows() == 1)
			{
				$row = $this->user_model->one_user($user_id)->row();
				$data['user_id']				= $row->user_id;
				$data['user_fullname']			= $row->user_fullname;
				$data['user_email']				= $row->user_email;
				$data['user_phone']				= $row->user_phone;
				$data['user_active']			= $row->user_active;
				$data['user_registered_type']	= $row->user_registered_type;
				$data['user_registered']		= $row->user_registered ;
				$data['user_active']			= $row->user_active;
				$data['parent_navlink']			= site_url('users');
				$data['form_action']			= site_url('users/update');
				$data['view']					= 'users/edit';
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
				$this->form_validation->set_rules('user_fullname', 'Name', 'required');
				$this->form_validation->set_rules('user_login', 'User Login', 'required|is_unique[users.user_login]',
													array('is_unique' => '&quot;'.$this->input->post('user_login').'&quot; already registered.'));
				$this->form_validation->set_rules('user_email', 'Email', 'required|is_unique[users.user_email]|valid_email',
													array('is_unique' => '&quot;'.$this->input->post('user_email').'&quot; already registered.'));
				$this->form_validation->set_rules('user_phone', 'Phone Number', 'required|is_unique[users.user_phone]|min_length[11]|callback_phone_validation',
													array('is_unique' => '&quot;'.$this->input->post('phone').'&quot; already registered')); 
				$this->form_validation->set_rules('user_active', 'Status', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
				$this->form_validation->set_rules('password2', 'Konfirmasi Password', 'required|matches[password]');

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
					$data['user_login']				= trim_input_value($this->input->post('user_login',true));
					$data['user_pass']				= md5(trim_input_value($this->input->post('password',true)));
					$data['user_fullname']			= trim_input_value($this->input->post('user_fullname',true));
					$data['user_email']				= trim_input_value($this->input->post('user_email',true));
					$data['user_phone']				= trim_input_value($this->input->post('user_phone',true));
					$data['user_registered']		= date('Y-m-d H:i:s');
					$data['user_activation_key']	= random_string('alnum', 10);
					$data['user_registered_type']	= 'web';
					$data['user_active']			= trim_input_value($this->input->post('user_active',true));
					$data['user_level']				= 'admin';
					$data['user_role']				= 1;

					if($this->user_model->save_user($data))
					{ 
						$res['status']      = 1;
						$res['redirect']    = 1;
						$res['link']    	= ''.site_url('users').'';
						$res['type']        = 'success';
						$res['message']     = 'New user added.';
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

                $this->form_validation->set_rules('user_fullname', 'Full Name', 'required');
                $this->form_validation->set_rules('user_email', 'Email Address', 'required|valid_email|callback_email_validation');
                $this->form_validation->set_rules('user_phone', 'Phone Number', 'required|min_length[11]|callback_phone_format|callback_phone_validation'); 
                
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
					$user_id 				= trim_input_value($this->input->post('id'));
                    $data['user_fullname']	= trim_input_value($this->input->post('user_fullname',true));
                    $data['user_email']		= trim_input_value($this->input->post('user_email',true));
                    $data['user_phone']		= trim_input_value($this->input->post('user_phone',true));
                    $data['user_active']	= trim_input_value($this->input->post('user_active',true));

                    if($this->user_model->update_user($data, $user_id))
                    {
                        $res['status']      = 1;
                        $res['redirect']    = 0;
                        $res['link']    	= '';
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

	function phone_format($phone_number)
	{
		$regex = '/^[+][0-9]*$/'; //validates +6282137792698
		if (!preg_match($regex, $phone_number)) {
			$this->form_validation->set_message('phone_format', 'Phone number format not valid.');
			return false;
		}
		return true; 
  	}
    
  	function phone_validation($phone_number)
  	{
		$id = $this->input->post('id');
		if ($phone_number != '' && $this->user_model->phone_validation($id, $phone_number)->num_rows() > 0) 
		{
			$this->form_validation->set_message('phone_validation', 'This phone number alrady used.');
			return false;
		} 
		else 
		{
			return true;
		}
  	}
    
  	function email_validation($email)
  	{
		$id = $this->input->post('id');
		if ($email != '' && $this->user_model->email_validation($id, $email)->num_rows() > 0) 
		{
			$this->form_validation->set_message('email_validation', 'This email already used.');
			return false;
		} 
		else 
		{
			return true;
		}
  	}

	function password($user_id = null)
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			if($user_id != '' && $this->user_model->one_user($user_id)->num_rows() == 1)
			{
				$row = $this->user_model->one_user($user_id)->row();
				$data['user_id']		= $row->user_id;
				$data['user_fullname']	= $row->user_fullname;
				$data['user_email']		= $row->user_email;
				$data['user_phone']		= $row->user_phone;
				$data['user_active']	= $row->user_active;
				$data['parent_navlink'] = site_url('users');
				$data['form_action']	= site_url('users/setpassword');
				$data['view']			= 'users/password';
				$this->load->view($data['view'], $data);
			}
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	function setpassword()
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

                $this->form_validation->set_rules('id', 'ID', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
                $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'required|matches[password]');
                
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
					$user_id 				= trim_input_value($this->input->post('id'));
                    $data['user_pass']		= md5(trim_input_value($this->input->post('password',true)));

                    if($this->user_model->update_user($data, $user_id))
                    {
                        $res['status']      = 1;
                        $res['redirect']    = 1;
                        $res['link']    	= ''.site_url('users').'';
                        $res['type']        = 'success';
                        $res['message']     = 'Password updated';
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

				$res['status']      = 1;
				$res['redirect']    = 0;
				$res['type']        = 'info';
				$res['message']     = 'Sorry, Deleting data is not allowed at the moment!';
				echo json_encode($res);
				return false;
			}
		}
	}

}