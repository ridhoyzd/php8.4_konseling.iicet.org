 
(function ($) {
	'use strict';
	$(function () {
		var body = $('body');
		var mainWrapper = $('.main-wrapper');
		var footer = $('footer');
		var sidebar = $('.sidebar');
		var navbar = $('.navbar').not('.top-navbar');
		
		// Applying perfect-scrollbar 
		if ($('.sidebar .sidebar-body').length) {
			const sidebarBodyScroll = new PerfectScrollbar('.sidebar-body');
		}
		if ($('.content-nav-wrapper').length) {
			const contentNavWrapper = new PerfectScrollbar('.content-nav-wrapper');
		}

		// Sidebar toggle to sidebar-folded
		$('.sidebar-toggler').on('click', function (e) {
			$(this).toggleClass('active');
			$(this).toggleClass('not-active');
			if (window.matchMedia('(min-width: 992px)').matches) {
				e.preventDefault();
				body.toggleClass('sidebar-folded');
			} else if (window.matchMedia('(max-width: 991px)').matches) {
				e.preventDefault();
				body.toggleClass('sidebar-open');
			}
		});

		// sidebar-folded on large devices
		function iconSidebar(e) {
			if (e.matches) {
				body.addClass('sidebar-folded');
			} else {
				body.removeClass('sidebar-folded');
			}
		}
		var desktopMedium = window.matchMedia('(min-width:992px) and (max-width: 1199px)');
		desktopMedium.addListener(iconSidebar);
		iconSidebar(desktopMedium);

		//  open sidebar-folded when hover
		$(".sidebar .sidebar-body").hover(
			function () {
				if (body.hasClass('sidebar-folded')) {
					body.addClass("open-sidebar-folded");
				}
			},
			function () {
				if (body.hasClass('sidebar-folded')) {
					body.removeClass("open-sidebar-folded");
				}
            }
        );

		// close sidebar when click outside on mobile/table    
		$(document).on('click touchstart', function (e) {
			e.stopPropagation();
			// closing of sidebar menu when clicking outside of it
			if (!$(e.target).closest('.sidebar-toggler').length) {
				var sidebar = $(e.target).closest('.sidebar').length;
				var sidebarBody = $(e.target).closest('.sidebar-body').length;
				if (!sidebar && !sidebarBody) {
					if ($('body').hasClass('sidebar-open')) {
						$('body').removeClass('sidebar-open');
					}
				}
			}
		});

		//Close other submenu in sidebar on opening any
		$(".sidebar-body > .nav > .nav-item > a[data-toggle='collapse']").on("click", function () {
			$(".sidebar-body > .nav > .nav-item").find('.collapse.show').collapse('hide');
        });
        
        /** add active class and stay opened when selected */
        var url = window.location;
        // for sidebar menu entirely but not cover sub-menu
        $('ul.nav a').filter(function() {
            return this.href == url;
        }).parent().addClass('active');

        // for sub-menu
        $('ul.nav .nav-item a').filter(function() {
            return this.href == url;
		}).parentsUntil(".nav > .sub-menu").parent().addClass('show').siblings().addClass('sibling').attr('aria-expanded',true);
		
		if( $('#ParentNavLink').val() != null) {
			//alert($('#ParentNavLink').val());
			var url = $('#ParentNavLink').val();
			// for sidebar menu entirely but not cover sub-menu
			$('ul.nav a').filter(function() {
				return this.href == url;
			}).parent().addClass('active');
			
			// for sub-menu
			$('ul.nav .nav-item a').filter(function() {
				return this.href == url;
			}).parentsUntil(".nav > .sub-menu").parent().addClass('show').siblings().addClass('sibling').attr('aria-expanded',true);
		}

		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		});
	});
	
})(jQuery);
