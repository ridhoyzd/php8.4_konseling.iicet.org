(function ($) {
	"use strict";

/*------------------------
   Preloader
-------------------------- */
$(window).on('load', function () {
	//$('.lds-ellipsis').fadeOut(); // will first fade out the loading animation
	$('.preloader').delay(333).fadeOut('slow'); // will fade out the white DIV that covers the website.
	$('body').delay(333);
}); 
 
/*------------------------
   Password strength meter
-------------------------- */
if ($("#password-strength-meter").length) {
    var password = document.getElementById('InputPassword');
    var meter = document.getElementById('password-strength-meter');
    var text = document.getElementById('password-strength-result');
    var strength = {
        0: "<div class=\"worst\"><span>Very weak <i class=\"far fa-sad-cry fa-1x\"></i></span></div>",
        1: "<div class=\"bad\"><span>Weak <i class=\"far fa-frown fa-1x\"></i></span></div>",
        2: "<div class=\"weak\"><span>Medium <i class=\"far fa-meh fa-1x\"></i></span></div>",
        3: "<div class=\"good\"><span>Good <i class=\"far fa-smile fa-1x\"></i></span></div>",
        4: "<div class=\"strong\"><span>Strong <i class=\"far fa-laugh-beam fa-1x\"></i></span></div>"
    }
    password.addEventListener('input', function()
    {
        var val     = password.value;
        var result  = zxcvbn(val);
        meter.value = result.score;
        if(val !== "") { text.innerHTML = "<div class=\"result-content\">" + strength[result.score] + "</div>"; }
        else { text.innerHTML = ""; }
    });
}

/*------------------------
   Bind password
-------------------------- */
function bindPasswordReveal() {
    $('input[type="password"] + .input-group-append').each(function() {
        $(this).on('mousedown touchstart', function() {
            $(this)
                .prev()
                .attr('type', 'text');
        });
        $(this).on('mouseup touchend', function() {
            $(this)
                .prev()
                .attr('type', 'password');
        });
    });
}
/* ----------------------
   Form with no submit when press enter
------------------------*/
$('form.no-enter input').keydown(function (e) {
    if (e.keyCode == 13) {
        var inputs = $(this).parents("form").eq(0).find(":input");
        if (inputs[inputs.index(this) + 1] != null) {                    
            inputs[inputs.index(this) + 1].focus();
        }
        e.preventDefault();
        return false;
    }
});
$('#AuthForm').submit(function(){ 
    var url     = $('#AuthForm').attr('action');
    var data 	= new FormData(this); data.append(csfr_token_name, $.cookie(csfr_cookie_name));
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
            Swal.fire({
                type: 'error',
                title: xhr.statusText,
                html: xhr.responseText
            });
        },
        success: function(data) { 
            if(data.status == 1)
            {
                $('#AuthForm input').val('');
                $('#AuthForm meter').val('');
                $('#AuthForm #password-strength-result').html('');
                Swal.fire({
                    type: data.type,
                    html: data.message
                });

                if (data.redirect == 1)
                { 
                    setTimeout(function() {
                        window.location.href = ""+data.link+"";
                    }, 1500);
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
$('#BtnConnectGoogle').on('click',function(){
    window.location = '' + $(this).attr('data-href') + '';
});
$('#BtnConnectFacebook').on('click',function(){
    window.location = '' + $(this).attr('data-href') + '';
});
window.addEventListener('load', function() {
    bindPasswordReveal();    
}); 
})(jQuery)