<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Dass Public Reports</title>
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
    <div class="main-wrapper">';
        $this->load->view('_sidebar');
        echo '		
        <div class="page-wrapper">';
            $this->load->view('_header');  echo '
            <div class="page-content">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div class="page-title-icon">
                                <i class="far fa-file"></i>
                            </div>
                            <div>
                                <span>DASS Reports</span>
                                <nav class="page-breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="'.site_url('home').'">Home</a></li>
                                        <li class="breadcrumb-item"><a href="'.site_url('dass_public').'">DASS</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Reports</li> 
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <a href="javascript:void(0);" id="BtnReload" data-href="'.site_url('dass_public').'" class="btn btn-dark btn-shadow mr-1"><i class="fa fa-sync"></i> Refresh</a>
                        </div>
                    </div>
                </div>

                <!-- Main Content Start --> 
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <div class="card main-card">
                    <div class="card-body">';
                        $a_val	= isset($group_id) ? $group_id : '';
                        $a_opt	= array('' => 'Select group','all'=> 'All data');
                        $a_attr = array('class' => 'form-select', 'required' => 'required', 'id' => 'gid');
                        $a_data	= $groups->result();
                        if ($groups->num_rows() > 0) {
                            foreach ($a_data as $dat) {
                                $a_opt[$dat->group_id] = $dat->group_name;
                            }
                        }

                        echo '
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <label class="form-label" for="iGroup">Select Group :</label> 
                                <div class="input-group mb-3">
                                    '.form_dropdown('group', $a_opt, $a_val, $a_attr).'
                                    <button class="btn btn-primary" type="button" id="BtnView" onclick="view_report();">View Data</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="text-end">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="text-end mb-3">
                                        <a href="'.site_url('report_public/full_excel/'.$group_id.'').'" class="btn btn-primary mr-1"><i class="far fa-file-excel"></i> Full Export</a>
                                        <a href="'.site_url('report_public/export_excel/'.$group_id.'').'" class="btn btn-primary"><i class="far fa-file-excel"></i> Export to Excel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th colspan="1" rowspan="2" width="40">No</th>
                                    <th colspan="1" rowspan="2">Nama</th>
                                    <th colspan="1" rowspan="2" width="135">Handphone</th>
                                    <th colspan="1" rowspan="2" width="82">Jekel</th>
                                    <th colspan="1" rowspan="2" width="45">Umur</th>
                                    <th colspan="1" rowspan="2" width="84">Tanggal Mengisi</th>
                                    <th colspan="2" rowspan="1" class="text-center">Tekanan<br>(Stress)</th>
                                    <th colspan="2" rowspan="1" class="text-center">Kebimbangan<br>(Anxiety)</th>
                                    <th colspan="2" rowspan="1" class="text-center">Kemurungan<br>(Depression)</th>
                                </tr>
                                <tr>
                                    <th width="40">Nilai</th>
                                    <th width="95">Kategori</th>
                                    <th width="40">Nilai</th>
                                    <th width="95">Kategori</th>
                                    <th width="40">Nilai</th>
                                    <th width="95">Kategori</th>
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
                                            <td>'.$no++.'</td>
                                            <td>'.$row->client_name.'</td>
                                            <td>'.$row->client_phone.'</td>
                                            <td>'.jenis_kelamin($row->client_gender).'</td>
                                            <td>'.$row->client_age.'</td>
                                            <td>'.substr($row->tanggal_mengisi,0,10).'</td>
                                            <td>'.$row->point_stress.'</td>
                                            <td>'.$row->stress.'</td>
                                            <td>'.$row->point_anxiety.'</td>
                                            <td>'.$row->anxiety.'</td>
                                            <td>'.$row->point_depression.'</td>
                                            <td>'.$row->depression.'</td>
                                        </tr>';
                                    }
                                }
                                else
                                {
                                    echo '
                                    <tr>
                                        <td colspan="12">
                                            <em class="text-muted">No data found!</em>
                                        </td>
                                    </tr>';
                                }
                                echo '
                            </tbody>
                        </table>
                        </div>
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
    <script src="<?php echo base_url('assets/plugins/sweetalert2/sweetalert2.min.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/admin/js/theme.js');?>"></script>
    <script src="<?php echo base_url('assets/themes/admin/js/script.js');?>"></script>
    <script> 
        function view_report()
        {
            var group_id = $('#gid').val();
            if (group_id != '') {
                window.location.href = ''+base_url+'report_public/group/'+group_id+'';
            }
            else
            {
                Swal.fire({ title: 'No Group Selected', html: 'Please select a group to view data', timer: 2500, type: 'warning', showConfirmButton: true });
            }
        }
    </script>
</body>
</html>
