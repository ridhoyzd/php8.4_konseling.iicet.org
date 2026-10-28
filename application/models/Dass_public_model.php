<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dass_public_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}
	
    function save_client_public($data)
    {
        $q = $this->db->insert('clients_public', $data);
        return $q;
    }
    
    function save_test_public($data)
    {
        $q = $this->db->insert('test_public', $data);
        return $q;
    }

    function save_hasil_test_public($data)
    {
        $q = $this->db->insert('hasil_test_public', $data);
        return $q;
    }

    function one_test($test_id)
    {
        $q = $this->db->get_where('test_public',array('test_id' => abs((int) $test_id)),1,0);
        return $q;
    }

    function hasil_test($test_id)
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
        $this->db->where('hasil_test_public.test_id', abs((int) $test_id));
        $this->db->where('soal.kategori_id', abs((int) $kategori_id));
        $this->db->order_by('soal.soal_id', 'asc');
        $q = $this->db->get();
        return $q;
    }

	function test_check($test_id, $client_id)
    {
        $this->db->from('test_public');
		$this->db->where('test_id', $test_id);
		$this->db->where('client_id', $client_id);
        $this->db->limit(1,0);
		$q = $this->db->get();
		return $q;
	}

	function test_update($data, $test_id)
    {
		$this->db->where('test_id', $test_id);
		$q = $this->db->update('test_public', $data);
		return $q;
	}

    function groups()
    {
        $this->db->select('*');
        $this->db->from('group_public');
        $q = $this->db->get();
        return $q;
    }

    function check_group_exist($client_id, $group_id)
    {
        $this->db->select('*');
        $this->db->from('client_group_public');
        $this->db->where('client_group_public.client_id', $client_id);
        $this->db->where('client_group_public.group_id', $group_id);
        $this->db->limit(1,0);
        $q = $this->db->get();
        return $q;
    }
	
    function add_to_group($data)
    {
        $q = $this->db->insert('client_group_public', $data);
        return $q;
    }
	
    function client_groups($client_id)
    {
        $this->db->select('client_group_public.cgp_id,
                           client_group_public.client_id,
                           client_group_public.group_id,
                           client_group_public.created,
                           group_public.group_name,
                           group_public.group_description,
                           group_public.group_created');
        $this->db->from('client_group_public');
        $this->db->join('group_public', 'group_public.group_id = client_group_public.group_id','left');
        $this->db->where('client_group_public.client_id', $client_id);
        $q = $this->db->get();
        return $q;
    }

    function remove_client_group($client_id, $group_id)
    {
        $q = $this->db->delete('client_group_public', array('client_id' => abs((int) $client_id), 'group_id' => abs((int) $group_id))); 
		return $q;
    }

    /* PROCESS -------------------------------------------------- */
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

    function update_test_public($data,$client_id,$test_id)
    {
        $this->db->where('client_id', $client_id);
        $this->db->where('test_id', $test_id);
		$q = $this->db->update('test_public', $data);
		return $q;
    }
    /* END OF PROCESS -------------------------------------------- */
}