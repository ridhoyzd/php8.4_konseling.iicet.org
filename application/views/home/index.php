<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Dashboard</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.css').'" rel="stylesheet">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/app.css').'">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/responsive.css').'">
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script> var base_url = "'.base_url().'";</script>
</head>
<body>
	<script src="'.base_url('assets/themes/admin/js/spinner.js').'"></script>
    <div class="main-wrapper">';

        $this->load->view('_sidebar');
        
        echo '		
        <div class="page-wrapper">';

            $this->load->view('_header');  echo '

            <div class="page-content">
                <div class="app-page-title2">
                    <div class="breadcrumb-title">Dashboard</div>
                    <div class="breadcrumb">
                        <nav class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="'.site_url('home').'"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card p-0 mb-3 bg-primary">
                            <div class="small-box text-white">
                                <div class="inner"> 
                                    <p>IICET Clients</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-building"></i>
                                </div>
                                <a href="'.site_url('clients').'" class="box-footer">Read More <i class="far fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="card p-0 mb-3 bg-info">
                            <div class="small-box text-white">
                                <div class="inner"> 
                                    <p>DASS Public Clients</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-door-open"></i>
                                </div>
                                <a href="'.site_url('dass_public').'" class="box-footer">Read More <i class="far fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3 col-xl-3">
                        <div class="card p-0 bg-warning">
                            <div class="small-box text-white">
                                <div class="inner"> 
                                    <p>Public Groups</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-tags"></i>
                                </div>
                                <a href="'.site_url('group_public').'" class="box-footer">Read More <i class="far fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3 col-xl-3">
                        <div class="card p-0 bg-success">
                            <div class="small-box text-white">
                                <div class="inner">
                                    <p>DASS Reports (Public)</p>
                                </div>
                                <div class="icon">
                                    <i class="far fa-file-alt"></i>
                                </div>
                                <a href="'.site_url('report_public').'" class="box-footer">Read More <i class="far fa-arrow-alt-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Start -->
                <div class="card main-card">
                    <div class="card-body">
                        <pre>'; /* print_r($this->session->userdata()); */ echo '</pre>
                    </div>
                </div>
                <!-- Main Content End -->
                <div style="text-align: center; min-height: 500px;"> - </div>
            </div>';

            $this->load->view('_footer');

            echo '
		</div>
	</div>
    ';?>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery-3.4.1.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.jquery.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/admin/js/theme.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/admin/js/script.js');?>"></script>
</body>
</html>
