<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rekam_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function one_client($id)
    {
        $q = $this->db->get_where('clients',array('id' => abs((int) $id)),1,0);
        return $q;
    }
    
    function one_test($test_id)
    {
        $q = $this->db->get_where('test',array('test_id' => abs((int) $test_id)),1,0);
        return $q;
    }

    function rekam_client($id)
    {
        $this->db->select('*');
        $this->db->from('test');
        $this->db->where('test.client_id', abs((int) $id));
        $this->db->order_by('test.test_id', 'asc');
        $q = $this->db->get();
        return $q;
    }

    function rekam_detail($client_id,$test_id)
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
        $this->db->where('test.client_id', abs((int) $client_id));
        $this->db->where('test.test_id', abs((int) $test_id));
        $this->db->limit(1,0);
        $q = $this->db->get();
        return $q;
    }    

    function get_soal()
    {
        $this->db->order_by('soal.soal_id', 'asc');
        $q = $this->db->get('soal');
        return $q;
    }

    function get_opsijawaban()
    {
        $this->db->order_by('m_jawaban.jawaban_id', 'asc');
        $q = $this->db->get('m_jawaban');
        return $q;
    }

    function hasil_test($test_id)
    {
        $this->db->select( 'hasil_test.hasil_id,
                            hasil_test.test_id,
                            hasil_test.client_id,
                            hasil_test.soal_id,
                            hasil_test.jawaban,
                            soal.pertanyaan,
                            soal.kategori_id,
                            soal_kategori.kategori_nama
                           ');
        $this->db->from('hasil_test');
        $this->db->join('soal', 'soal.soal_id = hasil_test.soal_id');
        $this->db->join('soal_kategori', 'soal_kategori.soal_kategori_id = soal.kategori_id');
        $this->db->where('hasil_test.test_id', abs((int) $test_id));
        $this->db->order_by('soal.soal_id', 'asc');
        $q = $this->db->get();
        return $q;
    }

    function kategori_soal()
    {
        $this->db->select( 'soal_kategori.soal_kategori_id,
                            soal_kategori.kategori_nama');
        $this->db->from('soal_kategori');
        $this->db->order_by('soal_kategori.soal_kategori_id', 'asc');
        $q = $this->db->get();
        return $q;
    } 

    function soal_by_kategori($kategori_id)
    {
        $this->db->select( 'soal.soal_id,
                            soal.pertanyaan,
                            soal.kategori_id,
                            soal_kategori.kategori_nama');
        $this->db->from('soal');
        $this->db->join('soal_kategori', 'soal_kategori.soal_kategori_id = soal.kategori_id');
        $this->db->where('soal.kategori_id', abs((int) $kategori_id));
        $this->db->order_by('soal.soal_id', 'asc');
        $q = $this->db->get();
        return $q;
    }

    function dass_by_group($test_id, $kategori_id)
    {
        $this->db->select( 'hasil_test.hasil_id,
                            hasil_test.test_id,
                            hasil_test.client_id,
                            hasil_test.soal_id,
                            hasil_test.jawaban,
                            soal.pertanyaan,
                            soal.kategori_id,
                            soal_kategori.kategori_nama
                           ');
        $this->db->from('hasil_test');
        $this->db->join('soal', 'soal.soal_id = hasil_test.soal_id');
        $this->db->join('soal_kategori', 'soal_kategori.soal_kategori_id = soal.kategori_id');
        $this->db->where('hasil_test.test_id', abs((int) $test_id));
        $this->db->where('soal.kategori_id', abs((int) $kategori_id));
        $this->db->order_by('soal.soal_id', 'asc');
        $q = $this->db->get();
        return $q;
    }
    
    function therapy()
    {
        $this->db->select( 'therapy_id,
                            therapy_name,
                            therapy_price,
                            created');
        $this->db->from('therapy');
        $this->db->order_by('therapy_id', 'asc');
        $q = $this->db->get();
        return $q;
    }

	function test_check($test_id, $client_id)
    {
        $this->db->from('test');
		$this->db->where('test_id', $test_id);
		$this->db->where('client_id', $client_id);
        $this->db->limit(1,0);
		$q = $this->db->get();
		return $q;
	}

	function test_therapy($test_id,$therapy_id)
    {
        $this->db->from('test_therapy');
		$this->db->where('test_id', $test_id);
		$this->db->where('therapy_id', $therapy_id);
		$this->db->limit(1,0);
		$q = $this->db->get();
		return $q;
	}
	
	function insert_test_therapy($data)
    {
	    $q = $this->db->insert('test_therapy', $data);
        return $q;
    }

	function test_update($data, $test_id)
    {
		$this->db->where('test_id', $test_id);
		$q = $this->db->update('test', $data);
		return $q;
	}
	
	function delete_test_therapy($test_id)
    {
		$q = $this->db->delete('test_therapy', array('test_id' => abs((int) $test_id))); 
		return $q;
	}
	
	function therapy_by_test($test_id)
    {
        $this->db->from('test_therapy');
		$this->db->where('test_id', $test_id);
		$q = $this->db->get();
		return $q;
	}

    
    
    function counselors()
    {
        $this->db->select( '*');
        $this->db->from('counselor');
        $this->db->order_by('counselor_name', 'asc');
        $q = $this->db->get();
        return $q;
    }
}