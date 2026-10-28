<?php
defined('BASEPATH') OR exit('No direct script access allowed'); echo '
'.form_open($form_action, array('id' => 'FormControl','autocomplete' => 'off')).' 
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">New Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <label for="name" class="col-sm-3 col-form-label">Group Name <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="" id="name" class="form-control" placeholder="Group Name ">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="desc" class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="desc" class="form-control" id="desc" rows="2"></textarea>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="desc" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">';
                        $a_val	= isset($group_active) ? $group_active : '';
                        $a_opt	= array('1' => 'Active', '0' => 'Inactive');
                        $a_attr = array('class' => 'form-select', 'required' => 'required', 'id' => 'active');
                        echo ''.form_dropdown('active', $a_opt, $a_val, $a_attr).'
                        <small class="form-text"><b>Active</b> Status will be displayed in DASS Public.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
'.form_close();?>
<script>
$('#FormControl').submit(function(){ 
    var url     = $(this).attr('action');
    var data 	= new FormData(this);data.append(jsxtoken, $.cookie(jsxcookie));
    $.ajax({
        type: 'post',
        url: url,
        data: data,
        processData: false,
        contentType: false,
        dataType: 'json',
        mimeType:'multipart/form-data',
        beforeSend: function() {
            $('.preloader').show();
            $('#BtnSubmit').prop('disabled', true);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
        },
        success: function(response) { 
            if(response.status == 1)
            {
                Swal.fire({type:response.type,html:response.message});
                if (response.redirect == 1)
                { 
                    setTimeout(function() { window.location.href = ''+ response.link +''; }, 1500);  return false;
                }
            }
            else
            {	 
                Swal.fire({ type: response.type, html: response.message });
            }
        },
        complete: function(){
            $('.preloader').hide();
            $('#BtnSubmit').prop('disabled', false);
        }
    });
    return false;
});
</script>