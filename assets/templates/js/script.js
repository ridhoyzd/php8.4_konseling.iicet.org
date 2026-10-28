$(document).ready(function() {
    "use strict";
    new WOW().init();
    
    if($('.sidebar-inner').length){
        if ($(window ).width() > 992) {
            $(".sidebar-inner").sticky({topSpacing:87});
            console.log('width > 992px');
        } 
        
    }
});

$(window).scroll(function() {
    if ($(window ).width() > 992) {
        if ($(window).scrollTop() >= 34.6) { 
            $('#header').addClass('fix-top'); 
            $('body').css({"margin-top": "84px"});
        }
        if ($(window).scrollTop() >= 34.6) 
        { 
            $('#header').addClass('show');
        }
        else
        {
            $('#header').removeClass('show fix-top'); 
            $('body').removeAttr('style'); 
        }
    }
});

$(".pqty").on("input", function() {
    var e = $(this), k = $(this).parent(".pqty-ctrl"), t = parseInt(e.val(), 10);
    if(t > -1 ? t : e.val(1));
    if(e.val() > 1) { k.find('.decrease').removeAttr('disabled'); }
    else{ k.find('.decrease').attr('disabled','disabled'); } 
});

if ($(".autosize").length) {
    $('.autosize').autosize({append: "\n"});
}
if ($(".datepicker").length) {
    $('.datepicker').datepicker({format: "yyyy-mm-dd"});
}

$(".pqty").change(function() {
    var e = $(this),a=null,b=null;
    a = e.attr('rowid'); b = e.val();
    updateCart(a,b);
});

$(".btn-sign.increase").on("click", function() {
    var e = $(this).parent(".pqty-ctrl"), f = e.find('.pqty'), i = e.find('.pqty').val(), t=0, a=null,b=null;
    t = (parseInt(i)+1); f.val(t);
    if(f.val() > 1) e.find('.decrease').removeAttr('disabled');
    a = e.find('.pqty').attr('rowid'); b = e.find('.pqty').val();
    updateCart(a,b);

});

$(".btn-sign.decrease").on("click", function() {
    var e = $(this).parent(".pqty-ctrl"), f = e.find('.pqty'), i = e.find('.pqty').val(), t=0,  a=null,b=null;
        t = (parseInt(i)-1); f.val(t);
    if(t <= 1) { f.val(1); e.find('.decrease').attr('disabled','disabled'); }
    a = e.find('.pqty').attr('rowid'); b = e.find('.pqty').val();
    updateCart(a,b);
});

$(".btn-sign.remove").on("click", function() {
    var c =$(this), b = c.attr('data-id'), e = $(this).parent(".pqty-ctrl"), f = e.find('.pqty'), a = f.attr('rowid');
    var data = {'a':a,'b': b};data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({ 
            url: '' + base_url + 'cart/remove',
            type: 'post',
            dataType: 'json', 
            data: data,
            beforeSend: function() { 
                $('.preloader').show();
            },
            success: function(response) {
                if(response.status == 1)
                {
                    $('#item' + b).fadeOut(500);
                    $('#CartCount').html(response.total_items);
                    $('#CartCountMobile').html(response.total_items);
                    $('#CartTotal').html(response.total);
                    if(response.redirect == 1)
                    {
                        setTimeout(function() { window.location.href = ''+response.link+''; }, 500);
                        return false;
                    }
                }
            },
            complete: function ()
            {
                $('.preloader').hide();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
            },
        });
});

$('.btn-checkout').on('click', function(){
    var CheckoutModal = new bootstrap.Modal(document.getElementById('CheckoutModal'), { keyboard: true,backdrop:'static',focus:true });
    var data = {};data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({ 
        url: '' + base_url + 'cart/checkstock',
        type: 'post',
        dataType: 'json', 
        data: data,
        beforeSend: function() { 
            $('.preloader').show();
            
        },
        success: function(response) {
            if(response.status == 0 && response.continue == 0)
            {
                $('#CheckoutModal .modal-body').html(response.message); CheckoutModal.show();                
            }
            else
            {
                setTimeout(function() { window.location.href = ''+base_url +'checkout'; },100);
            }
        },
        complete: function ()
        {
            $('.preloader').hide();
        },
        error: function(xhr) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText }); 
        },
        statusCode: {
            404: function() {
                Swal.fire({ type: 'error', title: 'Error', html: 'Page not found!' });
            }
        }
    });
});

$('#BtnConnectGoogle').on('click',function(){
    window.location = '' + $(this).attr('data-href') + '';
});

$('#BtnConnectFacebook').on('click',function(){
    window.location = '' + $(this).attr('data-href') + '';
});

$('#reCheckAuth').submit(function(){ 
    var url     = $('#reCheckAuth').attr('action'), data = new FormData(this); data.append(csfr_token_name, $.cookie(csfr_cookie_name));
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
        error: function(xhr) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
        },
        success: function(data) { 
            if(data.status == 1)
            {
                Swal.fire({ type: data.type, html: data.message });
                if (data.redirect == 1)
                { 
                    setTimeout(function() { window.location.href = ""+data.link+""; }, 100);
                    return false;
                }
            }
            else
            {
                Swal.fire({ type: data.type, html: data.message });
            } 
        },
        complete: function(){
            $('.preloader').hide();
        }
    });
    return false;
});

$('#checkoutForm').submit(function(){ 
    var url = $('#checkoutForm').attr('action'), data = new FormData(this); data.append(csfr_token_name, $.cookie(csfr_cookie_name));
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
        error: function(xhr) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
        },
        success: function(data) { 
            if(data.status == 1)
            {
                Swal.fire({ type: data.type, html: data.message });
                if (data.redirect == 1)
                { 
                    setTimeout(function() { window.location.href = ""+data.link+""; }, 100);
                    return false;
                }
            }
            else
            {
                Swal.fire({ type: data.type, html: data.message });
            } 
        },
        complete: function(){
            $('.preloader').hide();
        }
    });
    return false;
});

$('#CheckPaymentStatus').on('click',function(){
    var data = {'inv': $(this).attr('data-inv')};data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({ 
        url: '' + base_url + 'payment/check',
        type: 'post',
        dataType: 'json', 
        data: data,
        beforeSend: function() { 
            $('.preloader').show();
        },
        success: function(response) {
            Swal.fire({ type: response.type, title: response.title, html: response.message });
        },
        complete: function (){
            $('.preloader').hide();
        },
        error: function(xhr) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
        },
    });
});

$('#PaymentDetail').on('click',function(){
    var data = {'inv': $(this).attr('data-inv')};data[csfr_token_name] = $.cookie(csfr_cookie_name);
    var DefModal = new bootstrap.Modal(document.getElementById('DefModal'), { keyboard: true,backdrop:true,focus:true });
    $.ajax({ 
        url: '' + base_url + 'payment/detail',
        type: 'post',
        dataType: 'html', 
        data: data,
        beforeSend: function() { 
            $('.preloader').show();
        },
        success: function(response) {
            $('#DefModal .modal-data .modal-body').html(response);
            DefModal.show();
        },
        complete: function (){
            $('.preloader').hide();
        },
        error: function(xhr) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
        },
    });
});

function addCart(id)
{
    if (id != '')
    {
        var data = {'id':id};data[csfr_token_name] = $.cookie(csfr_cookie_name);
        var cartModal = new bootstrap.Modal(document.getElementById('AddCartModal'), { keyboard: true,backdrop:'static',focus:true });
        $.ajax({ 
            url: '' + base_url + 'product/addcart',
            type: 'post',
            dataType: 'json', 
            data: data,
            beforeSend: function() { 
                $('.preloader').show();
                $('#AddCartModal .modal-data').html('<div class="modal-content p-3 text-center"><div class="modal-body p-0"><div class="cart-modal-content"><i class="fa fa-spinner fa-spin fa-2x"></i></div></div></div>');
                cartModal.show();
            },
            success: function(response) {
                if(response.total_items > 0){ $('#CartCountMobile').html(response.total_items);$('#CartCount').html(response.total_items); }
                $('#AddCartModal .modal-data').html(response.content);
            },
            complete: function ()
            {
                $('.preloader').hide();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
            },
        });
    }
}

function updateCart(a,b)
{
    if (a != '' && b !='')
    {
        var data = {'a':a,'b': b};data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({ 
            url: '' + base_url + 'cart/update',
            type: 'post',
            dataType: 'json', 
            data: data,
            beforeSend: function() { 
                $('.preloader').show();
            },
            success: function(response) {
                if(response.status == 1) {
                    $('#CartCount').html(response.total_items);
                    $('#CartCountMobile').html(response.total_items);
                    $('#CartTotal').html(response.total);
                }
                else if(response.status == 2) {
                    $('#item'+ response.id + ' .pqty').val(response.qty);
                    $('#item'+ response.id + ' .pqty').attr('readonly', 'readonly');
                    $('#item'+ response.id + ' .decrease').attr('disabled', 'disabled');
                    $('#item'+ response.id + ' .increase').attr('disabled', 'disabled');
                    Swal.fire({ type: response.type, html: response.message });
                }
            },
            complete: function ()
            {
                $('.preloader').hide();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
            },
        });
    }
}
$(function() {
    'use strict'; 
    $('.mobile-toggler').on('click', function(){
        if($('.sidebar').hasClass('show')){
            $('#html body.sidebarActive .sidebarWrapper').hide();
            $('#html body').removeClass('sidebarActive');
        }
        else
        { 
            $('#html body').addClass('sidebarActive');
            $('#html body.sidebarActive .sidebarWrapper').show();
            
        }
        return false;
    });
    $('.sidebarWrapper').on('click', function(){
        if($('.sidebar').hasClass('show')){
            $('#html body.sidebarActive .sidebarWrapper').removeAttr('style');
            $('#html body').removeClass('sidebarActive');
            $('.sidebar').removeClass('show');
        }
    });
});
$('#CreatePaymentForm').submit(function(){ 
    var url = $('#CreatePaymentForm').attr('action'), data = new FormData(this); data.append(csfr_token_name, $.cookie(csfr_cookie_name));
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
        error: function(xhr) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
        },
        success: function(response) { 
            if(response.status == 1)
            {
                $('#CardPayment').html(''); $('#CardPayment').hide();
                $('#CardInfo').html('<div class="alert alert-success">'+response.message+'</div>');
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
}); 

$('#Form').submit(function(){ 
    var url = $('#Form').attr('action'), data = new FormData(this); data.append(csfr_token_name, $.cookie(csfr_cookie_name));
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
        error: function(xhr) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
        },
        success: function(response) { 
            Swal.fire({ type: response.type, html: response.message });
            if (response.redirect == 1)
            { 
                setTimeout(function() { window.location.href = ""+response.link+""; }, 2000);
                return false;
            }
        },
        complete: function(){
            $('.preloader').hide();
        }
    });
    return false;
}); 
$('#orderId').change(function(){
    var c =$(this), b = c.val();
    var data = {'id':b};data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({ 
        url: '' + base_url + 'user/order/getinvoice',
        type: 'post',
        dataType: 'json', 
        data: data,
        beforeSend: function() { 
            $('.preloader').show();
        },
        success: function(response) {
            if(response.status == 1)
            {
               $('#Nominal').val(''+response.total_order+'');
               $('#BankDestination').val(''+response.bank_name + ' ('+response.bank_account_number+')'+'');
            }
        },
        complete: function ()
        {
            $('.preloader').hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({ type: 'error', title: xhr.statusText, html: xhr.responseText });
        },
    });
}); 
$(document).ready(function() {
    "use strict"; 
});