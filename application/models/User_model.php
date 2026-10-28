<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function one_user($user_id)
    {
        $q = $this->db->get_where('users',array('user_id' => abs((int) $user_id)),1,0);
        return $q;
    }

    function save_user($data)
    {
        $q = $this->db->insert('users', $data);
        return $q;
    }

	function update_user($data, $user_id)
    {
		$this->db->where('user_id', $user_id);
		$q = $this->db->update('users', $data);
		return $q;
	}

    function phone_validation($user_id, $user_phone)
    {
        $this->db->select('user_id,user_phone');
		$this->db->from('users');
		$this->db->where('user_phone', $user_phone);
		$this->db->where('user_id !=', $user_id); 
        $q = $this->db->get();
        return $q;
    }

    function email_validation($user_id, $user_email)
    {
        $this->db->select('user_id,user_email');
		$this->db->from('users');
		$this->db->where('user_email', $user_email);
		$this->db->where('user_id !=', $user_id); 
        $q = $this->db->get();
        return $q;
    }
}