<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function process($data)
	{
		$query = $this->db->get_where('users', $data,1,0);			
		return $query;
	}

	function save_registration($data)
	{
		$q = $this->db->insert('users', $data);
		return $q;
	}

	public function login_with_facebook($fb_user)
	{
		if (!empty($fb_user)) 
		{
			$user = $this->get_user_by_email($fb_user->email);

			//check if user registered
			if (empty($user))
			{
				if (empty($fb_user->name)) 
				{
					$fb_user->name = "user-" . uniqid();
				}

				$username 	= $this->generate_unique_username($fb_user->name); 

				//add new user to database
				$data['facebook_id']			= $fb_user->id;
				$data['user_email']				= $fb_user->email;
				$data['user_login']				= $username;
				$data['user_fullname']			= $fb_user->name;
				$data['user_picture']			= 'https://graph.facebook.com/' . $fb_user->id . '/picture?type=large';
				$data['user_registered']		= date('Y-m-d H:i:s');
				$data['user_activation_key']	= random_string('alnum', 10);
				$data['user_active']			= 1;
				$data['user_registered_type']	= 'facebook';

				if (!empty($data['user_email'])) 
				{
					// cek setingan apakah mendaftar dengan facebook dibolehkan
					if(getconfig('facebook_register') == 'enable')
					{
						$this->db->insert('users', $data);
					}

					$user = $this->get_user_by_email($fb_user->email);
					$this->login_direct($user);
				}
			}
			else
			{
				//login
				$this->login_direct($user);
			}
		}
	}

	//login with google
	function login_with_google($g_user)
	{
		if (!empty($g_user)) 
		{
			$user = $this->get_user_by_email($g_user->email);
			if (empty($user))
			{
				if (empty($g_user->name)) 
				{
					$g_user->name = "user-" . uniqid();
				}

				$username 	= $this->generate_unique_username($g_user->name); 

				//add new user to database
				$data['google_id']				= $g_user->id;
				$data['user_email']				= $g_user->email;
				$data['user_login']				= $username;
				$data['user_fullname']			= $g_user->name;
				$data['user_picture']			= $g_user->avatar;
				$data['user_registered']		= date('Y-m-d H:i:s');
				$data['user_activation_key']	= random_string('alnum', 10);
				$data['user_active']			= 1;
				$data['user_registered_type']	= 'google';

				if (!empty($data['user_email'])) 
				{
					// cek setingan apakah mendaftar dengan google dibolehkan
					if(getconfig('google_register') == 'enable')
					{
						$this->db->insert('users', $data);
					}
					$user = $this->get_user_by_email($g_user->email);
					$this->login_direct($user);
				}
			}
			else
			{
				//login
				$this->login_direct($user);
			}
		}
	}

	//generate uniqe login
	public function generate_unique_username($username)
	{
		$slug = str_slug($username);
		if (!empty($this->get_user_by_username($slug))) 
		{
			$slug = str_slug($username . "-1");
			if (!empty($this->get_user_by_username($slug))) 
			{
				$slug = str_slug($username . "-2");
				if (!empty($this->get_user_by_username($slug))) 
				{
					$slug = str_slug($username . "-3");
					if (!empty($this->get_user_by_username($slug))) 
					{
						$slug = str_slug($username . "-" . uniqid());
					}
				}
			}
		}
		return $slug;
	}

	//login direct
	function login_direct($user)
	{
		if(!empty($user))
		{
			//set user data session
			$session_data['logged_as'] 			= 'member';
			$session_data['member_id'] 			= $user->user_id;
			$session_data['user_login'] 		= $user->user_login;
			$session_data['user_fullname'] 		= $user->user_fullname;
			$session_data['user_email'] 		= $user->user_email;
			$session_data['user_active'] 		= $user->user_active;
			$session_data['dashboard_link']		= ''.site_url('dashboard').'';
			$this->session->set_userdata($session_data);
		}
		else
		{
			$this->session->set_flashdata('login_message', '
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				Maaf, email Anda tidak cocok dengan akun mana pun.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>');
		}
	}

	//get user by username
	function get_user_by_username($username)
	{
		$this->db->where('user_login', $username);
	    $this->db->limit(1,0);
		$query  = $this->db->get('users');
		$row    =  $query->row();
		return $row;
	}

	//get user by email
	public function get_user_by_email($email)
	{
		$this->db->where('user_email', $email); 
	    $this->db->limit(1,0);
		$query  = $this->db->get('users');
		$row    =  $query->row();
		return $row;
	}

	
	//check whatsapp number
    function check_wa($id, $wa)
    {
        $this->db->select('user_id,user_phone');
		$this->db->from('users');
		$this->db->where('user_phone', $wa);
		$this->db->where('user_id !=', $id); 
        $q = $this->db->get();
        return $q;
    }

	function update_user($data, $user_id)
	{
		$this->db->where('user_id', abs((int) $user_id));
        $q = $this->db->update('users', $data);
        return $q;
	}

	//get user by activation key
	public function get_user_by_activation_key($key)
	{
		$this->db->where('user_activation_key', $key); 
	    $this->db->limit(1,0);
		$query  = $this->db->get('users');
		$row    =  $query->row();
		return $row;
	}

	//validate otp
	public function validate_otp($email,$key,$otp)
	{
		$this->db->where('user_email', $email);
		$this->db->where('user_activation_key', $key);
		$this->db->where('user_otp', $otp);
	    $this->db->limit(1,0);
		$query  = $this->db->get('users');
		$row    =  $query->row();
		return $row;
	}

	//validate user by key and email
	public function get_user_by_key_email($email,$key)
	{
		$this->db->where('user_email', $email);
		$this->db->where('user_activation_key', $key);
	    $this->db->limit(1,0);
		$query  = $this->db->get('users');
		$row    =  $query->row();
		return $row;
	}
}