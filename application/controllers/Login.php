<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		$this->load->model(array('auth_model'));
		$this->load->helper(array('string','text'));
		$this->load->library(array('email'));
	}

	public function index()
	{
		$this->session->set_userdata('redirect_call_back', $this->agent->referrer());

		if ($this->session->userdata('user_id') == '' && $this->session->userdata('logged_as') != 'admin')
        {
			$data['form_action']	= site_url('login/process');
			$data['view']			= 'auth/login';
			$this->load->view($data['view'], $data);
		}
		else
		{
			$redirect = ''.site_url('home').'';
			redirect($redirect, 'refresh');
		}
	}

	function process()
	{ 
		if ($this->input->is_ajax_request() == true)
		{
			if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) 
			{
				header('Allow: POST');
				header('HTTP/1.1 405 Method Not Allowed');
				header('Content-Type: text/plain');
				exit('ERROR: Method Not Allowed !');
			}

			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

			if ($this->form_validation->run() == FALSE)
			{
				$res['status']      = 0;
				$res['redirect']    = 0;
				$res['type']        = 'warning';
				$res['message']     = validation_errors();
				echo json_encode($res);
				return false;
			}
			else
			{
				$remember   = trim($this->input->post('remember',true));
				$username	= trim($this->input->post('username',true));
				$password	= md5(trim($this->input->post('password',true)));
				$user 		= $this->get_user_by_username($username);
				if(empty($user))
				{
					$user = $this->get_user_by_email($username);
				}

				if(!empty($user))
				{
					//Check Password
					if($password != $user->user_pass)
					{
						$res['status']	= 0;
						$res['type']  	= 'warning';
						$res['message']	= 'Invalid Password !';
						echo json_encode($res);
						return false;
					}

					if($user->user_active == 0)
					{
						$res['status']	= 0;
						$res['type']  	= 'error';
						$res['message']	= 'Your account not active, Please contact your administrator !';
						echo json_encode($res);
						return false;
					}
					else
					{
						// Active/
						$session_data['user_id'] 			= $user->user_id;
						$session_data['user_login'] 		= $user->user_login;
						$session_data['user_fullname'] 		= $user->user_fullname;
						$session_data['user_email'] 		= $user->user_email;
						$session_data['user_active'] 		= $user->user_active;
						$session_data['user_level'] 		= $user->user_level;
						$session_data['logged_as_admin']	= ($user->user_role == 1) ? 1 : 0;
						$this->session->set_userdata($session_data);

						$res['status']      = 1;
						$res['redirect']    = 1;
						$res['type']        = 'success';
						$res['message']     = 'Anda login sebagai '.$user->user_fullname.'';
						$res['link']        = ''.site_url('home').'';
						echo json_encode($res);
						return false;
					}
				}
				else
				{
					$res['status']      = 0;
					$res['redirect']    = 0;
					$res['type']        = 'error';
					$res['message']     = 'Akun tidak ditemukan !';
					echo json_encode($res);
					return false;
				}
			}
		}
		else
		{
			$res['status']      = 0;
			$res['redirect']    = 0;
			$res['type']        = 'error';
			$res['message']     = 'Action not allowed !';
			echo json_encode($res);
			return false;
		}
	}

	//get user by username
	public function get_user_by_username($username)
	{
		$this->db->where('user_login', $username);
		$this->db->limit(1,0);
		$q = $this->db->get('users');
		return $q->row();
	}

	//get user by email
	public function get_user_by_email($email)
	{
		$this->db->where('user_email', $email);
		$this->db->limit(1,0);
		$q = $this->db->get('users');
		return $q->row();
	}

	// forgot password
	function forgot_password()
	{
		$this->session->set_userdata('redirect_call_back', $this->agent->referrer());
		
		if ($this->session->userdata('user_id') == '' && $this->session->userdata('logged_as') != 'admin')
        {
			$data['form_action']	= site_url('forgot-password-process');
			$data['view']			= 'auth/forgot';
			$this->load->view($data['view'], $data);
		}
		else
		{
			$redirect = ''.site_url('home').'';
			redirect($redirect, 'refresh');
		}
	}

	// forgot password process
	function forgot_password_process()
	{
		if ($this->input->is_ajax_request() == true)
		{
			if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) 
			{
				header('Allow: POST');
				header('HTTP/1.1 405 Method Not Allowed');
				header('Content-Type: text/plain');
				exit('ERROR: Method Not Allowed !');
			}

			$this->form_validation->set_rules('email', 'Email Adress', 'required|valid_email');

			if ($this->form_validation->run() == FALSE)
			{
				$res['status']      = 0;
				$res['redirect']    = 0;
				$res['type']        = 'error';
				$res['message']     = validation_errors();
				echo json_encode($res);
				return false;
			}
			else
			{
				$email 	= trim($this->input->post('email'));
				$user 	= $this->auth_model->get_user_by_email($email);
				if (!empty($user))
				{
					/* Generate New OTP Code */
					$otp_code = random_string('numeric', 6);
					$otp_date = date('Y-m-d H:i:s');
					$otp_exp  = date('Y-m-d H:i:s', strtotime($otp_date . ' +1 day'));

					$data['user_otp']		 = $otp_code;
					$data['user_otp_expire'] = $otp_exp;
					$this->auth_model->update_user($data, $user->user_id);

					$message = '
					<!-- HTML START HERE -->
					<div style="background-color:#F2F2F2; margin:0 auto; padding:60px; font-family:Helvetica,Arial,sans-serif; font-size:16px; color:#303030;">
						<div style="font-size:18px; font-weight:600;">LimpatoCMS</div>
						<div style="border-radius: 6px 6px 6px 6px;box-shadow: 0 1px 2px 0 #DFDFDF;">
							<div style="background-color:#35B8E8;text-align:center;margin-top:32px;border-radius: 6px 6px 0 0;">
								<figure style="margin-bottom: 0;">
									<img src="https://cdn.redwhitepress.com/images/gloos-header-email.png" alt="LimpatoCMS" style="height: 120px;">
								</figure>
							</div>
							<div style="background-color:#ffffff;padding:48px;border-radius: 0 0 6px 6px;">
								<div style="margin:0 0 1rem 0;padding:5px;color:#666666">
									Hai '.$user->user_fullname.',
								</div>
								<div style="margin:0 0 1rem 0;padding:5px;color:#666666">
									Sistem kami telah menerima permintaan pengaturan ulang password untuk akun: '.$user->user_email.'. 
									Jika merasa tidak melakukan apapun, abaikan saja email ini dan tidak akan terjadi apa-apa pada akun Anda. 
									Kode untuk mengatur ulang password Adalah:
								</div>
								<div style="background: #f5f5f5;padding:1rem .75rem;margin:0.5rem 0 1rem;display:block;text-align:center;">
									<div style="text-align: center;font-size: 1.5rem;">'.$otp_code.'</div>
								</div>
								<div style="text-align:center;margin-bottom:1rem;">
									atau klik tautan dibawah ini untuk menuju halaman validasi
								</div>
								<div style="text-align:center;">
									<a href="'.site_url('otp/'.$user->user_activation_key.'').'" style="color: #fff;background:#28a745;border: 1px solid #28a745;
										display:inline-block;text-align: center;vertical-align: middle;
										padding: .375rem .875rem;font-size: 1rem;text-decoration:none;
										font-weight:600;max-width: 250px;margin: 0 auto;border-radius: .25rem;">Validasi Kode</a>
								</div>
							</div>
						</div>
						<div style="padding:48px;text-align:center;">
							<div style="font-size:12px;color:#6E6E6E;margin-top:24px;">
								<div style="margin:0;padding:4px;"> 
									Anda menerima email ini karena terdaftar pada LimpatoCMS
								</div>
								<div style="margin:0;"> 
									Mohon untuk tidak membalas pesan ini karena dikirim otomatis oleh sistem.
								</div>
							</div>
						</div>
					</div>
					<!-- HTML END HERE -->';
					/* SEND EMAIL */
                    $email_from					= ''.getconfig('smtp_user').'';
                    $email_to					= ''.$user->user_email.'';
                    $email_subject				= 'Reset Password';
                    $email_message				= html_entity_decode($message);
                    $mail_config['smtp_host']	= ''.getconfig('smtp_host').'';
                    $mail_config['smtp_user']	= ''.getconfig('smtp_user').'';
                    $mail_config['smtp_pass']	= ''.getconfig('smtp_pass').''; 
                    $mail_config['smtp_port']	= ''.getconfig('smtp_port').''; 
                    $mail_config['smtp_crypto']	= ''.getconfig('smtp_crypto').''; 
                    $mail_config['priority']	= ''.getconfig('smtp_priority').'';
                    $mail_config['protocol'] 	= ''.getconfig('smtp_protocol').'';
                    $mail_config['mailpath'] 	= ''.getconfig('smtp_mailpath').''; 
                    $mail_config['charset'] 	= 'iso-8859-1';
                    $mail_config['smtp_timeout'] = 30;
                    $mail_config['wordwrap'] 	= TRUE;
                    $mail_config['crlf'] 		= "\r\n";
                    $mail_config['newline'] 	= "\r\n";
                    $mail_config['mailtype'] 	= 'html'; 
                    $this->email->initialize($mail_config); 
                    $this->email->from($email_from,'LimpatoCMS',$email_from);
                    $this->email->to($email_to);
                    $this->email->subject($email_subject);
                    $this->email->message($email_message); 

                    if ($this->email->send())
                    {
                        $res['message']	= '<p>Link sudah kami kirimkan ke email anda, silahkan cek inbox di email anda</p><p>Jika Anda tidak melihat email dari kami, silakan periksa direktori Spam Anda</p>';
                        
                    }
                    else
                    {
                        $res['message']	= $this->email->print_debugger();
                    }
                    /* END SEND EMAIL */ 
                    
                    $res['status']		= 1;
                    $res['redirect']    = 1;
                    $res['link']    	= ''.site_url('otp/'.$user->user_activation_key.'').'';
                    $res['type']        = 'auth/success';
                    echo json_encode($res);
				}
				else
				{
					$res['status']      = 0;
					$res['redirect']    = 0;
					$res['type']        = 'error';
					$res['message']     = 'Email '.$email.' tidak cocok dengan akun mana pun!';
					echo json_encode($res);
					return false;
				}
			}
		}
	}

	// otp
	function otp($activation_key=null)
	{
		if(!empty($activation_key) && empty($this->session->userdata('user_id')))
		{
			$user 	= $this->auth_model->get_user_by_activation_key($activation_key);

			if (!empty($user))
			{
				$data['id']				= $user->user_id;
				$data['email']			= $user->user_email;
				$data['key']			= $user->user_activation_key;
				$data['otp_access']		= date('Y-m-d H:i:s');
				$data['otp_expire']		= $user->user_otp_expire;
				$data['form_action']	= site_url('verify-otp');
				$data['view']			= 'auth/otp';
				$this->load->view($data['view'], $data);
			}
			else
			{
				redirect(site_url('login'), 'refresh');
			}
		} 
		else
		{
			redirect(site_url('login'), 'refresh');
		}
	}

	// validate otp
	function otp_process()
	{
		if ($this->input->is_ajax_request() == true)
		{
			if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) 
			{
				header('Allow: POST');
				header('HTTP/1.1 405 Method Not Allowed');
				header('Content-Type: text/plain');
				exit('ERROR: Method Not Allowed !');
			}

			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('key', 'Key', 'required');
			$this->form_validation->set_rules('otp', 'Code', 'required|min_length[6]');

			if ($this->form_validation->run() == FALSE)
			{
				$res['status']      = 0;
				$res['redirect']    = 0;
				$res['type']        = 'error';
				$res['message']     = validation_errors();
				echo json_encode($res);
				return false;
			}
			else
			{
				$otp 	= trim($this->input->post('otp'));
				$key 	= trim($this->input->post('key'));
				$email 	= trim($this->input->post('email'));
				$user 	= $this->auth_model->validate_otp($email,$key,$otp);

				if (!empty($user))
				{
					/* Reset Activation key */
					$data['user_activation_key']	= random_string('alnum', 16);
					$data['user_otp'] 				= '';
					$data['user_otp_expire'] 		= null;
					$this->auth_model->update_user($data, $user->user_id);
                    /* End Reset Activation key */

                    $res['status']		= 1;
                    $res['redirect']    = 1;
					$res['message']     = 'Validasi Berhasil';
                    $res['link']    	= ''.site_url('new-password/'.$data['user_activation_key'].'').'';
                    $res['type']        = 'success';
                    echo json_encode($res);
				}
				else
				{
					$res['status']      = 0;
					$res['redirect']    = 0;
					$res['type']        = 'error';
					$res['message']     = 'Kode OTP tidak valid!';
					echo json_encode($res);
					return false;
				}
			}
		}
	}

	// new password form
	function new_password($activation_key=null)
	{
		if(!empty($activation_key) && empty($this->session->userdata('user_id')))
		{
			$user 	= $this->auth_model->get_user_by_activation_key($activation_key);
			if (!empty($user))
			{
				$data['id']				= $user->user_id;
				$data['email']			= $user->user_email;
				$data['key']			= $user->user_activation_key;
				$data['form_action']	= site_url('update-password');
				$data['view']			= 'auth/password';
				$this->load->view($data['view'], $data);
			}
		}
	}

	// update new password
	function set_new_password()
	{
		if ($this->session->userdata('user_id') == '' && $this->session->userdata('logged_as') != 'admin')
        {
			if ($this->input->is_ajax_request() == true)
			{
				if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) 
				{
					header('Allow: POST');
					header('HTTP/1.1 405 Method Not Allowed');
					header('Content-Type: text/plain');
					exit('ERROR: Method Not Allowed !');
				}
	
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
				$this->form_validation->set_rules('key', 'Key', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
				$this->form_validation->set_rules('password2', 'Konfirmasi Password', 'required|matches[password]');
	
				if ($this->form_validation->run() == FALSE)
				{
					$res['status']      = 0;
					$res['redirect']    = 0;
					$res['type']        = 'error';
					$res['message']     = validation_errors();
					echo json_encode($res);
					return false;
				}
				else
				{
					$key 	= trim($this->input->post('key'));
					$email 	= trim($this->input->post('email'));
	
					$user 	= $this->auth_model->get_user_by_key_email($email,$key);
					if (!empty($user))
					{
						/* Reset Activation key & Update Password */
						$data['user_activation_key']	= random_string('alnum', 16);
						$data['user_otp'] 				= '';
						$data['user_otp_expire'] 		= null;
						$data['user_pass']				= md5(trim($this->input->post('password',true)));
						$this->auth_model->update_user($data, $user->user_id);
						/* End Reset Activation key & Update Password */
	
						$this->session->set_flashdata('login_message', '
						<div class="alert alert-info alert-dismissible fade show" role="alert">
							<strong>Selamat!</strong>, Password Anda telah diperbarui. Sekarang Anda dapat masuk dengan password baru.
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>');
						
						$res['status']		= 1;
						$res['redirect']    = 1;
						$res['message']     = 'Password Anda telah diperbarui';
						$res['link']    	= ''.site_url('login').'';
						$res['type']        = 'success';
						echo json_encode($res);
					}
					else {
						$res['status']      = 0;
						$res['redirect']    = 0;
						$res['type']        = 'error';
						$res['message']     = 'Kode OTP tidak valid!';
						echo json_encode($res);
						return false;
					}
				}
			}
		}
	}

	//Connect with google
	public function google()
	{
		if(getconfig('google_login') != 'enable')
		{
			return false;
		}

		require_once APPPATH . "third_party/google/vendor/autoload.php";

		$provider = new League\OAuth2\Client\Provider\Google([
			'clientId' 		=> getconfig('google_client_id'),
			'clientSecret' 	=> getconfig('google_client_secret'),
			'redirectUri' 	=> site_url('login/google'),
		]);

		if (!empty($_GET['error'])) {
			// Got an error, probably user denied access
			exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));
		} elseif (empty($_GET['code'])) {

			// If we don't have an authorization code then get one
			$authUrl = $provider->getAuthorizationUrl();
			$_SESSION['oauth2state'] = $provider->getState();
			$this->session->set_userdata('g_login_referrer', $this->agent->referrer());
			header('Location: ' . $authUrl);
			exit();

		} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
			// State is invalid, possible CSRF attack in progress
			unset($_SESSION['oauth2state']);
			exit('Invalid state');
		} else {
			// Try to get an access token (using the authorization code grant)
			$token = $provider->getAccessToken('authorization_code', [
				'code' => $_GET['code']
			]);
			// Optional: Now you have a token you can look up a users profile data
			try {
				// We got an access token, let's now get the owner details
				$user = $provider->getResourceOwner($token);

				$g_user = new stdClass();
				$g_user->id 	= $user->getId();
				$g_user->email 	= $user->getEmail();
				$g_user->name 	= $user->getName();
				$g_user->avatar = $user->getAvatar();

				$this->auth_model->login_with_google($g_user);

				if (!empty($this->session->userdata('g_login_referrer'))) {
					redirect($this->session->userdata('g_login_referrer'));
				} else {
					redirect(site_url());
				}

			} catch (Exception $e) {
				// Failed to get user details
				exit('Something went wrong: ' . $e->getMessage());
			}
		}
	}

	// Connect with Facebook	
	public function facebook()
	{
		if(getconfig('facebook_login') != 'enable')
		{
			return false;
		}
		//include facebook login
		require_once APPPATH . "third_party/facebook/vendor/autoload.php"; 
		$fb = new Facebook\Facebook([
			'app_id' => ''.getconfig('facebook_app_id').'',
			'app_secret' => ''.getconfig('facebook_app_secret').'',
			'default_graph_version' => 'v3.2',
		]); 
		$helper         = $fb->getRedirectLoginHelper();
		$permissions    = ['email']; // Optional permissions
        $loginUrl       = $helper->getLoginUrl(''.site_url('fb_callback').'', $permissions);
        
		$this->session->set_userdata('fb_login_referrer', $this->agent->referrer());

		redirect(''.$loginUrl.'','refresh');
	}

	// Facebook Callback
	public function facebook_callback()
	{
		if(getconfig('facebook_login') != 'enable')
		{
			return false;
		}

		//include facebook login
		require_once APPPATH . "third_party/facebook/vendor/autoload.php";

		$fb = new \Facebook\Facebook([
			'app_id' 		=> getconfig('facebook_app_id'),
			'app_secret' 	=> getconfig('facebook_app_secret'),
			'default_graph_version' => 'v3.2',
		]); 

		$helper = $fb->getRedirectLoginHelper();

		try {
			if (isset($_GET['state'])) {
				$helper->getPersistentDataHandler()->set('state', $_GET['state']);
			}
			$accessToken 	= $helper->getAccessToken();
			$response 		= $fb->get('/me?fields=name,email', $accessToken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		if (! isset($accessToken)) {
			if ($helper->getError()) {
				header('HTTP/1.0 401 Unauthorized');
				echo "Error: " . $helper->getError() . "\n";
				echo "Error Code: " . $helper->getErrorCode() . "\n";
				echo "Error Reason: " . $helper->getErrorReason() . "\n";
				echo "Error Description: " . $helper->getErrorDescription() . "\n";
			} else {
				header('HTTP/1.0 400 Bad Request');
				echo 'Bad request';
			}
			exit;
		}

		$user = $response->getGraphUser();
		if(!empty($user))
		{
			$fb_user 		= new stdClass();
			$fb_user->id 	= $user->getId();
			$fb_user->email	= $user->getEmail();
			$fb_user->name 	= $user->getName();
			if($fb_user->email == '')
			{
			    $fb_user->email	= ''.$fb_user->id.'@facebook.com';
			}
			$this->auth_model->login_with_facebook($fb_user);
		}
		
		
		if($this->session->userdata('user_id') != '' && $this->session->userdata('dashboard_link') != '')
		{
			redirect(''.site_url('home').'', 'refresh');
		}
		else
		{
			redirect(''.site_url('login').'', 'refresh');
		}
	}

	//log Out
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(''.site_url().'', 'refresh');
	}
}
