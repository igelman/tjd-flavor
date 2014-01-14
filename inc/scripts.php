<script type="text/javascript">
	jQuery.noConflict(); 
	
	//DOCUMENT.READY
	jQuery(document).ready(function() { 
		//hide various jQuery elements until they are loaded
		jQuery('#featured ul').show();
		jQuery('.it-widget-tabs').show();
		//revolution slider
		<?php		
		$it_featured_layout=it_get_setting('featured_layout');
		$minisite = it_get_minisite($post->ID);
		if($minisite) $it_featured_layout = $minisite->featured_layout;

		# setup featured layout variables
		switch ($it_featured_layout) {
			case 'small':
				$startheight=380;
				$startwidth=650;
			break;
			case 'medium':
				$startheight=450;
				$startwidth=818;
			break;
			case 'large':
				$startheight=653;
				$startwidth=1118;
			break;	
			default:
				$startheight=380;
				$startwidth=650;
			break;
		}		
		?>			
		if (jQuery.fn.cssOriginal!=undefined)
		jQuery.fn.css = jQuery.fn.cssOriginal;
		
		jQuery('#featured').revolution(
			{    
			delay:<?php echo it_get_setting('featured_interval'); ?>000,                                                
			startheight:<?php echo $startheight; ?>,                            
			startwidth:<?php echo $startwidth; ?>,
			
			hideThumbs:200,
			
			thumbWidth:100,                            
			thumbHeight:50,
			thumbAmount:5,
			
			navigationType:"none",               
			navigationArrows:"solo",      
			navigationStyle:"round",               
										
			navigationHAlign:"center",             
			navigationVAlign:"bottom",                 
			navigationHOffset:0,
			navigationVOffset:20,
			
			soloArrowLeftHalign:"left",
			soloArrowLeftValign:"center",
			soloArrowLeftHOffset:20,
			soloArrowLeftVOffset:0,
			
			soloArrowRightHalign:"right",
			soloArrowRightValign:"center",
			soloArrowRightHOffset:20,
			soloArrowRightVOffset:0,
			touchenabled:"on",                      
			onHoverStop:"on",                        
			
			navOffsetHorizontal:0,
			navOffsetVertical:20,
			
			hideCaptionAtLimit:500,
			hideAllCaptionAtLilmit:0,
			hideSliderAtLimit:0,
			
			stopAtSlide:-1,
			stopAfterLoops:-1,
			
			shadow:0,
			fullWidth:"off"    
											
		});		
	
		//superfish
		jQuery('#top-menu ul').superfish({
			hoverClass:  'over', 						// the class applied to hovered list items 
			delay:       500,                            // one second delay on mouseout 
			animation:   {height:'show'},  // fade-in and slide-down animation 
			speed:       160,                          // faster animation speed 
			disableHI:   true,
			autoArrows:  false
		});
		jQuery('#main-menu ul').superfish({
			hoverClass:  'over', 						// the class applied to hovered list items 
			delay:       500,                            // one second delay on mouseout 
			animation:   {height:'show'},  // fade-in and slide-down animation 
			speed:       160,                          // faster animation speed 
			disableHI:   true,
			autoArrows:  false
		});
		jQuery('#sub-menu ul').superfish({
			hoverClass:  'over', 						// the class applied to hovered list items 
			delay:       500,                            // one second delay on mouseout 
			animation:   {height:'show'},  // fade-in and slide-down animation 
			speed:       160,                          // faster animation speed 
			disableHI:   true,
			autoArrows:  false
		});
		jQuery('#selector-wrapper ul').superfish({
			hoverClass:  'over', 						// the class applied to hovered list items 
			delay:       400,                            // one second delay on mouseout 
			animation:   {height:'show'},  // fade-in and slide-down animation 
			speed:       300,                          // faster animation speed 
			disableHI:   true,
			autoArrows:  false
		});	
		
		// trending slider
		jQuery('#trending-toggle').click(function() { 
			jQuery('#trending-wrapper').animate({				 
			 height: 'toggle'				 
			}, 200, 'linear' );		
		});
		jQuery('.carousel').carousel({
			interval: <?php echo it_get_setting("trending_interval"); ?>000
		});
				
		//sizzlin slider		
		jQuery('#sizzlin .slide').list_ticker({
			speed:<?php echo ( it_get_setting("sizzlin_interval") ) ? it_get_setting("sizzlin_interval") : 6; ?>000, //number of milliseconds to display the item
			effect:'<?php echo ( it_get_setting("sizzlin_effect") ) ? it_get_setting("sizzlin_effect") : 'slide'; ?>' //slide or fade
		});
		
		//don't show top ten slider until page is loaded
		jQuery('#top-ten').show();
		
		//top ten slider simply scroll plugin (standard carousel for IE8)		
		var isIE8 = jQuery.browser.msie && +jQuery.browser.version === 8;
		if ( isIE8 ) {
			jQuery('#top-ten-slider').wrapInner('<div id="#top-ten-inner" class="carousel-inner" />');
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
				
		//image darkening
		jQuery('body').on('mouseenter', '.darken', function(e) {
			jQuery(this).find('img').stop().animate({ opacity: .3 }, 150);
		}).on('mouseleave', '.darken', function(e) {
			jQuery(this).find('img').stop().animate({ opacity: 1.0 }, 500);
		});
		
		//jquery ui slider
		<?php # get variables based on current minisite
		global $post;
		$minisite = it_get_minisite($post->ID);
		$metric = '';
		if(isset($minisite->rating_metric))	$metric = $minisite->rating_metric;
		switch($metric) {
			case 'number':
				$value = 5;
				$min = 0;
				$max = 10;
				$step = .1;
			break;
			case 'percentage':
				$value = 50;
				$min = 0;
				$max = 100;
				$step = 1;
			break;
			case 'letter':
				$value = 7;
				$min = 1;
				$max = 14;
				$step = 1;
			break;
			case 'stars':
				$value = 2.5;
				$min = 0;
				$max = 5;
				$step = .5;
			break;
			default:
				$value = 5;
				$min = 0;
				$max = 10;
				$step = .1;
			break;
		}
		?>
		jQuery('.form-selector').slider({
			value: <?php echo $value; ?>,
			min: <?php echo $min; ?>,
			max: <?php echo $max; ?>,
			step: <?php echo $step; ?>,
			orientation: "horizontal",
			range: "min",
			animate: true,
			slide: function( event, ui ) {
				var rating = ui.value;
				<?php if($metric=='letter') { ?>				
					var numbers = {'1':'F', '2':'F+', '3':'D-', '4':'D', '5':'D+', '6':'C-', '7':'C', '8':'C+', '9':'B-', '10':'B', '11':'B+', '12':'A-', '13':'A', '14':'A+'};					
					var rating = numbers[rating];
				<?php } elseif($metric=='percentage') { ?>	
					var rating = rating + '<span class="percentage">&#37;</span>';
				<?php } ?>			
				jQuery(this).siblings('.rating-value').html( rating );
			}
		});
		
		//HD images		
		if (window.devicePixelRatio == 2) {	
			var images = jQuery("img.hires");		
			// loop through the images and make them hi-res
			for(var i = 0; i < images.length; i++) {		
				// create new image name
				var imageType = images[i].src.substr(-4);
				var imageName = images[i].src.substr(0, images[i].src.length - 4);
				imageName += "@2x" + imageType;		
				//rename image
				images[i].src = imageName;
			}
		}			
		
		//fancy responsive selectboxes
		jQuery("select#select-menu-top-menu, select#select-menu-main-menu, select#select-menu-sub-menu, select#select-menu-tax-menu").selectBox();
		
		//add bootstrap classes to wordpress generated elements
		jQuery('.avatar-70, .avatar-50').addClass('img-circle');
		jQuery('.comment-reply-link').addClass('btn');
		jQuery('#reply-form input#submit').addClass('btn');
		
		//put this in a function so we can call it from ajax easily
		dynamicElements();
				
	});
	
	//applied to elements within ajax panels
	function dynamicElements() {		
		//jQuery tooltips				
		jQuery('.info').tooltip();		
		jQuery('info-bottom').tooltip({ placement: 'bottom' });
		//jQuery popovers
		jQuery('.popthis').popover();
		//jQuery alert dismissals
		jQuery(".alert").alert();
		//jQuery fitvids
		jQuery('.post-loop .video_frame').fitVids();
		//equal height columns
		equalHeightColumns(jQuery(".main-loop, #sidebar"));	
	}
	
	//call equal height columns when window is resized
	jQuery(window).resize(function() {
		equalHeightColumns(jQuery(".main-loop, #sidebar"));
	});
	
	//call equal height columns when main content is resized
	jQuery(".main-loop-content").resize(function(e){
		equalHeightColumns(jQuery(".main-loop, #sidebar"));
	});
	
	//call equal height columns when sidebar is resized
	jQuery(".sidebar-inner").resize(function(e){
		equalHeightColumns(jQuery(".main-loop, #sidebar"));
	});
	
	//equal height columns
	function equalHeightColumns(group) {
		tallest = 0;
		width = jQuery(window).width();							
		group.each(function() {
			jQuery(this).removeAttr('style');			
			thisHeight = jQuery(this).height();
			if(thisHeight > tallest) {
				tallest = thisHeight;
			}
		});
		if(width > 767) {	
			group.height(tallest);	
		}
	}	
	
	//search form submission
	jQuery("#searchformtop input").keypress(function(event) {
		if (event.which == 13) {
			event.preventDefault();
			jQuery("#searchformtop").submit();
		}
	});
	
	//email subscribe form submission
	jQuery("#feedburner_subscribe button").click(function() {		
		jQuery("#feedburner_subscribe").submit();		
	});
	
	//login form submission
	jQuery(".wp-user-form #user_pass").keypress(function(event) {
		if (event.which == 13) {
			event.preventDefault();
			jQuery(".wp-user-form").submit();
		}
	});
	
	//scroll all #top elements to top
	jQuery("a[href='#top']").click(function() {
		jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});
	
	//rating animations
	function animateRating(pos,delay,eid) {
		jQuery('#' + eid + ' .rating-meter').delay(delay).animate({
			opacity:1,
			left: pos + '%'
		}, 2500, 'easeOutCubic');	
	}
	
	//responsive menu select drop downs
	jQuery("select#select-menu-top-menu").change(function() {
	  window.location = jQuery(this).find("option:selected").val();
	});
	jQuery("select#select-menu-main-menu").change(function() {
	  window.location = jQuery(this).find("option:selected").val();
	});
	jQuery("select#select-menu-sub-menu").change(function() {
	  window.location = jQuery(this).find("option:selected").val();
	});
	jQuery("select#select-menu-tax-menu").change(function() {	
	  window.location = jQuery(this).find("option:selected").val();
	});
	
	//pinterest
	(function(d){
		var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
		p.type = 'text/javascript';
		p.async = true;
		p.src = '//assets.pinterest.com/js/pinit.js';
		f.parentNode.insertBefore(p, f);
	}(document));
	
	//facebook
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
		
	//WINDOW.LOAD
	jQuery(window).load(function() {		
		
		//flickr
		<?php #deal with default values
		$flickr_count = it_get_setting('flickr_number');
		if(empty($flickr_count)) $flickr_count=9;
		?>
		jQuery('.flickr').jflickrfeed({
			limit: <?php echo $flickr_count; ?>,
			qstrings: {
				id: '<?php echo it_get_setting('flickr_id'); ?>'
			},
			itemTemplate: '<li>'+
							'<a rel="colorbox" class="darken small" href="{{image}}" title="{{title}}">' +
								'<img src="{{image_s}}" alt="{{title}}" width="75" height="75" />' +
							'</a>' +
						  '</li>'
		}, function(data) {
		});	
		
		//tabs - these must go in window.load so pinterest will work inside a tab
		jQuery('.featured-widgets .it-clouds').tabs({ fx: { opacity: 'toggle', duration: 150 } });
		jQuery('#sidebar .it-clouds').tabs({ fx: { opacity: 'toggle', duration: 150 } });
		jQuery('#footer .it-clouds').tabs({ fx: { opacity: 'toggle', duration: 150 } });
		jQuery('.featured-widgets .it-social-tabs').tabs({ fx: { opacity: 'toggle', duration: 150 } });
		jQuery('#sidebar .it-social-tabs').tabs({ fx: { opacity: 'toggle', duration: 150 } });
		jQuery('#footer .it-social-tabs').tabs({ fx: { opacity: 'toggle', duration: 150 } });
		
		equalHeightColumns(jQuery(".main-loop, #sidebar"));	
					
		});	
	
	jQuery.noConflict();
	
	<?php if(it_get_setting('show_demo_panel')) { ?>
	
	jQuery(document).ready(function() {
		
		jQuery('.accent-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeAccentColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.menu-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeMenuColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.menu-text-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeMenuTextColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.submenu-text-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeSubmenuTextColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.random-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeRandomColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.topten-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeToptenColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.topten-text-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeToptenTextColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.social-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeSocialColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.social-text-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeSocialTextColor(ui)}, clear: function() {}, hide: true, palettes: true});
		jQuery('.background-color').wpColorPicker({defaultColor: false, change: function(event, ui){changeBackgroundColor(ui)}, clear: function() {}, hide: true, palettes: true});	
	
		jQuery(".toggle-demo").click(function(){
			jQuery(".demo-wrapper").animate({
				left: "0px"
			}, "fast");
			jQuery(".toggle-demo").toggle();
		});	
		jQuery(".hide-demo").click(function(){
			jQuery(".demo-wrapper").animate({
				left: "-161px"
			}, "fast");
		});
		jQuery(".background-overlay").click(function(){
			jQuery('#bg-overlay').show();
			jQuery('.background-overlay').toggle();
			jQuery('.background-overlay').toggleClass('btn-primary');
		});
		jQuery(".remove-overlay").click(function(){		
			jQuery('#bg-overlay').hide();
		});	
		jQuery(".flatten-menu").click(function(){
			jQuery('#main-menu-wrapper .menu-inner, #main-menu-wrapper .right-shadow, #main-menu-wrapper').css('background-image','none');
			jQuery('.flatten-menu').toggle();
			jQuery('.flatten-menu').toggleClass('btn-primary');
		});
		jQuery(".unflatten-menu").click(function(){
			var img = jQuery(this).data('images');
			jQuery('#main-menu-wrapper').css('background-image','url(' + img + '/main-menu-bg.png)');
			jQuery('#main-menu-wrapper .right-shadow').css('background-image','url(' + img + '/main-menu-horizontal-right-bg.png)');
			jQuery('#main-menu-wrapper .menu-inner').css('background-image','url(' + img + '/main-menu-horizontal-bg.png)');
		});
		jQuery(".flatten-topten").click(function(){
			jQuery('#selector-wrapper, #top-ten-social').css('background-image','none');
			jQuery('.flatten-topten').toggle();
			jQuery('.flatten-topten').toggleClass('btn-primary');
		});
		jQuery(".unflatten-topten").click(function(){
			var img = jQuery(this).data('images');
			jQuery('#selector-wrapper, #top-ten-social').css('background-image','url(' + img + '/top-ten-bg.png)');
		});	
		jQuery('#menu-fonts').change(function(){
			var fontval = jQuery("#menu-fonts option:selected").val();
			var fontname = jQuery("#menu-fonts option:selected").text();
			jQuery('link#menu-fonts-link').attr({href:'http://fonts.googleapis.com/css?family=' + fontval});
			jQuery('style#menu-fonts-style').text('#top-menu ul li a, #main-menu-wrapper ul li a, #sub-menu ul li a { font-family:' + fontname + ', sans-serif; }');
		});	
		jQuery('#body-fonts').change(function(){
			var fontval = jQuery("#body-fonts option:selected").val();
			var fontname = jQuery("#body-fonts option:selected").text();
			jQuery('link#body-fonts-link').attr({href:'http://fonts.googleapis.com/css?family=' + fontval});
			jQuery('style#body-fonts-style').text('body, .the-content { font-family:' + fontname + ', sans-serif; }');
		});	
		jQuery('#widget-fonts').change(function(){
			var fontval = jQuery("#widget-fonts option:selected").val();
			var fontname = jQuery("#widget-fonts option:selected").text();
			jQuery('link#widget-fonts-link').attr({href:'http://fonts.googleapis.com/css?family=' + fontval});
			jQuery('style#widget-fonts-style').text('.widget { font-family:' + fontname + ', sans-serif; }');
		});
		
		jQuery("select#menu-fonts, select#body-fonts, select#widget-fonts").selectBox();
		
	});
	
	function changeAccentColor(ui) {
		jQuery('.post-tags a:hover, #trending-toggle a').css('background-color',ui.color.toString());	
		jQuery('.ratings .ui-slider-range').css('background',ui.color.toString());	
	};
	
	function changeMenuColor(ui) {
		jQuery('#main-menu-wrapper, #main-menu-wrapper ul li ul, #main-menu-wrapper ul li ul li a, #main-menu-wrapper ul li:hover ul li a, #main-menu-wrapper ul li.over ul li a, #main-menu-wrapper ul li ul li.over ul li a').css('background-color',ui.color.toString());	
		jQuery('#main-menu-wrapper ul li ul li a:hover, #main-menu-wrapper ul li ul li ul li a:hover').css('background-color','rgba(0, 0, 0, 0.2)');
	};
	
	function changeMenuTextColor(ui) {
		jQuery('#main-menu-wrapper ul li a, #main-menu-wrapper ul li:hover ul li a, #main-menu-wrapper ul li.over ul li a').css('color',ui.color.toString());	
		jQuery('#main-menu-wrapper ul li ul li a:hover, #main-menu-wrapper ul li ul li ul li a:hover').css('color','#FFFFFF');
	};
	
	function changeSubmenuTextColor(ui) {
		jQuery('#sub-menu ul li a').css('color',ui.color.toString());
	};
	
	function changeRandomColor(ui) {
		jQuery('#main-menu-wrapper #random-article a').css('color',ui.color.toString());
	};
	
	function changeToptenColor(ui) {
		jQuery('#selector-wrapper, #selector-wrapper ul li ul li a:hover').css('background-color',ui.color.toString());
	};
	
	function changeToptenTextColor(ui) {
		jQuery('#selector-wrapper, #top-ten-selected a.selector-button, #selector-wrapper ul li ul li a:hover, #selector-wrapper ul li ul li:hover .selector-icon, .selector-arrow').css('color',ui.color.toString());
	};
	
	function changeSocialColor(ui) {
		jQuery('#top-ten-social, #top-ten-social .input-prepend .add-on, #top-ten-social input').css('background-color',ui.color.toString());
		jQuery('#top-ten-social .input-prepend .add-on, #top-ten-social input').css('border-color',ui.color.toString());
	};
	
	function changeSocialTextColor(ui) {
		jQuery('#top-ten-social, #top-ten-social .social-counts span, #top-ten-social .social-counts a, #top-ten-social .social-counts a:hover').css('color',ui.color.toString());
	};
	
	function changeBackgroundColor(ui) {
		jQuery('body').css('background-color',ui.color.toString());
	};
	
	<?php } ?>
	
</script>