<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Rekam Psikologis '.$client_name.' </title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/sweetalert2/sweetalert2.min.css').'" rel="stylesheet">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/app.css').'">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/responsive.css').'">
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script> var base_url = "'.base_url().'";var csfr_token_name = "'.$this->security->get_csrf_token_name().'"; var csfr_cookie_name = "'.$this->config->item('csrf_cookie_name').'";</script>
</head>
<body>
	<script src="'.base_url('assets/themes/admin/js/spinner.js').'"></script>
    <div class="main-wrapper">';

        $this->load->view('_sidebar');
        
        echo '		
        <div class="page-wrapper">';

            $this->load->view('_header');  echo '

            <div class="page-content rpk">
                <div class="app-page-title2">
                    <div class="breadcrumb-title">&nbsp;</div>
                    <div class="ms-auto top-action">
                        <a href="'.site_url('rekam/client/'.$client_id.'').'"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                    </div>
                </div>

                <!-- Main Content Start -->
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                '.form_open($form_action, array('id' => 'AjaxForm','autocomplete' => 'off')).'
                '.form_hidden('test_id', $test_id).'
                '.form_hidden('client_id', $client_id).'
                <div class="card main-card">
                    <div class="card-body">
                        <div class="rpk-cop">
                            <h5 class="sub-title mb-0">REKAM PSIKOLOGIS KLIEN (RPK)</h5>
                            <div class="rahasia">RAHASIA</div>
                            <div class="sesi">
                                <div class="input-group" style="width:104px;">
                                    <span class="input-group-text rounded-0" id="sesi-addon">Sesi</span>
                                    <input type="text" name="sesi" class="form-control rounded-0" value="'; echo ($sesi !='') ? $sesi : abs((int) $this->input->get('sesi')); echo '" placeholder="Sesi" aria-label="Sesi" aria-describedby="sesi-addon">
                                </div>
                            </div>
                        </div>';
                        if($therapy->num_rows() > 0 && $tindak_lanjut != '') 
                        {
                            echo '
                            <div class="text-start mb-3">
                                <a href="'.site_url('rekam/pdf/'.$client_id.'/'.$test_id.'?file_type=pdf').'" target="_blank" class="btn btn-lg btn-danger ps-5 pe-5"><i class="fa fa-file-pdf"></i> Print PDF</a>
                            </div>';
                        }
                        echo '
                        <div class="card card-rpk mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Identitas</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">ID</dt>
                                            <dd class="col-md-8">'.$client_no.'</dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Nama</dt>
                                            <dd class="col-md-8">'.$client_name.'</dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Nomor HP</dt>
                                            <dd class="col-md-8">'.$client_phone.'</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Email</dt>
                                            <dd class="col-md-8">'.$client_email.'</dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Tgl. Lahir</dt>
                                            <dd class="col-md-8">'.indo_date($client_birthday).' <span class="text-secondary">('.umur_saat_dass($client_birthday,date('Y-m-d')).' Tahun)</span></dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Jenis Kelamin</dt>
                                            <dd class="col-md-8">'.jenis_kelamin($client_gender).'</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-rpk mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Kondisi Kesehatan Mental Berdasarkan DASS</h5>
                            </div>
                            <div class="card-bodies">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">';
                                        if($kategori_soal->num_rows() > 0)
                                        {
                                            echo '
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th width="80">Skor</th>
                                                    <th width="150">Kategori</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                                foreach ($kategori_soal->result() as $kategori)
                                                {
                                                    echo '
                                                    <tr>
                                                        <td>'.$kategori->kategori_nama.'</td>';
                                                        $jumlah = 0; 
                                                        foreach ($this->rekam_model->dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
                                                        {
                                                            $jumlah = $jumlah + $nilai->jawaban;
                                                        }
                                                        echo '
                                                        <td>
                                                            <span>'.$jumlah.'</span>
                                                            <input type="hidden" class="form-control" name="point_'.$kategori->soal_kategori_id.'" value="'.$jumlah.'" readonly="readonly"></td>
                                                        <td>
                                                            <span>'.dass_result($kategori->soal_kategori_id, $jumlah).'</span>
                                                            <input type="hidden" class="form-control" name="dass_'.$kategori->soal_kategori_id.'" value="'.dass_result($kategori->soal_kategori_id, $jumlah).'" readonly="readonly">
                                                        </td>
                                                    </tr>';
                                                }
                                                echo '
                                            </tbody>';
                                        }
                                        echo '
                                    </table>
                                </div>
                            </div>
                        </div>';
                        /*
                        echo '<p>Keputusan Stress : '.keputusan_dass($test_id,1) .'</p>';
                        echo '<p>Keputusan Anxiety : '.keputusan_dass($test_id,2) .'</p>';
                        echo '<p>Keputusan Depression : '.keputusan_dass($test_id,3) .'</p>';
                        */

                        echo '
                        <div class="card card-rpk mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Keterangan</h5>
                            </div>
                            <div class="card-bodies">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr class="bg-thead"><td colspan="6" class="text-center"><strong>SKOR</strong></td></tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td class="text-center">Normal</td>
                                                <td class="text-center">Ringan</td>
                                                <td class="text-center">Sederhana</td>
                                                <td class="text-center">Berat</td>
                                                <td class="text-center">Sangat Berat</td>
                                            </tr>
                                            <tr>
                                                <td>Stress</td>
                                                <td class="text-center">0-7</td>
                                                <td class="text-center">8-9</td>
                                                <td class="text-center">10-13</td>
                                                <td class="text-center">14-17</td>
                                                <td class="text-center">18+</td>
                                            </tr>
                                            <tr>
                                                <td>Anxiety</td>
                                                <td class="text-center">0-4</td>
                                                <td class="text-center">5-6</td>
                                                <td class="text-center">7-8</td>
                                                <td class="text-center">9-10</td>
                                                <td class="text-center">11+</td>
                                            </tr>
                                            <tr>
                                                <td>Depression</td>
                                                <td class="text-center">0-5</td>
                                                <td class="text-center">6-7</td>
                                                <td class="text-center">8-10</td>
                                                <td class="text-center">11-14</td>
                                                <td class="text-center">15+</td>
                                            </tr>
                                            <tr>
                                                <td>INTEREPTASI</td>
                                                <td class="text-center">Kehidupan<br>yang bahagia</td>
                                                <td class="text-center">Sehat</td>
                                                <td class="text-center">Perlu<br>pengelolaan</td>
                                                <td class="text-center">Temui Konselor</td>
                                                <td class="text-center">Temui Konselor &amp; Rujuk<br>psikiater jika perlu</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        
                        <div class="card card-rpk mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Deskripsi Kondisi Psikologis</h5>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control borderless autosize" rows="3" name="deskripsi_masalah" id="deskripsi_masalah" placeholder="Deskripsi Kondisi Psikologis ...">'.$deskripsi_masalah.'</textarea>
                            </div>
                        </div>
                        <div class="card card-rpk mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Intervensi dan Treatment</h5>
                            </div>
                            <div class="card-body">';
                                if($therapy->num_rows() > 0)
                                {
                                    $nt = 1;
                                    echo '<div class="row g-2">';
                                    foreach ($therapy->result() as $trow)
                                    {
                                        $test_therapy = $this->rekam_model->test_therapy($test_id, $trow->therapy_id);
                                        if($test_therapy->num_rows() == 1)
                                        {
                                            $checked = ' checked="checked" ';
                                        }
                                        else
                                        {
                                            $checked = '';
                                        }
                                        
                                        echo '
                                        <div class="col-12 col-sm-6">
                                            <div class="inputGroup pt-0 pb-0">
                                                <input name="therapy_id[]" id="therapy_id-'.$nt.'" onclick="return selectFill(\''.$trow->therapy_id.'\',\''.$trow->therapy_price.'\');" value="'.$trow->therapy_id.'" type="checkbox" '.$checked.'/>
                                                <label for="therapy_id-'.$nt.'">'.$trow->therapy_name.'</label>
                                            </div>
                                        </div>';
                                        /*
                                        echo '
                                        <div class="col-12 col-sm-6">
                                            <div class="inputGroup pt-0 pb-0">
                                                <input name="therapy_id" id="therapy_id-'.$nt.'" name="therapy_id" onclick="return selectFill(\''.$trow->therapy_id.'\',\''.$trow->therapy_price.'\');" value="'.$trow->therapy_id.'" type="checkbox"'; echo($trow->therapy_id == $therapy_id) ? ' checked="checked" ' : ''; echo '/>
                                                <label for="therapy_id-'.$nt.'">'.$trow->therapy_name.'</label>
                                            </div>
                                        </div>';
                                        */
                                        $nt++;
                                    }
                                    echo '</div>';
                                }
                                echo '
                            </div>
                        </div>
                        <div class="card card-rpk mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Tindak Lanjut</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tindak_lanjut" type="radio" id="tindak_lanjut1" value="1"'; echo ($tindak_lanjut == 1) ? ' checked="checked" ' : ''; echo '>
                                    <label class="form-check-label" for="tindak_lanjut1">Sesi dilanjutkan</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tindak_lanjut" type="radio" id="tindak_lanjut2" value="2"'; echo ($tindak_lanjut == 2) ? ' checked="checked" ' : ''; echo '>
                                    <label class="form-check-label" for="tindak_lanjut2">Sesi Selesai</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tindak_lanjut" type="radio" id="tindak_lanjut3" value="3"'; echo ($tindak_lanjut == 3) ? ' checked="checked" ' : ''; echo '>
                                    <label class="form-check-label" for="tindak_lanjut3">Diteruskan ke Ahli Lain</label>
                                </div>
                            </div>
                        </div>
                        <div class="card card-rpk mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Konselor / Terapis</h5>
                            </div>
                            <div class="card-body">
                                <input type="text" name="konselor" value="'.$konselor.'" class="form-control d-none" placeholder="Nama Konselor/Terapis">';
                                
                                // <input type="text" name="konselor" value="'.$konselor.'" class="form-control" placeholder="Nama Konselor/Terapis">

                                $v_val = isset($counselor_id) ? $counselor_id : '';
                                $v_opt = array('' => '--- Pilih Konselor / Terapis ---');
                                $v_att = array('class' => 'form-select', 'id' => 'konselor', 'required' => 'required');
                                $v_dat = $counselors->result();
                                if ($counselors->num_rows() > 0)
                                {
                                    foreach ($v_dat as $v_row)
                                    {
                                        $v_opt[$v_row->counselor_id] = ''.$v_row->counselor_name.'';
                                    }
                                }
                                echo form_dropdown('counselor_id', $v_opt, $v_val, $v_att).'
                            </div>
                        </div>
                        <div class="text-muted d-none">Rekam psikologis ini digunakan untuk kepentingan perekaman data dan untuk keperluan dilakukan tindak lanjut.</div>
                        <div class="row">
                            <div class="col-6">';
                                if($therapy->num_rows() > 0 && $tindak_lanjut != '') 
                                {
                                    echo '
                                        <div class="text-start">
                                            <a href="'.site_url('rekam/pdf/'.$client_id.'/'.$test_id.'?file_type=pdf').'" target="_blank" class="btn btn-lg btn-danger ps-5 pe-5"><i class="fa fa-file-pdf"></i> Print PDF</a>
                                        </div>';
                                }
                                echo '
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <a href="'.site_url('rekam/client/'.$client_id.'').'" class="text-muted me-4"> Batal </a>
                                    <button type="submit" class="btn btn-lg btn-primary ps-5 pe-5"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                '.form_close().'
                <!-- Main Content End -->';
                
                

                echo '
            </div>';

            $this->load->view('_footer');

            echo '
		</div>
	</div>
    ';?>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery-3.4.1.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.cookie.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.jquery.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/sweetalert2/sweetalert2.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/textarea/autosize.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/admin/js/theme.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/admin/js/script.js');?>"></script>
    <script>
        $(function() {
            LimpatoEditor.InitFormSubmit();
        });
        function selectFill(id,price){
            if(id !='' && price != '')
            {
                $("#Price").val(price);
            }
        }
    </script>
</body>
</html>
