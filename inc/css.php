<?php global $itMinisites, $post; ?>
<style type="text/css">
	<?php /*this style hack is necessary because of the custom function that adds the ancestor css attribute based
	on the custom menu item title attribute. if this wasn't here, any menu item with a child would appear
	as if it was the current active page ancestor. */
	if(is_front_page()) { ?>			
		#top-menu ul li.current_page_ancestor a, 
		#top-menu ul li.current_page_parent a {background-color:#000;background-color: rgba(0, 0, 0, 0.2);color:#FFF;}
		#top-menu ul li a:hover, #top-menu ul li:hover a, #top-menu ul li.over a {background-color:#000;background-color: rgba(0, 0, 0, 0.2);color:#FFF;}
		#main-menu-wrapper ul li.current_page_ancestor a, 
		#main-menu-wrapper ul li.current_page_parent a {background:none;}
		#main-menu-wrapper ul li a:hover, #main-menu-wrapper ul li:hover a, #main-menu-wrapper ul li.over a {background-color: rgba(0, 0, 0, 0.2);color:#FFF;}			
	<?php } ?>
	
	<?php #GENERAL THEME OPTIONS STYLE ?>
		
	<?php if(it_get_setting('bg_overlay')) { ?>
		#bg-overlay {display:block;}
	<?php } ?>
	<?php if(it_get_setting('color_accent_bg')) { ?>
		.post-tags a:hover {background-color:<?php echo it_get_setting('color_accent_bg') ?> !important;}
		a.thumbnail:hover, a.thumbnail:focus {border-color:<?php echo it_get_setting('color_accent_bg') ?>;}
		#trending-toggle a {background-color:<?php echo it_get_setting('color_accent_bg') ?>;}
		.ratings .ui-slider-range {background:<?php echo it_get_setting('color_accent_bg') ?>;}
		#comments .filterbar .sort-buttons a.reply-link {color:<?php echo it_get_setting('color_accent_fg') ?> !important;}
	<?php } ?>
	<?php if(it_get_setting('color_accent_fg')) { ?>
		#trending-toggle a, .main-loop.list .post-tags a:hover {color:<?php echo it_get_setting('color_accent_fg') ?>;}	
	<?php } ?>
	<?php if(it_get_setting('color_menu_bg')) { ?>
		#main-menu-wrapper, #main-menu-wrapper ul li ul, #main-menu-wrapper ul li ul li a, #main-menu-wrapper ul li:hover ul li a, #main-menu-wrapper ul li.over ul li a, #main-menu-wrapper ul li ul li.over ul li a {background-color:<?php echo it_get_setting('color_menu_bg') ?>;}
		#main-menu-wrapper ul li ul li a:hover, #main-menu-wrapper ul li ul li ul li a:hover {background-color: rgba(0, 0, 0, 0.2);}
	<?php } ?>
	<?php if(it_get_setting('color_menu_fg')) { ?>
		#main-menu-wrapper ul li a, #main-menu-wrapper ul li:hover ul li a, #main-menu-wrapper ul li.over ul li a {color:<?php echo it_get_setting('color_menu_fg') ?>;}
		#main-menu-wrapper ul li ul li a:hover, #main-menu-wrapper ul li ul li ul li a:hover {color:#FFF;}
	<?php } ?>
	<?php if(it_get_setting('color_submenu_fg')) { ?>
		#sub-menu ul li a {color:<?php echo it_get_setting('color_submenu_fg') ?>;}
	<?php } ?>
	<?php if(it_get_setting('color_random_button')) { ?>
		#main-menu-wrapper #random-article a {color:<?php echo it_get_setting('color_random_button') ?>;}
	<?php } ?>
	<?php if(it_get_setting('menu_flatten')) { ?>
		#main-menu-wrapper .menu-inner, #main-menu-wrapper .right-shadow, #main-menu-wrapper {background-image:none;}
	<?php } ?>
	<?php if(it_get_setting('color_topten_bg')) { ?>
		#selector-wrapper, #selector-wrapper ul li ul li a:hover {background-color:<?php echo it_get_setting('color_topten_bg') ?>;}
	<?php } ?>
	<?php if(it_get_setting('color_topten_fg')) { ?>
		#selector-wrapper, #top-ten-selected a.selector-button, #selector-wrapper ul li ul li a:hover, #selector-wrapper ul li ul li:hover .selector-icon, .selector-arrow {color:<?php echo it_get_setting('color_topten_fg') ?>;}
	<?php } ?>
	<?php if(it_get_setting('color_topten_social_bg')) { ?>
		#top-ten-social, #top-ten-social .input-prepend .add-on, #top-ten-social input {background-color:<?php echo it_get_setting('color_topten_social_bg') ?>;}
		#top-ten-social .input-prepend .add-on, #top-ten-social input {border-color:<?php echo it_get_setting('color_topten_social_bg') ?>;}
	<?php } ?>
	<?php if(it_get_setting('color_topten_social_fg')) { ?>
		#top-ten-social, #top-ten-social .social-counts span, #top-ten-social .social-counts a, #top-ten-social .social-counts a:hover {color:<?php echo it_get_setting('color_topten_social_fg') ?>;}
	<?php } ?>
	<?php if(it_get_setting('topten_flatten')) { ?>
		#selector-wrapper, #top-ten-social {background-image:none;}
	<?php } ?>
	<?php #google fonts
	$font_menus = it_get_setting('font_menus');	    
	if(!empty($font_menus) && $font_menus!='spacer') {		
		echo '#top-menu ul li a, #main-menu-wrapper ul li a, #sub-menu ul li a {font-family:'.$font_menus.';} ';			
	}
	$font_body = it_get_setting('font_body');
	$font_body_size = it_get_setting('font_body_size');	    
	if(!empty($font_body) && $font_body!='spacer') {		
		echo '.the-content {font-family:'.$font_body.';';
		if(!empty($font_body_size)) echo 'font-size:'.$font_body_size.'px;line-height:'.$font_body_size.'px;';
		echo '} body {font-family:'.$font_body.';}';			
	}	
	$font_widgets = it_get_setting('font_widgets');
	$font_widgets_size = it_get_setting('font_widgets_size');	    
	if(!empty($font_widgets) && $font_widgets!='spacer') {		
		echo '.widget {font-family:'.$font_widgets.';';
		if(!empty($font_widgets_size)) echo 'font-size:'.$font_widgets_size.'px !important;';
		echo '} ';			
	}		
		
	?>	
	
	<?php #MINISITE THEME OPTIONS STYLE ?>
	
	<?php $minisite = it_get_minisite($post->ID);
	if($minisite) {	 ?>
		<?php if($minisite->bg_color) { ?>
			body.it-background {background-color:<?php echo $minisite->bg_color; ?> !important;}
		<?php } ?>
		<?php if($minisite->bg_color_override) { ?>
			body.it-background {background-image:none !important;}
		<?php } ?>
		<?php if($minisite->bg_image) { ?>
			body.it-background {background-image:url(<?php echo $minisite->bg_image; ?>) !important;}
		<?php } ?>
		<?php if($minisite->bg_position) { ?>
			body.it-background {background-position:top <?php echo $minisite->bg_position; ?> !important;}
		<?php } ?>
		<?php if($minisite->bg_repeat) { ?>
			body.it-background {background-repeat:<?php echo $minisite->bg_repeat; ?> !important;}
		<?php } ?>
		<?php if($minisite->bg_attachment) { ?>
			body.it-background {background-attachment:<?php echo $minisite->bg_attachment; ?> !important;}
		<?php } ?>
		<?php if($minisite->bg_overlay) { ?>
			#bg-overlay {display:block;}
		<?php } ?>
		<?php if($minisite->color_accent_bg) { ?>
			.post-tags a:hover {background-color:<?php echo $minisite->color_accent_bg ?> !important;}
			a.thumbnail:hover, a.thumbnail:focus {border-color:<?php echo $minisite->color_accent_bg ?>;}
			#trending-toggle a {background-color:<?php echo $minisite->color_accent_bg ?>;}
			.ratings .ui-slider-range {background:<?php echo $minisite->color_accent_bg ?>;}
			.single-page .main-loop a, .single-page .main-loop a:visited {color:<?php echo $minisite->color_accent_bg ?>;}
			.single-page .main-loop a:hover {color:#000;}
			.single-page .main-loop a.like-button {color:#A99CAB;}
			.single-page .main-loop a.like-button:hover {color:#000;}
			#comments .filterbar .sort-buttons a.reply-link {color:<?php echo $minisite->color_accent_bg ?> !important;}
		<?php } ?>
		<?php if($minisite->color_accent_fg) { ?>
			#trending-toggle a, .main-loop.list .post-tags a:hover {color:<?php echo $minisite->color_accent_fg ?>;}
		<?php } ?>
		<?php if($minisite->color_menu_bg) { ?>
			#main-menu-wrapper, #main-menu-wrapper ul li ul, #main-menu-wrapper ul li ul li a, #main-menu-wrapper ul li:hover ul li a, #main-menu-wrapper ul li.over ul li a, #main-menu-wrapper ul li ul li.over ul li a {background-color:<?php echo $minisite->color_menu_bg ?>;}
			#main-menu-wrapper ul li ul li a:hover, #main-menu-wrapper ul li ul li ul li a:hover {background-color: rgba(0, 0, 0, 0.2);}
		<?php } ?>
		<?php if($minisite->color_menu_fg) { ?>
			#main-menu-wrapper ul li a, #main-menu-wrapper ul li:hover ul li a, #main-menu-wrapper ul li.over ul li a {color:<?php echo $minisite->color_menu_fg ?>;}
			#main-menu-wrapper ul li ul li a:hover, #main-menu-wrapper ul li ul li ul li a:hover {color:#FFF;}
		<?php } ?>
		<?php if($minisite->color_submenu_fg) { ?>
			#sub-menu ul li a {color:<?php echo $minisite->color_submenu_fg ?>;}
		<?php } ?>
		<?php if($minisite->color_random_button) { ?>
			#main-menu-wrapper #random-article a {color:<?php echo $minisite->color_random_button ?>;}
		<?php } ?>
		<?php if($minisite->menu_flatten) { ?>
			#main-menu-wrapper .menu-inner, #main-menu-wrapper .right-shadow, #main-menu-wrapper {background-image:none;}
		<?php } ?>
		<?php if($minisite->color_topten_bg) { ?>
			#selector-wrapper, #selector-wrapper ul li ul li a:hover {background-color:<?php echo $minisite->color_topten_bg ?>;}
		<?php } ?>
		<?php if($minisite->color_topten_fg) { ?>
			#selector-wrapper, #top-ten-selected a.selector-button, #selector-wrapper ul li ul li a:hover, #selector-wrapper ul li ul li:hover .selector-icon, .selector-arrow {color:<?php echo $minisite->color_topten_fg ?>;}
		<?php } ?>
		<?php if($minisite->color_topten_social_bg) { ?>
			#top-ten-social, #top-ten-social .input-prepend .add-on, #top-ten-social input {background-color:<?php echo $minisite->color_topten_social_bg ?>;}
			#top-ten-social .input-prepend .add-on, #top-ten-social input {border-color:<?php echo $minisite->color_topten_social_bg ?>;}
		<?php } ?>
		<?php if($minisite->color_topten_social_fg) { ?>
			#top-ten-social, #top-ten-social .social-counts span, #top-ten-social .social-counts a, #top-ten-social .social-counts a:hover {color:<?php echo $minisite->color_topten_social_fg ?>;}
		<?php } ?>
		<?php if($minisite->topten_flatten) { ?>
			#selector-wrapper, #top-ten-social {background-image:none;}
		<?php } ?>
	<?php } ?>
	
	<?php #PAGE SPECIFIC STYLES ?>
	
	<?php if(is_single() || is_page()) { 
		$bg_color = get_post_meta($post->ID, "_bg_color", $single = true);
		$bg_color_override = get_post_meta($post->ID, "_bg_color_override", $single = true);
		$bg_image = get_post_meta($post->ID, "_bg_image", $single = true);
		$bg_position = get_post_meta($post->ID, "_bg_position", $single = true);
		$bg_repeat = get_post_meta($post->ID, "_bg_repeat", $single = true);
		$bg_attachment = get_post_meta($post->ID, "_bg_attachment", $single = true);
		$bg_overlay = get_post_meta($post->ID, "_bg_overlay", $single = true);	
		?>	
		<?php if($bg_color) { ?>
			body.it-background {background-color:<?php echo $bg_color; ?> !important;}
		<?php } ?>
		<?php if($bg_color_override) { ?>
			body.it-background {background-image:none !important;}
		<?php } ?>
		<?php if($bg_image) { ?>
			body.it-background {background-image:url(<?php echo $bg_image; ?>) !important;}
		<?php } ?>
		<?php if($bg_position) { ?>
			body.it-background {background-position:top <?php echo $bg_position; ?> !important;}
		<?php } ?>
		<?php if($bg_repeat) { ?>
			body.it-background {background-repeat:<?php echo $bg_repeat; ?> !important;}
		<?php } ?>
		<?php if($bg_attachment) { ?>
			body.it-background {background-attachment:<?php echo $bg_attachment; ?> !important;}
		<?php } ?>
		<?php if($bg_overlay) { ?>
			#bg-overlay {display:block;}
		<?php } 
	} ?>
	
	<?php if( it_get_setting( 'custom_css' ) )  { ?>
		<?php echo stripslashes( it_get_setting( 'custom_css' ) ) . "\n"; ?>	
	<?php } ?>	
	
	<?php #MINISITE ICON STYLES ?>
	
	<?php foreach($itMinisites->minisites as $minisite) { 
		$id = $minisite->id;
		$icon = $minisite->icon;
		$iconhd = $minisite->iconhd;
		$iconwhite = $minisite->iconwhite;
		$iconhdwhite = $minisite->iconhdwhite;
		if(empty($iconwhite)) $iconwhite = $icon;
		if(empty($iconhdwhite)) $iconhdwhite = $iconhd;	
		$awards = $minisite->awards;
		?>

		@media screen {
		.minisite-icon-<?php echo $id; ?> {background:url(<?php echo $icon; ?>) no-repeat 0px 0px;background-size:16px 16px !important;width:16px;height:16px;float:left;}
		.minisite-icon-<?php echo $id; ?>.white, #footer .minisite-icon-<?php echo $id; ?>, #featured-bar-wrapper .minisite-icon-<?php echo $id; ?> {background:url(<?php echo $iconwhite; ?>) no-repeat 0px 0px;background-size:16px 16px !important;width:16px;height:16px;float:left;}
		<?php foreach($awards as $award){ 
			if(is_array($award)) {
				if(array_key_exists(0, $award)) {
					$awardname = stripslashes($award[0]->name);
					$awardid = preg_replace('/[^a-z0-9]/i', '', strtolower($awardname));
					$awardicon = $award[0]->icon;
					$awardiconwhite = $award[0]->iconwhite;
					if(empty($awardiconwhite)) $awardiconwhite = $awardicon;
					?>
					.award-icon-<?php echo $awardid; ?> {background:url(<?php echo $awardicon; ?>) no-repeat 0px 0px;background-size:16px 16px !important;width:16px;height:16px;float:left;}
					#featured .award-icon-<?php echo $awardid; ?> {background:url(<?php echo $awardiconwhite; ?>) no-repeat 0px 0px;background-size:16px 16px !important;width:16px;height:16px;float:left;}
				<?php } 
			}
		}?>
		}
		@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
		.minisite-icon-<?php echo $id; ?> {background:url(<?php echo $iconhd; ?>) no-repeat 0px 0px;background-size:16px 16px !important;width:16px;height:16px;float:left;}
		.minisite-icon-<?php echo $id; ?>.white, #footer .minisite-icon-<?php echo $id; ?>, #featured-bar-wrapper .minisite-icon-<?php echo $id; ?> {background:url(<?php echo $iconhdwhite; ?>) no-repeat 0px 0px;background-size:16px 16px !important;width:16px;height:16px;float:left;}
		<?php foreach($awards as $award){ 
			if(is_array($award)) {
				if(array_key_exists(0, $award)) {
					$awardname = stripslashes($award[0]->name);
					$awardid = preg_replace('/[^a-z0-9]/i', '', strtolower($awardname));
					$awardiconhd = $award[0]->iconhd;
					$awardiconhdwhite = $award[0]->iconhdwhite;
					if(empty($awardiconhdwhite)) $awardiconhdwhite = $awardiconhd;
					?>
					.award-icon-<?php echo $awardid; ?> {background:url(<?php echo $awardiconhd; ?>) no-repeat 0px 0px;background-size:16px 16px !important;width:16px;height:16px;float:left;}
					#featured .award-icon-<?php echo $awardid; ?> {background:url(<?php echo $awardiconhdwhite; ?>) no-repeat 0px 0px;background-size:16px 16px !important;width:16px;height:16px;float:left;}
				<?php } 
			}
		}?>
		}
		
	<?php } ?>
</style>