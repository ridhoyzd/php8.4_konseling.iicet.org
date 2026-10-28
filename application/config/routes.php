<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller']            = 'login';
$route['404_override']                  = '';
$route['translate_uri_dashes']          = FALSE;
$route['fb_callback']                   = 'login/facebook_callback';
$route['logout']                        = 'login/logout';
$route['forgot-password']               = 'login/forgot_password';
$route['forgot-password-process']       = 'login/forgot_password_process'; 
$route['otp/(:any)']                    = 'login/otp/$1';
$route['verify-otp']                    = 'login/otp_process';
$route['new-password/(:any)']           = 'login/new_password/$1';
$route['update-password']               = 'login/set_new_password';

// user
$route['s']                             = 'dass/input';