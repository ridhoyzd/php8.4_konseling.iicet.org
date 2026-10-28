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
        @page {
            size: A4 portrait;
        }
        body{
            background: #f5f5f5;
        }
        .page{
            margin: 0 auto;
            width: 210mm;
            padding: 40pt;
            border: 1px solid #f0f0f0;
            background: #ffffff;
            box-shadow: 0 6px 12px -17px rgba(50,50,93,.25),0 7px 6px -8px rgba(0,0,0,.3),0 -6px 16px -6px rgba(0,0,0,.03);
        }
        .img-signature{
            max-height: 150px;
        }
        @media print {
            @page {size: A4 portrait;max-height:100%;max-width:100%;}
            body{background:#ffffff;padding-top:0;}
            .navbar{display:none;}
            .page{border:none;box-shadow:none;page-break: avoid;page-break-inside: avoid;}
        }
    </style>
</head>
<body>

    <div class="page">
        <div class="alert alert-danger text-center p-5 mb-0">
        <h1 class="text-center">404</h1>
        <h4 class="mb-0">OPPS! PAGE NOT FOUND</h4>
        <p class="mb-0">Sorry, the page you\'re looking for doesn\'t exist. If you think something is broken, report  a problem</p>
        </div>
    </div>
        
    <script src="'.base_url('assets/plugins/jquery/jquery-3.4.1.min.js').'"></script>
    <script src="'.base_url('assets/plugins/jquery/jquery.cookie.js').'"></script>
    <script src="'.base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js').'"></script>
</body>
</html>';