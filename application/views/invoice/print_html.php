<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Invoice <?php echo $invoice_code;?></title>
<link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
<link href="<?php echo base_url('assets/plugins/fontawesome/css/all.min.css');?>" rel="stylesheet">
<link href="<?php echo base_url('assets/images/favicon.png');?>" rel="icon">
<style>
body{
    background: #f0f0f0;
    padding-top: 4rem;
}
.navbar{
    box-shadow: 0 0 5px #aaa;
}
.page{
    width: 210mm;
    margin: 0 auto;
	background: #fff;
    position:relative;
    box-shadow: 0 1px 3px #aaa;
    margin-top: 15px;
    margin-bottom: 15px;
    border-radius: 0.125rem;
}
.invoice-4 {
	background: #fff;
}
.invoice-content { 
	color: #535353;
	font-size: 14px;
}
.invoice-4 .invoice-info {
	background: #fff;
}
.invoice-4 .invoice-logo {
	clip-path: polygon(0 0, 100% 0, 100% 0%, 85% 100%, 0% 100%);
	width: 340px;
}
.invoice-4 .invoice-logo::before {
	width: 310px;
	background: #7ba632;
	position: absolute;
	content: "";
	height: 110px;
	clip-path: polygon(0 0, 100% 0, 100% 0%, 87% 100%, 0% 100%);
}
.invoice-4 .invoice-logo .logo {
	position: relative;
	width: 290px;
	padding: 25px 50px 0 35px;
	clip-path: polygon(0 0, 100% 0, 100% 0%, 85% 100%, 0% 100%);
	height: 125px;
	z-index: 999;
	background-image: linear-gradient(to bottom, #2a3547, #2a3547);
}
.invoice-4 .invoice-logo img {
	height: 55px;
}
.invoice-4 .invoice-logo::after {
	width: 330px;
	position: absolute;
	content: "";
	height: 95px;
	top: 0;
	clip-path: polygon(0 0, 100% 0, 100% 0%, 90% 100%, 0% 100%);
	z-index: -9;
	background-image: linear-gradient(to bottom, #8ab935, #8ab935);
}
.invoice-4 .invoice-id .info {
	max-width: 270px;
	margin: 0 50px 0 auto;
	padding: 35px 0 0;
}
.invoice-4 .inv-header-1 {
	font-weight: 600;
	color: #8dba3d;
	font-size: 30px;
	text-transform: uppercase;
}
.invoice-4 p {
	font-size: 14px;
}
.invoice-4 .invoice-top {
	padding: 40px 50px 10px;
	font-size: 15px;
}
.invoice-4 .mb-30 {
	margin-bottom: 30px;
}
.invoice-4 .name {
	font-size: 18px;
	margin-bottom: 10px;
	text-transform: uppercase;
	color: #262525;
	font-weight: 500;
}
.invoice-content .invo-addr-1 {
	font-size: 15px;
	margin-bottom: 20px;
}
.invoice-4 .invoice-number-inner {
	max-width: 270px;
	margin-left: auto;
}
.invoice-4 .inv-title-1 {
	color: #8dba3d;
	margin-bottom: 5px;
	font-weight: 500;
	font-size: 16px;
}
.invoice-4 .invoice-center {
	padding: 0 50px 40px;
}
.invoice-4 .bg-active {
	background: #f3f3f3;
	color: #262525 !important;
}
.invoice-table tfoot, th, thead, tr {
	border: solid 1px #f3f2f2;
}
.invoice-table .table tr, .table tr {
	border: 1px solid #e9ecef;
}
.table > :not(:last-child) > :last-child > * {
	border-bottom-color: #f3f2f2;
}
.invoice-content .invoice-table th:first-child, .invoice-content .invoice-table td:first-child {
	text-align: left;
}
.invoice-4 .table > :not(caption) > * > * {
	padding: 13px 30px;
}
.invoice-4 .active-color {
	color: #648d1e !important;
} 
.invoice-4 .invoice-bottom {
	padding: 0 50px 10px;
}
.invoice-4 .invoice-bottom h3 {
	margin-bottom: 7px;
}
.invoice-4 .payment-method ul {
	list-style: none;
}
.invoice-4 .payment-method-list-1 {
	padding: 0;
}
.invoice-4 .invoice-contact {
	background-image: linear-gradient(to bottom, #2a3547, #2a3547);
	padding: 30px 50px 20px;
}
.invoice-4 .contact-info .link {
	margin: 0 30px 10px 0;
	color: #fff;
	font-size: 14px;
	line-height: 50px;
    text-decoration: none;
}
.invoice-4 .contact-info .link i {
	width: 50px;
	height: 50px;
	background: #ffffff;
	text-align: center;
	font-size: 20px;
	line-height: 50px;
	margin-right: 10px;
	color: #8dba3d;
	border-radius: 60px;
}
.invoice-4 .f-w-600{
	font-weight: 600;
}
@media print{
    @page{
        size: A4 portrait;
        margin: 0;
    }
    html, body {
        width: 210mm;
        height: 297mm;
    }
    body{
        background: #ffffff;
        padding-top: 0;
    }
    .page{
        box-shadow: none;
        margin-top: 0;
        margin-bottom: 0;
    }
    .navbar, .invoice-contact{
        display:none;
    }
}
</style>
</head>
<body>
<?php echo '
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
<div class="container">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <button class="btn btn-sm btn-success btn-print ps-4 pe-4"><i class="fa fa-print"></i> Print</button>
        </li>
    </ul>
</div>
</nav>
<div class="page">
    <div class="invoice-4 invoice-content">
        <div class="invoice-inner">
            <div class="invoice-info " id="invoice_wrapper">
                <div class="invoice-headar">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="invoice-logo">
                                <!-- logo started -->
                                <div class="logo">
                                    <img src="'.site_url('assets/images/logo/iicet_logo_light.png').'" alt="IICET">
                                </div>
                                <!-- logo ended -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="invoice-id">
                                <div class="info">
                                    <h1 class="inv-header-1">Invoice</h1>
                                    <p class="mb-1">Nomor: <span>#'.$invoice_code.'</span></p>
                                    <p class="mb-0">Tanggal: <span>'.$tanggal_mengisi.'</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invoice-top">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="invoice-number mb-30">
                                <h4 class="inv-title-1">Invoice Kepada:</h4>
                                <h2 class="name">'.$client_name.'</h2>
                                <p class="invo-addr-1">
                                    '.$client_phone.' <br>
                                    '.$client_email.'<br>
                                    '.$client_address.'<br>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="invoice-number mb-30">
                                <div class="invoice-number-inner">
                                    <h4 class="inv-title-1">Invoice Dari:</h4>
                                    <h2 class="name">IICET</h2>
                                    <p class="invo-addr-1">
                                        (0751) 8970975 <br>
                                        info@iicet.org <br>
                                        Jl. Bunda I No. 19 Ulak Karang - Padang <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invoice-center">
                    <div class="table-responsive">
                        <table class="table mb-0 invoice-table">
                            <thead class="bg-active">
                                <tr class="tr">
                                    <th width="65">No</th>
                                    <th>Deskripsi</th>
                                    <th width="200" class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>';
                                if($therapies->num_rows() > 0)
                                {
                                    $total = 0;
                                    $no = 1;
                                    foreach($therapies->result() as $ter)
                                    {
                                        $total = ($total + $ter->therapy_price);
                                        echo '
                                        <tr>
                                            <td>'.$no++.'</td>
                                            <td>'.$ter->therapy_name.'</td>
                                            <td class="text-end">Rp '.number_format($ter->therapy_price,0,',','.').'</td>
                                        </tr>
                                        ';
                                    }
                                    echo '
                                    <tr>
                                        <td></td>
                                        <td class="text-end f-w-600 active-color">Total</td>
                                        <td class="f-w-600 text-end active-color">Rp '.number_format($total,0,',','.').',-</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-end f-w-600 active-color">Diskon '.$diskon.'%</td>
                                        <td class="f-w-600 text-end active-color">- Rp '.number_format($jumlah_diskon,0,',','.').',-</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-end f-w-600 active-color">Grand Total</td>
                                        <td class="f-w-600 text-end active-color">Rp '.number_format(($total-$jumlah_diskon),0,',','.').',-</td>
                                    </tr>';
                                }
                                echo '
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="invoice-footer">
                    <div class="invoice-bottom">
                        <div class="row">
                            <div class="col-lg-6 col-md-5 col-sm-5">
                                <div class="payment-method mb-30">
                                    <h3 class="inv-title-1">Rekening Pembayaran</h3>
                                    <ul class="payment-method-list-1 text-14">
                                        <li><strong>Nomor Rekening:</strong> '.getconfig('bank_account_number').'</li>
                                        <li><strong>Atas Nama:</strong> '.getconfig('bank_account_name').'</li>
                                        <li><strong>Nama Bank:</strong> '.getconfig('bank_name').'</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-7 d-none">
                                <div class="terms-conditions mb-30">
                                    <h3 class="inv-title-1">Syarat &amp; Ketentuan</h3>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-contact">
                        <div class="row g-0">
                            <div class="col-sm-12">
                                <div class="contact-info d-flex">
                                    <div class="col text-center"><a class="link"><i class="fa fa-phone"></i> (0751) 8970975</a></div>
                                    <div class="col text-center"><a class="link"><i class="fa fa-envelope"></i> info@iicet.org</a></div>
                                    <div class="col text-center"><a class="link"><i class="fa fa-map-marker-alt"></i> Padang, Indonesia</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery-3.4.1.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
<script>
$( document ).ready(function() {
    console.log( "print ready!" );
    window.print();
});
$('.btn-print').on('click',function(){
    window.print();
});
</script>
</body>
</html>