<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Invoice</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/datatable/datatables.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/sweetalert2/sweetalert2.min.css').'" rel="stylesheet">
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
                    <div class="breadcrumb-title">Invoice</div>
                </div>

                <!-- Main Content Start -->
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <div class="card main-card">
                    <div class="card-body">
                        <table id="DataTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Client ID</th>
                                    <th>Name</th>
                                    <th>Amount (Rp)</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
			'ajax'			: {'url': base_url + 'invoice/datatable', 'method':'post'},
			'order'			: [[ 0, 'desc' ]],
			'pageLength'	: 25,
			'lengthMenu'	: [[10,25,50,100,-1], [10,25,50,100,'All']], 
			'columns'		: [	 
								{ 'data': 'test_id' },
								{ 'data': 'invoice_code' },
								{ 'data': 'tanggal_mengisi' },
								{ 'data': 'client_no' },
								{ 'data': 'client_name' },
								{ 'data': 'tagihan' }, 
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
									'targets': [1],
									'visible': true,
									'searchable': true,
									'orderable': false,
								},
								{
									'targets': [5],
									'visible': true,
									'searchable': false,
									'orderable': false,
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
