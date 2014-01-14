<script type="text/javascript">
	jQuery.noConflict(); 
	
	// AJAX SORTING BUTTONS
	// trending slider
	jQuery('body').on('click', '#trending-wrapper .sort-buttons a', function(e){
		jQuery("#trending-wrapper .loading").show();
		jQuery("#trending").animate({opacity: "0.2"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var sorter = jQuery(this).data('sorter');
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var thumbnail = jQuery(this).parent().data('thumbnail');
		var rating = jQuery(this).parent().data('rating');
		var icon = jQuery(this).parent().data('icon');
		var numarticles = jQuery(this).parent().data('numarticles');
		var currentquery = jQuery("#trending-wrapper").data('currentquery');
		var action = 'sort';	
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, action: action, location: location, thumbnail: thumbnail, rating: rating, icon: icon, numarticles: numarticles, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#trending-wrapper .loading").hide();
				jQuery("#trending").animate({opacity: "1"}, 500);	
				jQuery("#trending .carousel-inner").html(data.content);
				if (data.pages > 1) {
					jQuery(".carousel-control-wrapper").show();
				} else {
					jQuery(".carousel-control-wrapper").hide();
				}
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#trending-wrapper .loading").hide();
				jQuery("#trending").animate({opacity: "1"}, 500);	
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// trending featured widget
	jQuery('body').on('click', '.featured-widgets .trending-articles .sort-buttons a', function(e){
		jQuery(".featured-widgets .trending-articles .loading").show();
		jQuery(".featured-widgets .trending-articles .post-list").animate({opacity: "0.2"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var sorter = jQuery(this).data('sorter');
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var thumbnail = jQuery(this).parent().data('thumbnail');
		var rating = jQuery(this).parent().data('rating');
		var icon = jQuery(this).parent().data('icon');
		var numarticles = jQuery(this).parent().data('numarticles');
		var currentquery = jQuery(".trending-articles").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, action: action, location: location, thumbnail: thumbnail, rating: rating, icon: icon, numarticles: numarticles, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery(".featured-widgets .trending-articles .loading").hide();
				jQuery(".featured-widgets .trending-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery(".featured-widgets .trending-articles .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery(".featured-widgets .trending-articles .loading").hide();
				jQuery(".featured-widgets .trending-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// trending sidebar widget
	jQuery('body').on('click', '#sidebar .trending-articles .sort-buttons a', function(e){
		jQuery("#sidebar .trending-articles .loading").show();
		jQuery("#sidebar .trending-articles .post-list").animate({opacity: "0.2"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var sorter = jQuery(this).data('sorter');
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var thumbnail = jQuery(this).parent().data('thumbnail');
		var rating = jQuery(this).parent().data('rating');
		var icon = jQuery(this).parent().data('icon');
		var numarticles = jQuery(this).parent().data('numarticles');
		var currentquery = jQuery(".trending-articles").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, action: action, location: location, thumbnail: thumbnail, rating: rating, icon: icon, numarticles: numarticles, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#sidebar .trending-articles .loading").hide();
				jQuery("#sidebar .trending-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery("#sidebar .trending-articles .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#sidebar .trending-articles .loading").hide();
				jQuery("#sidebar .trending-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// trending footer widget
	jQuery('body').on('click', '#footer .trending-articles .sort-buttons a', function(e){
		jQuery("#footer .trending-articles .loading").show();
		jQuery("#footer .trending-articles .post-list").animate({opacity: "0.2"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var sorter = jQuery(this).data('sorter');
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var thumbnail = jQuery(this).parent().data('thumbnail');
		var rating = jQuery(this).parent().data('rating');
		var icon = jQuery(this).parent().data('icon');
		var numarticles = jQuery(this).parent().data('numarticles');
		var currentquery = jQuery(".trending-articles").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, action: action, location: location, thumbnail: thumbnail, rating: rating, icon: icon, numarticles: numarticles, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#footer .trending-articles .loading").hide();
				jQuery("#footer .trending-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery("#footer .trending-articles .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#footer .trending-articles .loading").hide();
				jQuery("#footer .trending-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// top ten slider
	jQuery('body').on('click', '#top-ten-selector a.clickable', function(e){
		jQuery("#top-ten .loading").show();
		jQuery("#top-ten-slider").animate({opacity: "0"}, 0);			
		
		var sorter = jQuery(this).data('sorter');
		var label = jQuery(this).data('label');
		var loop = jQuery(this).parent().parent().data('loop');
		var timeperiod = jQuery(this).parent().parent().data('timeperiod');
		var currentquery = jQuery("#top-ten").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, action: action, timeperiod: timeperiod, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#top-ten .loading").hide();
				jQuery("#top-ten-selected a").html(label);
				jQuery("#top-ten-selected .selector-icon span").removeClass().addClass('icon-'+sorter);
				jQuery('#selector-wrapper ul li').removeClass('over');
				jQuery('#selector-wrapper ul li ul').hide();
				jQuery("#top-ten-slider").animate({opacity: "1"}, 500);	
				jQuery("#top-ten-slider").html(data.content);
				//top ten slider simply scroll plugin (standard carousel for IE8)		
				var isIE8 = jQuery.browser.msie && +jQuery.browser.version === 8;
				if ( isIE8 ) {
					jQuery('#top-ten-slider').carousel({
						interval: 3500
					});
				} else {			
					jQuery("#top-ten-slider").simplyScroll({
						customClass: 'top-ten',
						orientation: 'horizontal', 
						direction: 'forwards',
						pauseOnHover: true,
						frameRate: 48,
						speed: 1		
					});	
				}
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#top-ten .loading").hide();
				jQuery("#top-ten-slider").animate({opacity: "1"}, 500);	
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// top ten featured widget
	jQuery('body').on('click', '.featured-widgets .topten-articles .sort-buttons a', function(e){
		jQuery(".featured-widgets .topten-articles .loading").show();
		jQuery(".featured-widgets .topten-articles .post-list").animate({opacity: "0"}, 0);
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');			
		
		var sorter = jQuery(this).data('sorter');
		var loop = jQuery(this).parent().data('loop');
		var timeperiod = jQuery(this).parent().data('timeperiod');
		var currentquery = jQuery(".topten-articles").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, action: action, timeperiod: timeperiod, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery(".featured-widgets .topten-articles .loading").hide();
				jQuery(".featured-widgets .topten-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery(".featured-widgets .topten-articles .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery(".featured-widgets .topten-articles .loading").hide();
				jQuery(".featured-widgets .topten-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// top ten sidebar widget
	jQuery('body').on('click', '#sidebar .topten-articles .sort-buttons a', function(e){
		jQuery("#sidebar .topten-articles .loading").show();
		jQuery("#sidebar .topten-articles .post-list").animate({opacity: "0"}, 0);
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');			
		
		var sorter = jQuery(this).data('sorter');
		var loop = jQuery(this).parent().data('loop');
		var timeperiod = jQuery(this).parent().data('timeperiod');
		var currentquery = jQuery(".topten-articles").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, action: action, timeperiod: timeperiod, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#sidebar .topten-articles .loading").hide();
				jQuery("#sidebar .topten-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery("#sidebar .topten-articles .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#sidebar .topten-articles .loading").hide();
				jQuery("#sidebar .topten-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// top ten footer widget
	jQuery('body').on('click', '#footer .topten-articles .sort-buttons a', function(e){
		jQuery("#footer .topten-articles .loading").show();
		jQuery("#footer .topten-articles .post-list").animate({opacity: "0"}, 0);
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');			
		
		var sorter = jQuery(this).data('sorter');
		var loop = jQuery(this).parent().data('loop');
		var timeperiod = jQuery(this).parent().data('timeperiod');
		var currentquery = jQuery(".topten-articles").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, action: action, timeperiod: timeperiod, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#footer .topten-articles .loading").hide();
				jQuery("#footer .topten-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery("#footer .topten-articles .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#footer .topten-articles .loading").hide();
				jQuery("#footer .topten-articles .post-list").animate({opacity: "1"}, 500);	
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// main loop view switcher
	jQuery('body').on('click', '.post-loop .sortbar-control a', function(e){
		jQuery(".main-loop .loading").show();
		jQuery(".main-loop-inner").animate({opacity: "0.15"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var view = jQuery(this).data('view');
		if(view=='grid') {
			remove='list';
		} else {
			remove='grid';
		}
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var sorter = jQuery(this).parent().data('sorter');
		var paginated = jQuery(this).parent().data('paginated');
		var columns = jQuery(this).parent().data('columns');
		var currentquery = jQuery(".post-loop").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, location: location, action: action, view: view, columns: columns, paginated: paginated, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) { 
				jQuery(".main-loop .loading").hide();				
				jQuery(".main-loop-inner").animate({opacity: "1"}, 500);
				jQuery('.main-loop').removeClass(remove).addClass(view);
				jQuery(".main-loop-inner").html(data.content);
				if(data.updatepagination==1) {
					jQuery(".pagination-inner").html(data.pagination);
					jQuery(".pagination-inner-mobile").html(data.paginationmobile);
				}
				jQuery('.post-loop .sort-buttons').data('view', view);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery(".main-loop .loading").hide();
				jQuery(".main-loop-inner").animate({opacity: "1"}, 500);
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// main loop sorting
	jQuery('body').on('click', '.post-loop .sortbar .sort-buttons a', function(e){
		jQuery(".main-loop .loading").show();
		jQuery(".main-loop-inner").animate({opacity: "0.15"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var view = jQuery(this).parent().data('view');
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var sorter = jQuery(this).data('sorter');
		var columns = jQuery(this).parent().data('columns');
		var paginated = jQuery(this).parent().data('paginated');
		var title = jQuery(this).data('original-title');
		var currentquery = jQuery(".post-loop").data('currentquery');
		var action = 'sort';
		
		// update loop content
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, location: location, action: action, view: view, columns: columns, paginated: paginated, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery(".main-loop .loading").hide();
				jQuery(".main-loop-inner").animate({opacity: "1"}, 500);
				jQuery(".main-loop-inner").html(data.content);
				if(data.updatepagination==1) {
					jQuery(".pagination-inner").html(data.pagination);
					jQuery(".pagination-inner-mobile").html(data.paginationmobile);
				}
				jQuery(".post-loop .sortbar-title").html(title);
				jQuery('.post-loop .sortbar-control').data('sorter', sorter);
				jQuery('.post-loop .pagination .sort-buttons').data('sorter', sorter);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery(".main-loop .loading").hide();
				jQuery(".main-loop-inner").animate({opacity: "1"}, 500);
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// main loop pagination
	jQuery('body').on('click', '.post-loop .pagination .sort-buttons a', function(e){
		jQuery(".main-loop .loading").show();
		jQuery(".main-loop-inner").animate({opacity: "0.15"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		jQuery('html, body').animate({
			scrollTop: jQuery(".post-loop").offset().top
		}, 300);
		
		var view = jQuery(this).parent().data('view');
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var sorter = jQuery(this).parent().data('sorter');
		var columns = jQuery(this).parent().data('columns');
		var paginated = jQuery(this).data('paginated');
		var currentquery = jQuery(".post-loop").data('currentquery');
		var action = 'sort';
		
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, location: location, loop: loop, action: action, view: view, columns: columns, paginated: paginated, currentquery: currentquery },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery(".main-loop .loading").hide();
				jQuery(".main-loop-inner").animate({opacity: "1"}, 500);
				jQuery(".main-loop-inner").html(data.content);
				if(data.updatepagination==1) {
					jQuery(".pagination-inner").html(data.pagination);
					jQuery(".pagination-inner-mobile").html(data.paginationmobile);
				}
				jQuery('.post-loop .sortbar-control').data('paginated', paginated);
				jQuery('.post-loop .sortbar .sort-buttons').data('paginated', paginated);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery(".main-loop .loading").hide();
				jQuery(".main-loop-inner").animate({opacity: "1"}, 500);
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// featured widgets minisite filtering
	jQuery('body').on('click', '.featured-widgets .minisite-tabs .sort-buttons a', function(e){
		jQuery(".featured-widgets .minisite-tabs .loading").show();
		jQuery(".featured-widgets .minisite-tabs .post-list").animate({opacity: "0.15"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var thumbnail = jQuery(this).parent().data('thumbnail');
		var morelink = jQuery(this).parent().data('morelink');
		var rating = jQuery(this).parent().data('rating');
		var numarticles = jQuery(this).parent().data('numarticles');
		var sorter = jQuery(this).data('sorter');
		var action = 'sort';
		
		// update loop content
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, location: location, action: action, thumbnail: thumbnail, rating: rating, numarticles: numarticles, morelink: morelink },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery(".featured-widgets  .minisite-tabs .loading").hide();
				jQuery(".featured-widgets  .minisite-tabs .post-list").animate({opacity: "1"}, 500);
				jQuery(".featured-widgets  .minisite-tabs .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery(".featured-widgets  .minisite-tabs .loading").hide();
				jQuery(".featured-widgets  .minisite-tabs .post-list").animate({opacity: "1"}, 500);
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// sidebar widget minisite filtering
	jQuery('body').on('click', '#sidebar .minisite-tabs .sort-buttons a', function(e){
		jQuery("#sidebar .minisite-tabs .loading").show();
		jQuery("#sidebar .minisite-tabs .post-list").animate({opacity: "0.15"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var thumbnail = jQuery(this).parent().data('thumbnail');
		var morelink = jQuery(this).parent().data('morelink');
		var rating = jQuery(this).parent().data('rating');
		var numarticles = jQuery(this).parent().data('numarticles');
		var sorter = jQuery(this).data('sorter');
		var action = 'sort';
		
		// update loop content
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, location: location, action: action, thumbnail: thumbnail, rating: rating, numarticles: numarticles, morelink: morelink },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#sidebar .minisite-tabs .loading").hide();
				jQuery("#sidebar .minisite-tabs .post-list").animate({opacity: "1"}, 500);
				jQuery("#sidebar .minisite-tabs .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#sidebar .minisite-tabs .loading").hide();
				jQuery("#sidebar .minisite-tabs .post-list").animate({opacity: "1"}, 500);
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// footer widget minisite filtering
	jQuery('body').on('click', '#footer .minisite-tabs .sort-buttons a', function(e){
		jQuery("#footer .minisite-tabs .loading").show();
		jQuery("#footer .minisite-tabs .post-list").animate({opacity: "0.15"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');
		var thumbnail = jQuery(this).parent().data('thumbnail');
		var morelink = jQuery(this).parent().data('morelink');
		var rating = jQuery(this).parent().data('rating');
		var numarticles = jQuery(this).parent().data('numarticles');
		var sorter = jQuery(this).data('sorter');
		var action = 'sort';
		
		// update loop content
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { sorter: sorter, loop: loop, location: location, action: action, thumbnail: thumbnail, rating: rating, numarticles: numarticles, morelink: morelink },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#footer .minisite-tabs .loading").hide();
				jQuery("#footer .minisite-tabs .post-list").animate({opacity: "1"}, 500);
				jQuery("#footer .minisite-tabs .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#footer .minisite-tabs .loading").hide();
				jQuery("#footer .minisite-tabs .post-list").animate({opacity: "1"}, 500);
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// like button
	jQuery('body').on('click', 'a.do-like', function(e){
		 jQuery(this).removeClass('do-like');
		 var postID = jQuery(this).data('postid');
		 var likeaction = jQuery(this).data('likeaction');
		 var location = jQuery(".main-loop").data('location');
		 var action = 'like';
		 var _this = this;
		 jQuery.ajax({
			 url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			 data: { id: postID, action: action, likeaction: likeaction, location: location },
			 type: 'POST',
			 dataType: 'json',
			 success: function (data) {
				 jQuery(_this).addClass('do-like');
				 jQuery('a.like-button.' + postID + ' .numcount').html(data.content);
				 if(likeaction=='like') {
					 jQuery('a.like-button.' + postID + ' .icon').removeClass('like').addClass('unlike');
					 jQuery('a.like-button.' + postID).data('likeaction', 'unlike');
				 } else {
					 jQuery('a.like-button.' + postID + ' .icon').removeClass('unlike').addClass('like');
					 jQuery('a.like-button.' + postID).data('likeaction', 'like');
				 }
				 jQuery('#ajax-error').hide();
			 },
			 error: function (jxhr, msg, err) {
				 jQuery(_this).addClass('do-like');
				 jQuery('#ajax-error').show();
				 jQuery('#ajax-error').html(msg);
			 }
		 });
	 });
	// user rating panel display
	jQuery('body').on('mouseover', '.user-rating .rating-wrapper.single', function(e) {				
		jQuery(this).children('.rating-bar').addClass('over');	
		jQuery(this).children('.rating-bar').children('.rating-meter').hide();	
		jQuery(this).children().children('.form-selector').show();		
	});
	jQuery('body').on('mouseleave', '.user-rating .rating-wrapper', function(e) {				
		jQuery(this).children('.rating-bar').removeClass('over');	
		jQuery(this).children().children('.form-selector').hide();
		jQuery(this).children('.rating-bar').children('.rating-meter').show();			
	});	
	// update user ratings
	jQuery( ".user-rating .form-selector" ).on( "slidestop", function( event, ui ) {
		var meta = jQuery(this).parent().data('meta');
		var divID = jQuery(this).parent().attr("id");
		var action = 'rate';
		<?php # get variables based on current minisite
		global $post;
		$minisite = it_get_minisite($post->ID, true);
		$metric = !empty($minisite) ? $minisite->rating_metric : '';
		?>
		var postID = '<?php echo $post->ID; ?>';
		var rating = ui.value;
		var metric = '<?php echo $metric; ?>';		
		jQuery.ajax({
			 url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			 data: { id: postID, action: action, meta: meta, rating: rating, metric: metric, divID: divID },
			 type: 'POST',
			 dataType: 'json',
			 success: function (data) {
				 jQuery('.user-rating .rated-legend').addClass('active');
				 if(data.unlimitedratings != 1) {
				 	jQuery('#' + data.divID + '_wrapper').removeClass('single');
				 }
				 jQuery('#' + data.divID + ' .rating-value').hide(100)
				 	.delay(300)
					.queue(function(n) {
						jQuery(this).html(data.newrating);
						n();
					}).show(150);
				 jQuery('.user-rating .rating-wrapper.total .rating-value').delay(200)
				 	.hide(200)
				 	.delay(400)
					.queue(function(n) {
						jQuery(this).html(data.totalrating);
						n();
					}).show(400);
				 jQuery('#' + data.divID + ' .icon-check').delay(100).fadeIn(100);	
				 var left = -(100 - data.normalized);
				 animateRating(left, 400, data.divID);
			 },
			 error: function (jxhr, msg, err) {
				 jQuery('#ajax-error').show();
				 jQuery('#ajax-error').html(msg);
			 }
		 });
	});
	// user comment rating
	jQuery( "#respond .form-selector" ).on( "slidestop", function( event, ui ) {
		var divID = jQuery(this).parent().attr("id");	
		var rating = jQuery(this).siblings('.rating-value').html();
		jQuery('#' + divID + ' .icon-check').delay(100).fadeIn(100);
		jQuery('#' + divID + ' .hidden-rating-value').val(rating);
	});
	// star user ratings
	jQuery('.rating-wrapper .rateit').bind('rated reset', function (e) {
		 var ri = jQuery(this);
		
		 var noupdate = ri.data('noupdate');
		 var rating = ri.rateit('value');
		 var postID = ri.data('postid');
		 var meta = ri.data('meta');
		 var divID = ri.parent().parent().attr('id');
		 var action = 'rate';
		 var metric = 'stars';
		 var unlimitedratings = ri.data('unlimitedratings');
	
		 //disable rating ability after user submits rating
		 if(unlimitedratings != 1) {
		 	ri.rateit('readonly', true);
		 }
		 
		 if(noupdate==1) {
			 var divID = jQuery(this).parent().parent().attr("id");
			 jQuery('#' + divID + ' .hidden-rating-value').val(rating);
		 } else {	
			 jQuery.ajax({
				 url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
				 data: { id: postID, action: action, meta: meta, rating: rating, metric: metric, divID: divID },
				 type: 'POST',
				 dataType: 'json',
				 success: function (data) {
					 jQuery('.user-rating .rated-legend').addClass('active');
					 jQuery('.user-rating .rating-wrapper.total .rating-value').delay(200)
						.hide(200)
						.queue(function(n) {
							jQuery(this).html(data.totalrating);
							n();
						}).show(400);
					 var left = -(100 - data.normalized);
					 jQuery('#' + data.divID + ' .icon-check').delay(100).fadeIn(100);
					 animateRating(left, 400, data.divID);
				 },
				 error: function (jxhr, msg, err) {
					 jQuery('#ajax-error').show();
					 jQuery('#ajax-error').html(msg);
				 }
			 });
		 }
	 });
	 // recommended filtering
	jQuery('body').on('click', '#recommended .filterbar .sort-buttons a', function(e){
		jQuery("#recommended .loading").show();
		jQuery("#recommended .post-list").animate({opacity: "0.15"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var loop = jQuery(this).parent().data('loop');
		var location = jQuery(this).parent().data('location');		
		var thumbnail = jQuery(this).parent().data('thumbnail');
		var icon = jQuery(this).parent().data('icon');
		var columns = jQuery(this).parent().data('columns');
		var container = jQuery(this).parent().data('container');
		var rating = jQuery(this).parent().data('rating');
		var numarticles = jQuery(this).parent().data('numarticles');
		var targeted = jQuery(this).parent().data('targeted');
		var sorter = jQuery(this).data('sorter');
		var method = jQuery(this).data('method');
		var taxonomy = jQuery(this).data('taxonomy');
		var action = 'sort';
		
		<?php global $post; ?>
		var postID = '<?php echo $post->ID; ?>';
		
		// update loop content
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { id: postID, sorter: sorter, taxonomy: taxonomy, loop: loop, location: location, method: method, action: action, thumbnail: thumbnail, rating: rating, numarticles: numarticles, icon: icon, targeted: targeted, container: container, columns: columns },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#recommended .loading").hide();
				jQuery("#recommended .post-list").animate({opacity: "1"}, 500);
				jQuery("#recommended .post-list").html(data.content);
				jQuery('#ajax-error').hide();
				dynamicElements();
			},
			error: function (jxhr, msg, err) {
				jQuery("#recommended .loading").hide();
				jQuery("#recommended .post-list").animate({opacity: "1"}, 500);
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	// comment pagination
	jQuery('body').on('click', '#comments .sort-buttons a.sort-button', function(e){
		jQuery("#comments .loading").show();
		jQuery("#comments .comment-list").animate({opacity: "0.15"}, 0);	
		jQuery(this).addClass('active');
		jQuery(this).siblings().removeClass('active');
		
		var commentsperpage = jQuery(this).parent().data('number');
		var type = jQuery(this).parent().data('type');	
		var offset = jQuery(this).data('offset');	
		var action = 'paginate_comments';
		
		<?php global $post; ?>
		var postID = '<?php echo $post->ID; ?>';
		
		// update loop content
		jQuery.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/functions/ajax.php',
			data: { id: postID, action: action, commentsperpage: commentsperpage, offset: offset, type: type },
			type: 'POST',
			dataType: 'json',
			success: function (data) {			 
				jQuery("#comments .loading").hide();
				jQuery("#comments .comment-list").animate({opacity: "1"}, 500);
				jQuery("#comments .comment-list").html(data.content);
				jQuery('#ajax-error').hide();
			},
			error: function (jxhr, msg, err) {
				jQuery("#comments .loading").hide();
				jQuery("#comments .comment-list").animate({opacity: "1"}, 500);
				jQuery('#ajax-error').show();
				jQuery('#ajax-error').html(msg);
			}
		});
	});
	
</script>