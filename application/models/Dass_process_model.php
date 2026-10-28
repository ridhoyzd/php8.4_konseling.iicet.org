<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dass_process_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}
    
    function all_dass_public($limit=null,$start=null,$total=false)
	{
		$this->db->select( 'test_public.test_id,
                            test_public.client_id,
                            test_public.tanggal_mengisi,
                            test_public.point_stress,
                            test_public.point_anxiety,
                            test_public.point_depression,
                            test_public.stress,
                            test_public.anxiety,
                            test_public.depression,
                            test_public.keterangan,
                            test_public.tanggal_ditetapkan, 
                            clients_public.client_name,
                            clients_public.client_phone,
                            clients_public.client_gender,
                            clients_public.client_registered,
                            clients_public.client_code,
                            clients_public.client_age');
		$this->db->from('test_public');
		$this->db->join('clients_public', 'clients_public.id = test_public.client_id', 'left');
		$this->db->order_by('test_public.test_id', 'desc');
		if($total == false) {
			$this->db->limit(abs((int) $limit), abs((int) $start));
		}
		$q = $this->db->get();
		return $q;
	}

    function check_data($client_id, $test_id)
    {
        $this->db->select('*');
		$this->db->from('test_public');
        $this->db->where('test_public.client_id', $client_id);
        $this->db->where('test_public.test_id', $test_id);
        $this->db->limit(1,0);
		$q = $this->db->get();
		return $q;
    }

    function hasil_test($client_id,$test_id)
    {
        $this->db->select( 'hasil_test_public.hasil_id,
                            hasil_test_public.test_id,
                            hasil_test_public.client_id,
                            hasil_test_public.soal_id,
                            hasil_test_public.jawaban,
                            soal.pertanyaan,
                            soal.kategori_id,
                            soal_kategori.kategori_nama
                           ');
        $this->db->from('hasil_test_public');
        $this->db->join('soal', 'soal.soal_id = hasil_test_public.soal_id');
        $this->db->join('soal_kategori', 'soal_kategori.soal_kategori_id = soal.kategori_id');
        $this->db->where('hasil_test_public.client_id', abs((int) $client_id));
        $this->db->where('hasil_test_public.test_id', abs((int) $test_id));
        $this->db->order_by('soal.soal_id', 'asc');
        $q = $this->db->get();
        return $q;
    }

    function get_opsijawaban()
    {
        $this->db->order_by('m_jawaban.jawaban_id', 'asc');
        $q = $this->db->get('m_jawaban');
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
    
    function dass_by_group($test_id, $kategori_id)
    {
        $this->db->select( 'hasil_test_public.hasil_id,
                            hasil_test_public.test_id,
                            hasil_test_public.client_id,
                            hasil_test_public.soal_id,
                            hasil_test_public.jawaban,
                            soal.pertanyaan,
                            soal.kategori_id,
                            soal_kategori.kategori_nama');
        $this->db->from('hasil_test_public');
        $this->db->join('soal', 'soal.soal_id = hasil_test_public.soal_id');
        $this->db->join('soal_kategori', 'soal_kategori.soal_kategori_id = soal.kategori_id');
        $this->db->where('hasil_test_public.test_id', abs((int) $test_id));
        $this->db->where('soal.kategori_id', abs((int) $kategori_id));
        $this->db->order_by('soal.soal_id', 'asc');
        $q = $this->db->get();
        return $q;
    }

    function update_test_public($data,$client_id,$test_id)
    {
        $this->db->where('client_id', $client_id);
        $this->db->where('test_id', $test_id);
		$q = $this->db->update('test_public', $data);
		return $q;
    }
}