<?php
define ('K_PATH_IMAGES', realpath(APPPATH . '../assets/images/logo/'));
define ('PDF_HEADER_LOGO', realpath(APPPATH . '../assets/images/logo/logo.png'));

require_once(realpath(APPPATH . 'libraries/tcpdf/config/tcpdf_config.php'));
require_once(realpath(APPPATH . 'libraries/tcpdf/tcpdf.php')); 

class MYPDF extends TCPDF { 
    public function Header() {
        $cop_logo  = realpath(APPPATH . '../assets/images/logo/cop_pdf.png');
        $this->Image($cop_logo, $x=20, $y=8, $w=36, $h=0, $type='png', $link='', $align='',  $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=true);
        $this->SetFont('helvetica', 'B', 13);
        $this->MultiCell($w=(210 - 90), $h=0, $txt='Indonesian Institute for Counseling', $border=0, $align='L', $fill=false, $ln=1, $x=61, $y=8, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
        $this->MultiCell($w=(210 - 90), $h=0, $txt='Education and Therapy (IICET)', $border=0, $align='L', $fill=false, $ln=1, $x=61, $y=13, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
        $this->MultiCell($w=(210 - 40), $h=0, $txt='<div style="font-weight:normal;font-size:11px;">Jl. Bunda I No. 19 Ulak Karang - Padang - Sumatera Barat - Indonesia 25131<br>Telp. (0751) 8970975,  Email: info@iicet.org | Home Page: http://iicet.org</div>', $border=0, $align='C', $fill=false, $ln=1, $x=20, $y=20, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
        $this->MultiCell($w=(210 - 40), $h=0, $txt='<div style="font-weight:bold;font-size:14px;border-bottom:2px solid #000;"></div>', $border=0, $align='C', $fill=false, $ln=1, $x=20, $y=23, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
    }
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', '', 8);
        $footer_logo  = realpath(APPPATH . '../assets/images/logo/footer_pdf.png');
        $this->Image($footer_logo, $x=20, $y=(330 - 15), $w=20, $h=0, $type='png', $link='', $align='', $resize=false, $dpi=72, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false);
        $this->MultiCell($w=(210 - 40), $h=0, $txt='<div style="color:#666666;line-height:11px;"><span style="font-weight:bold;font-size:8px;">KLINIK KONSELING DAN HIPNOTERAPI | </span><span style="font-size:8px;">Jl. Bunda 1 No.19 UlakKarang-Padang</span><br><span style="font-size:8px;">Tel. +(0751) 8970975 Email. info@iicet.org Website. www.iicet.org</span></div>', $border=0, $align='L', $fill=false, $ln=1, $x=42, $y=(330-15), $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
        $this->MultiCell($w=(210-40), $h=0, $txt='<div style="color:#007200;line-height:11px;"><span style="font-weight:bold;font-style:italic;font-size:9px;">"Semoga Allah selalu berikan kesehatan<br>untuk jiwa,tubuh dan iman kita"</span>', $border=0, $align='R', $fill=false, $ln=1, $x=20, $y=(330-15), $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', false);
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// protect pdft
// $pdf->SetProtection(array('print'), '', null, 1, null); // print only

//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetHeaderMargin(45);
$pdf->SetFooterMargin(40);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 40);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// CONTENT -------------------------------------------------------------

// add new page P = Portrait , L = Landscape
$pdf->AddPage('P', 'F4');

$pdf->SetFont('helvetica', '', 9);
$pdf->setCellHeightRatio(1.25);

$margin_left    = 20;
$margin_right   = 20;
$page_width     = 210; // F4 = 210mm x 330mm
$cell_width     = ($page_width - ($margin_left + $margin_right));
//REKAM PSIKOLOGIS KLIEN (RPK)
$pdf->MultiCell($w=$cell_width, $h=0, $txt='<div style="font-size:13px;font-weight:bold;padding-top:5px;">REKAM PSIKOLOGIS KLIEN (RPK)</div>', $border=0, $align='C', $fill=false, $ln=1, $x=$margin_left, $y=34, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=25, $h=0, $txt='<div style="font-size:12px;font-weight:bold;padding-top:5px;">RAHASIA</div>', $border=0, $align='C', $fill=false, $ln=1, $x=164, $y=33, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=25, $h=8, $txt='', $border=1, $align='C', $fill=false, $ln=1, $x=164, $y=31, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=20, $h=0, $txt='<div style="font-size:12px;font-weight:bold;padding-top:5px;">Sesi '.$sesi.'</div>', $border=0, $align='R', $fill=false, $ln=1, $x=171, $y=40, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);


$table_identitas = '
<table border="0" cellpadding="1">
<tr>
    <td width="90"><span style="font-weight:bold;">ID</span></td>
    <td width="222">'.$client_no.'</td>
    <td width="100"><span style="font-weight:bold;">Email</span></td>
    <td width="191">'.$client_email.'</td>
</tr>
<tr>
    <td width="90"><span style="font-weight:bold;">Nama</span></td>
    <td width="222">'.$client_name.'</td>
    <td width="100"><span style="font-weight:bold;">Tgl. Lahir</span></td>
    <td width="191">'.indo_date($client_birthday).' / '.umur_saat_dass($client_birthday,date('Y-m-d')).' Tahun</td>
</tr>
<tr>
    <td width="90"><span style="font-weight:bold;">Nomor HP</span></td>
    <td width="222">'.$client_phone.'</td>
    <td width="100"><span style="font-weight:bold;">Jenis Kelamin</span></td>
    <td width="191">'.jenis_kelamin($client_gender).'</td>
</tr>
</table>';

$table_skor = '
<table border="1" cellpadding="2">';
    if($kategori_soal->num_rows() > 0)
    {
        $table_skor .= '
        <thead>
            <tr>
                <th width="365"></th>
                <th width="80"><span style="font-weight:bold;">Skor</span></th>
                <th width="150"><span style="font-weight:bold;">Kategori</span></th>
            </tr>
        </thead>
        <tbody>';
            foreach ($kategori_soal->result() as $kategori)
            {
                $table_skor .= '
                <tr>
                    <td width="365">'.$kategori->kategori_nama.'</td>';
                    $jumlah = 0; 
                    foreach ($this->rekam_model->dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
                    {
                        $jumlah = $jumlah + $nilai->jawaban;
                    }
                    $table_skor .= '
                    <td width="80">
                        <span>'.$jumlah.'</span>
                    </td>
                    <td width="150">
                        <span>'.dass_result($kategori->soal_kategori_id, $jumlah).'</span>
                    </td>
                </tr>';
            }
            $table_skor .= '
        </tbody>';
    }
    $table_skor .= '
</table>';

$table_keterangan = '
<table border="1" cellpadding="2">
    <tbody>
        <tr class="bg-thead"><td colspan="6" style="text-align:center;"><strong>SKOR</strong></td></tr>
        <tr>
            <td>&nbsp;</td>
            <td style="text-align:center;"><strong>Normal</strong></td>
            <td style="text-align:center;"><strong>Ringan</strong></td>
            <td style="text-align:center;"><strong>Sederhana</strong></td>
            <td style="text-align:center;"><strong>Berat</strong></td>
            <td style="text-align:center;"><strong>Sangat Berat</strong></td>
        </tr>
        <tr>
            <td>Stress</td>
            <td style="text-align:center;">0-7</td>
            <td style="text-align:center;">8-9</td>
            <td style="text-align:center;">10-13</td>
            <td style="text-align:center;">14-17</td>
            <td style="text-align:center;">18+</td>
        </tr>
        <tr>
            <td>Anxiety</td>
            <td style="text-align:center;">0-4</td>
            <td style="text-align:center;">5-6</td>
            <td style="text-align:center;">7-8</td>
            <td style="text-align:center;">9-10</td>
            <td style="text-align:center;">11+</td>
        </tr>
        <tr>
            <td>Depression</td>
            <td style="text-align:center;">0-5</td>
            <td style="text-align:center;">6-7</td>
            <td style="text-align:center;">8-10</td>
            <td style="text-align:center;">11-14</td>
            <td style="text-align:center;">15+</td>
        </tr>
        <tr>
            <td>INTEREPTASI</td>
            <td style="text-align:center;">Kehidupan<br>yang bahagia</td>
            <td style="text-align:center;">Sehat</td>
            <td style="text-align:center;">Perlu<br>pengelolaan</td>
            <td style="text-align:center;">Temui Konselor</td>
            <td style="text-align:center;">Temui Konselor &amp; Rujuk<br>psikiater jika perlu</td>
        </tr>
    </tbody>
</table>';

$intervensi_dan_treatment = '';
if ($this->rekam_model->therapy_by_test($test_id)->num_rows() > 0)
{
    $intervensi_dan_treatment .= '';
    $int_no = 1;
    foreach($this->rekam_model->therapy_by_test($test_id)->result() as $tbt )
    {
        $intervensi_dan_treatment .= '<span style="font-size: 12px;">'.$tbt->therapy_name.'</span>';
        if($this->rekam_model->therapy_by_test($test_id)->num_rows() > $int_no){ $intervensi_dan_treatment .= ', '; }
        $int_no++;
    }
    $intervensi_dan_treatment .= '';
}

$deskripsi_kondisi_psikologis = '
<p style="font-size: 12px;">'.nl2br($deskripsi_masalah).'</p>
<p><span style="font-size: 12px;font-weight:bold;">Intervensi dan Treatment</span><br>'.$intervensi_dan_treatment.'</p>
<p><span style="font-size: 12px;font-weight:bold;">Tindak Lanjut</span><br><span style="font-size: 12px;">'.tindak_lajut_rpk($tindak_lanjut).'</span></p>
<p style="text-align: right;"><br>
Padang, '.indo_date($tanggal_ditetapkan).'<br>
Konselor / Terapis</p>
<div style="text-align:right;">'.$qrcode_image.'</div>
<p style="text-align: right;"><strong>( '.$konselor.' )</strong></p>
';
$pdf->MultiCell($w=$cell_width, $h=0, $txt='<div style="font-size: 12px;font-weight:bold;">Identitas</div>', $border=0, $align='L', $fill=false, $ln=1, $x=($margin_left-1), $y=42, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=$cell_width, $h=0, $txt=''.$table_identitas.'', $border=0, $align='L', $fill=false, $ln=1, $x=19, $y=47, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=$cell_width, $h=0, $txt='<div style="font-size: 12px;font-weight:bold;">Kondisi Kesehatan Mental Berdasarkan DASS</div>', $border=0, $align='L', $fill=false, $ln=1, $x=$margin_left, $y=65, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=$cell_width, $h=0, $txt=''.$table_skor.'', $border=0, $align='L', $fill=false, $ln=1, $x=$margin_left, $y=70, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=$cell_width, $h=0, $txt='<div style="font-size: 12px;font-weight:bold;">Keterangan</div>', $border=0, $align='L', $fill=false, $ln=1, $x=$margin_left, $y=93, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=$cell_width, $h=0, $txt=''.$table_keterangan.'', $border=0, $align='L', $fill=false, $ln=1, $x=$margin_left, $y=98, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=$cell_width, $h=0, $txt='<div style="font-size: 12px;font-weight:bold;">Deskripsi Kondisi Psikologis</div>', $border=0, $align='L', $fill=false, $ln=1, $x=$margin_left, $y=139, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);
$pdf->MultiCell($w=$cell_width, $h=0, $txt=''.$deskripsi_kondisi_psikologis.'', $border=0, $align='L', $fill=false, $ln=1, $x=$margin_left, $y=144, $reseth=true, $stretch=0, $ishtml=true, $autopadding=true, $maxh=0, 'T', true);








$pdf->lastPage();

// END CONTENT ---------------------------------------------------------
$pdf->Output('RPK-'.$client_id.'.pdf', 'I');

/*
I : send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
D : send to the browser and force a file download with the name given by name.
F : save to a local server file with the name given by name.
S : return the document as a string (name is ignored).
FI : equivalent to F + I option
FD : equivalent to F + D option
E : return the document as base64 mime multi-part email attachment (RFC 2045)
*/