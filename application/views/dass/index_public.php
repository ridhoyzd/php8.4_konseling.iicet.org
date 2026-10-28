<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>DASS PUBLIC</title>
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
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div class="page-title-icon">
                                <i class="far fa-file"></i>
                            </div>
                            <div>
                                <span>DASS Public</span>
                                <nav class="page-breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="'.site_url().'">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">DASS Public</li> 
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
                    <div class="card-body custom-datatable" >
                        <table id="DataTable" class="table table-sm table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="80">Client ID</th>
                                    <th>Nama</th>
                                    <th>Gender</th>
                                    <th>Umur</th>
                                    <th width="100">Tgl. Mengisi</th>
                                    <th>Group(s)</th>
                                    <th width="220">Add to Group</th>
                                    <th width="80" class="px-0">Actions</th>
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
        'use strict'
        $(document).ready(function () {
            var tblData = $('#DataTable').DataTable({
                'processing'	: true,
                'serverSide'	: true,
                'paging'		: true,
                'info'			: true,
                'filter'		: true,
                'responsive'	: true,
                'ajax'			: {'url': base_url + 'dass_public/datatable', 'method':'post'},
                'order'			: [[ 0, 'DESC' ]],
                'pageLength'	: 25,
                'lengthMenu'	: [[10,25,50,100,-1], [10,25,50,100,'All']], 
                'columns'		: [	 
                                    { 'data': 'test_id' },
                                    { 'data': 'client_name' }, 
                                    { 'data': 'client_gender' },
                                    { 'data': 'client_age' },
                                    { 'data': 'tanggal_mengisi' }, 
                                    { 'data': 'client_group' }, 
                                    { 'data': 'form_group' }, 
                                    { 'data': 'actions' },
                                ],
                'columnDefs'	: [ 
                                    {
                                        'targets': [0],
                                        'visible': false,
                                        'searchable': true,
                                        'orderable': true,
                                    },
                                    {
                                        'targets': [2],
                                        'visible': false,
                                        'searchable': false,
                                        'orderable': false,
                                    },
                                    {
                                        'targets': [3],
                                        'visible': false,
                                        'searchable': false,
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
                                    },
                                    {
                                        'targets': [7],
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

            $('#BtnReload').on('click',function(){
                tblData.ajax.reload();
            }); 

            $('.form_submit').on( "click", function(e) {
                console.log( $( this ).text() );
                e.preventDefault();
            });
        });

        function form_submit(id) {
            var form = $('#form-'+id)[0]; 
            var url         = $('#form-'+id).attr('action');
			var formData 	= new FormData(form);formData.append(csfr_token_name, $.cookie(csfr_cookie_name));
            var test     = $('#form-'+id+' #test'+id+'').val();
            var client   = $('#form-'+id+' #client'+id+'').val();
            var group    = $('#form-'+id+' #group'+id+'').val();
            if (group != '' && test != '' && client != '')
            {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    mimeType:'multipart/form-data',
                    beforeSend: function() {
                        $('.preloader').show();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    },
                    success: function(response) { 
                        if(response.status == "1")
                        {
                            form.reset();
                            $('#list-group-'+response.client+'').append('<li id="item-group-'+response.group + response.client+'"><a href="javascript:void(0);" class="text-danger" onclick="return DeleteGroup('+response.group+','+response.client+')" title="Delete Group"><i class="fa fa-times close-inline"></i></a>&nbsp;'+response.group_name+'</li>');
                        }
                        else
                        {	 
                            form.reset(); 
                            Swal.fire({ title: response.title, html: response.message, timer: 2500, type: response.type, showConfirmButton: false });
                        }
                    },
                    complete: function(){
                        $('.preloader').hide();
                    }
                });
                return false;
            }
            else
            {
                //Swal.fire({ type: 'warning', title: '', html: 'Please select a group for this user!' });
            }
        }
        function DeleteGroup(group,client)
        {
            Swal.fire({
                title: 'Confirm Deletion',
                text: "Remove this user from this group? This action cannot be undone",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    var data = { 'group': group,'client':client}; data[csfr_token_name] = $.cookie(csfr_cookie_name);
                    $.ajax({
                        type: 'post',
                        url: base_url + 'dass_public/remove_group',
                        data: data,
                        dataType: 'json',
                        beforeSend: function() {
                            $('.preloader').show();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
                        },
                        success: function(response) {
                            if(response.status == 1) {
                                $('#item-group-'+group+client+'').fadeOut(100, function() { $('#item-group-'+group+client+'').remove(); });
                            }
                            else
                            {
                                Swal.fire({ type: response.type, html: response.message });
                            }
                        },
                        complete: function(){
                            $('.preloader').hide();
                        }
                    });
                    return false;
                }
            });
        }

        
    </script>
</body>
</html>
