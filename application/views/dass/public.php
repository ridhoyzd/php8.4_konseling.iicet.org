<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Instrumen DASS</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet"> 
    <link href="'.base_url('assets/plugins/sweetalert2/sweetalert2.min.css').'" rel="stylesheet">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/dass.css').'"> 
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script> var base_url = "'.base_url().'";var csfr_token_name = "'.$this->security->get_csrf_token_name().'"; var csfr_cookie_name = "'.$this->config->item('csrf_cookie_name').'";</script>
</head>
<body>
    <div class="main-wrapper">
        <div class="page-content"> 
            <!-- Main Content Start -->
            <div id="guhpyh"></div>
            <form id="FormDass" action="'.$form_action.'" autocomplete="off" method="post" class="needs-validation" novalidate>
                '.form_hidden('client_id', $client_id).'
                '.form_hidden('client_no', $client_no).'
                <div class="card-body"> 
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Identitas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row mb-0">
                                        <dt class="col-md-4 col-sm-4 col-1 g-0"><span class="icon-label"><i class="fas fa-sort-numeric-down"></i></span><span class="title-label">ID</span></dt>
                                        <dd class="col-md-8 col-sm-8 col-11">'.$client_no.'</dd>
                                    </dl>
                                    <dl class="row mb-0">
                                        <dt class="col-md-4 col-sm-4 col-1 g-0"><span class="icon-label"><i class="fa fa-user"></i></span><span class="title-label">Nama</span></dt>
                                        <dd class="col-md-8 col-sm-8 col-11">'.$client_name.'</dd>
                                    </dl>
                                    <dl class="row mb-0">
                                        <dt class="col-md-4 col-sm-4 col-1 g-0"></span><span class="icon-label"><i class="fa fa-phone"></i></span><span class="title-label">Telepon</span></dt>
                                        <dd class="col-md-8 col-sm-8 col-11">'.$client_phone.'</dd>
                                    </dl>
                                    <dl class="row mb-0">
                                        <dt class="col-md-4 col-sm-4 col-1 g-0"></span><span class="icon-label"><i class="far fa-envelope"></i></span><span class="title-label">Email</span></dt>
                                        <dd class="col-md-8 col-sm-8 col-11">'.$client_email.'</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row mb-0">
                                        <dt class="col-md-4 col-sm-4 col-1 g-0"></span><span class="icon-label"><i class="far fa-calendar-alt"></i></span><span class="title-label">Tgl. Lahir</span></dt>
                                        <dd class="col-md-8 col-sm-8 col-11">'.indo_date($client_birthday).' <span class="text-secondary">('.umur_saat_dass($client_birthday,date('Y-m-d')).' Tahun)</span></dd>
                                    </dl>
                                    <dl class="row mb-0">
                                        <dt class="col-md-4 col-sm-4 col-1 g-0"></span><span class="icon-label">'; echo ($client_gender == 'Male') ? '<i class="fas fa-male"></i>': '<i class="fas fa-female"></i>'; echo '</span><span class="title-label">Gender</span></dt>
                                        <dd class="col-md-8 col-sm-8 col-11">'.jenis_kelamin($client_gender).'</dd>
                                    </dl>
                                    <dl class="row mb-0">
                                        <dt class="col-md-4 col-sm-4 col-1 g-0"></span><span class="icon-label"><i class="fas fa-map-marker-alt"></i></span><span class="title-label">Alamat</span></dt>
                                        <dd class="col-md-8 col-sm-8 col-11">'.$client_address.'</dd>
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
                                <div class="card card-question card-question-public mb-3">
                                    <div class="card-body">
                                        <div class="question d-flex"><div class="number">'.$no++.'.</div><div>'.$row->pertanyaan.'</div></div>
                                        <div class="options">';
                                        if($opsi_jawaban->num_rows() > 0)
                                        {
                                            foreach ($opsi_jawaban->result() as $opsi)
                                            {
                                                echo '
                                                <div class="form-check mb-3">
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
            </form>
            <!-- Main Content End -->
            
        </div>
	</div>
    ';?>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery-3.4.1.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.cookie.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/sweetalert2/sweetalert2.min.js');?>"></script> 
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
                                if(response.status == 1)
                                { 
                                    $('#FormDass').remove();
                                    $('#guhpyh').html(response.message);
                                }
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
