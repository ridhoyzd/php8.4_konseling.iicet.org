<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Rekam Psikologis '.$client_name.'</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/sweetalert2/sweetalert2.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/themes/admin/css/app.css').'" rel="stylesheet">
    <link href="'.base_url('assets/themes/admin/css/responsive.css').'" rel="stylesheet">
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script>
        var base_url = "'.base_url().'";
        var csfr_token_name = "'.$this->security->get_csrf_token_name().'";
        var csfr_cookie_name = "'.$this->config->item('csrf_cookie_name').'";
    </script>
</head>
<body>
	<script src="'.base_url('assets/themes/admin/js/spinner.js').'"></script>
    <div class="main-wrapper">';

        $this->load->view('_sidebar');
        
        echo '		
        <div class="page-wrapper">';

            $this->load->view('_header');  echo '

            <div class="page-content">
                <div class="app-page-title2 d-flex align-items-center">
                    <div class="breadcrumb-title">Rekam Psikologis Klien</div>
                    <div class="ms-auto top-action">
                        <a href="'.site_url('rekam').'"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                    </div>
                </div>  

                <!-- Main Content Start --> 
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <div class="card main-card">
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-2">ID</dt>
                            <dd class="col-sm-10">'.$client_no.'</dd>
                        </dl>
                        <dl class="row mb-0">
                            <dt class="col-sm-2">Nama</dt>
                            <dd class="col-sm-10">'.$client_name.'</dd>
                        </dl>
                        <dl class="row mb-0">
                            <dt class="col-sm-2">Telp</dt>
                            <dd class="col-sm-10">'.$client_phone.'</dd>
                        </dl>
                        <dl class="row mb-0">
                            <dt class="col-sm-2">Email</dt>
                            <dd class="col-sm-10">'.$client_email.'</dd>
                        </dl>
                        <dl class="row mb-0">
                            <dt class="col-sm-2">Umur</dt>
                            <dd class="col-sm-10">'.umur_saat_dass($client_birthday,date('Y-m-d')).' Tahun</dd>
                        </dl>
                        <div class="table-responsive">
                            <table id="Table" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="45">Sesi</th>
                                        <th width="140">Tanggal Mengisi</th>
                                        <th width="115">Stress</th>
                                        <th width="115">Anxiety</th>
                                        <th width="115">Depression</th>
                                        <th>Jenis Terapi</th>
                                        <th width="160">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                if($rekam->num_rows() > 0)
                                {
                                    $no = 1;
                                    foreach ($rekam->result() as $row)
                                    {
                                        $sesi = ($row->sesi == '') ? $no : $row->sesi;
                                        echo '
                                        <tr>
                                            <td>'.$sesi.'</td>
                                            <td><div class="cursor-pointer" title="'.indo_date(substr($row->tanggal_mengisi,0,10)).' . '.substr($row->tanggal_mengisi,10,6).' WIB">'.indo_date(substr($row->tanggal_mengisi,0,10)).'<div><div class="text-muted"><small>'.substr($row->tanggal_mengisi,10,6).' WIB</small></div></td>
                                            <td>'.$row->stress.'</td>
                                            <td>'.$row->anxiety.'</td>
                                            <td>'.$row->depression.'</td>
                                            <td>';
                                                if ($this->rekam_model->therapy_by_test($row->test_id)->num_rows() > 0)
                                                { 
                                                    $n = 1;
                                                    foreach($this->rekam_model->therapy_by_test($row->test_id)->result() as $tbt )
                                                    {
                                                        echo $tbt->therapy_name;
                                                        if ($n < $this->rekam_model->therapy_by_test($row->test_id)->num_rows())
                                                        {
                                                            echo ', ';
                                                        }
                                                        $n++;
                                                    }
                                                }
                                                echo '
                                            </td>
                                            <td>
                                                <div class="d-gridx">
                                                    <button type="button" class="btn btn-sm btn-primary mb-1 me-1" data-bs-toggle="tooltip" onclick="return view_dass('.$row->test_id.');" title="Hasil DASS"><i class="far fa-file-alt"></i> DASS</button>
                                                    <a href="'.site_url('rekam/detail/'.$row->client_id.'/'.$row->test_id.'?sesi='.$sesi.'').'" class="btn btn-sm btn-success mb-1"><i class="far fa-file-alt"></i> Detail</a>
                                                </div>
                                            </td>
                                        </tr>
                                        ';
                                        $no++;
                                    }
                                }
                                echo '
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Main Content End --> 
            </div>';

            $this->load->view('_footer');

            echo '
		</div>
	</div>
    <div class="modal fade" id="AjaxViewModal" tabindex="-1" aria-labelledby="AjaxViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-data modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">DASS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
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
        /* ----------------------
        Ajax View DASS
        ------------------------*/
        function view_dass(id)
        {
            if(id != '')
            {
                var AjaxViewModal = new bootstrap.Modal(document.getElementById('AjaxViewModal'), { keyboard: true,backdrop:'static',focus:true });
                var data = { 'id': id}; data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    type: 'post',
                    url: base_url + 'rekam/ajax_dass',
                    data: data,
                    dataType: 'html',
                    beforeSend: function() {
                        //$('.preloader').show();
                        $('#AjaxViewModal .modal-body').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div>');
                        AjaxViewModal.show();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        Swal.fire({ icon: 'error', title: xhr.statusText, html: xhr.responseText });
                    },
                    success: function(response) {
                        $('#AjaxViewModal .modal-body').html(response); 
                    },
                    complete: function(){
                        $('.preloader').hide();
                    }
                });
                return false;
            }
        }
    </script>
</body>
</html>
