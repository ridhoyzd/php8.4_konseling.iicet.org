<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group_model extends CI_Model {
    
	public function __construct()
	{
		parent::__construct();
	}

    function fetch_group()
    {
        $this->db->select( 'group_public.group_id,
                            group_public.group_name,
                            group_public.group_description,
                            group_public.group_created,
                            group_public.group_active,
                            COUNT(client_group_public.cgp_id) as jumlah_client');
        $this->db->from('group_public');
        $this->db->join('client_group_public', 'client_group_public.group_id = group_public.group_id','left');
        $this->db->group_by('group_public.group_id');
        $this->db->order_by('group_public.group_id', 'ASC');
        $q = $this->db->get();
        return $q;
    }

    function one_group($id)
    {
        $q = $this->db->get_where('group_public',array('group_id' => abs((int) $id)),1,0);
        return $q;
    }

    function save_group($data)
    {
        $q = $this->db->insert('group_public', $data);
        return $q;
    }

	function update_group($data, $id)
    {
		$this->db->where('group_id', $id);
		$q = $this->db->update('group_public', $data);
		return $q;
	}

	function delete_group($id)
    {
		$q = $this->db->delete('group_public', array('group_id' => abs((int) $id)));
		return $q;
	}

    function name_validation($id, $name)
    {
        $this->db->select('group_id,group_name');
		$this->db->from('group_public');
		$this->db->where('group_name', $name);
		$this->db->where('group_id !=', $id); 
        $q = $this->db->get();
        return $q;
    }

}