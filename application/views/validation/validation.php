<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Validasi Rekam Psikologis Klien (RPK)</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/themes/admin/css/loa.css').'" rel="stylesheet">
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script>
        var base_url = "'.base_url().'";
        var csfr_token_name = "'.$this->security->get_csrf_token_name().'";
        var csfr_cookie_name = "'.$this->config->item('csrf_cookie_name').'";
    </script>
    <style>
    body { background: #fff; color: #333; -webkit-font-smoothing: antialiased !important; }
    a{ text-decoration:none;}
    section { display: block; }
    #top-area { height: 500px;  background: #1dbe72 url(assets/images/logo_head.svg) no-repeat center center; background-size: auto; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; position: relative; }
    #top-area .wrapper{ background: linear-gradient(to bottom, rgba(0,0,0,0.5), transparent); padding: 0; display: flex; height: 100%; }
    .main-title { text-align: center; text-shadow: 0 1px 1px rgba(0,0,0,.25); color: #ffffff; }
    .main-title h1 { font-size: 44px; line-height: 40px; padding-top: 120px; text-transform: uppercase; font-weight: bold; text-rendering: optimizelegibility; font-family: \'Open Sans\',Arial,sans-serif; }
    .main-title p { font-size: 26px; font-weight: 300; margin-top: -5px; }
    #validation_container {background-color: #fff;margin-top: -200px;margin-bottom: 100px; z-index: 9;position: relative;box-shadow: 0 0 5px #777;}
    .middle {padding: 100px;font-size: 1.125rem;}
    footer{margin-bottom: 200px; }
    @media(max-width:480px){
    .middle{padding: 1rem;font-size:0.875rem;}
    .main-title h1 {font-size: 1.5rem;padding-top:60px;}
    .main-title p {font-size: 1rem}
    #validation_container {margin-top: -350px;margin-bottom:50px;}
    footer{font-size: 0.8rem;margin-bottom:50px;}
    }
    </style>
</head>
<body>
<section id="top-area">
    <div class="wrapper">
        <div class="container">
            <div class="main-title">
                <h1>QR Code Validation</h1>
                <p>Rekap Psikologis Klient (RPK)</p>
            </div>
        </div>
    </div>
</section>
<section id="main" class="container">
    <div id="validation_container">
        <div class="middle">
            <p class="mb-5">The URL is <strong class="text-success">Valid</strong></p>
            <dl class="row mb-1">
                <dt class="col-md-3">Name</dt>
                <dd class="col-md-9">'.$client_name.'</dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-md-3">Birthdate</dt>
                <dd class="col-md-9">'.indo_date($client_birthday).'</dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-md-3">Gender</dt>
                <dd class="col-md-9">'.jenis_kelamin($client_gender).'</dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-md-3">Email</dt>
                <dd class="col-md-9">'.$client_email.'</dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-md-3">Phone Number</dt>
                <dd class="col-md-9">'.$client_phone.'</dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-md-3">Address</dt>
                <dd class="col-md-9">'.$client_address.'</dd>
            </dl> 
            <p>The information is recorded in our database.</p>
            <p>To ensure that you are accessing the correct Database, make sure that the URL in your browser is:<br><code><a href="'.site_url('validation/signature?code='.nuxencrypt($test_id,'hide').'').'"  class="text-danger">'.site_url('validation/signature?code='.nuxencrypt($test_id,'hide').'').'</a></code></p>
        </div>
    </div>
</section>
<footer>
    <div class="container">
        <p class="text-center">Sistem Konseling IICET<br><a href="https://www.iicet.org" target="_blank">www.iicet.org</a></p>
    </div>
</footer>
</body>
</html>';