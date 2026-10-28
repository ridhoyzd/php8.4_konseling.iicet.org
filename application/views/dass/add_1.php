<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>DASS</title>
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
                    <div class="breadcrumb">
                        <nav class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="'.site_url('home').'"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="'.site_url('dass').'">DASS</a></li>
                                <li class="breadcrumb-item active" aria-current="page">New</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                    

                <!-- Main Content Start -->
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <div class="card main-card">               
                <form id="FormDass" action="'.$form_action.'" method="post" class="needs-validation" novalidate>
                '.form_hidden('client_id', $client_id).'
                '.form_hidden('client_no', $client_no).'
                    <div class="card-body"> 
                        <div class="alert alert-success public-link">
                            Link Publik : <a href="'.site_url('s').'?code='.$client_code.'" target="_blank">'.site_url('s').'?code=<code style="font-size:1.2rem;font-weight:700;">'.$client_code.'</code></a>
                        </div>
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
                                            <dt class="col-md-4">Gender</dt>
                                            <dd class="col-md-8">'.$client_gender.'</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="alert alert-info mb-3">
                            <p class="mb-0">Silahkan baca setiap pernyataan dan pilihlah alternatif jawaban yang opsi yang tersedia 
                            pada skala yang mengambarkan keadaan Anda <strong>SEMINGGU YANG LALU</strong>. 
                            Tidak ada jawaban <strong>benar</strong> atau <strong>salah</strong>. Isilah dengan cepat dan cermat.</p>
                        </div>';
                            if($soal->num_rows() > 0)
                            {
                                $no = 1;
                                foreach ($soal->result() as $row)
                                {
                                    echo '
                                    <div class="card card-question mb-3">
                                        <div class="card-body">
                                            <div class="question d-flex"><div class="number">'.$no++.'.</div><div>'.$row->pertanyaan.'</div></div>
                                            <div class="options">';
                                            if($opsi_jawaban->num_rows() > 0)
                                            {
                                                foreach ($opsi_jawaban->result() as $opsi)
                                                {
                                                    echo '
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="soal['.$row->soal_id.']" id="opsi_'.$row->soal_id.$opsi->jawaban_id.'" value="'.$opsi->jawaban_initial.'" required="required">
                                                        <label class="form-check-label" for="opsi_'.$row->soal_id.$opsi->jawaban_id.'">'.$opsi->jawaban_text.'</label>
                                                    </div>';
                                                }
                                            }
                                            echo '
                                            </div>
                                        </div>
                                    </div>';
                                }
                            }
                            echo '
                            <div class="d-grid">
                                <button type="submit" class="btn btn-lg btn-primary"><i class="far fa-paper-plane"></i> Kirim Jawaban</button>
                            </div>
                    </div>
                '.form_close().'
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
        $('#FormDass').submit(function(event){ 
            event.preventDefault();
            if ($('#FormDass')[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            else
            {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Pastikan Anda sudah memberikan jawaban untuk setiap pertanyaan. Kirim jawaban sekarang?",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#57319a',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kirim Sekarang'
                }).then((result) => {
                    if (result.value) {
                        var url  = $('#FormDass').attr('action');
                        var data = new FormData(this); data.append(csfr_token_name, $.cookie(csfr_cookie_name));
                        $.ajax({
                            type: 'post',
                            url: url,
                            data: data,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            mimeType:'multipart/form-data',
                            beforeSend: function() {
                                $('.preloader').show();
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
                            },
                            success: function(response) { 
                                Swal.fire({ type: response.type, html: response.message });
                                if (response.redirect == 1)
                                { 
                                    setTimeout(function() {  window.location.href = ""+response.link+"";}, 2500);
                                    return false;
                                }
                            },
                            complete: function(){
                                $('.preloader').hide();
                                $('#FormDass')[0].reset();
                                $('#FormDass').removeClass('was-validated');
                            }
                        });
                        return false;
                    }
                });
            }
            $('#FormDass').addClass('was-validated');
        });
    </script>
</body>
</html>
