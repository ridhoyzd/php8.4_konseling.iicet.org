<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice '.$invoice_code.'</title>
    <link href="'.base_url('assets/plugins/bootstrap/css/bootstrap.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/fontawesome/css/all.min.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/perfect-scrollbar/perfect-scrollbar.css').'" rel="stylesheet">
    <link href="'.base_url('assets/plugins/sweetalert2/sweetalert2.min.css').'" rel="stylesheet">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/app.css').'">
    <link rel="stylesheet" href="'.base_url('assets/themes/admin/css/responsive.css').'">
    <link href="'.base_url('assets/images/favicon.png').'" rel="icon">
    <script> var base_url = "'.base_url().'";var csfr_token_name = "'.$this->security->get_csrf_token_name().'"; var csfr_cookie_name = "'.$this->config->item('csrf_cookie_name').'";</script>
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
                    <div class="breadcrumb-title">#'.$invoice_code.'</div>
                    <div class="ms-auto top-action">
                        <button class="btn btn-primary" id="downloadPdf" data-id="'.$test_id.'"><i class="fa fa-file-pdf"></i> Download PDF</button>
                        <a href="'.site_url('invoice/print/'.$test_id.'').'" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>

                <!-- Main Content Start -->
                <input type="hidden" value="';echo isset($parent_navlink)? $parent_navlink : ''; echo '" id="ParentNavLink" class="form-control" readonly="readonly">
                <div class="card main-card">
                    <div class="card-bodies">


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
                                        '.form_open($form_action, array('id' => 'AjaxForm','autocomplete' => 'off')).'
                                        '.form_hidden('test_id', $test_id).'
                                        '.form_hidden('client_id', $client_id).'
                                        <div class="table-responsive">
                                            <table class="table mb-0 invoice-table">
                                                <thead class="bg-active">
                                                    <tr>
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
                                                            <td class="f-w-600 text-end active-color">
                                                                Rp <span id="Total">'.number_format($total,0,',','.').',-</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-end f-w-600 active-color">
                                                                <div class="input-diskon d-flex d-flex align-items-center justify-content-end">
                                                                    <span class="me-2">Diskon </span>
                                                                    <div class="input-group" style="width:115px;">';

                                                                        $v_val = isset($diskon) ? $diskon : '';
                                                                        $v_opt = array();
                                                                        $v_att = array('class' => 'form-select', 'id' => 'Diskon' );
                                                                        
                                                                        for ($i=0; $i <= 100 ; $i++) { 
                                                                            $v_opt[$i] = ''.$i.'';
                                                                        }

                                                                        echo form_dropdown('diskon', $v_opt, $v_val, $v_att).'
                                                                        <span class="input-group-text" id="basic-diskon">%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="f-w-600 text-end active-color">
                                                                - Rp <span id="JumlahDiskon">'.number_format($jumlah_diskon,0,',','.').',-</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-end f-w-600 active-color">Grand Total</td>
                                                            <td class="f-w-600 text-end active-color">
                                                                Rp <span id="TotalBayar">'.number_format(($total - $jumlah_diskon),0,',','.').',-</span>
                                                                <input type="hidden" value="'.$total.'" id="TotalInvoice" readonly="readonly" name="total_invoice" class="form-control">
                                                                <input type="hidden" value="'.($total - $jumlah_diskon).'" id="InputTotalBayar" readonly="readonly" name="total_bayar" class="form-control">
                                                                <input type="hidden" value="'.$jumlah_diskon.'" id="InputDiskon" readonly="readonly" name="input_diskon" class="form-control">
                                                            </td>
                                                        </tr>';
                                                    }
                                                    echo '
                                                </tbody>
                                            </table>
                                            <div class="text-end mt-3">
                                                <button type="submit" class="btn btn-sm btn-primary" id="BtnSubmit"><i class="fa fa-save"></i> Set Diskon</button>
                                            </div>
                                        </div>
                                        '.form_close().'
                                    </div>
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
        $(function() {
            LimpatoEditor.InitFormSubmit();
        });
        $('#downloadPdf').on('click',function(){
            var id = $(this).attr('data-id');
            window.location.href = "<?php echo site_url('invoice/pdf/'.$test_id.'');?>";
        });
        function FormatRibuan(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
        }
        $('#Diskon').on( "change", function() {
            var harga = $('#TotalInvoice').val();
            var diskon = $(this).val();
            var total_diskon = (diskon * harga )/ 100;
            var total_bayar = (harga - total_diskon);
            $('#TotalBayar').html(FormatRibuan(total_bayar));
            $('#JumlahDiskon').html(FormatRibuan(total_diskon));
            $('#InputTotalBayar').val(total_bayar);
            $('#InputDiskon').val(total_diskon);
            console.log('diskon : Rp ' + total_diskon);
            console.log('Total : Rp ' + total_bayar);
        });
    </script>
</body>
</html>
