<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_public extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
		$this->load->model(array('client_model'));
	}

	function index()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			$data['view']			= 'clients/index';
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
				$list 	= $this->client_datatable->get_datatables();
				$data 	= array();
				$draw 	= isset($_POST['draw']) ? $_POST['draw'] : 0;
				$num 	= isset($_POST['start']) ? $_POST['start'] : 0;
				$no 	= $num+1;
				$i		= 1;
				foreach ($list as $post) 
				{
					$row = array();
					$row['no']					= $no++;
					$row['id'] 					= $post->id;
					$row['client_no'] 			= $post->client_no;
					$row['client_name'] 		= $post->client_name;
					$row['client_email'] 		= $post->client_email;
					$row['client_phone'] 		= $post->client_phone; 
					$row['client_address'] 		= $post->client_address;
					$row['client_birthday'] 	= $post->client_birthday;
					$row['client_gender'] 	= $post->client_gender;
					$row['client_registered'] 	= indo_date(substr($post->client_registered,0,10)).'<br><small class="text-muted">'.substr($post->client_registered,10,9). ' WIB</small>';

					$actions = '
								<a href="'.site_url('dass/add?id='.$post->id.'').'" class="btn btn-sm btn-primary btn-action" data-bs-toggle="tooltip" data-bs-placement="left" title="Input Instrumen DASS"><i class="fa fa-plus"></i> DASS</a>
								<div class="dropdown dropdown-action">
									<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="ActionMenu'.$i.'" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>
									<ul class="dropdown-menu" aria-labelledby="ActionMenu'.$i.'">
										<li><a class="dropdown-item" href="'.base_url('clients/edit/'.$post->id.'').'"><i class="fas fa-edit"></i> Edit</a></li>
										<div class="dropdown-divider"></div>
										<li><a class="dropdown-item datatable-delete" href="javascript:void(0);" onclick="row_delete(\''.$post->id.'\',\''.site_url('clients/delete').'\')"><i class="fa fa-times"></i> Delete</a></li>
									</ul>
								</div>
								';
					
					$row['actions'] 	= $actions;

					$i++;
					$data[] = $row;
					
				}
				
				$output = array('draw' => $draw,
								'recordsTotal' => $this->client_datatable->count_all(),
								'recordsFiltered' => $this->client_datatable->count_filtered(),
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
			$data['parent_navlink']			= site_url('clients');
			$data['form_action']			= site_url('clients/save');
			$data['view']					= 'clients/add';
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
			if($id != '' && $this->client_model->one_client($id)->num_rows() == 1)
			{
				$row = $this->client_model->one_client($id)->row();
				$data['id']						= $row->id;
				$data['client_no']				= $row->client_no;
				$data['client_name']			= $row->client_name;
				$data['client_email']			= $row->client_email;
				$data['client_phone']			= $row->client_phone;
				$data['client_address']			= $row->client_address;
				$data['client_registered']		= $row->client_registered ;
				$data['client_birthday']		= $row->client_birthday;
				$data['client_gender']			= $row->client_gender;
				$data['cday']					= substr($row->client_birthday, 8,2);
				$data['cmonth']					= substr($row->client_birthday, 5,2);
				$data['cyear']					= substr($row->client_birthday, 0,4);


				$data['parent_navlink']			= site_url('clients');
				$data['form_action']			= site_url('clients/update');
				$data['view']					= 'clients/edit';
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
				$this->form_validation->set_rules('client_name', 'Name', 'required');
				$this->form_validation->set_rules('client_email', 'Email', 'required|is_unique[clients.client_email]|valid_email',
													array('is_unique' => '&quot;'.$this->input->post('client_email').'&quot; already registered.'));
				$this->form_validation->set_rules('client_phone', 'Phone Number', 'required|is_unique[clients.client_phone]|min_length[11]|callback_phone_validation',
													array('is_unique' => '&quot;'.$this->input->post('client_phone').'&quot; already registered')); 
				$this->form_validation->set_rules('cday', 'Date of birth', 'required');
				$this->form_validation->set_rules('cmonth', 'Month of birth ', 'required');
				$this->form_validation->set_rules('cyear', 'Year of birth ', 'required');
				$this->form_validation->set_rules('client_address', 'Address', 'required');
				$this->form_validation->set_rules('client_gender', 'Gender', 'required');

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
					$c_day 								= trim_input_value($this->input->post('cday',true));
					$c_month 							= trim_input_value($this->input->post('cmonth',true));
					$c_year 							= trim_input_value($this->input->post('cyear',true));
					$data['client_name']				= trim_input_value($this->input->post('client_name',true));
					$data['client_email']				= trim_input_value($this->input->post('client_email',true));
					$data['client_phone']				= trim_input_value($this->input->post('client_phone',true));
					$data['client_birthday']			= ''.$c_year.'-'.$c_month.'-'.$c_day.'';
					$data['client_registered']			= date('Y-m-d H:i:s');
					$data['client_address']				= trim_input_value($this->input->post('client_address',true));
					$data['client_gender']				= trim_input_value($this->input->post('client_gender',true));

					if($this->client_model->save_client($data))
					{
						$client_id 		= $this->db->insert_id();
						$client_code 	= strtolower($client_id.random_string('alnum', 4));
						$update['client_no'] 	= 'ICP00'.$client_id;
						$update['client_code'] 	= strtolower($client_code);
						$this->client_model->update_client($update, $client_id);


						$res['status']      = 1;
						$res['redirect']    = 1;
						$res['link']    	= ''.site_url('clients').'';
						$res['type']        = 'success';
						$res['message']     = 'New client added.';
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

                $this->form_validation->set_rules('client_name', 'Full Name', 'required');
                $this->form_validation->set_rules('client_email', 'Email Address', 'required|valid_email|callback_email_validation');
                $this->form_validation->set_rules('client_phone', 'Phone Number', 'required|min_length[11]|callback_phone_format|callback_phone_validation'); 
				$this->form_validation->set_rules('cday', 'Date of birth', 'required');
				$this->form_validation->set_rules('cmonth', 'Month of birth ', 'required');
				$this->form_validation->set_rules('cyear', 'Year of birth ', 'required');
				$this->form_validation->set_rules('client_address', 'Address', 'required');
				$this->form_validation->set_rules('client_gender', 'Gender', 'required');
                
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
					$id 								= trim_input_value($this->input->post('id'));
					$c_day 								= trim_input_value($this->input->post('cday',true));
					$c_month 							= trim_input_value($this->input->post('cmonth',true));
					$c_year 							= trim_input_value($this->input->post('cyear',true));

					$data['client_name']				= trim_input_value($this->input->post('client_name',true));
					$data['client_email']				= trim_input_value($this->input->post('client_email',true));
					$data['client_phone']				= trim_input_value($this->input->post('client_phone',true));
					$data['client_address']				= trim_input_value($this->input->post('client_address',true));
					$data['client_gender']				= trim_input_value($this->input->post('client_gender',true));
					$data['client_birthday']			= ''.$c_year.'-'.$c_month.'-'.$c_day.'';
					
                    if($this->client_model->update_client($data, $id))
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
		if ($phone_number != '' && $this->client_model->phone_validation($id, $phone_number)->num_rows() > 0) 
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
		if ($email != '' && $this->client_model->email_validation($id, $email)->num_rows() > 0) 
		{
			$this->form_validation->set_message('email_validation', 'This email already used.');
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

				$id = trim_input_value($this->input->post('id'));
				if($id != '' && $this->client_model->one_client($id)->num_rows() == 1)
				{
					// Delete Client
					$this->client_model->delete_client($id);
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