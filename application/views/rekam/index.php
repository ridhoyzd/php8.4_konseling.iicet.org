<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Rekam Psikologis Klien</title>
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
                    <div class="breadcrumb-title">Rekam Psikologis Klien (RPK)</div>
                    <div class="ms-auto top-action d-none">
                        <a href="'.site_url('therapy/add').'" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                </div>  

                <!-- Main Content Start --> 
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <div class="card main-card">
                    <div class="card-body custom-datatable" >
                        <table id="DataTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client ID</th>
                                    <th>Nama</th>
                                    <th>Tgl. Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Jml. Sesi</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
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
        $('#DataTable').dataTable({
			'processing'	: true,
			'serverSide'	: true,
			'paging'		: true,
			'info'			: true,
			'filter'		: true,
			'responsive'	: true,
			'ajax'			: {'url': base_url + 'rekam/datatable', 'method':'post'},
			'order'			: [[ 0, 'DESC' ]],
			'pageLength'	: 25,
			'lengthMenu'	: [[10,25,50,100,-1], [10,25,50,100,'All']], 
			'columns'		: [	 
								{ 'data': 'id' },
								{ 'data': 'client_no' },
								{ 'data': 'client_name' },
								{ 'data': 'client_birthday' },
								{ 'data': 'client_gender' },
								{ 'data': 'jumlah_dass' }, 
								{ 'data': 'actions' },
							  ],
			'columnDefs'	: [ 
								{
									'targets': [0],
									'visible': false,
									'searchable': false,
									'orderable': true,
								},
								{
									'targets': [6],
									'visible': true,
									'searchable': false,
									'orderable': false,
								}
							   ],
			'createdRow': function (row, data) {
				$(row).attr('id', 'row_' + data.id);
				$('td', row).eq(5).attr('class', 'td-action');
			},
			'language': {
				'search' : '',
                'searchPlaceholder': 'Search Clients...'
			}
		});
    </script>
</body>
</html>
