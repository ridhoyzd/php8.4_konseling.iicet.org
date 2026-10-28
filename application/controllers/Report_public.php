<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Load library phpspreadsheet
require_once(APPPATH.'libraries/excel/vendor/autoload.php'); 
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
// End load library phpspreadsheet

class Report_public extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		$this->load->model(array('report_model'));
		$this->load->helper(array('string','text')); 
	}

	function index()
	{
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{ 
            $data['groups']         = $this->report_model->groups();
            $data['parent_navlink'] = ''.site_url('report_public').'';
			$data['view']			= 'reports/index';
			$this->load->view($data['view'], $data);
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

    function group($group_id=null,$export=null)
    {
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            if ($group_id != '' && $this->report_model->one_group($group_id)->num_rows() == 1)
            {
                $data['group_id']       = $group_id;
                $data['groups']         = $this->report_model->groups();
                $data['query']          = $this->report_model->client_by_group($group_id);
                $data['parent_navlink'] = ''.site_url('report_public').'';
                if($export){
                    $data['view']			= 'reports/group_export';
                }
                else
                {
                    $data['view']			= 'reports/group';
                }
                $this->load->view($data['view'], $data);
            }
            else
            {
                if($group_id == 'all')
                {
                    $data['group_id']       = $group_id;
                    $data['groups']         = $this->report_model->groups();
                    $data['query']          = $this->report_model->all_clients();
                    $data['parent_navlink'] = ''.site_url('report_public').'';
                    $data['view']			= 'reports/all';
                    $this->load->view($data['view'], $data);
                }
                else
                {
                    redirect(''.site_url('report_public').'');
                }
            }
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
    }
    function export_excel($group_id=null)
    {
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            if ($group_id != '' && $this->report_model->one_group($group_id)->num_rows() == 1)
            {
                $query          = $this->report_model->client_by_group($group_id);
                $spreadsheet    = new Spreadsheet();
                // Add some data
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('G1', 'Stress')
                ->setCellValue('I1', 'Anxiety')
                ->setCellValue('K1', 'Depression')
                ->setCellValue('A1', 'No')
                ->setCellValue('B1', 'Nama')
                ->setCellValue('C1', 'Handphone')
                ->setCellValue('D1', 'Jekel')
                ->setCellValue('E1', 'Umur')
                ->setCellValue('F1', 'Tgl. Mengisi')
                ->setCellValue('G2', 'Nilai')
                ->setCellValue('h2', 'Kategori')
                ->setCellValue('I2', 'Nilai')
                ->setCellValue('J2', 'Kategori')
                ->setCellValue('K2', 'Nilai')
                ->setCellValue('L2', 'Kategori')
                ;
                
                $spreadsheet->getActiveSheet()->mergeCells('A1:A2');
                $spreadsheet->getActiveSheet()->mergeCells('B1:B2');
                $spreadsheet->getActiveSheet()->mergeCells('C1:C2');
                $spreadsheet->getActiveSheet()->mergeCells('D1:D2');
                $spreadsheet->getActiveSheet()->mergeCells('E1:E2');
                $spreadsheet->getActiveSheet()->mergeCells('F1:F2');
                
                $spreadsheet->getActiveSheet()->mergeCells('G1:H1');
                $spreadsheet->getActiveSheet()->mergeCells('I1:J1');
                $spreadsheet->getActiveSheet()->mergeCells('K1:L1');

                if($query->num_rows() > 0)
                {
                    $i = 3;
                    $n = 1;
                    foreach ($query->result() as $row)
                    {
                        $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $n)
                        ->setCellValue('B'.$i, $row->client_name)
                        ->setCellValue('C'.$i, $row->client_phone)
                        ->setCellValue('D'.$i, jenis_kelamin($row->client_gender))
                        ->setCellValue('E'.$i, $row->client_age)
                        ->setCellValue('F'.$i, substr($row->tanggal_mengisi,0,10))
                        ->setCellValue('G'.$i, $row->point_stress)
                        ->setCellValue('H'.$i, $row->stress)
                        ->setCellValue('I'.$i, $row->point_anxiety)
                        ->setCellValue('J'.$i, $row->anxiety)
                        ->setCellValue('K'.$i, $row->point_depression)
                        ->setCellValue('L'.$i, $row->depression);
                        $i++;
                        $n++;
                    }
                }
                // Style
                $HeaderStyle = [
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'ffeeeeee',
                        ],
                        'endColor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ];
                $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('C1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('D1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('E1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('F1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('G1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('H1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('I1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('J1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('K1')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('L1')->applyFromArray($HeaderStyle);

                $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('F2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('G2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('H2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('I2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('J2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('K2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('L2')->applyFromArray($HeaderStyle);

                // Width Size
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(255, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(105, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(80, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(45, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(90, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(40, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(90, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(90, 'px');
                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle(''.group_info($group_id,'group_name').'');
                
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $spreadsheet->setActiveSheetIndex(0);
                
                // Redirect output to a client’s web browser (Xlsx)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Report '.group_info($group_id,'group_name').'_excel.xlsx"');
                header('Cache-Control: max-age=0');
                
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                exit;
            }
            else
            {
                if($group_id == 'all')
                {
                    $query          = $this->report_model->all_clients();
                    $spreadsheet    = new Spreadsheet();
                    // Add some data
                    $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('G1', 'Stress')
                    ->setCellValue('I1', 'Anxiety')
                    ->setCellValue('K1', 'Depression')
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Nama')
                    ->setCellValue('C1', 'Handphone')
                    ->setCellValue('D1', 'Jekel')
                    ->setCellValue('E1', 'Umur')
                    ->setCellValue('F1', 'Tgl. Mengisi')
                    ->setCellValue('G2', 'Nilai')
                    ->setCellValue('h2', 'Kategori')
                    ->setCellValue('I2', 'Nilai')
                    ->setCellValue('J2', 'Kategori')
                    ->setCellValue('K2', 'Nilai')
                    ->setCellValue('L2', 'Kategori')
                    ;
                    
                    $spreadsheet->getActiveSheet()->mergeCells('A1:A2');
                    $spreadsheet->getActiveSheet()->mergeCells('B1:B2');
                    $spreadsheet->getActiveSheet()->mergeCells('C1:C2');
                    $spreadsheet->getActiveSheet()->mergeCells('D1:D2');
                    $spreadsheet->getActiveSheet()->mergeCells('E1:E2');
                    $spreadsheet->getActiveSheet()->mergeCells('F1:F2');
                    
                    $spreadsheet->getActiveSheet()->mergeCells('G1:H1');
                    $spreadsheet->getActiveSheet()->mergeCells('I1:J1');
                    $spreadsheet->getActiveSheet()->mergeCells('K1:L1');

                    if($query->num_rows() > 0)
                    {
                        $i = 3;
                        $n = 1;
                        foreach ($query->result() as $row)
                        {
                            $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i, $n)
                            ->setCellValue('B'.$i, $row->client_name)
                            ->setCellValue('C'.$i, $row->client_phone)
                            ->setCellValue('D'.$i, jenis_kelamin($row->client_gender))
                            ->setCellValue('E'.$i, $row->client_age)
                            ->setCellValue('F'.$i, substr($row->tanggal_mengisi,0,10))
                            ->setCellValue('G'.$i, $row->point_stress)
                            ->setCellValue('H'.$i, $row->stress)
                            ->setCellValue('I'.$i, $row->point_anxiety)
                            ->setCellValue('J'.$i, $row->anxiety)
                            ->setCellValue('K'.$i, $row->point_depression)
                            ->setCellValue('L'.$i, $row->depression);
                            $i++;
                            $n++;
                        }
                    }
                    
                    // Style
                    $HeaderStyle = [
                        'font' => [
                            'bold' => true,
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                            'right' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                            'left' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ]
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                            'rotation' => 90,
                            'startColor' => [
                                'argb' => 'ffeeeeee',
                            ],
                            'endColor' => [
                                'argb' => 'FFFFFFFF',
                            ],
                        ],
                    ];
                    $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('C1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('D1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('E1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('F1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('G1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('H1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('I1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('J1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('K1')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('L1')->applyFromArray($HeaderStyle);

                    $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('F2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('G2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('H2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('I2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('J2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('K2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('L2')->applyFromArray($HeaderStyle);
                    
                    
                    // Width Size
                    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(255, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(105, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(80, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(45, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(90, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(40, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(90, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(40, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(90, 'px');
                    // Rename worksheet
                    $spreadsheet->getActiveSheet()->setTitle('Dass Public');
                    
                    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                    $spreadsheet->setActiveSheetIndex(0);
                    
                    // Redirect output to a client’s web browser (Xlsx)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="Report DASS Public_excel.xlsx"');
                    header('Cache-Control: max-age=0');
                    
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                    exit;
                }
                else
                {
                    redirect(''.site_url('report_public').'');
                }
            }
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
    }

    function full_excel($group_id=null)
    {
		if($this->session->userdata('logged_as_admin') == 1 && $this->session->userdata('user_level') == 'admin')
		{
            if ($group_id != '' && $this->report_model->one_group($group_id)->num_rows() == 1)
            {
                $query          = $this->report_model->client_by_group($group_id);
                $kategori_soal  = $this->report_model->kategori_soal();
                $spreadsheet    = new Spreadsheet();
                // Add some data
                $spreadsheet->setActiveSheetIndex(0) 
                ->setCellValue('A1', 'No')
                ->setCellValue('B1', 'Nama')
                ->setCellValue('C1', 'Handphone')
                ->setCellValue('D1', 'Jekel')
                ->setCellValue('E1', 'Umur')
                ->setCellValue('F1', 'Tgl. Mengisi') 
                ;
                // Width Size
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(255, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(105, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(80, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(45, 'px');
                $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true); 
                // Style
                $HeaderStyle = [
                    'font' => ['bold' => true],
                    'borders' => ['top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN ]],
                    'fill' => [ 'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, 
                                'rotation' => 90, 
                                'startColor' => ['argb' => 'FFFFFFFF'], 
                                'endColor' => [ 'argb' => 'FFFFFFFF']],
                ];
                $spreadsheet->getActiveSheet()->getStyle('A1:A2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('B1:B2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('C1:C2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('D1:D2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('E1:E2')->applyFromArray($HeaderStyle);
                $spreadsheet->getActiveSheet()->getStyle('F1:F2')->applyFromArray($HeaderStyle);

                if($kategori_soal->num_rows() > 0)
                {
                    $kolom_ke = 6;
                    $merge_ke = 6;
                    foreach ($kategori_soal->result() as $kategori)
                    {
                        if($this->report_model->soal_per_kategori($kategori->soal_kategori_id)->num_rows() > 0)
                        {
                            $jumlah_soal_kategori = $this->report_model->soal_per_kategori($kategori->soal_kategori_id)->num_rows();
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue(''.columnFromIndex($kolom_ke).'1', $kategori->kategori_nama);
                            //echo '<pre>'; echo ''.columnFromIndex($kolom_ke).'1:'.columnFromIndex(($kolom_ke+$jumlah_soal_kategori+1)).'1'; echo '</pre>';
                            $spreadsheet->getActiveSheet()->mergeCells(''.columnFromIndex($kolom_ke).'1:'.columnFromIndex(($kolom_ke+$jumlah_soal_kategori+1)).'1');
                            $spreadsheet->getActiveSheet()->getStyle(''.columnFromIndex($kolom_ke).'1:'.columnFromIndex(($kolom_ke+$jumlah_soal_kategori+1)).'1')->applyFromArray($HeaderStyle);

                            foreach ($this->report_model->soal_per_kategori($kategori->soal_kategori_id)->result() as $soal)
                            {
                                $spreadsheet->setActiveSheetIndex(0)->setCellValue(''.columnFromIndex($kolom_ke).'2', 'S'.$soal->soal_id);
                                $spreadsheet->getActiveSheet()->getColumnDimension(''.columnFromIndex($kolom_ke).'')->setWidth(30, 'px');
                                $spreadsheet->getActiveSheet()->getStyle(''.columnFromIndex($kolom_ke).'2')->applyFromArray($HeaderStyle);
                                $kolom_ke++;
                            } 
                        }
                        $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue(''.columnFromIndex($kolom_ke).'2', 'Nilai')
                        ->setCellValue(''.columnFromIndex($kolom_ke+1).'2', 'Kategori')
                        ;
                        $spreadsheet->getActiveSheet()->getColumnDimension(''.columnFromIndex($kolom_ke+1).'')->setWidth(85, 'px');

                        $spreadsheet->getActiveSheet()->getStyle(''.columnFromIndex($kolom_ke).'2')->applyFromArray($HeaderStyle);
                        $spreadsheet->getActiveSheet()->getStyle(''.columnFromIndex($kolom_ke+1).'2')->applyFromArray($HeaderStyle);

                        $kolom_ke = $kolom_ke+2;
                    }
                }
                
                $spreadsheet->getActiveSheet()->mergeCells('A1:A2');
                $spreadsheet->getActiveSheet()->mergeCells('B1:B2');
                $spreadsheet->getActiveSheet()->mergeCells('C1:C2');
                $spreadsheet->getActiveSheet()->mergeCells('D1:D2');
                $spreadsheet->getActiveSheet()->mergeCells('E1:E2');
                $spreadsheet->getActiveSheet()->mergeCells('F1:F2');

                /* KONTEN DATA --------------------------------------------------- */
                if($query->num_rows() > 0)
                {
                    $row = 3;
                    $no = 1;
                    foreach ($query->result() as $data)
                    {
                        $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A'.$row, $no)
                        ->setCellValue('B'.$row, $data->client_name)
                        ->setCellValue('C'.$row, $data->client_phone)
                        ->setCellValue('D'.$row, jenis_kelamin($data->client_gender))
                        ->setCellValue('E'.$row, $data->client_age)
                        ->setCellValue('F'.$row, substr($data->tanggal_mengisi,0,10));
                        
                        //get jawaban
                        if($kategori_soal->num_rows() > 0)
                        {
                            $row_kolom = 6;
                            foreach ($kategori_soal->result() as $kat)
                            {
                                if($this->report_model->dass_by_group($data->test_id, $kat->soal_kategori_id)->num_rows() > 0)
                                {
                                    $jumlah = 0; 
                                    foreach ($this->report_model->dass_by_group($data->test_id, $kat->soal_kategori_id)->result() as $nilai)
                                    {
                                        $spreadsheet->setActiveSheetIndex(0)->setCellValue(''.columnFromIndex($row_kolom).$row, $nilai->jawaban);
                                        $jumlah = $jumlah + $nilai->jawaban;
                                        $row_kolom++;
                                    }
                                }
                                $spreadsheet->setActiveSheetIndex(0)
                                ->setCellValue(''.columnFromIndex($row_kolom).$row, $jumlah)
                                ->setCellValue(''.columnFromIndex($row_kolom+1).$row, dass_result($kat->soal_kategori_id, $jumlah))
                                ;
                                $row_kolom = $row_kolom+2;
                            }
                        }
                        //end get jawaban

                        $row++;
                        $no++;
                    }
                }
                /* KONTEN DATA --------------------------------------------------- */

                // Rename worksheet
                $spreadsheet->getActiveSheet()->setTitle('Dass Public');
                    
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $spreadsheet->setActiveSheetIndex(0);
                
                // Redirect output to a client’s web browser (Xlsx)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Report DASS Public_full_excel.xlsx"');
                header('Cache-Control: max-age=0');
                
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                exit;
            }
            else
            {
                if($group_id == 'all')
                {
                    $query          = $this->report_model->all_clients();
                    $kategori_soal  = $this->report_model->kategori_soal();
                    $spreadsheet    = new Spreadsheet();
                    // Add some data
                    $spreadsheet->setActiveSheetIndex(0) 
                    ->setCellValue('A1', 'No')
                    ->setCellValue('B1', 'Nama')
                    ->setCellValue('C1', 'Handphone')
                    ->setCellValue('D1', 'Jekel')
                    ->setCellValue('E1', 'Umur')
                    ->setCellValue('F1', 'Tgl. Mengisi') 
                    ;
                    // Width Size
                    $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(255, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(105, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(80, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(45, 'px');
                    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true); 
                    // Style
                    $HeaderStyle = [
                        'font' => ['bold' => true],
                        'borders' => ['top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                    'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                                    'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN ]],
                        'fill' => [ 'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, 
                                    'rotation' => 90, 
                                    'startColor' => ['argb' => 'FFFFFFFF'], 
                                    'endColor' => [ 'argb' => 'FFFFFFFF']],
                    ];
                    $spreadsheet->getActiveSheet()->getStyle('A1:A2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('B1:B2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('C1:C2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('D1:D2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('E1:E2')->applyFromArray($HeaderStyle);
                    $spreadsheet->getActiveSheet()->getStyle('F1:F2')->applyFromArray($HeaderStyle);

                    if($kategori_soal->num_rows() > 0)
                    {
                        $kolom_ke = 6;
                        $merge_ke = 6;
                        foreach ($kategori_soal->result() as $kategori)
                        {
                            if($this->report_model->soal_per_kategori($kategori->soal_kategori_id)->num_rows() > 0)
                            {
                                $jumlah_soal_kategori = $this->report_model->soal_per_kategori($kategori->soal_kategori_id)->num_rows();
                                $spreadsheet->setActiveSheetIndex(0)->setCellValue(''.columnFromIndex($kolom_ke).'1', $kategori->kategori_nama);
                                //echo '<pre>'; echo ''.columnFromIndex($kolom_ke).'1:'.columnFromIndex(($kolom_ke+$jumlah_soal_kategori+1)).'1'; echo '</pre>';
                                $spreadsheet->getActiveSheet()->mergeCells(''.columnFromIndex($kolom_ke).'1:'.columnFromIndex(($kolom_ke+$jumlah_soal_kategori+1)).'1');
                                $spreadsheet->getActiveSheet()->getStyle(''.columnFromIndex($kolom_ke).'1:'.columnFromIndex(($kolom_ke+$jumlah_soal_kategori+1)).'1')->applyFromArray($HeaderStyle);

                                foreach ($this->report_model->soal_per_kategori($kategori->soal_kategori_id)->result() as $soal)
                                {
                                    $spreadsheet->setActiveSheetIndex(0)->setCellValue(''.columnFromIndex($kolom_ke).'2', 'S'.$soal->soal_id);
                                    $spreadsheet->getActiveSheet()->getColumnDimension(''.columnFromIndex($kolom_ke).'')->setWidth(30, 'px');
                                    $spreadsheet->getActiveSheet()->getStyle(''.columnFromIndex($kolom_ke).'2')->applyFromArray($HeaderStyle);
                                    $kolom_ke++;
                                } 
                            }
                            $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue(''.columnFromIndex($kolom_ke).'2', 'Nilai')
                            ->setCellValue(''.columnFromIndex($kolom_ke+1).'2', 'Kategori')
                            ;
                            $spreadsheet->getActiveSheet()->getColumnDimension(''.columnFromIndex($kolom_ke+1).'')->setWidth(85, 'px');

                            $spreadsheet->getActiveSheet()->getStyle(''.columnFromIndex($kolom_ke).'2')->applyFromArray($HeaderStyle);
                            $spreadsheet->getActiveSheet()->getStyle(''.columnFromIndex($kolom_ke+1).'2')->applyFromArray($HeaderStyle);

                            $kolom_ke = $kolom_ke+2;
                        }
                    }
                    
                    $spreadsheet->getActiveSheet()->mergeCells('A1:A2');
                    $spreadsheet->getActiveSheet()->mergeCells('B1:B2');
                    $spreadsheet->getActiveSheet()->mergeCells('C1:C2');
                    $spreadsheet->getActiveSheet()->mergeCells('D1:D2');
                    $spreadsheet->getActiveSheet()->mergeCells('E1:E2');
                    $spreadsheet->getActiveSheet()->mergeCells('F1:F2');

                    /* KONTEN DATA --------------------------------------------------- */
                    if($query->num_rows() > 0)
                    {
                        $row = 3;
                        $no = 1;
                        foreach ($query->result() as $data)
                        {
                            $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A'.$row, $no)
                            ->setCellValue('B'.$row, $data->client_name)
                            ->setCellValue('C'.$row, $data->client_phone)
                            ->setCellValue('D'.$row, jenis_kelamin($data->client_gender))
                            ->setCellValue('E'.$row, $data->client_age)
                            ->setCellValue('F'.$row, substr($data->tanggal_mengisi,0,10));
                            
                            //get jawaban
                            if($kategori_soal->num_rows() > 0)
                            {
                                $row_kolom = 6;
                                foreach ($kategori_soal->result() as $kat)
                                {
                                    if($this->report_model->dass_by_group($data->test_id, $kat->soal_kategori_id)->num_rows() > 0)
                                    {
                                        $jumlah = 0; 
                                        foreach ($this->report_model->dass_by_group($data->test_id, $kat->soal_kategori_id)->result() as $nilai)
                                        {
                                            $spreadsheet->setActiveSheetIndex(0)->setCellValue(''.columnFromIndex($row_kolom).$row, $nilai->jawaban);
                                            $jumlah = $jumlah + $nilai->jawaban;
                                            $row_kolom++;
                                        }
                                    }
                                    $spreadsheet->setActiveSheetIndex(0)
                                    ->setCellValue(''.columnFromIndex($row_kolom).$row, $jumlah)
                                    ->setCellValue(''.columnFromIndex($row_kolom+1).$row, dass_result($kat->soal_kategori_id, $jumlah))
                                    ;
                                    $row_kolom = $row_kolom+2;
                                }
                            }
                            //end get jawaban

                            $row++;
                            $no++;
                        }
                    }
                    /* KONTEN DATA --------------------------------------------------- */

                    // Rename worksheet
                    $spreadsheet->getActiveSheet()->setTitle('Dass Public');
                        
                    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                    $spreadsheet->setActiveSheetIndex(0);
                    
                    // Redirect output to a client’s web browser (Xlsx)
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="Report DASS Public_full_excel.xlsx"');
                    header('Cache-Control: max-age=0');
                    
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');

                    // If you're serving to IE over SSL, then the following may be needed
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header('Pragma: public'); // HTTP/1.0

                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                    exit;
                }
            }
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
    }
}