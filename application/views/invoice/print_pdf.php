<?php

require_once(APPPATH . 'libraries/pdf-php8/vendor/autoload.php');

$pdf_font_root = APPPATH . 'libraries/pdf-php8/vendor/tecnickcom/tc-lib-pdf-font/target/fonts';
$fallback_font_root = APPPATH . 'libraries/pdf-php8/fonts';
$pdf_font_file = '';
$pdf_font_variants = ['helvetica', 'helveticab', 'helveticai', 'helveticabi'];
$pdf_missing_fonts = [];

foreach ($pdf_font_variants as $pdf_font_variant) {
    $pdf_font_paths = [
        $pdf_font_root . '/' . $pdf_font_variant . '.json',
        $pdf_font_root . '/core/' . $pdf_font_variant . '.json',
    ];
    if (!array_filter($pdf_font_paths, 'is_readable')) {
        $pdf_font_root = $fallback_font_root;
        break;
    }
}

if (!is_dir($pdf_font_root)) {
    $pdf_font_root = $fallback_font_root;
}

$pdf_font_root = realpath($pdf_font_root);
foreach ($pdf_font_variants as $pdf_font_variant) {
    $pdf_font_paths = [
        $pdf_font_root !== false ? $pdf_font_root . '/' . $pdf_font_variant . '.json' : '',
        $pdf_font_root !== false ? $pdf_font_root . '/core/' . $pdf_font_variant . '.json' : '',
    ];
    if (!array_filter($pdf_font_paths, 'is_readable')) {
        $pdf_missing_fonts[] = $pdf_font_variant . '.json';
    }
}

if ($pdf_font_root !== false && is_readable($pdf_font_root . '/core/helvetica.json')) {
    $pdf_font_file = realpath($pdf_font_root . '/core/helvetica.json');
} elseif ($pdf_font_root !== false && is_readable($pdf_font_root . '/helvetica.json')) {
    $pdf_font_file = realpath($pdf_font_root . '/helvetica.json');
}

if ($pdf_font_file === false || $pdf_font_file === '') {
    throw new RuntimeException(
        'PDF font definition not found. Expected helvetica.json in: '
        . APPPATH . 'libraries/pdf-php8/vendor/tecnickcom/tc-lib-pdf-font/target/fonts/core'
    );
}

if ($pdf_missing_fonts !== []) {
    throw new RuntimeException(
        'PDF font definitions are unreadable: ' . implode(', ', $pdf_missing_fonts)
        . '. Font root: ' . ($pdf_font_root === false ? 'not found' : $pdf_font_root)
    );
}

if (!defined('K_PATH_FONTS')) {
    define('K_PATH_FONTS', $pdf_font_root);
}

function pdf_remove_png_icc_profile($png_data)
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
        $chunk_data = substr($png_data, $offset + 8, $chunk_length);
        if ($chunk_type !== 'iCCP') {
            $output .= substr($png_data, $offset, 8 + $chunk_length + 4);
        }
        $offset += 12 + $chunk_length;
        if ($chunk_type === 'IEND') {
            break;
        }
    }

    return $offset < $length ? $output . substr($png_data, $offset) : $output;
}

$inv_total = 0;
$rows = '';
$inv_no = 1;
if ($therapies->num_rows() > 0) {
    foreach ($therapies->result() as $inv_row) {
        $inv_total += $inv_row->therapy_price;
        $rows .= '<tr><td class="number">' . $inv_no++ . '</td>'
            . '<td>' . htmlspecialchars($inv_row->therapy_name, ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td class="price">Rp ' . number_format($inv_row->therapy_price, 0, ',', '.') . '</td></tr>';
    }
}

$grand_total = $inv_total - $jumlah_diskon;
$logo_file = realpath(APPPATH . '../assets/images/logo/invoice_logo.png');
$logo = '';
if ($logo_file !== false) {
    $logo_png = pdf_remove_png_icc_profile(file_get_contents($logo_file));
    $logo = '<img src="data:image/png;base64,' . base64_encode($logo_png) . '" style="width:82mm;">';
}

function pdf_escape($value = '')
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$html = '<div style="font-family:helvetica;font-size:10pt;color:#535353;">'
    . '<table width="100%" cellspacing="0" cellpadding="0"><tr>'
    . '<td width="55%">' . $logo . '</td>'
    . '<td width="45%"><div style="font-size:24pt;font-weight:bold;color:#8dba3d;margin-bottom:4mm;">INVOICE</div>'
    . '<div>Nomor: ' . pdf_escape($invoice_code) . '</div><div>Tanggal: ' . pdf_escape($tanggal_mengisi) . '</div></td>'
    . '</tr></table>'
    . '<br><table width="100%" cellspacing="0" cellpadding="0"><tr>'
    . '<td width="52%"><div class="heading">Invoice Kepada:</div><div class="client">' . pdf_escape(strtoupper($client_name)) . '</div>'
    . '<div>' . pdf_escape($client_phone) . '<br>' . pdf_escape($client_email) . '<br>' . pdf_escape($client_address) . '</div></td>'
    . '<td width="48%"><div class="heading">Invoice Dari:</div><div class="client">IICET</div>'
    . '<div>(0751) 8970975<br>info@iicet.org<br>Jl. Bunda I No. 19 Ulak Karang - Padang</div></td>'
    . '</tr></table>'
    . '<br><br><table class="invoice" width="100%" cellspacing="0" cellpadding="7">'
    . '<thead><tr><th width="8%">No</th><th width="67%">Deskripsi</th><th width="25%">Jumlah</th></tr></thead><tbody>'
    . $rows
    . '<tr><td></td><td class="total-label">Total</td><td class="price total">Rp ' . number_format($inv_total, 0, ',', '.') . '</td></tr>'
    . '<tr><td></td><td class="total-label">Diskon ' . pdf_escape($diskon) . '%</td><td class="price total">- Rp ' . number_format($jumlah_diskon, 0, ',', '.') . '</td></tr>'
    . '<tr><td></td><td class="total-label">Grand Total</td><td class="price total">Rp ' . number_format($grand_total, 0, ',', '.') . '</td></tr>'
    . '</tbody></table>'
    . '<br><div class="heading">Rekening Pembayaran</div>'
    . '<div><b>Nomor Rekening:</b> ' . pdf_escape(getconfig('bank_account_number')) . '</div>'
    . '<div><b>Atas Nama:</b> ' . pdf_escape(getconfig('bank_account_name')) . '</div>'
    . '<div><b>Nama Bank:</b> ' . pdf_escape(getconfig('bank_name')) . '</div>'
    . '<style>'
    . '.heading{color:#8dba3d;font-weight:bold;font-size:14pt;margin-bottom:3mm;}'
    . '.client{color:#262525;font-size:16pt;font-weight:bold;margin-bottom:2mm;}'
    . '.invoice{border:1px solid #e9ecef;border-collapse:collapse;}'
    . '.invoice th{background-color:#f3f3f3;border:1px solid #e9ecef;font-weight:bold;text-align:left;}'
    . '.invoice td{border:1px solid #e9ecef;}'
    . '.number{text-align:center;}.price{text-align:right;}.total-label{text-align:right;color:#648d1e;font-weight:bold;}.total{color:#648d1e;font-weight:bold;}'
    . '</style></div>';

$pdf = new \Com\Tecnick\Pdf\Tcpdf('mm');
$pdf->setPDFFilename('Invoice-' . $test_id . '.pdf');
$page = $pdf->addPage(['format' => 'A4', 'orientation' => 'P']);
$bfont = $pdf->font->insert(
    $pdf->pon,
    'helvetica',
    '',
    10,
    null,
    null,
    $pdf_font_file
);
$pdf->page->addContent($bfont['out']);
$pdf->addHTMLCell($html, 10, 5, 190);

$pdf->downloadPDF($pdf->getOutPDFString());
