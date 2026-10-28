var LimpatoEditor = function() {
    var AjaxFormSubmit = function() {
        $('#AjaxForm').submit(function(){ 
            var url  = $('#AjaxForm').attr('action');
            var data = new FormData(this); data.append(csfr_token_name, $.cookie(csfr_cookie_name));
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
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
                },
                success: function(data) { 
                    if(data.status == 1)
                    {
                        Swal.fire({ type: data.type, html: data.message });
        
                        if (data.redirect == 1)
                        { 
                            setTimeout(function() {  window.location.href = ""+data.link+"";}, 2000);
                            return false;
                        }
                    }
                    else
                    {
                        Swal.fire({
                            type: data.type,
                            html: data.message
                        });
                    } 
                },
                complete: function(){
                    $('.preloader').hide();
                }
            });
            return false;
        });
    }
    var ProductImageUpload = function(){
        $('.upload-product-image .btn-post-upload').off("click").on("click", function(t) {
            var e = $(this);
            $('#gs-form').remove();
            $('body').prepend('<form enctype="multipart/form-data"  id="gs-form" accept="image/x-png, image/gif, image/jpeg, image/jpg" style="display: none;"><input type="file" name="userfile" value="" /></form>');
            $('#gs-form input[name="userfile"]').trigger('click');
            if (typeof timer != 'undefined') 
            {
                clearInterval(timer);
            }
            timer = setInterval(function() {
                if ($('#gs-form input[name="userfile"]').val() != '') 
                {
                    clearInterval(timer);
                    var data = new FormData($('#gs-form')[0]); data.append(csfr_token_name, $.cookie(csfr_cookie_name));
                    $.ajax({
                        url: $('.upload-product-image .btn-post-upload').attr('data-href'),
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded / evt.total) * 100;
                                    $('#progress .progress-bar').css( 'width', percentComplete + '%' );
                                    $('#progress .progress-bar').html( percentComplete + ' %')
                                }
                            }, false);
                            return xhr;
                        },
                        beforeSend: function() {
                            $('#progress .progress-bar').css( 'width', 0 + '%' );
                            $('#progress .progress-bar').html( '')
                            $('.preloader').show();
                        },
                        success: function(response) {
                            if (response.status == 1 && response.error == 0)
                            {
                                $("#UploadedPreview").append(response.content);
                                if($('.uploaded_image').length >= max_uploads){
                                    $('#SelectFileContainer').hide();
                                }else{
                                    $('#SelectFileContainer').show();
                                }
                            }
                            else
                            {
                                Swal.fire({ type: 'error', html: response.message });
                            }
                        },
                        complete: function(){
                            $('.preloader').hide();
                            $('#progress .progress-bar').css( 'width', 0 + '%' );
                            $('#progress .progress-bar').html('')
                        }
                    });
                }
            }, 500);
        });
        $( "#uploaded_images" ).on( "click", ".img_rmv", function() {
            $(this).parent().remove();
            if($('.uploaded_image').length >= max_uploads){
                $('#SelectFileContainer').hide();
            }else{
                $('#SelectFileContainer').show();
            }
        });
    }

    var handleEditor = function() {
        if ($('.summernote').length) {
            $('.summernote').summernote({ 
                placeholder: $('.summernote').attr('placeholder'),
                disableDragAndDrop: true,
                spellCheck: false,
                disableGrammar: true,
                height: $('.summernote').attr('data-height'),
                toolbar: [
                    ['group1', ['style']],
                    ['group2', ['fontname','bold','italic','underline','strikethrough']],                    
                    ['group3', ['color']],
                    ['group4', ['superscript', 'subscript','clear']],
                    ['group5', ['ul', 'ol']],
                    ['group6', ['paragraph','table']],
                    ['group7', ['uploadimage','uploadfile']],
                    ['group8', ['link','hr','picture','video']],
                    ['group9', ['codeview','fullscreen','help']] 
                ]
            });
        }
    }

    var handleRemove = function(){ 
        
    } 
    return {
        InitFileUpload: function() {
            ProductImageUpload();
        },
        InitFormSubmit: function() {
            AjaxFormSubmit();
        },
		InitEditor: function() {
			handleEditor();
		},
        InitDelete: function() {
            handleRemove(); 
        }
    }
}();


/* ----------------------
   Init. textarea autosize
------------------------*/
if ($(".autosize").length) {
    $('.autosize').autosize({append: "\n"});
}
/* ----------------------
Delete item 
------------------------*/
function row_delete(id,url)
{
    Swal.fire({
        title: 'Confirm Deletion',
        text: "Are you sure you want to permanently delete this item from your site. This action cannot be undone",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            var data = { 'id': id,'uri': ''+url+''}; data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('.preloader').show();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
                },
                success: function(data) { 
                    $('#DataTable').DataTable().ajax.reload();
                    Swal.fire({ type: data.type, html: data.message });
                    if (data.redirect == 1)
                    { 
                        setTimeout(function() { window.location.href = ""+data.link+""; }, 2000);
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
