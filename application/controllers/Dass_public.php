<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dass_public extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
		$this->load->model(array('dass_public_model','dass_public_datatable'));
	}

	function index()
	{
		if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
			$data['view']			= 'dass/index_public';
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
                $groups = $this->dass_public_model->groups();
				$list 	= $this->dass_public_datatable->get_datatables();
				$data 	= array();
				$draw 	= isset($_POST['draw']) ? $_POST['draw'] : 0;
				$num 	= isset($_POST['start']) ? $_POST['start'] : 0;
				$no 	= $num+1;
				$i		= 1;
                
				foreach ($list as $post) 
				{
                    $a_val	= '';
                    $a_opt	= array(''=> '- Select a Group -');
                    $a_attr = array('class' => 'form-select form-control-sm', 'required' => 'required', 'id' => 'group'.$post->test_id.'' ,'onchange'=>'form_submit('.$post->test_id.')');
                    $a_data	= $groups->result();
                    if ($groups->num_rows() > 0) { foreach ($a_data as $dat) { $a_opt[$dat->group_id] = $dat->group_name; } }
                    $form_group = '
                    <form method="post" id="form-'.$post->test_id.'" class="form" action="'.site_url('dass_public/add_group').'">
                    <input type="hidden" id="test'.$post->test_id.'" name="test" value="'.$post->test_id.'" readonly="readonly">
                    <input type="hidden" id="client'.$post->test_id.'" name="client" value="'.$post->client_id.'" readonly="readonly">
                    <div class="input-group">'.form_dropdown('group', $a_opt, $a_val, $a_attr).'<span class="input-group-btn"> <button class="btn btn-primary form_submit" type="button" onclick="form_submit('.$post->test_id.');" data-test_id="'.$post->test_id.'" data-client_id="'.$post->client_id.'" title="Add Group"><i class="fa fa-plus"></i></button> </span></div>
                    </form>';

                    $client_groups = '<ul class="list-unstyled mb-0" id="list-group-'.$post->client_id.'">';
                    if($this->dass_public_model->client_groups($post->client_id)->num_rows() > 0)
                    {
                       foreach ($this->dass_public_model->client_groups($post->client_id)->result() as $group)
                       {
                            $client_groups .='<li id="item-group-'.$group->group_id.$group->client_id.'"><a href="javascript:void(0);" class="text-danger" onclick="return DeleteGroup('.$group->group_id.','.$group->client_id.')" title="Delete Group"><i class="fa fa-times close-inline"></i></a>&nbsp;'.$group->group_name.'</li>';
                       } 
                    }
                    $client_groups .='</ul>';

					$row = array();
                    $client_name = '<strong>'.$post->client_name.'</strong><br><small class="text-muted"> '.jenis_kelamin($post->client_gender).'  '; $client_name .= ($post->client_age) ? ' &mdash; '.$post->client_age.' Tahun' : ''; $client_name .= '</small>';
					$row['no']					= $no++;
					$row['test_id'] 		    = $post->test_id;
					$row['client_id'] 		    = $post->client_id;
                    $row['tanggal_mengisi'] 	= substr($post->tanggal_mengisi,0,10) . '<br><small class="text-muted">' .substr($post->tanggal_mengisi,11,10) . ' WIB</small>';
					$row['client_name'] 		= $client_name;
					$row['client_phone'] 		= $post->client_phone; 
					$row['client_gender'] 	    = $post->client_gender;
					$row['client_age'] 	        = $post->client_age;
					$row['client_registered'] 	= indo_date(substr($post->client_registered,0,10)).'<br><small class="text-muted">'.substr($post->client_registered,10,9). ' WIB</small>';
                    $row['client_group']        = $client_groups;
                    $row['form_group']          = $form_group;

					$actions = '<div class="d-gridx">
                                <a href="'.site_url('dass_public/cprint/'.$post->test_id.'').'" class="btn btn-sm btn-danger" title="Print"><i class="fa fa-print"></i></a>
                                <a href="'.site_url('dass_public/detail/'.$post->test_id.'').'" class="btn btn-sm btn-primary" title="Detail"><i class="far fa-file-alt"></i></a>
					            </div>';
					$row['actions'] = $actions;
					$i++;
					$data[] = $row;
					
				}
				
				$output = array('draw' => $draw,
								'recordsTotal' => $this->dass_public_datatable->count_all(),
								'recordsFiltered' => $this->dass_public_datatable->count_filtered(),
								'data' => $data);
				$result = json_encode($output, JSON_UNESCAPED_SLASHES);
				
				$clean_result = str_replace(array('\n','\t','\r'), '', $result);
				echo $clean_result;
			}
		}
	}

    function detail($test_id=null)
    {
        if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            if($test_id !='' && $this->dass_public_model->one_test($test_id)->num_rows() == 1)
            {
                $row                        = $this->dass_public_model->one_test($test_id)->row();
                $client_id                  = $row->client_id;
                $data['test_id']            = $test_id;
                $data['client_id']          = $client_id;
                $data['client_name']        = client_public_info($client_id,'client_name');
                $data['client_gender']      = client_public_info($client_id,'client_gender');
                $data['client_phone']       = client_public_info($client_id,'client_phone');
                $data['client_age']         = client_public_info($client_id,'client_age');
                $data['tanggal_mengisi']    = $row->tanggal_mengisi;

                $data['stress']             = $row->stress;
                $data['anxiety']            = $row->anxiety;
                $data['depression']         = $row->depression;

                $data['parent_navlink']     = ''.site_url('dass_public').'';
                $data['hasil_test']         = $this->dass_public_model->hasil_test($test_id); 
                $data['opsi_jawaban']       = $this->dass_public_model->get_opsijawaban(); 
                $data['kategori_soal']      = $this->dass_public_model->kategori_soal();
                $data['view']			    = 'dass/detail_public';
                $this->load->view($data['view'], $data);
            }
        }
        else
        {
            redirect(''.site_url('login').'', 'refresh');
        }
    }

    function cprint($test_id=null)
    {
        if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            if($test_id !='' && $this->dass_public_model->one_test($test_id)->num_rows() == 1)
            {
                $row                        = $this->dass_public_model->one_test($test_id)->row();
                $client_id                  = $row->client_id;
                $data['test_id']            = $test_id;
                $data['client_id']          = $client_id;
                $data['client_name']        = client_public_info($client_id,'client_name');
                $data['client_gender']      = client_public_info($client_id,'client_gender');
                $data['client_phone']       = client_public_info($client_id,'client_phone');
                $data['client_age']         = client_public_info($client_id,'client_age');
                $data['tanggal_mengisi']    = $row->tanggal_mengisi;

                $data['stress']             = $row->stress;
                $data['anxiety']            = $row->anxiety;
                $data['depression']         = $row->depression;

                $data['parent_navlink']     = ''.site_url('dass_public').'';
                $data['hasil_test']         = $this->dass_public_model->hasil_test($test_id); 
                $data['opsi_jawaban']       = $this->dass_public_model->get_opsijawaban(); 
                $data['kategori_soal']      = $this->dass_public_model->kategori_soal();
                $data['view']			    = 'dass/detail_print';
                $this->load->view($data['view'], $data);
            }
        }
        else
        {
            redirect(''.site_url('login').'', 'refresh');
        }
    }

    function add_group()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			$test_id		= trim($this->input->post('test'));
			$client_id		= trim($this->input->post('client'));
			$group_id		= trim($this->input->post('group'));
			
			if ($this->dass_public_model->check_group_exist($client_id, $group_id)->num_rows() == 1)
			{
				$res['status']	= 0;
				$res['group']	= $group_id;
				$res['client']	= $client_id;
				$res['test']	= $test_id;
				$res['title']	= 'Information';
				$res['type']	= 'warning';
				$res['message']	= 'User already added to this group';
				echo json_encode($res);
			}
			else
			{
                $data['client_id']  = $client_id;
                $data['group_id']   = $group_id;
                $data['created']    = date('Y-m-d H:i:s');
				if ($this->dass_public_model->add_to_group($data))
				{
					$res['status']      = 1;
					$res['group']	    = $group_id;
                    $res['group_name']	= group_info($group_id, 'group_name');
                    $res['client']	    = $client_id;
                    $res['test']	    = $test_id;
                    $res['message']	    = 'User already added to this group';
                    $res['title']	    = 'Information';
                    $res['type']	    = 'success';
                    $res['message']	    = 'Successfully added to <strong>'.$res['group_name'].'</strong>';
					echo json_encode($res);
				}
			}
		}
		else
		{
			$res['status']		= 0;
			$res['message']		= 'Your session has been expired!, Please login again';
			$res['title']		= 'Error';
			$res['type']		= 'error';
			$res['message']	    = 'Your session has been expired!, Please login again';
			echo json_encode($res);
		}
	}

    function remove_group()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			$client_id		= trim($this->input->post('client'));
			$group_id		= trim($this->input->post('group'));
			
			if ($this->dass_public_model->check_group_exist($client_id, $group_id)->num_rows() == 1)
			{
                if($this->dass_public_model->remove_client_group($client_id, $group_id))
                {
                    $res['status']	= 1;
                    $res['group']	= $group_id;
                    $res['client']	= $client_id;
                    $res['title']	= 'Information';
                    $res['type']	= 'info';
                    $res['message']	= 'Group deleted!';
                    echo json_encode($res);
                }
			}
		}
		else
		{
			$res['status']		= 0;
			$res['message']		= 'Your session has been expired!, Please login again';
			$res['title']		= 'Error';
			$res['type']		= 'error';
			$res['message']	    = 'Your session has been expired!, Please login again';
			echo json_encode($res);
		}
	}
}