<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>New Client</title>
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
                    <div class="breadcrumb-title">Add New Client</div>
                    <div class="breadcrumb">
                        <nav class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="'.site_url('home').'"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="'.site_url('clients').'">Clients</a></li>
                                <li class="breadcrumb-item active">Add</li>
                            </ol>
                        </nav>
                    </div> 
                </div> 

                <!-- Main Content Start -->
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                '.form_open($form_action, array('id' => 'AjaxForm','autocomplete' => 'off')).'
                <div class="card main-card">
                    <div class="card-body"> 
                        <div class="mb-2 row">
                            <label for="Name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" value="" name="client_name" id="ClientName" class="form-control" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" value="" name="client_email" id="Email" class="form-control" placeholder="mail@domain.com">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Phone" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="text" value="" name="client_phone" id="Phone" class="form-control" placeholder="+628123123123">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label">Gender</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="client_gender" id="GenderMale" value="Male">
                                    <label class="form-check-label" for="GenderMale">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="client_gender" id="GenderFemale" value="Female">
                                    <label class="form-check-label" for="GenderFemale">Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Name" class="col-sm-2 col-form-label">Birthday</label>
                            <div class="col-sm-10 col-md-10 col-lg-6">
                                <div class="row">
                                    <div class="col-4">
                                        <select class="form-select" name="cday">
                                            <option value="">Day</option>';
                                                for($day = 1; $day <= 31; $day++)
                                                {
                                                    echo '<option value="'.$day.'">'.$day.'</option>';
                                                }
                                                echo '
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select class="form-select" name="cmonth">
                                            <option value="">Month</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select class="form-select" name="cyear">
                                            <option value="">Year</option>';
                                                for($year = 1945; $year <= (date('Y') - 10) ; $year++)
                                                {
                                                    echo '<option value="'.$year.'">'.$year.'</option>';
                                                }
                                                echo '
                                        </select>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Name" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea name="client_address" id="ClientAddress" class="form-control" placeholder="Full Address"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-sm-end">
                        <a href="'.site_url('clients').'" class="btn btn-light text-muted me-3">Cancel</a>
                        <button type="submit" class="btn btn-success ps-5 pe-5"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
                '.form_close().'
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
    </script>
</body>
</html>
