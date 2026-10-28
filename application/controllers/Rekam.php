<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekam extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		$this->load->model(array('rekam_model','rekam_datatable'));
		$this->load->helper(array('string','text')); 
	}

	function index()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{ 
			$data['view']			= 'rekam/index';
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
				$list 	= $this->rekam_datatable->get_datatables();
				$data 	= array();
				$draw 	= isset($_POST['draw']) ? $_POST['draw'] : 0;
				$num 	= isset($_POST['start']) ? $_POST['start'] : 0;
				$no 	= $num+1;
				$i		= 1;
				foreach ($list as $post) 
				{
					$row = array();
					$row['no']					= $no++;
					$row['id'] 		    		= $post->id;
					//$row['tanggal_mengisi'] 	= indo_date(substr($post->tanggal_mengisi,0,10));
					//$row['umur_client'] 		= $post->umur_client . ' Tahun'; 
					$row['client_no'] 			= $post->client_no;
					$row['client_name'] 		= $post->client_name;
					$row['client_email'] 		= $post->client_email;
					$row['client_phone'] 		= $post->client_phone; 
					$row['client_birthday'] 	= indo_date($post->client_birthday);
					$row['client_gender'] 	    = jenis_kelamin($post->client_gender);
					$row['jumlah_dass'] 		= $post->jumlah_dass;
					$row['client_registered'] 	= indo_date(substr($post->client_registered,0,10)).'<br><small class="text-muted">'.substr($post->client_registered,10,9). ' WIB</small>';

					$actions = '<div class="d-grid"><a href="'.site_url('rekam/client/'.$post->id.'').'" class="btn btn-sm btn-primary"><i class="far fa-file-alt"></i> Detail</a></div>';
					
					$row['actions'] 	= $actions;

					$i++;
					$data[] = $row;
					
				}
				
				$output = array('draw' => $draw,
								'recordsTotal' => $this->rekam_datatable->count_all(),
								'recordsFiltered' => $this->rekam_datatable->count_filtered(),
								'data' => $data);
				$result = json_encode($output, JSON_UNESCAPED_SLASHES);
				
				$clean_result = str_replace(array('\n','\t'), '', $result);
				echo $clean_result;
			}
		}
	}

	function client($client_id)
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
			if($client_id != '' && $this->rekam_model->one_client($client_id)->num_rows() == 1)
			{
				$row = $this->rekam_model->one_client($client_id)->row();
				

				$data['id']					= $row->id;
				$data['client_no']			= $row->client_no;
				$data['client_name']		= $row->client_name;
				$data['client_email']		= $row->client_email;
				$data['client_phone']		= $row->client_phone;
				$data['client_birthday']	= $row->client_birthday;
				$data['client_gender']		= jenis_kelamin($row->client_gender);
				$data['client_address']		= $row->client_address;
				$data['client_registered']	= $row->client_registered;
				$data['client_code']		= $row->client_code;
				$data['rekam']				= $this->rekam_model->rekam_client($row->id);
				$data['parent_navlink']		= ''.site_url('rekam').'';
				$data['view']				= 'rekam/client';
				$this->load->view($data['view'], $data);
			}
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	function detail($client_id=null,$test_id=null)
    {
        if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            if($client_id !='' && $test_id !='' && $this->rekam_model->rekam_detail($client_id,$test_id)->num_rows() == 1)
            {
                if(!file_exists(FCPATH . '/assets/qrcode/qr-'.$test_id.'.png'))
                {
                    // GENERATE QRCODE IMAGE =================================
                    $this->load->library('ciqrcode');       //pemanggilan library QR CODE 
                    $config['cacheable']    = false;         //boolean, the default is true
                    //$config['cachedir']   = './assets/images/';  //string, the default is application/cache/
                    //$config['errorlog']   = './assets/qrcode/logs/'; //string, the default is application/logs/
                    $config['imagedir']     = '/assets/qrcode/'; //direktori penyimpanan qr code
                    $config['quality']      = true;     //boolean, the default is true
                    $config['size']         = '1024';   //interger, the default is 1024
                    $config['black']        = array(0, 0, 0); // array, default is array(255,255,255)
                    $config['white']        = array(255, 255, 255); // array, default is array(0,0,0)
                    $this->ciqrcode->initialize($config);

                    $image_name             = 'qr-'.$test_id.'.png'; //buat name dari qr code sesuai dengan Transaksi ID
                    $params['data']         = ''.site_url('validation/signature/?code='.nuxencrypt($test_id,'hide').'').''; //data yang akan di jadikan QR CODE
                    $params['level']        = 'L'; //H=High M=Medium L=Low
                    $params['size']         = 3;
                    $params['savename']     = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/qrcode
                    $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
                    // END GENERATE QRCODE IMAGE =================================
                    if(file_exists(FCPATH . '/assets/qrcode/qr-'.$test_id.'.png')){ $qrcode_image = ''; } else { $qrcode_image = '<p class="text-danger">QRCode not found!</p>'; }
                }
                else
                {
                    $qrcode_image = '<p class="text-danger">QRCode not found!</p>';
                }

                $row                        = $this->rekam_model->rekam_detail($client_id,$test_id)->row();
                $client_id                  = $row->client_id;
                $data['test_id']            = $test_id;
                $data['client_id']          = $client_id;
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

                $data['parent_navlink']     = ''.site_url('rekam').'';
                $data['form_action']        = ''.site_url('rekam/save').'';
                $data['hasil_test']         = $this->rekam_model->hasil_test($test_id);
                $data['opsi_jawaban']       = $this->rekam_model->get_opsijawaban();
                $data['kategori_soal']      = $this->rekam_model->kategori_soal();
                $data['therapy']            = $this->rekam_model->therapy();
                $data['counselors']         = $this->rekam_model->counselors();
                $data['view']			    = 'rekam/detail';

                $data['qrcode_image']       = $qrcode_image;
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

                $test_id    = trim_input_value($this->input->post('test_id'));
                $client_id  = trim_input_value($this->input->post('client_id'));

                if($this->rekam_model->test_check($test_id,$client_id)->num_rows() == 1)
                {
                    $this->form_validation->set_rules('test_id', 'ID', 'required');
                    $this->form_validation->set_rules('client_id', 'ClientID', 'required');
                    $this->form_validation->set_rules('therapy_id[]', 'TherapyID', 'required', 
                                                       array('required' => 'Silahkan pilih jenis terapi !'));
                    $this->form_validation->set_rules('tindak_lanjut', 'Tindak Lanjut', 'required', 
                                                       array('required' => 'Silahkan pilih tidak lanjut yang harus dilakukan !'));

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
                        $row = $this->rekam_model->test_check($test_id,$client_id)->row();
                        $therapy_id		            = $this->input->post('therapy_id');
                        $sesi		            	= trim_input_value($this->input->post('sesi',true));
                        //$data['therapy_id']		= $therapy_id;
                        //$data['therapy_name']		= therapy_info($therapy_id, 'therapy_name');
                        //$data['therapy_price']	= therapy_info($therapy_id, 'therapy_price');
                        $data['deskripsi_masalah']  = trim_input_value($this->input->post('deskripsi_masalah',true));
                        $data['tindak_lanjut']		= trim_input_value($this->input->post('tindak_lanjut',true));
                        //$data['konselor']		    = trim_input_value($this->input->post('konselor',true));
                        $data['counselor_id']		= trim_input_value($this->input->post('counselor_id',true));
                        $data['counselor_name']		= counselor_info($this->input->post('counselor_id'), 'counselor_name');
                        $data['konselor']		    = counselor_info($this->input->post('counselor_id'), 'counselor_name');
                        $data['sesi']		        = $sesi;

                        $data['point_stress']       =  poin_dass($test_id,1);
                        $data['point_anxiety']      =  poin_dass($test_id,2);
                        $data['point_depression']   =  poin_dass($test_id,3);

                        $data['stress']             =  keputusan_dass($test_id,1);
                        $data['anxiety']            =  keputusan_dass($test_id,2);
                        $data['depression']         =  keputusan_dass($test_id,3);
                        $data['tanggal_ditetapkan'] =  date('Y-m-d'); 
                        
                        if($row->invoice_code == '')
                        {
                            $nomor1                 = ''.$client_id.$test_id.$therapy_id.'';
                            $nomor2                 = str_pad($nomor1, 5, "0",STR_PAD_LEFT);
                            $nomor3                 = 'INV'.date('Ymd');
                            $data['invoice_code']   = $nomor3.$nomor2;
                        }
                        
                        // delete current test_therapy
                        $this->rekam_model->delete_test_therapy($test_id);
                        
                        // insert new test_therapy
                        if(is_array($therapy_id) )
                        {
                           foreach($therapy_id as $ter)
                           {
                                $test_therapy['therapy_id']     = $ter;
                                $test_therapy['test_id']        = $test_id;
                                $test_therapy['therapy_name']	= therapy_info($ter, 'therapy_name');
                                $test_therapy['therapy_price']	= therapy_info($ter, 'therapy_price');
                                $this->rekam_model->insert_test_therapy($test_therapy);
                           }
                        }
                        
                        // GENERATE QRCODE IMAGE =================================
                        $this->load->library('ciqrcode');       //pemanggilan library QR CODE 
                        $config['cacheable']    = false;         //boolean, the default is true
                        //$config['cachedir']   = './assets/images/';  //string, the default is application/cache/
                        //$config['errorlog']   = './assets/qrcode/logs/'; //string, the default is application/logs/
                        $config['imagedir']     = '/assets/qrcode/'; //direktori penyimpanan qr code
                        $config['quality']      = true;     //boolean, the default is true
                        $config['size']         = '1024';   //interger, the default is 1024
                        $config['black']        = array(0, 0, 0); // array, default is array(255,255,255)
                        $config['white']        = array(255, 255, 255); // array, default is array(0,0,0)
                        $this->ciqrcode->initialize($config);

                        $image_name             = 'qr-'.$test_id.'.png'; //buat name dari qr code sesuai dengan Transaksi ID
                        $params['data']         = ''.site_url('validation/signature/?code='.nuxencrypt($test_id,'hide').'').''; //data yang akan di jadikan QR CODE
                        $params['level']        = 'L'; //H=High M=Medium L=Low
                        $params['size']         = 3;
                        $params['savename']     = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/qrcode
                        $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
                        // END GENERATE QRCODE IMAGE =================================

                        if($this->rekam_model->test_update($data, $test_id))
                        {
                            $res['status']      = 1;
                            $res['redirect']    = 1;
                            $res['link']        = ''.site_url('rekam/detail/'.$client_id.'/'.$test_id.'?sesi='.$sesi.'').'';
                            $res['type']        = 'success';
                            $res['message']     = 'Rekam data berhasil disimpan';
                            echo json_encode($res);
                            return false;
                        }
                    }
                }
                else
                {
                    $res['status']      = 0;
                    $res['redirect']    = 0;
                    $res['type']        = 'warning';
                    $res['message']     = 'Data tidak ditemukan';
                    echo json_encode($res);
                    return false;
                }
            }
            else
            {
                $res['status']      = 0;
                $res['redirect']    = 0;
                $res['type']        = 'warning';
                $res['message']     = 'Akses tidak diizinkan, Silahkan login !';
                echo json_encode($res);
                return false;
            }
        }
    }

    function ajax_dass()
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

                $test_id                    = trim_input_value($this->input->post('id'));
                $row                        = $this->rekam_model->one_test($test_id)->row();
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

                $data['parent_navlink']     = ''.site_url('rekam').'';
                $data['hasil_test']         = $this->rekam_model->hasil_test($test_id); 
                $data['opsi_jawaban']       = $this->rekam_model->get_opsijawaban(); 
                $data['kategori_soal']      = $this->rekam_model->kategori_soal();
                $data['therapy']            = $this->rekam_model->therapy();

                $data['view']			    = 'rekam/ajax_dass';
                $this->load->view($data['view'], $data);
            }
        }
    }

    function pdf($client_id=null,$test_id=null)
    {
        if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            if($client_id !='' && $test_id !='' && $this->rekam_model->rekam_detail($client_id,$test_id)->num_rows() == 1)
            {
                if(file_exists(FCPATH . '/assets/qrcode/qr-'.$test_id.'.png'))
                {
                    $data['qrcode_image']       = '<img src="'.base_url('assets/qrcode/qr-'.$test_id.'.png').'" width="60">';
                }
                else
                {
                    // GENERATE QRCODE IMAGE =================================
                    $this->load->library('ciqrcode');       //pemanggilan library QR CODE 
                    $config['cacheable']    = false;         //boolean, the default is true
                    //$config['cachedir']   = './assets/images/';  //string, the default is application/cache/
                    //$config['errorlog']   = './assets/qrcode/logs/'; //string, the default is application/logs/
                    $config['imagedir']     = '/assets/qrcode/'; //direktori penyimpanan qr code
                    $config['quality']      = true;     //boolean, the default is true
                    $config['size']         = '1024';   //interger, the default is 1024
                    $config['black']        = array(0, 0, 0); // array, default is array(255,255,255)
                    $config['white']        = array(255, 255, 255); // array, default is array(0,0,0)
                    $this->ciqrcode->initialize($config);

                    $image_name             = 'qr-'.$test_id.'.png'; //buat name dari qr code sesuai dengan Transaksi ID
                    $params['data']         = ''.site_url('validation/signature/?code='.nuxencrypt($test_id,'hide').'').''; //data yang akan di jadikan QR CODE
                    $params['level']        = 'L'; //H=High M=Medium L=Low
                    $params['size']         = 3;
                    $params['savename']     = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/qrcode
                    $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
                    // END GENERATE QRCODE IMAGE =================================
                    $data['qrcode_image']       = '<img src="'.base_url('assets/qrcode/qr-'.$test_id.'.png').'" width="60">';
                } 

                $row                        = $this->rekam_model->rekam_detail($client_id,$test_id)->row();
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
                //$data['therapy_id']         = $row->therapy_id;
                //$data['therapy_name']       = $row->therapy_name;
                //$data['therapy_price']      = $row->therapy_price;
                $data['deskripsi_masalah']  = $row->deskripsi_masalah;
                $data['keterangan']         = $row->keterangan;
                $data['tindak_lanjut']   	= $row->tindak_lanjut;
                $data['sesi']   	        = $row->sesi;
                $data['konselor']   	    = $row->konselor;

                $data['stress']             = $row->stress;
                $data['anxiety']            = $row->anxiety;
                $data['depression']         = $row->depression;
                $data['tanggal_ditetapkan'] = ($row->tanggal_ditetapkan != '') ? $row->tanggal_ditetapkan : date('Y-m-d');

                $data['hasil_test']         = $this->rekam_model->hasil_test($test_id); 
                $data['opsi_jawaban']       = $this->rekam_model->get_opsijawaban(); 
                $data['kategori_soal']      = $this->rekam_model->kategori_soal();
                $data['therapy']            = $this->rekam_model->therapy();
                

                $data['view']			    = 'rekam/pdf';
                $this->load->view($data['view'], $data);
            }
        }
        else
        {
            redirect(''.site_url('login').'', 'refresh');
        }
    }
}