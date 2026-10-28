<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Counselors</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/datatable/datatables.min.css').'" rel="stylesheet"> 
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
                    <div class="breadcrumb-title">Counselors</div>
                    <div class="breadcrumb">
                        <nav class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="'.site_url('home').'"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Counselors</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto top-action">
                        <a href="'.site_url('counselor/add').'" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                </div>  

                <!-- Main Content Start --> 
                <div class="card main-card">
                    <div class="card-body table-responsive" >
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="65">No</th>
                                    <th>Counselor</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>';
                            if($query->num_rows() > 0)
                            {
                                $no = 1;
                                foreach ($query->result() as $row)
                                {
                                    echo '
                                    <tr>
                                        <td>'.$no.'</td>
                                        <td>'.$row->counselor_name.'</td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="ActionMenu'.$no.'" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i> Actions</button>
                                                <ul class="dropdown-menu" aria-labelledby="ActionMenu'.$no.'">
                                                    <li><a class="dropdown-item" href="'.base_url('counselor/edit/'.$row->counselor_id.'').'"><i class="fas fa-edit"></i> Edit</a></li>
                                                    <div class="dropdown-divider"></div>
                                                    <li><a class="dropdown-item datatable-delete" href="javascript:void(0);" onclick="row_delete(\''.$row->counselor_id.'\',\''.site_url('counselor/delete').'\')"><i class="fa fa-times"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>';
                                    $no++;
                                }
                            }
                            else
                            {
                                echo '<tr><td colspan="4"><em class="text-muted">No Data found</em></td></tr>';
                            }
                            echo '
                            </tbody>
                        </table>
                    </div>
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
    <script src="<?php echo base_url('assets/plugins/datatable/datatables.min.js');?>"></script>
    <script src="<?php echo base_url('assets/plugins/sweetalert2/sweetalert2.min.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/admin/js/theme.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/admin/js/script.js');?>"></script>
    <script>
    </script>
</body>
</html>
