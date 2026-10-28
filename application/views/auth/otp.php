<?php
defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Verification OTP</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet" type="text/css">
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/sweetalert2/sweetalert2.min.css').'" rel="stylesheet">
    <link rel="stylesheet" href="'.base_url('assets/themes/auth/css/login.css').'">
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script> var base_url = "'.base_url().'"; var csfr_token_name = "'.$this->security->get_csrf_token_name().'"; var csfr_cookie_name = "'.$this->config->item('csrf_cookie_name').'";</script>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader" align="center" style="display: nonex;">
        <div class="text">
            <div class="progress">
                <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    <span>Loading ...</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader End -->
    <div id="main-wrapper" class="limpato-login-register theme-primary">
        <div class="container-fluid">
            <div class="row min-vh-100">
                <!-- Welcome Text ======================== -->
                <div class="col-md-6 px-0">
                    <div class="left-wrap d-flex align-items-center h-100">
                        <div class="left-mask opacity-8 bg-primary"></div>
                        <div class="left-bg left-bg-scroll" style="background-image:url(\'../assets/themes/auth/images/login-bg.jpg\');"></div>
                        <div class="left-content mx-auto w-100 h-100 d-flex flex-column">
                            <div class="row no-gutters">
                                <div class="col-10 col-lg-9 mx-auto">
                                    <div class="logo mt-4 mb-5 mb-md-0">
                                        <a class="d-flex" href="'.site_url().'" title="Login Page">
                                            <img src="'.base_url('assets/images/logo/iicet_logo_light.png').'" alt="IICET">
                                            <!-- <span class="logo-text">IICET<b></b></span> -->
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-gutters my-auto">
                                <div class="col-10 col-lg-9 mx-auto">
                                    <h1 class="text-11 text-white mb-4">We care about your account security.</h1>
                                    <p class="text-4 text-white line-height-4 mb-5">We are glad to see you again! Get access to your account.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- Form ======================== -->
                <div class="col-md-6 px-0 d-flex align-items-center">
                    <div class="container my-auto py-5">';
                    $tgl_akses  = strtotime($otp_access);
                    $tgl_otp    = strtotime($otp_expire); 
                    if ($tgl_akses > $tgl_otp)
                    {
                        echo '
                        <div class="row">
                            <div class="col-11 col-lg-9 col-xl-8 mx-auto">
                                <h3 class="font-weight-600 mb-4">Validate OTP</h3>
                                <p class="mb-4"><span class="text-danger">Page not available!</span></p>
                                <p class="pt-4 mb-4">Kembali ke halaman <a class="btn-link" href="'.site_url('login').'">Login</a></p>
                            </div>
                        </div>';
                    }
                    else 
                    {
                        echo '
                        '.form_open($form_action, array('id' => 'AuthForm','autocomplete' => 'off')).'
                            '.form_hidden('email',$email).'
                            '.form_hidden('key',$key).'
                            <div class="row">
                                <div class="col-11 col-lg-9 col-xl-8 mx-auto">
                                    <h3 class="font-weight-600 mb-4">Validate OTP</h3>
                                    '.$this->session->flashdata('login_message').'
                                    <p class="text-muted mb-4">
                                    Please enter the OTP Code to verify your account. The code has been sent to your email.
                                    </p>
                                    <p class="text-muted">Enter your code here: </p>
                                    <div class="form-group"> 
                                        <input type="text" name="otp" class="form-control" id="InputOtp" placeholder="">
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary my-4" type="submit">Verify</button>
                                    </div>
                                    <p class="text-center text-muted mb-0">Back to <a class="btn-link" href="'.site_url('login').'">Login</a></p>
                                </div> 
                            </div>
                        '.form_close().'';
                    }
                    echo '
                    </div>
                </div>
            </div>        
        </div>
    </div> '; 
    ?>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery-3.4.1.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.cookie.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/sweetalert2/sweetalert2.min.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/auth/js/theme.js');?>"></script> 
</body>
</html>