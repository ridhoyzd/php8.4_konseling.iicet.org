<?php

require_once(APPPATH . 'libraries/pdf-php8/vendor/autoload.php');

function rekam_pdf_remove_png_icc_profile($png_data)
{
    if (substr($png_data, 0, 8) !== "\x89PNG\r\n\x1a\n") {
        return $png_data;
    }

    $output = substr($png_data, 0, 8);
    $offset = 8;
    $length = strlen($png_data);
    while ($offset + 12 <= $length) {
        $chunk_length = unpack('Nlength', substr($png_data, $offset, 4))['length'];
        if ($offset + 12 + $chunk_length > $length) {
            break;
        }

        $chunk_type = substr($png_data, $offset + 4, 4);
        if ($chunk_type !== 'iCCP') {
            $output .= substr($png_data, $offset, 12 + $chunk_length);
        }

        $offset += 12 + $chunk_length;
        if ($chunk_type === 'IEND') {
            break;
        }
    }

    return $output;
}

$pdf_font_root = APPPATH . 'libraries/pdf-php8/vendor/tecnickcom/tc-lib-pdf-font/target/fonts';
$fallback_font_root = APPPATH . 'libraries/pdf-php8/fonts';
$pdf_font_variants = ['helvetica', 'helveticab', 'helveticai', 'helveticabi'];

foreach ($pdf_font_variants as $pdf_font_variant) {
    $pdf_font_paths = [
        $pdf_font_root . '/' . $pdf_font_variant . '.json',
        $pdf_font_root . '/core/' . $pdf_font_variant . '.json',
        $fallback_font_root . '/' . $pdf_font_variant . '.json',
        $fallback_font_root . '/core/' . $pdf_font_variant . '.json',
    ];
    if (!array_filter($pdf_font_paths, 'is_readable')) {
        throw new RuntimeException('PDF font definition not found: ' . $pdf_font_variant . '.json');
    }
}

$pdf_font_file = null;
foreach ([$pdf_font_root . '/helvetica.json', $pdf_font_root . '/core/helvetica.json',
          $fallback_font_root . '/helvetica.json', $fallback_font_root . '/core/helvetica.json'] as $font_path) {
    if (is_readable($font_path)) {
        $pdf_font_file = realpath($font_path);
        break;
    }
}

if ($pdf_font_file === null) {
    throw new RuntimeException('PDF font definition not found: helvetica.json');
}

if (!defined('K_PATH_FONTS')) {
    define('K_PATH_FONTS', dirname($pdf_font_file));
}

// CONTENT -------------------------------------------------------------

$margin_left    = 20;
$margin_right   = 20;
$page_width     = 210;
$cell_width     = ($page_width - ($margin_left + $margin_right));


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
<table width="100%" border="1" cellpadding="2" style="table-layout:fixed;">
';
    if($kategori_soal->num_rows() > 0)
    {
        $table_skor .= '
        <thead>
            <tr>
                <th width="60%"></th>
                <th width="15%" style="text-align:center;"><span style="font-weight:bold;">Skor</span></th>
                <th width="25%" style="text-align:center;"><span style="font-weight:bold;">Kategori</span></th>
            </tr>
        </thead>
        <tbody>';
            foreach ($kategori_soal->result() as $kategori)
            {
                $table_skor .= '
                <tr>
                    <td width="60%">'.$kategori->kategori_nama.'</td>';
                    $jumlah = 0; 
                    foreach ($this->rekam_model->dass_by_group($test_id, $kategori->soal_kategori_id)->result() as $nilai)
                    {
                        $jumlah = $jumlah + $nilai->jawaban;
                    }
                    $table_skor .= '
                    <td width="15%" style="text-align:center;">
                        <span>'.$jumlah.'</span>
                    </td>
                    <td width="25%">
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
<table width="100%" border="1" cellpadding="2" style="table-layout:fixed;">
    <tbody>
        <tr class="bg-thead"><td colspan="6" style="text-align:center;"><strong>SKOR</strong></td></tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="16%" style="text-align:center;"><strong>Normal</strong></td>
            <td width="16%" style="text-align:center;"><strong>Ringan</strong></td>
            <td width="16%" style="text-align:center;"><strong>Sederhana</strong></td>
            <td width="16%" style="text-align:center;"><strong>Berat</strong></td>
            <td width="16%" style="text-align:center;"><strong>Sangat Berat</strong></td>
        </tr>
        <tr>
            <td width="20%">Stress</td>
            <td width="16%" style="text-align:center;">0-7</td>
            <td width="16%" style="text-align:center;">8-9</td>
            <td width="16%" style="text-align:center;">10-13</td>
            <td width="16%" style="text-align:center;">14-17</td>
            <td width="16%" style="text-align:center;">18+</td>
        </tr>
        <tr>
            <td width="20%">Anxiety</td>
            <td width="16%" style="text-align:center;">0-4</td>
            <td width="16%" style="text-align:center;">5-6</td>
            <td width="16%" style="text-align:center;">7-8</td>
            <td width="16%" style="text-align:center;">9-10</td>
            <td width="16%" style="text-align:center;">11+</td>
        </tr>
        <tr>
            <td width="20%">Depression</td>
            <td width="16%" style="text-align:center;">0-5</td>
            <td width="16%" style="text-align:center;">6-7</td>
            <td width="16%" style="text-align:center;">8-10</td>
            <td width="16%" style="text-align:center;">11-14</td>
            <td width="16%" style="text-align:center;">15+</td>
        </tr>
        <tr>
            <td width="20%">INTEREPTASI</td>
            <td width="16%" style="text-align:center;">Kehidupan<br>yang bahagia</td>
            <td width="16%" style="text-align:center;">Sehat</td>
            <td width="16%" style="text-align:center;">Perlu<br>pengelolaan</td>
            <td width="16%" style="text-align:center;">Temui Konselor</td>
            <td width="16%" style="text-align:center;">Temui Konselor &amp; Rujuk<br>psikiater jika perlu</td>
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
$cop_logo_file = realpath(APPPATH . '../assets/images/logo/cop_pdf.png');
$cop_logo = '';
if ($cop_logo_file !== false) {
    $cop_logo_png = rekam_pdf_remove_png_icc_profile(file_get_contents($cop_logo_file));
    $cop_logo = '<img src="data:image/png;base64,' . base64_encode($cop_logo_png) . '" style="width:32mm;">';
}

$qrcode_image = preg_replace_callback(
    '/src=["\']([^"\']+)["\']/i',
    function ($matches) {
        if (strpos($matches[1], '/assets/qrcode/') === false) {
            return $matches[0];
        }

        $qrcode_file = realpath(FCPATH . '/assets/qrcode/' . basename($matches[1]));
        if ($qrcode_file === false) {
            return $matches[0];
        }

        $qrcode_png = rekam_pdf_remove_png_icc_profile(file_get_contents($qrcode_file));
        return 'src="data:image/png;base64,' . base64_encode($qrcode_png) . '"';
    },
    $qrcode_image
);

$html = '<div style="font-family:helvetica;font-size:9pt;color:#000;">'
    . '<table width="100%" cellspacing="0" cellpadding="0"><tr>'
    . '<td width="22%">' . $cop_logo . '</td>'
    . '<td width="78%"><div style="font-size:14pt;font-weight:bold;">Indonesian Institute for Counseling</div>'
    . '<div style="font-size:14pt;font-weight:bold;">Education and Therapy (IICET)</div>'
    . '<div style="font-size:8pt;">Jl. Bunda I No. 19 Ulak Karang - Padang - Sumatera Barat - Indonesia 25131<br>'
    . 'Telp. (0751) 8970975, Email: info@iicet.org | Home Page: http://iicet.org</div></td>'
    . '</tr></table><hr>'
    . '<div style="text-align:center;font-size:13pt;font-weight:bold;">REKAM PSIKOLOGIS KLIEN (RPK)</div>'
    . '<div style="text-align:right;">RAHASIA<br>Sesi ' . htmlspecialchars((string) $sesi, ENT_QUOTES, 'UTF-8') . '</div>'
    . '<div style="margin-top:3mm;font-size:12pt;font-weight:bold;">Identitas</div>' . $table_identitas
    . '<div style="margin-top:4mm;font-size:12pt;font-weight:bold;">Kondisi Kesehatan Mental Berdasarkan DASS</div>' . $table_skor
    . '<div style="margin-top:4mm;font-size:12pt;font-weight:bold;">Keterangan</div>' . $table_keterangan
    . '<div style="margin-top:4mm;font-size:12pt;font-weight:bold;">Deskripsi Kondisi Psikologis</div>' . $deskripsi_kondisi_psikologis
    . '<style>table{border-collapse:collapse;width:100%;}td,th{vertical-align:top;}'
    . 'table[border="1"] td,table[border="1"] th{border:1px solid #000;padding:1mm;}'
    . '</style></div>';

$pdf = new \Com\Tecnick\Pdf\Tcpdf('mm');
$pdf->setPDFFilename('RPK-' . $client_id . '.pdf');
$pdf->addPage(['format' => 'F4', 'orientation' => 'P']);
$bfont = $pdf->font->insert(
    $pdf->pon,
    'helvetica',
    '',
    9,
    null,
    null,
    $pdf_font_file
);
$pdf->page->addContent($bfont['out']);
$pdf->addHTMLCell($html, 10, 8, 190);
$pdf->downloadPDF($pdf->getOutPDFString());