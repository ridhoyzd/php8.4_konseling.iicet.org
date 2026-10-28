<?php
defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<nav class="navbar navbar-expand-lg"> 
    <a href="javascript:void(0);" class="sidebar-toggler">
        <i class="fa fa-bars"></i>
    </a>
    <div class="navbar-content">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown dropdown-profile text-end">
                <a href="#" class="nav-link dropdown-toggle profile-link" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="media user-box align-items-center">
                        <div class="media-body user-info">
                            <p class="user-name mb-0">'.$this->session->userdata('user_fullname').'</p>
                            <p class="user-level mb-0">'.$this->session->userdata('user_level').'</p>
                        </div>
                        <img src="'.base_url('assets/images/user.png').'" alt="mdo" class="user-img">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="dropdownUser1" data-popper-placement="bottom-start"> 
                    <li><a class="dropdown-item" href="'.site_url('users/my_profile').'"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="'.site_url('users/my_profile').'"><i class="far fa-user"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="'.site_url('users/my_profile').'"><i class="fas fa-lock"></i>Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="'.site_url('logout').'"><i class="fas fa-power-off"></i>Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<div class="preloader" style="display:none;">
    <div class="text">
        <div class="progress">
            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                <span>Loading ...</span>
            </div>
        </div>
    </div>
</div>
';