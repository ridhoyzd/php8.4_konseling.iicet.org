<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>DASS</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/datatable/datatables.min.css').'" rel="stylesheet"> 
    <link href="'.base_url('assets/plugins/sweetalert2/sweetalert2.min.css').'" rel="stylesheet">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/app.css').'">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/responsive.css').'">
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
                <div class="app-page-title2">
                    <div class="breadcrumb-title">DASS</div>
                    <div class="ms-auto top-action">
                        <button class="btn btn-primary" id="NewDass"><i class="fa fa-plus"></i> Add New</button>
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
                                    <th>Umur</th>
                                    <th>Gender</th>
                                    <th>Tgl. Mengisi</th>
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
    <div id="DassModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Klien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="TblClients" class="table table-hover" style="width:100% !important;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client ID</th>
                            <th>Nama</th>
                            <th>Tgl Lahir</th>
                            <th>Gender</th>
                            <th>Telp</th>
                            <th width="120">#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
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
        
        $('#NewDass').on('click',function(){
            var DassModal = new bootstrap.Modal(document.getElementById('DassModal'), { keyboard: false, backdrop: 'static' });
            DassModal.show();
        });
        $('#DataTable').dataTable({
			'processing'	: true,
			'serverSide'	: true,
			'paging'		: true,
			'info'			: true,
			'filter'		: true,
			'responsive'	: true,
			'ajax'			: {'url': base_url + 'dass/datatable', 'method':'post'},
			'order'			: [[ 0, 'DESC' ]],
			'pageLength'	: 25,
			'lengthMenu'	: [[10,25,50,100,-1], [10,25,50,100,'All']], 
			'columns'		: [	 
								{ 'data': 'test_id' },
								{ 'data': 'client_no' },
								{ 'data': 'client_name' }, 
								{ 'data': 'umur_client' }, 
								{ 'data': 'client_gender' },
								{ 'data': 'tanggal_mengisi' }, 
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
				$(row).attr('id', 'row_' + data.test_id); 
				$('td', row).eq(7).attr('class', 'td-action');
			},
			'language': {
				'search' : '',
                'searchPlaceholder': 'Search Clients...'
			}
		}); 

        $('#TblClients').dataTable({
            'processing'	: true,
            'serverSide'	: true,
            'paging'		: true,
            'info'			: true,
            'filter'		: true,
            'responsive'	: true,
            'ajax'			: {'url': '<?php echo site_url('dass/client_datatable');?>', 'method':'post'},
            'order'			: [[ 0, 'DESC' ]],
            'pageLength'	: 10,
            'lengthMenu'	: [[10,25,50,100,-1], [10,25,50,100,'All']], 
            'columns'		: [	 
                                { 'data': 'id' },
                                { 'data': 'client_no' },
                                { 'data': 'client_name' }, 
                                { 'data': 'client_birthday' }, 
                                { 'data': 'client_gender' },
                                { 'data': 'client_phone' }, 
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
                $('td', row).eq(7).attr('class', 'td-action');
            },
            'language': {
                'search' : '',
                'searchPlaceholder': 'Search Clients...'
            }
        });
    </script>
</body>
</html>
