<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dass extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->helper(array('string','text'));
		$this->load->model(array('dass_model','dass_public_model','dass_datatable','client_datatable'));
	}

	function index()
	{
		if($this->session->userdata('user_id') !='' && $this->session->userdata('user_level') == 'admin')
		{
			$data['view']			= 'dass/index';
			$this->load->view($data['view'], $data);
		}
		else
		{
            //redirect(''.site_url('login').'', 'refresh');
            $data['soal']               = $this->dass_model->get_soal();
            $data['opsi_jawaban']       = $this->dass_model->get_opsijawaban();
            $data['groups']             = $this->dass_model->public_groups();
            $data['parent_navlink']     = ''.site_url('dass').'';
            $data['form_action']	    = ''.site_url('dass/save_dass_public').'';
            $data['view']			    = 'dass/dass_public';
            $this->load->view($data['view'], $data);
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
				$list 	= $this->dass_datatable->get_datatables();
				$data 	= array();
				$draw 	= isset($_POST['draw']) ? $_POST['draw'] : 0;
				$num 	= isset($_POST['start']) ? $_POST['start'] : 0;
				$no 	= $num+1;
				$i		= 1;
				foreach ($list as $post) 
				{
					$row = array();
					$row['no']					= $no++;
					$row['test_id'] 		    = $post->test_id;
					$row['client_id'] 		    = $post->client_id;
					$row['tanggal_mengisi'] 	= indo_date(substr($post->tanggal_mengisi,0,10));
					$row['umur_client'] 		= $post->umur_client . ' Tahun'; 
					$row['client_no'] 			= $post->client_no;
					$row['client_name'] 		= $post->client_name;
					$row['client_email'] 		= $post->client_email;
					$row['client_phone'] 		= $post->client_phone; 
					$row['client_address'] 		= $post->client_address;
					$row['client_birthday'] 	= $post->client_birthday;
					$row['client_gender'] 	    = $post->client_gender;
					$row['client_registered'] 	= indo_date(substr($post->client_registered,0,10)).'<br><small class="text-muted">'.substr($post->client_registered,10,9). ' WIB</small>';

					$actions = '<div class="d-grid"><a href="'.site_url('dass/detail/'.$post->test_id.'').'" class="btn btn-sm btn-primary"><i class="far fa-file-alt"></i> Detail</a></div>';
					
					$row['actions'] 	= $actions;

					$i++;
					$data[] = $row;
					
				}
				
				$output = array('draw' => $draw,
								'recordsTotal' => $this->dass_datatable->count_all(),
								'recordsFiltered' => $this->dass_datatable->count_filtered(),
								'data' => $data);
				$result = json_encode($output, JSON_UNESCAPED_SLASHES);
				
				$clean_result = str_replace(array('\n','\t'), '', $result);
				echo $clean_result;
			}
		}
	}

    function table_clients()
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
                $data['view']			= 'dass/table_clients';
                $this->load->view($data['view'], $data);
            }
        }
    }

    function client_datatable()
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
					$row['client_birthday'] 	= indo_date(substr($post->client_birthday,0,10));
					$row['client_gender'] 	    = $post->client_gender;
					$row['client_registered'] 	= indo_date(substr($post->client_registered,0,10)).'<br><small class="text-muted">'.substr($post->client_registered,10,9). ' WIB</small>';

					$actions = '<div class="d-grid"><a href="'.site_url('dass/add?id='.$post->id.'').'" class="btn btn-sm btn-primary btn-action" data-bs-toggle="tooltip" data-bs-placement="left" title="Input Instrumen DASS"><i class="fa fa-plus"></i> DASS</a></div>';
					
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
            $client_id = trim($this->input->get('id'));
            if($client_id != '')
            {
                if($this->dass_model->one_client($client_id)->num_rows() == 1)
                {
                    $row = $this->dass_model->one_client($client_id)->row();
                    $data['client_id']          = $client_id;
                    $data['client_no']          = $row->client_no;
                    $data['client_name']        = $row->client_name;
                    $data['client_birthday']    = $row->client_birthday;
                    $data['client_gender']      = $row->client_gender;
                    $data['client_email']       = $row->client_email;
                    $data['client_phone']       = $row->client_phone;
                    $data['client_address']     = $row->client_address;
                    $data['client_code']        = $row->client_code;
                    $data['soal']               = $this->dass_model->get_soal();
                    $data['opsi_jawaban']       = $this->dass_model->get_opsijawaban();
                    $data['parent_navlink']     = ''.site_url('dass').'';
                    $data['form_action']	    = ''.site_url('dass/save').'';
                    $data['view']			    = 'dass/add_1';
                    $this->load->view($data['view'], $data);
                }
            }
            else
            {
                $data['view']			= 'dass/add_2';
                $this->load->view($data['view'], $data);
            }
        }
        else
        {

        }
    }

    function input()
    {
        $client_code = trim($this->input->get('code'));
        if($client_code != '')
        {
            if($this->dass_model->client_by_code($client_code)->num_rows() == 1)
            {
                $row = $this->dass_model->client_by_code($client_code)->row();
                $data['client_id']          = $row->id;
                $data['client_no']          = $row->client_no;
                $data['client_name']        = $row->client_name;
                $data['client_birthday']    = $row->client_birthday;
                $data['client_gender']      = $row->client_gender;
                $data['client_email']       = $row->client_email;
                $data['client_phone']       = $row->client_phone;
                $data['client_address']     = $row->client_address;
                $data['soal']               = $this->dass_model->get_soal();
                $data['opsi_jawaban']       = $this->dass_model->get_opsijawaban();
                $data['parent_navlink']     = ''.site_url('dass').'';
                $data['form_action']	    = ''.site_url('dass/save_public').'';
                $data['view']			    = 'dass/public';
                $this->load->view($data['view'], $data);
            }
        }
        else
        {
            $data['view']			= 'dass/add_2';
            $this->load->view($data['view'], $data);
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

                $this->form_validation->set_rules('client_id', 'ID', 'required');
                $this->form_validation->set_rules('client_no', 'Client No', 'required');
                
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
                    // $json 		= file_get_contents("php://input");
                    // $obj        = json_decode($json,true);
                    // $input      = json_decode($json); 
                    
                    $client_id                  = trim($this->input->post('client_id'));
                    $test['client_id']          = $client_id;
                    $test['tanggal_mengisi']    = date('Y-m-d H:i:s');
                    $test['umur_client']        = umur_saat_dass(client_info($client_id,'client_birthday'), date('Y-m-d'));
                    if ($this->dass_model->save_test($test))
                    {
                        $test_id    = $this->db->insert_id();
                        $input		= $this->input->post('soal');

                        foreach ($input as $field => $k) 
                        {
                            $hasil['test_id']        = $test_id;
                            $hasil['client_id']      = $client_id;
                            $hasil['soal_id']        = $field;
                            $hasil['jawaban']        = $k;
                            $this->dass_model->save_hasil_test($hasil);
                        }

                        // Response
                        $res['status']      = 1;
                        $res['redirect']    = 0;
                        $res['type']        = 'success';
                        $res['message']     = 'Data berhasil disimpan';
                        echo json_encode($res);
                        return false;
                    }
                    else
                    {
                        $res['status']      = 0;
                        $res['redirect']    = 0;
                        $res['type']        = 'error';
                        $res['message']     = 'Gagal saat menyimpan data!';
                        echo json_encode($res);
                        return false;
                    }
                }
            }
        }
    }

    function save_public()
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

            $this->form_validation->set_rules('client_id', 'ID', 'required');
            $this->form_validation->set_rules('client_no', 'Client No', 'required');
            
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
                $client_id                  = trim($this->input->post('client_id'));
                $test['client_id']          = $client_id;
                $test['tanggal_mengisi']    = date('Y-m-d H:i:s');
                $test['umur_client']        = umur_saat_dass(client_info($client_id,'client_birthday'), date('Y-m-d'));
                if ($this->dass_model->save_test($test))
                {
                    $test_id    = $this->db->insert_id();
                    $input		= $this->input->post('soal');

                    foreach ($input as $field => $k) 
                    {
                        $hasil['test_id']        = $test_id;
                        $hasil['client_id']      = $client_id;
                        $hasil['soal_id']        = $field;
                        $hasil['jawaban']        = $k;
                        $this->dass_model->save_hasil_test($hasil);
                    }

                    // Response
                    $res['status']      = 1;
                    $res['redirect']    = 0;
                    $res['type']        = 'success';
                    $res['message']     = '<div class="p-3"><div class="alert alert-success mb-0">Terimakasih telah menjawab pertanyaan yang diberikan dengan baik. Jawban yang anda berikan sudah disimpan.</div></div>';
                    echo json_encode($res);
                    return false;
                }
                else
                {
                    $res['status']      = 0;
                    $res['redirect']    = 0;
                    $res['type']        = 'error';
                    $res['message']     = 'Gagal saat menyimpan data!';
                    echo json_encode($res);
                    return false;
                }
            }
        }
    }

    function save_dass_public()
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

            $this->form_validation->set_rules('client_name', 'Nama Lengkap', 'required');
            $this->form_validation->set_rules('client_phone', 'No. WhatsApp', 'required');
            $this->form_validation->set_rules('client_gender', 'Jenis Kelamin', 'required');
            $this->form_validation->set_rules('client_age', 'umur', 'required');
            $this->form_validation->set_rules('group', 'Group/Kelompok', 'required');
            
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
                $client_public['client_code']       = strtolower(date('is').random_string('alnum', 4));
                $client_public['client_name']       = trim($this->input->post('client_name'));
                $client_public['client_phone']      = trim($this->input->post('client_phone'));
                $client_public['client_gender']     = trim($this->input->post('client_gender'));
                $client_public['client_age']        = trim($this->input->post('client_age'));
                $client_public['client_registered'] = date('Y-m-d H:i:s');
                
                if($this->dass_public_model->save_client_public($client_public))
                {
                    $client_id    = $this->db->insert_id();
                    
                }

                // Save client_group_public
                $group['client_id']         = $client_id;
                $group['group_id']          = trim($this->input->post('group'));
                $group['created']           = date('Y-m-d H:i:s');
                $this->dass_public_model->add_to_group($group);

                // Save test_public
                $test['client_id']          = $client_id;
                $test['tanggal_mengisi']    = date('Y-m-d H:i:s');
                if ($this->dass_public_model->save_test_public($test))
                {
                    $test_id    = $this->db->insert_id();
                    $input		= $this->input->post('soal');

                    foreach ($input as $field => $k) 
                    {
                        // Save hasil_test_public
                        $hasil['test_id']        = $test_id;
                        $hasil['client_id']      = $client_id;
                        $hasil['soal_id']        = $field;
                        $hasil['jawaban']        = $k;
                        $this->dass_public_model->save_hasil_test_public($hasil);
                    }

                    //Process-----------------------------------------------------------------------------------------
                    $check_data     = $this->dass_public_model->check_data($client_id,$test_id);
                    $kategori_soal  = $this->dass_public_model->kategori_soal();
                    if($check_data->num_rows() == 1)
                    {
                        if($kategori_soal->num_rows() > 0)
                        {
                            foreach ($kategori_soal->result() as $kategori)
                            {
                                if($this->dass_public_model->dass_by_group($test_id, $kategori->soal_kategori_id)->num_rows() > 0)
                                {
                                    $jumlah = 0; 
                                    foreach ($this->dass_public_model->dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
                                    {
                                        $jumlah = $jumlah + $nilai->jawaban;
                                    }
                                    
                                    if($kategori->soal_kategori_id == 1) 
                                    {
                                        // Stress / Tekanan
                                        $data['point_stress']   = $jumlah;
                                        $data['stress']         = dass_result($kategori->soal_kategori_id, $jumlah);
                                        $this->dass_public_model->update_test_public($data,$client_id,$test_id);
                                    }
                                    else if($kategori->soal_kategori_id == 2)
                                    {
                                        // Anxiety / Kebimbangan 
                                        $data['point_anxiety']   = $jumlah;
                                        $data['anxiety']         = dass_result($kategori->soal_kategori_id, $jumlah);
                                        $this->dass_public_model->update_test_public($data,$client_id,$test_id);
                                    }
                                    else if($kategori->soal_kategori_id == 3)
                                    {
                                        // Depression / Kemurungan
                                        $data['point_depression']   = $jumlah;
                                        $data['depression']         = dass_result($kategori->soal_kategori_id, $jumlah);
                                        $this->dass_public_model->update_test_public($data,$client_id,$test_id);
                                    }
                                }
                            }
                        }
                    }

                    //End Of Process----------------------------------------------------------------------------------
                    // Response
                    $res['status']      = 1;
                    $res['redirect']    = 0;
                    $res['type']        = 'success';
                    $res['message']     = '<div class="p-3"><div class="alert alert-success mb-0">Terimakasih telah menjawab pertanyaan yang diberikan dengan baik. Jawban yang anda berikan sudah disimpan.<p class="text-center">[<a href="'.site_url('dass').'">Kembali</a>]</p></div></div>';
                    echo json_encode($res);
                    return false;
                }
                else
                {
                    $res['status']      = 0;
                    $res['redirect']    = 0;
                    $res['type']        = 'error';
                    $res['message']     = 'Gagal saat menyimpan data!';
                    echo json_encode($res);
                    return false;
                }
            }
        }
    }

    function detail($test_id=null)
    {
        if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            if($test_id !='' && $this->dass_model->one_test($test_id)->num_rows() == 1)
            {
                $row                        = $this->dass_model->one_test($test_id)->row();
                $client_id                  = $row->client_id;
                $data['test_id']            = $test_id;
                $data['client_id']          = $client_id;
                $data['client_no']          = client_info($client_id,'client_no');
                $data['client_name']        = client_info($client_id,'client_name');
                $data['client_birthday']    = client_info($client_id,'client_birthday');
                $data['client_gender']      = client_info($client_id,'client_gender');
                $data['client_email']       = client_info($client_id,'client_email');
                $data['client_phone']       = client_info($client_id,'client_phone');
                $data['client_address']     = client_info($client_id,'client_address');
                $data['tanggal_mengisi']    = $row->tanggal_mengisi;
                $data['therapy_id']         = $row->therapy_id;
                $data['therapy_name']       = $row->therapy_name;
                $data['therapy_price']      = $row->therapy_price;
                $data['deskripsi_masalah']  = $row->deskripsi_masalah;
                $data['keterangan']         = $row->keterangan;

                $data['stress']             = $row->stress;
                $data['anxiety']            = $row->anxiety;
                $data['depression']         = $row->depression;

                $data['parent_navlink']     = ''.site_url('dass').'';
                $data['hasil_test']         = $this->dass_model->hasil_test($test_id); 
                $data['opsi_jawaban']       = $this->dass_model->get_opsijawaban(); 
                $data['kategori_soal']      = $this->dass_model->kategori_soal();
                $data['therapy']            = $this->dass_model->therapy();
                $data['view']			    = 'dass/detail';
                $this->load->view($data['view'], $data);
            }
        }
        else
        {
            redirect(''.site_url('login').'', 'refresh');
        }
    }
}