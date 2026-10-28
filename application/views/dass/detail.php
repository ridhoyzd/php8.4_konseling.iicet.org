<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Detail DASS </title>
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
    <div class="main-wrapper">';

        $this->load->view('_sidebar');
        
        echo '		
        <div class="page-wrapper">';

            $this->load->view('_header');  echo '

            <div class="page-content">
                <div class="app-page-title2">
                    <div class="breadcrumb-title">Instrumen DASS</div>
                </div>

                <!-- Main Content Start -->
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <div class="card main-card">
                    <div class="card-body">
                        <div class="card mb-3">
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
                                            <dt class="col-md-4">Telp</dt>
                                            <dd class="col-md-8">'.$client_phone.'</dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Email</dt>
                                            <dd class="col-md-8">'.$client_email.'</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Tgl. Lahir</dt>
                                            <dd class="col-md-8">'.indo_date($client_birthday).' <span class="text-secondary">('.umur_saat_dass($client_birthday,date('Y-m-d')).' Tahun)</span></dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Jenis Kelamin</dt>
                                            <dd class="col-md-8">'.jenis_kelamin($client_gender).'</dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-md-4">Tgl. Mengisi</dt>
                                            <dd class="col-md-8">'.indo_date(substr($tanggal_mengisi,0,10)).' . '.substr($tanggal_mengisi,10,6).' WIB</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-3">
                                <thead>
                                    <tr class="bg-thead">
                                        <th>No</th>
                                        <th>Pertanyaan</th>
                                        <th class="text-center" width="33">Tidak Pernah</th>
                                        <th class="text-center" width="33">Jarang</th>
                                        <th class="text-center" width="33">Sering</th>
                                        <th class="text-center" width="33">Sangat Sering</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                if($hasil_test->num_rows() > 0)
                                {
                                    $no = 1;
                                    foreach ($hasil_test->result() as $row)
                                    {
                                        echo '
                                        <tr>
                                            <td>'.$no++.'</td>
                                            <td>'.$row->pertanyaan.'</td>';
                                            if($opsi_jawaban->num_rows() > 0)
                                            {
                                                foreach ($opsi_jawaban->result() as $opsi)
                                                {
                                                    if($row->jawaban == $opsi->jawaban_initial)
                                                    {
                                                        echo '<td class="text-center"><span class="opsi_selected"><strong class="text-success">'.$opsi->jawaban_initial.'</strong></span></td>';
                                                    }
                                                    else
                                                    {
                                                        echo '<td class="text-center"><span class="opsi_unselected text-secondary">'.$opsi->jawaban_initial.'</span></td>';
                                                    }
                                                }
                                            }
                                            echo '
                                        </tr>';
                                    }
                                }

                                echo '
                                </tbody>
                            </table>
                        </div>';
                        // kategori
                        if($kategori_soal->num_rows() > 0)
                        {
                            echo '
                            <div class="table-responsive">
                                <table class="table card-table table-bordered mb-3">';
                                foreach ($kategori_soal->result() as $kategori)
                                {
                                    echo '
                                    <tr class="bg-thead">
                                        <td colspan="10" class="text-center"><strong>'.$kategori->kategori_nama.'</strong></td>
                                    </tr>';
                                    if($this->dass_model->dass_by_group($test_id, $kategori->soal_kategori_id)->num_rows() > 0)
                                    {
                                        echo '
                                        <tr>
                                            <td>Soalan</td>';
                                            foreach ($this->dass_model->soal_by_kategori($kategori->soal_kategori_id)->result() as $kat_row)
                                            {
                                                echo '<td><strong>S'.$kat_row->soal_id.'</strong></td>';
                                            }
                                            echo '
                                            <td><strong>Jumlah</strong></td>
                                            <td><strong>Kategori</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Nilai</td>';
                                            $jumlah = 0; 
                                            foreach ($this->dass_model->dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
                                            {
                                                
                                                echo '
                                                <td>'.$nilai->jawaban.'</td>'; 
                                                $jumlah = $jumlah + $nilai->jawaban;
                                            }
                                            echo '
                                            <td>'.$jumlah.'</td>
                                            <td>
                                                <strong class="text-primary">'.dass_result($kategori->soal_kategori_id, $jumlah).'</strong>
                                                <input type="hidden" class="form-control" name="point_'.$kategori->soal_kategori_id.'" value="'.$jumlah.'" readonly="readonly">
                                                <input type="hidden" class="form-control" name="dass_'.$kategori->soal_kategori_id.'" value="'.dass_result($kategori->soal_kategori_id, $jumlah).'" readonly="readonly">
                                            </td>
                                        </tr>';
                                    }
                                }
                                echo '
                                </table>
                            </div>';
                        }
                        /*
                        echo '<p>Keputusan Stress : '.keputusan_dass($test_id,1) .'</p>';
                        echo '<p>Keputusan Anxiety : '.keputusan_dass($test_id,2) .'</p>';
                        echo '<p>Keputusan Depression : '.keputusan_dass($test_id,3) .'</p>';
                        */
                        echo '
                        <!-- -------------------- -->
                        <table class="table table-bordered mt-3 d-nonex">
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
                <!-- Main Content End -->
                
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
