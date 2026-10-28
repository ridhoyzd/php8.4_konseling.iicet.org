<?php
defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>IICET</title>
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
                        <div class="left-bg left-bg-scroll" style="background-image:url(\'./assets/themes/auth/images/login-bg.jpg\');"></div>
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
                                    <h1 class="text-11 text-white mb-4">Selamat Datang</h1>
                                    <p class="text-4 text-white line-height-4 mb-5">Silahkan masuk menggunakan akun yang sudah terdaftar atau masukkan kode untuk mengaksess Instrumen Dass secara cepat.</p>
                                    <form method="get" action="'.site_url('s').'">
                                        <div class="input-group mb-3">
                                            <input type="text" name="code" id="DassCode" class="form-control" placeholder="Masukkan Kode" required="required" >
                                            <button class="btn btn-primary" type="submit" id="BtnDass1">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- Form ======================== -->
                <div class="col-md-6 px-0 d-flex align-items-center">
                    <div class="container my-auto py-5">
                    '.form_open($form_action, array('id' => 'AuthForm','autocomplete' => 'off')).'
                        <div class="row">
                            <div class="col-11 col-lg-9 col-xl-8 mx-auto">
                                <h3 class="font-weight-600 mb-4">Log In</h3>';
                                echo $this->session->flashdata('login_message');
                                echo '
                                <div class="mb-3">
                                    <label for="InputUsername" class="form-label">Login</label>
                                    <input type="text" name="username" class="form-control" id="InputUsername" placeholder="Email Address">
                                </div>
                                <div class="mb-3">
                                    <label for="InputPassword" class="form-label">Password</label>
                                    <div class="pwdMask mb-3">
                                        <input type="password" name="password" id="InputPassword" class="form-control" placeholder="Enter Password">
                                        <div class="input-group-append cursor-pointer">
                                            <span class="pwd-toggle">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16">
                                                    <g transform="translate(0 0)">
                                                        <g transform="translate(0 0)">
                                                            <path d="M23.609,117.568a15.656,15.656,0,0,0-5.045-4.859,12.823,12.823,0,0,0-6.38-1.811c-.062,0-.309,0-.371,0a12.823,12.823,0,0,0-6.38,1.811,15.656,15.656,0,0,0-5.045,4.859,2.464,2.464,0,0,0,0,2.658,15.656,15.656,0,0,0,5.045,4.859,12.822,12.822,0,0,0,6.38,1.811c.062,0,.309,0,.371,0a12.823,12.823,0,0,0,6.38-1.811,15.656,15.656,0,0,0,5.045-4.859A2.464,2.464,0,0,0,23.609,117.568Zm-17.74,6.489a14.622,14.622,0,0,1-4.712-4.538,1.155,1.155,0,0,1,0-1.245,14.621,14.621,0,0,1,4.712-4.538,12.747,12.747,0,0,1,1.586-.79,8.964,8.964,0,0,0,0,11.9A12.748,12.748,0,0,1,5.869,124.057ZM12,125.75c-3.213,0-5.827-3.074-5.827-6.853s2.614-6.853,5.827-6.853,5.827,3.074,5.827,6.853S15.211,125.75,12,125.75Zm10.841-6.23a14.621,14.621,0,0,1-4.712,4.538,12.737,12.737,0,0,1-1.585.788,8.964,8.964,0,0,0,0-11.9,12.74,12.74,0,0,1,1.587.791,14.622,14.622,0,0,1,4.712,4.538A1.155,1.155,0,0,1,22.839,119.52Z" transform="translate(0.002 -110.897)" data-original="#000000" class="active-path" data-old_color="#000000" fill="#717171"/>
                                                        </g>
                                                        <g transform="translate(9.565 5.565)">
                                                            <path d="M205.24,202.8a2.435,2.435,0,1,0,2.435,2.435A2.438,2.438,0,0,0,205.24,202.8Zm0,3.917a1.482,1.482,0,1,1,1.482-1.482A1.483,1.483,0,0,1,205.24,206.721Z" transform="translate(-202.805 -202.804)"  data-original="#000000" class="active-path" data-old_color="#000000" fill="#717171"/>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm text-end">
                                        <a class="btn-link" href="'.site_url('forgot-password').'">Forgot Password ?</a>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary my-2" type="submit">Log In</button>
                                </div>';
                                if (getconfig('google_login') == 'enable' || getconfig('facebook_login') == 'enable')
                                {
                                    echo '<div class="text-separator">or login with</div>';                                    
                                }
                                echo '
                                <div class="d-grid gap-2">';
                                    if (getconfig('google_login') == 'enable')
                                    {
                                        echo '<button class="btn btn-outline-secondary btn-block mt-4 mb-1" type="button" id="BtnConnectGoogle" data-href="'.site_url('login/google').'">Google</button>';
                                    }
                                    if (getconfig('facebook_login') == 'enable')
                                    {
                                        echo '<button class="btn btn-outline-secondary btn-block mb-4" type="button" id="BtnConnectFacebook" data-href="'.site_url('login/facebook').'">Facebook</button>';
                                    }
                                    echo '
                                    
                                </div>
                                
                            </div>
                        </div>
                    '.form_close().'
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
