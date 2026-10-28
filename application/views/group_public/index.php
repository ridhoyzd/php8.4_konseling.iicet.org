<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Groups</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/sweetalert2/sweetalert2.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/themes/admin/css/app.css').'" rel="stylesheet">
    <link href="'.base_url('assets/themes/admin/css/responsive.css').'" rel="stylesheet">
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script>
        var base_url = "'.base_url().'"; 
        var jsxtoken = "'.$this->security->get_csrf_token_name().'";
        var jsxcookie = "'.$this->config->item('csrf_cookie_name').'";
        
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
                                <span>Groups</span>
                                <nav class="page-breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="'.site_url().'">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Groups</li> 
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <button type="button" class="btn btn-success btn-shadow mr-1" onclick="addNew();"><i class="fa fa-plus"></i> New Group</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <!-- Main Content Start --> 
                <div class="card main-card">
                    <div class="card-body custom-datatable" >
                        <table id="DataTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="65">No</th>
                                    <th>Group Name</th>
                                    <th>Description</th>
                                    <th>Peoples</th>
                                    <th>Status</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>';
                                $no = 1;
                                foreach ($query->result() as $row)
                                {
                                    echo '
                                    <tr id="tr_'.$row->group_id.'">
                                        <td>'.$no++.'</td>
                                        <td>'.$row->group_name.'</td>
                                        <td>'.$row->group_description.'</td>
                                        <td>'.$row->jumlah_client.'</td>
                                        <td>'; echo ($row->group_active == 1) ? '<span class="text-success"><i class="fas fa-eye"></i> Active</span>' : '<span class="text-danger"><i class="fas fa-eye-slash"></i> Inactive</span>'; echo '</td>
                                        <td>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item mr-0"><button type="button" onclick="return edit('.$row->group_id.');" data-toggle="tooltip" title="Edit" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></button></li>
                                                <li class="list-inline-item mr-0"><button type="button" onclick="return remove('.$row->group_id.');" data-toggle="tooltip" title="Hapus" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i></button></li>
                                            </ul>
                                        </td>
                                    </tr>';
                                }
                                echo '
                            </tbody>
                        </table>
                        <p><i class="fas fa-info-circle"></i> <small class="form-text"><b>Active</b> Status will be displayed in DASS Public.</small></p>
                    </div>
                </div>
                <!-- Main Content End --> 
            </div>';

            $this->load->view('_footer');

            echo '
		</div>
	</div>
    <!-- Modal -->
    <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true"> 
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
        
        function addNew() {
            var data = {'uri': ''+window.location.href+''};data[jsxtoken] = $.cookie(jsxcookie);
            $.ajax({
                type: 'post',
                url: base_url + 'group_public/add',
                data: data,
                dataType: 'html',
                beforeSend: function() {
                    $('.preloader').show();
                    $('#BtnSubmit').prop('disabled', true);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire({ type: 'error', title: xhr.statusText,html: xhr.responseText});
                },
                success: function(response) {  
                    $('#modalForm').html(response);
                    var modalForm = new bootstrap.Modal(document.getElementById("modalForm"), {focus:true,keyboard:true,backdrop:'static'});
                    modalForm.show();
                },
                complete: function(){
                    $('.preloader').hide();
                    $('#BtnSubmit').prop('disabled', false);
                }
            });
            return false;
        }
        function edit(id) {
            var data = { 'id': id,'uri': ''+window.location.href+''};data[jsxtoken] = $.cookie(jsxcookie);
            $.ajax({
                type: 'post',
                url: base_url + 'group_public/edit',
                data: data,
                dataType: 'html',
                beforeSend: function() {
                    $('.preloader').show();
                    $('#BtnSubmit').prop('disabled', true);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire({ type: 'error', title: xhr.statusText,html: xhr.responseText});
                },
                success: function(response) {  
                    $('#modalForm').html(response);
                    var modalForm = new bootstrap.Modal(document.getElementById("modalForm"), {focus:true,keyboard:true,backdrop:'static'});
                    modalForm.show();
                },
                complete: function(){
                    $('.preloader').hide();
                    $('#BtnSubmit').prop('disabled', false);
                }
            });
            return false;
        }
        function remove(id) {
            Swal.fire({
            title: 'Delete Group',
            text: "Are you sure you want to delete this group? This action can't be undone!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.value) {
                    var data = { 'id': id,'uri': ''+window.location.href+''};data[jsxtoken] = $.cookie(jsxcookie);
                    $.ajax({
                        type: 'post',
                        url: base_url + 'group_public/delete',
                        data: data,
                        dataType: 'json',
                        beforeSend: function() {
                            $('.preloader').show();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
                        },
                        success: function(response) { 
                            Swal.fire({ type: response.type, html: response.message });
                            if (response.redirect == 1)
                            {
                                setTimeout(function() { window.location.href = ""+response.link+""; }, 1500);
                                return false;
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
