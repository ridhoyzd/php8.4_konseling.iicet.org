<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {
    
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
    
    function therapy_by_test($test_id)
    {
        $this->db->from('test_therapy');
		$this->db->where('test_id', $test_id);
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
}