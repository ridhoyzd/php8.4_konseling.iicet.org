<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Detail DASS </title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script> var base_url = "'.base_url().'";var csfr_token_name = "'.$this->security->get_csrf_token_name().'"; var csfr_cookie_name = "'.$this->config->item('csrf_cookie_name').'";</script>
    <style>
        @page {
            size: A4 portrait;
        }
        body{
            background: #f5f5f5;
            padding-top:70px;
            font-size: 0.875rem;
        }
        .page{
            margin: 0 auto;
            width: 210mm;
            height: auto;
            padding: 30pt;
            border: 1px solid #f0f0f0;
            background: #ffffff;
            box-shadow: 0 6px 12px -17px rgba(50,50,93,.25),0 7px 6px -8px rgba(0,0,0,.3),0 -6px 16px -6px rgba(0,0,0,.03);
        }
        .opsi_selected {
            font-weight: 600;
            border: 1px solid #198754;
            color: #198754;
            width: 26px;
            display: inline-block;
            height: 26px;
            border-radius: 50%;
            text-align: center;
            vertical-align: middle;
            padding-top:1px;
        }
        /*
        .table{border: 1px solid #000000;}
        .card{border:1px solid #000000;}
        .card-header{border-bottom:1px solid #000000;}
        */
        @media print {
            @page {size: A4 portrait;max-height:100%;max-width:100%;}
            body.view{background:#ffffff;padding-top:0;}
            .navbar{display:none;}
            .page{border:none;box-shadow:none;page-break: avoid;page-break-inside: avoid;}
            .table{border: 1px solid #000000;}
            .card{border:1px solid #000000;}
            .card-header{border-bottom:1px solid #000000;}
            table { page-break-inside:auto }
            tr { page-break-inside:avoid; page-break-after:auto }
        }
    </style>
</head>
<body class="view">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container">
            <div class=me-auto"><a href="javascript:void(0);" data-href="'.site_url('dass_public/detail/'.$test_id.'').'" onclick="history.go(-1)" class="text-light" style="text-decoration:none"><i class="fa fa-arrow-left"></i> Back</a></div>
            <div class="ms-auto">
                <button type="button" class="btn btn-success btn-print ps-5 pe-5" id="BtnPrint" onclick="print_page()"><i class="fa fa-print"></i> Print</button>
            </div>
        </div>
    </nav>
    <div class="page">
        <div class="main-header">
            <div class="mb-3" style="font-size: 1rem;font-weight:600;text-align:center;">INSTRUMEN DASS</div>
        </div>
        <div class="main-card">
            <div class="card mb-3" style="border-radius:0;background:#ffffff;">
                <div class="card-header" style="background:#ffffff;">
                    <h5 class="card-title mb-0" style="font-size:1rem;font-weight:600;">Identitas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="row mb-0">
                                <dt class="col-3">ID</dt>
                                <dd class="col-9">'.$client_id.'</dd>
                            </dl>
                            <dl class="row mb-0">
                                <dt class="col-3">Nama</dt>
                                <dd class="col-9">'.$client_name.'</dd>
                            </dl>
                            <dl class="row mb-0">
                                <dt class="col-3">Telp</dt>
                                <dd class="col-9">'.$client_phone.'</dd>
                            </dl>
                            <dl class="row mb-0">
                                <dt class="col-3">Jenis Kelamin</dt>
                                <dd class="col-9">'.jenis_kelamin($client_gender).'</dd>
                            </dl>
                            <dl class="row mb-0">
                                <dt class="col-3">Umur</dt>
                                <dd class="col-9">'; echo ($client_age) ? $client_age.' Tahun' : '&nbsp;'; echo '</dd>
                            </dl>
                            <dl class="row mb-0">
                                <dt class="col-3">Tgl. Mengisi</dt>
                                <dd class="col-9">'.indo_date(substr($tanggal_mengisi,0,10)).' . '.substr($tanggal_mengisi,10,6).' WIB</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
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
                        if($this->dass_public_model->dass_by_group($test_id, $kategori->soal_kategori_id)->num_rows() > 0)
                        {
                            echo '
                            <tr>
                                <td>Soalan</td>';
                                foreach ($this->dass_public_model->soal_by_kategori($kategori->soal_kategori_id)->result() as $kat_row)
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
                                foreach ($this->dass_public_model->dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
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
        <!-- Main Content End -->
	</div>
    ';?>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery-3.4.1.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.cookie.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
    <script>
        function print_page() { window.print(); }
    </script>
</body>
</html>
