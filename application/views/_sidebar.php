<?php
defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<nav class="sidebar sidebar-dark">
    <div class="sidebar-header">
        <a href="'.site_url('home').'" class="sidebar-brand">
            <img src="'.base_url('assets/images/logo/iicet_logo_light.png').'" class="logo" alt="IICET" style="max-width:150px;">
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Content</li> 
            <li class="nav-item ">
                <a href="'.site_url('home').'" class="nav-link">
                    <i class="link-icon fa fa-home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item ">
                <a href="'.site_url('clients').'" class="nav-link">
                    <i class="link-icon fa fa-users"></i>
                    <span class="link-title">Clients</span>
                </a>
            </li>
            <li class="nav-item ">
                <a href="'.site_url('dass').'" class="nav-link">
                    <i class="link-icon fas fa-clipboard-list"></i>
                    <span class="link-title">DASS</span>
                </a>
            </li>
            <li class="nav-item nav-category">PUBLICS</li> 
            <li class="nav-item ">
                <a href="'.site_url('dass_public').'" class="nav-link">
                    <i class="link-icon fas fa-clipboard-list"></i>
                    <span class="link-title">DASS Public</span>
                </a>
            </li>
            <li class="nav-item ">
                <a href="'.site_url('group_public').'" class="nav-link">
                    <i class="link-icon fa fa-users"></i>
                    <span class="link-title">Groups</span>
                </a>
            </li>
            <li class="nav-item ">
                <a href="'.site_url('report_public').'" class="nav-link">
                    <i class="link-icon far fa-file-alt"></i>
                    <span class="link-title">Reports</span>
                </a>
            </li>
            <li class="nav-item d-none">
                <a href="'.site_url('dass_process').'" class="nav-link">
                    <i class="link-icon fa fa-cogs"></i>
                    <span class="link-title">Process Dass</span>
                </a>
            </li>
            <li class="nav-item nav-category">Reports</li> 
            <li class="nav-item ">
                <a href="'.site_url('rekam').'" class="nav-link">
                    <i class="link-icon far fa-list-alt"></i>
                    <span class="link-title">Rekam Psikologis</span>
                </a>
            </li>
            <li class="nav-item ">
                <a href="'.site_url('invoice').'" class="nav-link">
                    <i class="link-icon fa fa-money-bill-alt"></i>
                    <span class="link-title">Invoice</span>
                </a>
            </li>
            <li class="nav-item nav-category">Master</li> 
            <li class="nav-item ">
                <a href="'.site_url('therapy').'" class="nav-link">
                    <i class="link-icon fa fa-hands-helping"></i>
                    <span class="link-title">Therapy</span>
                </a>
            </li>
            <li class="nav-item ">
                <a href="'.site_url('counselor').'" class="nav-link">
                    <i class="link-icon fas fa-user-tie"></i>
                    <span class="link-title">Konselor/Terapis</span>
                </a>
            </li>
            <li class="nav-item ">
                <a href="'.site_url('users').'" class="nav-link">
                    <i class="link-icon fa fa-user"></i>
                    <span class="link-title">Users</span>
                </a>
            </li>
            <!--
            <li class="nav-item ">
                <a href="#posts" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="posts">
                    <i class="link-icon fa fa-clone"></i>
                    <span class="link-title">Posts</span>
                    <i class="link-arrow fa fa-chevron-down"></i>
                </a>
                <div class="collapse" id="posts">
                    <ul class="nav sub-menu">
                        <li class="nav-item"><a href="#" class="nav-link">All Posts</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Add New</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Draft</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Unpublish</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Awaiting Approval</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Trash</a></li>
                    </ul>
                </div>
            </li>
            -->
            <li class="nav-item nav-category">Exit</li>

            <li class="nav-item ">
                <a href="'.site_url('logout').'" class="nav-link">
                    <i class="link-icon fa fa-sign-out-alt"></i>
                    <span class="link-title">Sign Out</span>
                </a>
            </li>
        </ul>
    </div>
</nav>';