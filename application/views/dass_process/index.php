<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <title>Dass Public</title>
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
	<script src="'.base_url('assets/themes/admin/js/spinner.js').'"></script>
    <div class="main-wrapper">';
        $this->load->view('_sidebar');
        echo '		
        <div class="page-wrapper">';
            $this->load->view('_header');  echo '
            <div class="page-content">
                <div class="app-page-title2 d-flex align-items-center">
                    <div class="breadcrumb-title">Users</div>
                    <div class="breadcrumb">
                        <nav class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="'.site_url('home').'"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dass Public</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="ms-auto top-action">
                        <button type="button" id="BtnSync" class="btn btn-primary" onclick="return process_all();"><i class="fas fa-sync-alt"></i> Sync Data</button>
                    </div>
                </div>  

                <!-- Main Content Start --> 
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <div class="card main-card">
                    <div class="card-body custom-datatable" >
                        <table id="DataTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="check-column" width="40">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="cb-select-all-users" class="custom-control-input">
                                            <label class="custom-control-label" for="cb-select-all-users"></label>
                                        </div>
                                    </th>
                                    <th width="65">No</th>
                                    <th>Name</th>
                                    <th>Jekel</th>
                                    <th>Umur</th>
                                    <th width="100">Status</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>';
                            if($query->num_rows() > 0)
                            {
                                $i = $no + 1;
                                $x = 0;
                                foreach ($query->result() as $row)
                                {
                                    echo '
                                    <tr class="tr_'.$x.'">
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="usercheck[]" id="user-'.$row->test_id.'" value="'.$row->test_id.'" class="checklist custom-control-input">
                                                <label class="custom-control-label" for="user-'.$row->test_id.'"></label>
                                            </div>
                                        </td>
                                        <td>'.$i++.'</td>
                                        <td>'.$row->client_name.'</td>
                                        <td>'.$row->client_gender.'</td>
                                        <td>'.$row->client_age.'</td>
                                        <td><span id="status_'.$row->client_id.'" class="status_'.$x.' status"></span></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-block btn-primary btn-send-one" onclick="one_process('.$row->client_id.','.$row->test_id.')"><i class="fas fa-cogs"></i> Process</button>
                                        </td>
                                    </tr>';
                                    $x++;
                                }
                            }
                            echo '
                            </tbody>
                        </table>
                        <nav>'.$pagination.'</nav>
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
    $("thead > tr > .check-column :checkbox").on("click", function() {
        $('tbody > tr input:checkbox').not(this).prop('checked', this.checked);
        $('tfoot > tr input:checkbox').not(this).prop('checked', this.checked);
    });
    function process_all(){
        var jml=$(".checklist").length; 
        var count_jml = $(".checklist:checked").length; 
        if(count_jml > 0)
        {
            alert('Akan memproses ' + count_jml + ' data ?');
            (async function loop() {
                $('#cb-select-all-users').prop('disabled',true);
                $('.checklist').prop('disabled',true);
                $('.btn-send-one').prop('disabled',true);
                $('.btn-send-one').removeClass('btn-success');
                $('.btn-send-one').addClass('btn-secondary');
                for (let i = 0; i < count_jml; i++) 
                {
                    var checked=$(".checklist").eq(i).prop("checked");
                    if(checked)
                    {
                        let j = i+1;
                        $(".tr_"+i).find(".btn-send-one").click();
                        let max_second=5;
                        let rand_time=(Math.floor(Math.random() * max_second) + 1)*1000;
                        $(".tr_"+ (j-1)).find(".status").html('Loading ('+(rand_time/1000)+')...');
                        await new Promise(resolve => setTimeout(resolve, rand_time));
                    }
                }
                $('#cb-select-all-users').prop('disabled',false);
                $('.checklist').prop('disabled',false);
                $('.btn-send-one').prop('disabled',false);
                $('.btn-send-one').addClass('btn-success');
                $('.btn-send-one').removeClass('btn-secondary');
            })(); 
        }
        else
        {
            alert('Belum ada penerima');
        }
    }
    function one_process(client,test)
    {
        $("#status_"+client).addClass("loader");
        $.ajax({
            url : base_url + 'dass_process/process',
            data: $.param({client:client,test:test}),
            dataType: 'json',
            mimeType:'multipart/form-data',
            method : "post",
            beforeSend: function() {
                $("#status_"+client).html('<div class="progress"><div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div>').hide().fadeIn();
            },
            success : function( response )
            {
                if(response.status == 1)
                {
                    $("#status_"+client).html('<span class="status text-success">'+response.message+'</span>').hide().fadeIn();
                }
                else
                {
                    $("#status_"+client).html('<span class="status text-danger">'+response.message+'</span>').hide().fadeIn();
                } 
            },
            error : function(error){  }
        });
        return false;
    }
    </script>
</body>
</html>
