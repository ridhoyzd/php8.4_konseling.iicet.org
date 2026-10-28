<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Edit Client</title>
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
                    <div class="breadcrumb-title">Clients</div>
                    <div class="breadcrumb">
                        <nav class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="'.site_url('home').'"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="'.site_url('clients').'">Clients</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </nav>
                    </div> 
                </div> 

                <!-- Main Content Start -->
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                '.form_open($form_action, array('id' => 'AjaxForm','autocomplete' => 'off')).'
                '.form_hidden('id',$id).'
                <div class="card main-card">
                    <div class="card-body">
                        <div class="mb-2 row">
                            <label for="Name" class="col-sm-2 col-form-label">Client ID</label>
                            <div class="col-sm-10">
                                <input type="text" value="'.$client_no.'" name="client_no" id="ClientName" class="form-control-plaintext" readonly placeholder="Client ID">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" value="'.$client_name.'" name="client_name" id="ClientName" class="form-control" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" value="'.$client_email.'" name="client_email" id="Email" class="form-control" placeholder="mail@domain.com">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Phone" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="text" value="'.$client_phone.'" name="client_phone" id="Phone" class="form-control" placeholder="+628123123123">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-sm-2 col-form-label">Gender</label>
                            <div class="col-sm-10">';
                                    $gender_val = isset($client_gender) ? $client_gender : set_value('client_gender');
                                    $extra = array('class' => 'form-check-input');
                                    if ($gender_val == 'Male')
                                    {
                                        
                                        echo '
                                        <div class="form-check form-check-inline">
                                            '.form_radio('client_gender', 'Male', TRUE, array('id' => 'GenderMale','class' => 'form-check-input')).'
                                            <label class="form-check-label" for="GenderMale">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            '.form_radio('client_gender', 'Female', FALSE, array('id' => 'GenderFemale','class' => 'form-check-input')).' 
                                            <label class="form-check-label" for="GenderFemale">Female</label>
                                        </div>';
                                    }
                                    else if ($gender_val == 'Female')
                                    {
                                        echo '
                                        <div class="form-check form-check-inline">
                                            '.form_radio('client_gender', 'Male', FALSE, array('id' => 'GenderMale','class' => 'form-check-input')).'
                                            <label class="form-check-label" for="GenderMale">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            '.form_radio('client_gender', 'Female', TRUE, array('id' => 'GenderFemale','class' => 'form-check-input')).'
                                            <label class="form-check-label" for="GenderFemale">Female</label>
                                        </div>';
                                    }
                                    else
                                    {
                                        echo '
                                        <div class="form-check form-check-inline">
                                            '.form_radio('client_gender', 'Male', FALSE, array('id' => 'GenderMale','class' => 'form-check-input')).'
                                            <label class="form-check-label" for="GenderMale">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            '.form_radio('client_gender', 'Female', FALSE, array('id' => 'GenderFemale','class' => 'form-check-input')).'
                                            <label class="form-check-label" for="GenderFemale">Female</label>
                                        </div>';
                                    }
                            echo '
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Name" class="col-sm-2 col-form-label">Birthday</label>
                            <div class="col-sm-10 col-md-10 col-lg-6">
                                <div class="row">
                                    <div class="col-4">';
                                        $a_val	= isset($cday) ? $cday : '';
                                        $a_opt	= array(''=> 'Day');
                                        $a_att = array('class' => 'form-select', 'id' => 'cDay',  'required' => 'required');
                                        for($day = 1; $day <= 31; $day++)
                                        {
                                            $a_opt[$day] = $day;
                                        } 
                                        echo form_dropdown('cday', $a_opt, $a_val, $a_att) . '
                                    </div>
                                    <div class="col-4">';
                                        $b_opt = array( '' => 'Month',
                                                        '01' => 'January',
                                                        '02' => 'February',
                                                        '03' => 'March',
                                                        '04' => 'April',
                                                        '05' => 'May',
                                                        '06' => 'June',
                                                        '07' => 'July',
                                                        '08' => 'August',
                                                        '09' => 'September',
                                                        '10' => 'October',
                                                        '11' => 'November',
                                                        '12' => 'December',
                                                    );
                                        $b_att = array('class' => 'form-select', 'id' => 'cMonth', 'required' => 'required');
                                        $b_val = isset($cmonth) ? $cmonth : '';
                                        echo form_dropdown('cmonth', $b_opt, $b_val, $b_att) . '
                                    </div>
                                    <div class="col-4">';
                                        $c_val	= isset($cyear) ? $cyear : '';
                                        $c_opt	= array(''=> 'Year');
                                        $c_att = array('class' => 'form-select', 'id' => 'cYear',  'required' => 'required');
                                        for($year = 1945; $year <= (date('Y') - 10) ; $year++)
                                        {
                                            $c_opt[$year] = $year;
                                        } 
                                        echo form_dropdown('cyear', $c_opt, $c_val, $c_att) . '
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Name" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea name="client_address" id="ClientAddress" class="form-control" placeholder="Full Address">'.$client_address.'</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-sm-end">
                        <a href="'.site_url('clients').'" class="btn btn-light text-muted me-3">Cancel</a>
                        <button type="submit" class="btn btn-success ps-5 pe-5"><i class="fa fa-save"></i> Update</button>
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
