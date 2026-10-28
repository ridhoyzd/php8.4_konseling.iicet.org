<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dass_public_datatable extends CI_Model {
	private $select         = array();
    private $columns        = array();
    private $filter         = array();
    private $add_columns    = array();
    private $edit_columns   = array();
    private $unset_columns  = array();
    
	public function __construct()
	{
		parent::__construct();
	}
	
	private function _get_datatables_query()
    {
		$this->select( 'test_public.test_id,
                        test_public.client_id,
                        test_public.tanggal_mengisi,
                        clients_public.client_name,
                        clients_public.client_phone,
                        clients_public.client_registered,
                        clients_public.client_gender,
                        clients_public.client_age');
    $this->db->from('test_public');
    
    $mColArray 	= $this->input->post('columns');
		
		$sWhere 	= '';		
		$search 	= $this->input->post('search');		
		$sSearch 	= $this->db->escape_like_str(trim($search['value']));		
		$columns 	= array_values(array_diff($this->columns, $this->unset_columns));		
		
		if($sSearch != '')
		{
			for($i = 0; $i < count($mColArray); $i++)
				if ($mColArray[$i]['searchable'] == 'true' && !array_key_exists($mColArray[$i]['data'], $this->add_columns))
					if($this->check_cType())
						$sWhere .= $this->select[$mColArray[$i]['data']] . " LIKE '%" . $sSearch . "%' OR ";
					else
						$sWhere .= $this->select[$this->columns[$i]] . " LIKE '%" . $sSearch . "%' OR ";
						$sWhere = substr_replace($sWhere, '', -3);
					if($sWhere != '')
						$this->db->where('(' . $sWhere . ')');
						foreach($this->filter as $val)
							$this->db->where($val[0], $val[1], $val[2]);
		}
		
        $this->db->join('clients_public', 'clients_public.id = test_public.client_id');
        
        $Data = $this->input->post('columns');
		if ($this->input->post('order'))
		{
			foreach ($this->input->post('order') as $key)
			{
				if($this->check_cType())
				{
					$this->db->order_by($Data[$key['column']]['data'], $key['dir']);		
				}
				else
				{
					$this->db->order_by($this->columns[$key['column']] , $key['dir']);
				}
            }
        }   
        
    }
	
    
    function get_datatables()
    {
		$this->db->query('SET lc_time_names = "id_ID"');		
        $this->_get_datatables_query();        
        $Start 	= $this->input->post('start');
		$Length = $this->input->post('length');		
		if($Length != '' && $Length != '-1') $this->db->limit($Length, ($Start)? $Start : 0);        
		$query = $this->db->get();		 
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
	public function count_all()
	{
		$this->db->from('test_public');
		return $this->db->count_all_results();
	}
	
	public function add_column($column, $content, $match_replacement = NULL)
	{
		$this->add_columns[$column] = array('content' => $content, 'replacement' => $this->explode(',', $match_replacement));
		return $this;
	}
    
	private function balanceChars($str, $open, $close)
	{
		$openCount = substr_count($str, $open);
		$closeCount = substr_count($str, $close);
		$retval = $openCount - $closeCount;
		return $retval;
	}
     
    private function explode($delimiter, $str, $open = '(', $close=')')
    {
		$retval = array();
		$hold = array();
		$balance = 0;
		$parts = explode($delimiter, $str);
		foreach($parts as $part)
		{
			$hold[] = $part;
			$balance += $this->balanceChars($part, $open, $close);
			if($balance < 1)
			{
				$retval[] = implode($delimiter, $hold);
				$hold = array();
				$balance = 0;
			}
		}
        if(count($hold) > 0)
            $retval[] = implode($delimiter, $hold);
        return $retval;
    }
    
    public function select($columns, $backtick_protect = TRUE)
    {
		foreach($this->explode(',', $columns) as $val)
		{
			$column = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
			$column = preg_replace('/.*\.(.*)/i', '$1', $column); // get name after `.`
			$this->columns[] =  $column;
			$this->select[$column] =  trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));
		}
        $this->db->select($columns, $backtick_protect);
        return $this;
    }
    
	private function check_cType()
    {
        $column = $this->input->post('columns');
        if(is_numeric($column[0]['data']))
            return FALSE;
        else
            return TRUE;
    }
    
    private function unset_column($column)
    {
		$column=explode(',',$column);
		$this->unset_columns=array_merge($this->unset_columns,$column);
		return $this;
    }
}