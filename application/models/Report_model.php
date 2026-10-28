<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function one_client($id)
    {
        $q = $this->db->get_where('clients',array('id' => abs((int) $id)),1,0);
        return $q;
    }

    function one_group($id)
    {
        $q = $this->db->get_where('group_public',array('group_id' => abs((int) $id)),1,0);
        return $q;
    }

    function groups()
    {
        $this->db->select('*');
        $this->db->from('group_public');
        $this->db->order_by('group_public.group_id', 'desc');
        $q = $this->db->get();
        return $q;
    }

    function all_clients()
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
                            clients_public.client_age,
                            client_group_public.group_id,
                            group_public.group_name,');
        $this->db->from('test_public');
        $this->db->join('clients_public', 'clients_public.id = test_public.client_id', 'left');
        $this->db->join('client_group_public', 'client_group_public.client_id = test_public.client_id', 'left');
        $this->db->join('group_public', 'group_public.group_id = client_group_public.group_id', 'left');
        $this->db->order_by('test_public.test_id', 'desc');
        $q = $this->db->get();
        return $q;
    }

    function client_by_group($group_id)
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
                            clients_public.client_age,
                            client_group_public.group_id,
                            group_public.group_name,');
        $this->db->from('test_public');
        $this->db->join('clients_public', 'clients_public.id = test_public.client_id', 'left');
        $this->db->join('client_group_public', 'client_group_public.client_id = test_public.client_id', 'left');
        $this->db->join('group_public', 'group_public.group_id = client_group_public.group_id', 'left');
        $this->db->where('client_group_public.group_id', $group_id);
        $this->db->order_by('test_public.test_id', 'desc');
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

    function soal_per_kategori($kategori_id)
    {

        $this->db->select( 'soal.soal_id,
                            soal.pertanyaan,
                            soal.kategori_id,
                            soal_kategori.kategori_nama');
        $this->db->from('soal');
        $this->db->join('soal_kategori','soal_kategori.soal_kategori_id = soal.kategori_id', 'left');
        $this->db->where('soal.kategori_id', $kategori_id);
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
}