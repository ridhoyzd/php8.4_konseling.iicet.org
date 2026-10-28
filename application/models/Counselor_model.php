<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Counselor_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function one_data($id)
    {
        $q = $this->db->get_where('counselor',array('counselor.counselor_id' => abs((int) $id)),1,0);
        return $q;
    }

    function fetch_data()
    {
        $this->db->select('*');
        $this->db->from('counselor');
        $this->db->order_by('counselor.counselor_name', 'asc');
        $q = $this->db->get();
        return $q;
    }

    function save_data($data)
    {
        $q = $this->db->insert('counselor', $data);
        return $q;
    }

	function update_data($data, $id)
    {
		$this->db->where('counselor.counselor_id', $id);
		$q = $this->db->update('counselor', $data);
		return $q;
	}

	function delete_data($id)
    {
		$q = $this->db->delete('counselor', array('counselor.counselor_id' => abs((int) $id))); 
		return $q;
	} 
}