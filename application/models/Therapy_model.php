<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Therapy_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function one_data($id)
    {
        $q = $this->db->get_where('therapy',array('therapy.therapy_id' => abs((int) $id)),1,0);
        return $q;
    }

    function fetch_data()
    {
        $this->db->select('*');
        $this->db->from('therapy');
        $this->db->order_by('therapy.therapy_id', 'desc');
        $q = $this->db->get();
        return $q;
    }

    function save_data($data)
    {
        $q = $this->db->insert('therapy', $data);
        return $q;
    }

	function update_data($data, $id)
    {
		$this->db->where('therapy.therapy_id', $id);
		$q = $this->db->update('therapy', $data);
		return $q;
	}

	function delete_data($id)
    {
		$q = $this->db->delete('therapy', array('therapy.therapy_id' => abs((int) $id))); 
		return $q;
	} 
}