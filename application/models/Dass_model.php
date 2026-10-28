<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dass_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function one_client($id)
    {
        $q = $this->db->get_where('clients',array('id' => abs((int) $id)),1,0);
        return $q;
    }

    function client_by_code($code)
    {
        $q = $this->db->get_where('clients',array('client_code' => $code),1,0);
        return $q;
    }

    function get_soal()
    {
        $this->db->order_by('soal.soal_id', 'asc');
        //$this->db->limit(5,0);
        $q = $this->db->get('soal');
        return $q;
    }

    function get_opsijawaban()
    {
        $this->db->order_by('m_jawaban.jawaban_id', 'asc');
        $q = $this->db->get('m_jawaban');
        return $q;
    }

    function save_test($data)
    {
        $q = $this->db->insert('test', $data);
        return $q;
    }

    function save_hasil_test($data)
    {
        $q = $this->db->insert('hasil_test', $data);
        return $q;
    }

    function one_test($test_id)
    {
        $q = $this->db->get_where('test',array('test_id' => abs((int) $test_id)),1,0);
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

	function test_update($data, $test_id)
    {
		$this->db->where('test_id', $test_id);
		$q = $this->db->update('test', $data);
		return $q;
	}

    function public_groups()
    {
        $this->db->select('*');
        $this->db->from('group_public');
		$this->db->where('group_public.group_active',1);
		$this->db->order_by('group_public.group_id','desc');
		$q = $this->db->get();
		return $q;
    }
}