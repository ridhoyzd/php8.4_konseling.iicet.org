<?php
define ('K_PATH_IMAGES', realpath(APPPATH . '../assets/images/logo/'));
define ('PDF_HEADER_LOGO', realpath(APPPATH . '../assets/images/logo/logo.png'));

require_once(realpath(APPPATH . 'libraries/tcpdf/config/tcpdf_config.php'));
require_once(realpath(APPPATH . 'libraries/tcpdf/tcpdf.php')); 

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
// protect pdft
//$pdf->SetProtection(array('print'), '', '', 3, null); // print only
//$pdf->SetProtection(array('print'), $user_pass='', $owner_pass=null, $mode=3, null); // print only

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetMargins(0,0,0,0);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// add new page P = Portrait , L = Landscape
$pdf->AddPage('P', 'A4');
$logo_kiri   = realpath(APPPATH . '../assets/images/logo/invoice_logo.png'); 

// Set Logo
// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
$pdf->Image($logo_kiri, $x=0, $y=0, $w=81, $h=0, $type='png', $link='', $align='',  $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=true);

$pdf->SetFont('helvetica', '', 10);
$pdf->setCellHeightRatio(1.25);

$margin_left    = 10;
$margin_right   = 10;
$page_width     = 210; // A4 = 210mm x 297mm
$cell_width     = ($page_width - ($margin_left + $margin_right));

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$pdf->MultiCell($w=75, $h=0, $txt='<span style="color:#8dba3d;font-weight:bold;font-size:24px;">INVOICE</span>', $border=0, $align='L', $fill=false, $ln=1, $x=125, $y=10, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
$pdf->MultiCell($w=75, $h=0, $txt='<span style="color:#535353;font-size:13px;">Nomor: </span><span style="color:#535353;font-size:13px;">'.$invoice_code.'</span>', $border=0, $align='L', $fill=false, $ln=1, $x=125, $y=20, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
$pdf->MultiCell($w=75, $h=0, $txt='<span style="color:#535353;font-size:13px;">Tanggal: </span><span style="color:#535353;font-size:13px;">'.$tanggal_mengisi.'</span>', $border=0, $align='L', $fill=false, $ln=1, $x=125, $y=25, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);

$invoice_to = '
<table width="100%" border="0" cellpadding="2">
<tr><td style="color:#8dba3d;font-weight:bold;font-size:14px;">Invoice Kepada:</td></tr>
<tr><td style="color:#262525;font-weight:bold;font-size:16px;"><div>'.strtoupper($client_name).'<br></div></td></tr>
<tr><td style="color:#535353;">'.$client_phone.' <br>'.$client_email.'<br>'.$client_address.'</td></tr>
</table>';
$invoice_from = '
<table width="100%" border="0" cellpadding="2">
<tr><td style="color:#8dba3d;font-weight:bold;font-size:14px;">Invoice Dari:</td></tr>
<tr><td style="color:#262525;font-weight:bold;font-size:16px;"><div>IICET<br></div></td></tr>
<tr><td style="color:#535353;">(0751) 8970975<br>info@iicet.org<br>Jl. Bunda I No. 19 Ulak Karang - Padang</td></tr>
</table>';
$table_invoice = '
<table border="0" cellpadding="13" cellspacing="0" style="border-color:#e9ecef;border:1px solid #e9ecef;">
<thead class="bg-active">
<tr class="tr">
<th width="55" style="border-color:#e9ecef;background-color:#f3f3f3;font-weight:bold;">No</th>
<th width="493" style="border-color:#e9ecef;background-color:#f3f3f3;font-weight:bold;">Deskripsi</th>
<th width="120" style="border-color:#e9ecef;background-color:#f3f3f3;font-weight:bold;">Jumlah</th>
</tr>
</thead>
<tbody>';
if($therapies->num_rows() > 0)
{
$inv_total = 0;
$inv_no = 1;
foreach($therapies->result() as $inv_row)
{
$inv_total = ($inv_total + $inv_row->therapy_price);
$table_invoice .= '
<tr style="border: 1px solid #e9ecef;border-color:#e9ecef;">
<td width="55" style="border-color:#e9ecef;">'.$inv_no++.'</td>
<td width="493" style="border-color:#e9ecef;">'.$inv_row->therapy_name.'</td>
<td width="120" style="border-color:#e9ecef;">Rp '.number_format($inv_row->therapy_price,0,',','.').'</td>
</tr>';
}
$table_invoice .= '
<tr style="border: 1px solid #e9ecef;border-color:#e9ecef;">
<td width="55" style="border-color:#e9ecef;"></td>
<td width="493" style="border-color:#e9ecef;text-align:right;"><span style="color:#648d1e;font-weight:bold;">Total</span></td>
<td width="120" style="border-color:#e9ecef;"><span style="color:#648d1e;font-weight:bold;">Rp '.number_format($inv_total,0,',','.').',-</span></td>
</tr>
<tr style="border: 1px solid #e9ecef;border-color:#e9ecef;">
<td width="55" style="border-color:#e9ecef;"></td>
<td width="493" style="border-color:#e9ecef;text-align:right;"><span style="color:#648d1e;font-weight:bold;">Diskon '.$diskon.'%</span></td>
<td width="120" style="border-color:#e9ecef;"><span style="color:#648d1e;font-weight:bold;">- Rp '.number_format($jumlah_diskon,0,',','.').',-</span></td>
</tr>
<tr style="border: 1px solid #e9ecef;border-color:#e9ecef;">
<td width="55" style="border-color:#e9ecef;"></td>
<td width="493" style="border-color:#e9ecef;text-align:right;"><span style="color:#648d1e;font-weight:bold;">Grand Total</span></td>
<td width="120" style="border-color:#e9ecef;"><span style="color:#648d1e;font-weight:bold;">Rp '.number_format(($inv_total - $jumlah_diskon),0,',','.').',-</span></td>
</tr>';
}
/*
<tr style="border: 1px solid #e9ecef;border-color:#e9ecef;">
<td width="55" style="border-color:#e9ecef;">1</td>
<td width="493" style="border-color:#e9ecef;">'.$therapy_name.'</td>
<td width="120" style="border-color:#e9ecef;">'.$therapy_price.'</td>
</tr>
<tr style="border: 1px solid #e9ecef;border-color:#e9ecef;">
<td width="55" style="border-color:#e9ecef;"></td>
<td width="493" style="border-color:#e9ecef;text-align:right;"><span style="color:#648d1e;font-weight:bold;">Grand Total</span></td>
<td width="120" style="border-color:#e9ecef;"><span style="color:#648d1e;font-weight:bold;">Rp '.$therapy_price.',-</span></td>
</tr>
*/
$table_invoice .= '
</tbody>
</table>
<p><br></p>
<p><br> </p>
<table width="100%" border="0" cellpadding="2">
<tr><td style="color:#8dba3d;font-weight:bold;font-size:14px;">Rekening Pembayaran</td></tr>
<tr><td><span style="color:#262525;font-weight:bold;">Nomor Rekening: </span><span style="color:#535353;">'.getconfig('bank_account_number').'</span></td></tr>
<tr><td><span style="color:#262525;font-weight:bold;">Atas Nama: </span><span style="color:#535353;">'.getconfig('bank_account_name').'</span></td></tr>
<tr><td><span style="color:#262525;font-weight:bold;">Nama Bank: </span><span style="color:#535353;">'.getconfig('bank_name').'</span></td></tr>
</table>';
$pdf->MultiCell($w=($cell_width/3)+10, $h=0, $txt=$invoice_to, $border=0, $align='L', $fill=false, $ln=0, $x=$margin_left, $y=45, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
$pdf->MultiCell($w=($cell_width/3)+10, $h=0, $txt=$invoice_from, $border=0, $align='L', $fill=false, $ln=0, $x=125, $y=45, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
$pdf->MultiCell($w=$cell_width, $h=0, $txt=$table_invoice, $border=0, $align='L', $fill=false, $ln=0, $x=$margin_left, $y=90, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
//$pdf->MultiCell($w=$cell_width, $h=0, $txt=$payment_account, $border=0, $align='L', $fill=false, $ln=0, $x=$margin_left, $y=135, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('Invoice-'.$test_id.'.pdf', 'D');