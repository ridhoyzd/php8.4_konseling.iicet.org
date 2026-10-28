<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function one_client($id)
    {
        $q = $this->db->get_where('clients',array('id' => abs((int) $id)),1,0);
        return $q;
    }

    function save_client($data)
    {
        $q = $this->db->insert('clients', $data);
        return $q;
    }

	function update_client($data, $id)
    {
		$this->db->where('id', $id);
		$q = $this->db->update('clients', $data);
		return $q;
	}

	function delete_client($id)
    {
		$q = $this->db->delete('clients', array('id' => abs((int) $id))); 
		return $q;
	}

    function phone_validation($id, $client_phone)
    {
        $this->db->select('id,client_phone');
		$this->db->from('clients');
		$this->db->where('client_phone', $client_phone);
		$this->db->where('id !=', $id); 
        $q = $this->db->get();
        return $q;
    }

    function email_validation($id, $client_email)
    {
        $this->db->select('id,client_email');
		$this->db->from('clients');
		$this->db->where('client_email', $client_email);
		$this->db->where('id !=', $id); 
        $q = $this->db->get();
        return $q;
    }
}