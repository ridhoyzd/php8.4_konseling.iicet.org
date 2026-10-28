<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Validation_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function rekam_detail($test_id)
    {
        $this->db->select( 'test.test_id,
                            test.client_id,
                            test.tanggal_mengisi,
                            test.umur_client,
                            test.point_stress,
                            test.point_anxiety,
                            test.point_depression,
                            test.stress,
                            test.anxiety,
                            test.depression,
                            test.invoice_code,
                            test.invoice_status,
                            test.therapy_id,
                            test.therapy_name,
                            test.therapy_price,
                            test.deskripsi_masalah,
                            test.keterangan,
                            test.tindak_lanjut,
                            test.sesi,
                            test.konselor,
                            test.counselor_id,
                            test.tanggal_ditetapkan,
                            counselor.counselor_name
        ');
        $this->db->from('test');
        $this->db->join('counselor', 'counselor.counselor_id = test.counselor_id', 'left');
        $this->db->where('test.test_id', abs((int) $test_id));
        $this->db->limit(1,0);
        $q = $this->db->get();
        return $q;
    }

}