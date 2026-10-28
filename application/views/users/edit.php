<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Edit Admin</title>
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
                    <div class="breadcrumb-title">Users</div>
                    <div class="breadcrumb">
                        <nav class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="'.site_url('home').'"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="'.site_url('users').'">Users</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </nav>
                    </div> 
                </div> 

                <!-- Main Content Start -->
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                '.form_open($form_action, array('id' => 'AjaxForm','autocomplete' => 'off')).'
                '.form_hidden('id',$user_id).'
                <div class="card main-card">
                    <div class="card-body">
                        <div class="mb-2 row">
                            <label for="Name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" value="'.$user_fullname.'" name="user_fullname" id="Name" class="form-control" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" value="'.$user_email.'" name="user_email" id="Email" class="form-control" placeholder="mail@domain.com">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Phone" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="text" value="'.$user_phone.'" name="user_phone" id="Phone" class="form-control" placeholder="+628123123123">
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="Status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-3">';
                                $a_opt = array( '1' => 'Active',
                                                '0' => 'Inactive');
                                $a_att = array('class' => 'form-select', 'id' => 'Status', 'required' => 'required');
                                $a_val = isset($user_active) ? $user_active : '';
                                echo ''.form_dropdown('user_active', $a_opt, $a_val, $a_att).'
                            </div>
                        </div>
                        <div class="mb-1 row">
                            <label class="col-sm-2 col-form-label">Registered Date</label>
                            <div class="col-sm-10">
                                <input type="text" readonly value="'.indo_date(substr($user_registered,0,10)).' @'.substr($user_registered,11,5).' WIB" class="form-control-plaintext text-muted">
                            </div>
                        </div>
                        <div class="mb-1 row">
                            <label class="col-sm-2 col-form-label">Registered Via</label>
                            <div class="col-sm-10">
                                <input type="text" readonly value="'.ucwords($user_registered_type).'" class="form-control-plaintext text-muted">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-sm-end">
                        <a href="'.site_url('users').'" class="btn btn-light text-muted me-3">Cancel</a>
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
