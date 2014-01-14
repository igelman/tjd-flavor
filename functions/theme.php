<?php 
/*
this file contains functions relating to theme presentation that apply to all areas 
of the theme, including all posts, pages, and post types.
*/

#register sidebars
if ( !function_exists( 'it_sidebars' ) ) :
	function it_sidebars() {
		#setup array of default sidebars
		$sidebars = array(
			'loop-sidebar' => array(
				'name' => __( 'Loop Sidebar', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the sidebar of the main loop.', IT_TEXTDOMAIN )
			),
			'page-sidebar' => array(
				'name' => __( 'Page Sidebar', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the sidebar of the page content.', IT_TEXTDOMAIN )
			),
			'minisite-directory-sidebar' => array(
				'name' => __( 'Minisite Directory Sidebar', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the sidebar for minisite directory pages if the unique sidebar option for minisite directory pages is turned on.', IT_TEXTDOMAIN )
			),
			'404-sidebar' => array(
				'name' => __( '404 Sidebar', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the sidebar of the page content if the unique sidebar option for 404 pages is turned on.', IT_TEXTDOMAIN )
			),
			'featured-column-1' => array(
				'name' => __( 'Featured Column 1', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the first column of widgets next to the featured slider', IT_TEXTDOMAIN )
			),
			'featured-column-2' => array(
				'name' => __( 'Featured Column 2', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the second column of widgets next to the featured slider', IT_TEXTDOMAIN )
			),
			'top-ten-social' => array(
				'name' => __( 'Top Ten Bar Social', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the right of the top ten bar in the colored area.', IT_TEXTDOMAIN )
			),
			'footer-column-1' => array(
				'name' => __( 'Footer Column 1', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the first footer column.', IT_TEXTDOMAIN )
			),
			'footer-column-2' => array(
				'name' => __( 'Footer Column 2', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the second footer column.', IT_TEXTDOMAIN )
			),
			'footer-column-3' => array(
				'name' => __( 'Footer Column 3', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the third footer column.', IT_TEXTDOMAIN )
			),
			'footer-column-4' => array(
				'name' => __( 'Footer Column 4', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the fourth footer column.', IT_TEXTDOMAIN )
			),
			'footer-column-5' => array(
				'name' => __( 'Footer Column 5', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the fifth footer column.', IT_TEXTDOMAIN )
			),
			'footer-column-6' => array(
				'name' => __( 'Footer Column 6', IT_TEXTDOMAIN ),
				'desc' => __( 'These widgets appear in the sixth footer column.', IT_TEXTDOMAIN )
			)
		);
		
		#add woocommerce sidebar
		if(function_exists('is_woocommerce')) {
			$sidebars['woo-sidebar'] = array(
				'name' => __( 'WooCommerce Sidebar', IT_TEXTDOMAIN ), 
				'desc' => __( 'These widgets appear in the sidebar of the woocommerce pages (if unique woocommerce sidebars are turned on in the theme options).', IT_TEXTDOMAIN)
			);	
		}
		
		#add minisite sidebars to array
		global $itMinisites;
		if(is_array($itMinisites->minisites)) {
			foreach($itMinisites->minisites as $minisite){
				if($minisite->enabled) {
					#standard sidebar
					$sidebars[strtolower($minisite->safe_name) . '-sidebar'] = array(
						'name' => ucwords($minisite->name) . __( ' Sidebar', IT_TEXTDOMAIN ), 
						'desc' => __( 'These widgets appear in the sidebar of the ', IT_TEXTDOMAIN) . $minisite->name . __(' minisite pages.', IT_TEXTDOMAIN )
					);
					#featured sidebars
					$sidebars[strtolower($minisite->safe_name) . '-featured-column-1'] = array(
						'name' => ucwords($minisite->name) . __( ' Featured Column 1', IT_TEXTDOMAIN ), 
						'desc' => __( 'These widgets appear in featured column 1 for the ', IT_TEXTDOMAIN) . $minisite->name . __(' minisite pages.', IT_TEXTDOMAIN )
					);
					$sidebars[strtolower($minisite->safe_name) . '-featured-column-2'] = array(
						'name' => ucwords($minisite->name) . __( ' Featured Column 2', IT_TEXTDOMAIN ), 
						'desc' => __( 'These widgets appear in featured column 2 for the ', IT_TEXTDOMAIN) . $minisite->name . __(' minisite pages.', IT_TEXTDOMAIN )
					);			
													 
				}
				
			}	
		}
		
		#register sidebars
		foreach ( $sidebars as $type => $sidebar ){
			register_sidebar(array(
				'name' => $sidebar['name'],
				'id'=> $type,
				'description' => $sidebar['desc'],
				'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="header">',
				'after_title' => '</div>',
			));
		}
		
		#register custom sidebars areas
		$custom_sidebars = get_option( IT_SIDEBARS );
		if( !empty( $custom_sidebars ) ) {
			foreach ( $custom_sidebars as $id => $name ) {
				register_sidebar(array(
					'name' => $name,
					'id'=> "it_custom_sidebar_{$id}",
					'description' => '"' . $name . '"' . __(' custom sidebar was created in the Theme Options', IT_TEXTDOMAIN),
					'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<div class="header">',
					'after_title' => '</div>',
				));
			}
		}		
	}
endif;

#register widgets
if ( !function_exists( 'it_widgets' ) ) :
	function it_widgets() {
		# Load each widget file.
		require_once( THEME_WIDGETS . '/widget-latest-articles.php' );
		require_once( THEME_WIDGETS . '/widget-top-reviewed.php' );
		require_once( THEME_WIDGETS . '/widget-social-counts.php' );
		require_once( THEME_WIDGETS . '/widget-text-unwrapped.php' );
		require_once( THEME_WIDGETS . '/widget-minisite-tabs.php' );
		require_once( THEME_WIDGETS . '/widget-trending-articles.php' );
		require_once( THEME_WIDGETS . '/widget-top-ten.php' );
		require_once( THEME_WIDGETS . '/widget-clouds.php' );
		require_once( THEME_WIDGETS . '/widget-social-tabs.php' );
	
		# Register each widget.
		register_widget( 'it_latest_articles' );
		register_widget( 'it_top_reviewed' );
		register_widget( 'it_social_counts' );
		register_widget( 'it_text_unwrapped' );
		register_widget( 'it_minisite_tabs' );
		register_widget( 'it_trending_articles' );
		register_widget( 'it_top_ten' );
		register_widget( 'it_clouds' );
		register_widget( 'it_social_tabs' );
	}
endif;
#head scripts and css
function it_header_scripts() { ?>

	<?php #begin style ?> 
     
	<link media="screen, projection" rel="stylesheet" href="<?php echo THEME_STYLES_URI; ?>/bootstrap.css" type="text/css" />
    <?php if(!it_get_setting('responsive_disable')) { ?>
    	<link media="screen, projection" rel="stylesheet" href="<?php echo THEME_STYLES_URI; ?>/bootstrap-responsive.css" type="text/css" />
    <?php } else { ?>
    	<link media="screen, projection" rel="stylesheet" href="<?php echo THEME_STYLES_URI; ?>/bootstrap-non-responsive.css" type="text/css" />
    <?php } ?>
	<link media="screen, projection" rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" /> 
    <?php if(!it_get_setting('responsive_disable')) { ?>
    	<link media="screen, projection" rel="stylesheet" href="<?php echo THEME_STYLES_URI; ?>/responsive.css" type="text/css" />
    <?php } ?>
    <link media="screen, projection" rel="stylesheet" href="<?php echo THEME_STYLES_URI; ?>/retina.css" type="text/css" />
    <!--[if IE 8]>
            <link media="screen, projection" rel="stylesheet" type="text/css" href="<?php echo THEME_STYLES_URI; ?>/ie8.css" />
    <![endif]-->
    <!--[if IE 9]>
            <link media="screen, projection" rel="stylesheet" type="text/css" href="<?php echo THEME_STYLES_URI; ?>/ie9.css" />
    <![endif]-->
    <!--[if gt IE 9]>
            <link media="screen, projection" rel="stylesheet" type="text/css" href="<?php echo THEME_STYLES_URI; ?>/ie10.css" />
    <![endif]-->
    
    <?php it_get_template_part('css'); # styles with php variables ?>
    
    <?php #end style ?>
    
    <?php #custom favicon ?>	
	<link rel="shortcut icon" href="<?php if( it_get_setting( 'favicon_url' ) ) { ?><?php echo esc_url( it_get_setting( 'favicon_url' ) ); ?><?php } else { ?>/favicon.ico<?php } ?>" />
    
    <?php #google fonts  
	#get specified subsets if any
	$subset = '';
	$subsets = ( is_array( it_get_setting("font_subsets") ) ) ? it_get_setting("font_subsets") : array();
	foreach ($subsets as $s) {
		$subset .= $s . ',';
	}
	#remove last comma
	if(!empty($subset)) $subset = mb_substr($subset, 0, -1);
	#custom typography fonts
	$fonts = array();
	$font_menus = it_get_setting('font_menus');
	$font_menus = str_replace(' ', '+', str_replace('"', '', str_replace(', sans-serif', '', $font_menus)));
	$fonts[$font_menus] = $font_menus;
	$font_body = it_get_setting('font_body');
	$font_body = str_replace(' ', '+', str_replace('"', '', str_replace(', sans-serif', '', $font_body)));
	$fonts[$font_body] = $font_body;
	$font_widgets = it_get_setting('font_widgets');
	$font_widgets = str_replace(' ', '+', str_replace('"', '', str_replace(', sans-serif', '', $font_widgets)));
	$fonts[$font_widgets] = $font_widgets;
	
	$fonts = array_unique($fonts);
    foreach ($fonts as $font) {
		#exclude web fonts and default google fonts
		if(!empty($font) && (strpos($font, 'Arial')===false && strpos($font, 'Verdana')===false && strpos($font, 'Lucida+Sans')===false && strpos($font, 'Georgia')===false && strpos($font, 'Times+New+Roman')===false && strpos($font, 'Trebuchet+MS')===false && strpos($font, 'Courier+New')===false && strpos($font, 'Haettenschweiler')===false && strpos($font, 'Tahoma')===false && strpos($font, 'Oswald')===false && strpos($font, 'Signika')===false && strpos($font, 'Source+Sans+Pro')===false && strpos($font, 'spacer')===false))
			echo "<link href='http://fonts.googleapis.com/css?family=".$font."&subset=".$subset."' rel='stylesheet' type='text/css'> \n";
	}
	#default fonts 
	$family = 'http://fonts.googleapis.com/css?family=Oswald:300,400|Signika:400,300,600,700|Source+Sans+Pro:700&amp;subset=';  
	echo '<link href="'.$family.$subset.'" rel="stylesheet" type="text/css">';
	?>
	
<?php }
#demo panel scripts
function it_demo_styles() { 
	$show_demo = it_get_setting('show_demo_panel');
	if($show_demo) { ?>
    
    <link id="menu-fonts-link" href='#' rel='stylesheet' type='text/css'>
    <link id="body-fonts-link" href='#' rel='stylesheet' type='text/css'>
    <link id="widget-fonts-link" href='#' rel='stylesheet' type='text/css'>
    <style id="menu-fonts-style" type="text/css"></style>
    <style id="body-fonts-style" type="text/css"></style>
    <style id="widget-fonts-style" type="text/css"></style>  
    
<?php }
}
#demo panel content
function it_demo_panel() { 
	$show_demo = it_get_setting('show_demo_panel');
	if($show_demo) it_get_template_part('demo-panel');
}
#custom javascript
function it_footer_scripts() { ?>
	<script type="text/javascript" src="<?php echo THEME_JS_URI; ?>/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_JS_URI; ?>/plugins.min.js"></script>
    
    <?php it_get_template_part('scripts'); # custom js ?>   
    <?php it_get_template_part('scripts-ajax'); # custom js for ajax filtering ?>     

<?php
}
#custom javascript
function it_custom_javascript() {
	$custom_js = it_get_setting( 'custom_js' );
	
	if( empty( $custom_js ) )
		return;
		
	$custom_js = preg_replace( "/(\r\n|\r|\n)\s*/i", '', $custom_js );
	?><script type="text/javascript">
	/* <![CDATA[ */
	<?php echo stripslashes( $custom_js ); ?>
	/* ]]> */
</script>
<?php
}
#after post
function it_after_loop() { ?>
<!--
<div class="hide-pagination">
	<?php // there is an error when running ThemeCheck that says this theme does not have pagination when
    // in fact it does (see feed.php >> which calls the pagination function in functions/custom.php
    // so this code is added to bypass that error, but it is hidden so it doesn't show up on the page
    paginate_links();
	$args="";
	wp_link_pages( $args );
    ?>
</div>
-->	
<?php }
#html display of background ad
function it_background_ad() {
	$out = '';
	$url = it_get_setting('ad_background');
	global $post;
	$minisite = it_get_minisite($post->id);
	if($minisite) $url = $minisite->ad_background;
	if(!empty($url)) $out .= '<a id="background-ad" href="' . $url .'" target="_blank"></a>';
	return $out;
}
#get custom length excerpts
function it_excerpt($len) {
	$excerpt = get_the_excerpt();
	if (empty($len)) $len = 230;		
	if (mb_strlen($excerpt)>$len) $excerpt = mb_substr($excerpt, 0, $len-3) . "...";
	return $excerpt;
}
#get custom length titles
function it_title($len) {
	$title = get_the_title();		
	if (!empty($len) && mb_strlen($title)>$len) $title = mb_substr($title, 0, $len-3) . "...";
	return $title;
}
#html display of signup form
function it_signup_form() {
?>

	<div class="sortbar-right">
        <form id="feedburner_subscribe" class="subscribe" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo it_get_setting('feedburner_name'); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
                                    
            <div class="email-label"><?php echo it_get_setting("loop_email_label"); ?></div>
        
            <div class="input-append info" title="<?php echo it_get_setting("loop_email_label"); ?>">
                <input class="span2" id="appendedInputButton" type="text" name="email" placeholder="<?php _e('Email address',IT_TEXTDOMAIN); ?>">
                <button class="btn icon-check" type="button"></button>
            </div>
            
            <input type="hidden" value="<?php echo it_get_setting('feedburner_name'); ?>" name="uri"/>
            <input type="hidden" name="loc" value="en_US"/>
        
        </form>
    </div>
    
<?php 	
}
#html display of pagination
function it_pagination($pages = '', $format, $range = 6) {	
	global $paged;
	$out = '';	
	$cols = !empty($format['columns']) ? $format['columns'] : '';
	$view = !empty($format['view']) ? $format['view'] : '';
	$sort = !empty($format['sort']) ? $format['sort'] : '';	
	$location = !empty($format['location']) ? $format['location'] : '';
	if(empty($paged)) $paged = !empty($format['paged']) ? $format['paged'] : '';
	if(empty($paged)) $paged = 1;	
	if($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if(!$pages)	$pages = 1;
	} 
	if(empty($range)) $range = 6;
	$start = $paged - $range;
	$stop = $paged + $range;
	$leftshown = false;
	$firstshown = false;
	if(1 != $pages)	{	
		$out .= '<div class="sort-buttons" data-loop="main loop" data-location="'.$location.'" data-sorter="'.$sort.'" data-columns="'.$cols.'" data-view="'.$view.'">';			
		for ($i = $start; $i <= $stop; $i++) {	
			if($i>0 && $i<=$pages) {
				$class="inactive";
				if($paged == $i) $class="active";
				#first page
				if($start > 1 && !$firstshown && !it_get_setting('first_last_disable')) {
					$firstshown = true;
					$out .= '<a class="inactive first" data-paginated="1">&laquo;</a>';	
				}
				#left arrow	
				if($start > 1 && !$leftshown && !it_get_setting('prev_next_disable')) {
					$leftshown = true;
					$leftnum = $i - 1;
					$out .= '<a class="inactive" data-paginated="' . $leftnum . '"><span class="icon-left"></span></a>';	
				}
				#page number
				$out .= '<a class="' . $class . '" data-paginated="' . $i . '">' . $i . '</a>';						
			}
		}
		#right and last arrows	
		if($stop < $pages) {
			$rightnum = $i + 1;
			if(!it_get_setting('prev_next_disable')) $out .= '<a class="inactive" data-paginated="' . $rightnum . '"><span class="icon-right"></span></a>';	
			if(!it_get_setting('first_last_disable')) $out .= '<a class="inactive last" data-paginated="' . $pages . '">&raquo;</a>';	
		}		
		$out .= '</div>';
		return $out;
	}
}
#html display of responsive drop down menu
function it_responsive_menu($menu_name) {
	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		global $post;
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );                                    
		$menu_items = wp_get_nav_menu_items($menu->term_id);                                    
		$menu_list = '<select id="select-menu-' . $menu_name . '" class="mobile_menu"><option>'.__( 'Category Navigation',IT_TEXTDOMAIN).'</option>';      
		$flag = false;                              
		foreach ( (array) $menu_items as $key => $menu_item ) {
			$title = $menu_item->title;
			$url = $menu_item->url;
			$parentid = $menu_item->menu_item_parent;
			$indent = '';
			if($parentid!=0) { //see if this item needs to be indented
				$indent .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';							
			}
			$objectid = $menu_item->object_id;
			$type = $menu_item->type;
			$selected = '';
			$selectedoption = '';
			if((is_tax() || is_category() || is_tag()) && $type=='taxonomy') { //see if this is the currently displayed taxonomy
				$termid = get_queried_object()->term_id;
				if($termid == $objectid && !$flag) {
					$flag = true;
					$selected = "selected";
					$selectedoption = 'selected="selected"';
				}
			} elseif($objectid == $post->ID && ($type == 'post_type') && !$flag) { //see if this is the currently displayed page/post
				$flag = true;
				$selected = "selected";
				$selectedoption = 'selected="selected"';
			}
			
			$menu_list .= '<option ';
			if($selectedoption!='') {
				$menu_list .= $selectedoption;
			}
			$menu_list .= ' ';
			if($selected!='') {
				$menu_list .= 'class="' . $selected . '"';
			}
			$menu_list .= ' value="' . $url . '">' . $indent . $title . '</option>';
		}
		$menu_list .= '</select>';
	} else {
		$menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';
	}                                    
	return $menu_list;	
}
#html display of sidebar
function it_sidebar($sidebar, $layout) {
	echo '<div id="sidebar-wrapper" class="'.$layout.'">';	
		echo '<div id="sidebar">';
			echo '<div class="sidebar-inner">';				
			if ( function_exists('dynamic_sidebar') && dynamic_sidebar($sidebar) ) : else :			
				echo '<div class="widget">';			
					echo '<div class="header">';					
						echo '<h3>'.$sidebar.'</h3>';						
					echo '</div>';
				echo '</div>';			
			endif;	
			echo '</div>';		
		echo '</div>';		
	echo '</div>';	
}
#get featured image
function it_featured_image($postid, $size, $width, $height, $wrapper = false, $itemprop = false) {
	$out = '';
	$featured_image = '';
	if($wrapper) $out.='<div class="featured-image-wrapper"><div class="featured-image-inner">';
	if($itemprop) {
		$featured_image .= get_the_post_thumbnail($postid, $size, array( 'title' => get_the_title(), 'itemprop' => 'image' ));
	} else {
		$featured_image .= get_the_post_thumbnail($postid, $size, array( 'title' => get_the_title()));
	}
	if(empty($featured_image)) {
		$featured_image .= '<img';
		if($itemprop) $featured_image .= ' itemprop="image"';
		$featured_image .= ' src="'.THEME_IMAGES.'/placeholder-'.$width.'.png" alt="featured image" width="'.$width.'" height="'.$height.'" />';
	}
	$out.=$featured_image;
	if($wrapper) $out.='</div></div>';
	return $out;
}
#html display of featured video
function it_video( $args = array() ) {	
	extract( $args );	
	$url = str_replace('https://','http://',$url);
	# Vimeo video
	if( preg_match_all( '#http://(www.vimeo|vimeo)\.com(/|/clip:)(\d+)(.*?)#i', $url, $matches ) ) {
		if( !empty( $parse ) )
			return do_shortcode( '[vimeo url="' . $url . '" title="0" fs="0" portrait="0" height="' . $height . '" width="' . $width . '"]' );
		else
			return 'vimeo';
		
	} elseif( preg_match( '#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(.*?)#i', $url, $matches ) ) {
		if( !empty( $parse ) )			
			return do_shortcode( '[youtube url="' . $url . '" controls="' . ( empty( $video_controls ) ? 0 : 1 ) . '" showinfo="0" fs="1" height="' . $height . '" width="' . $width . '"]' );
		else			
			return 'youtube';
			
	} else {
		return false;
	}
}
#inject ad into loop
function it_get_ad($ads, $postcount, $adcount, $cols, $nonajax) {	
	$out = '';		
	if(it_get_setting('ad_num')!=0 && (($postcount==it_get_setting('ad_offset')+1) || (($postcount-it_get_setting('ad_offset')-1) % (it_get_setting('ad_increment'))==0) && $postcount>it_get_setting('ad_offset') && (it_get_setting('ad_num')>$adcount)) && ($nonajax || it_get_setting('ad_ajax'))) {				
		$out.='<div class="panel-wrapper it-ad">';				
			$out.='<div class="panel">';			
				$out .= do_shortcode($ads[$adcount]);			
			$out.='</div>';				 
		$out.='</div>';				
		if($postcount % $cols==0) $out.='<br class="visible-desktop clearer" />';                
		if($postcount % 2==0) $out.='<br class="hidden-desktop clearer two-panel" />'; #visible for tablets and down when sidebar is set to display		
		if($postcount % 3==0) $out.='<br class="hidden-desktop clearer three-panel" />'; #visible for tablets and down when sidebar is hidden (full width)
		$adcount++; #increase adcount		
		$postcount++; #increase postcount				
	}	
	$counts=array('ad' => $out, 'postcount' => $postcount, 'adcount' => $adcount);	
	return $counts;	
}

#html display of list of comma separated categories
function it_get_categories($postid, $label = false) {
	$categories = get_the_category($postid);
	$separator = ', ';
	$out = '';
	if($categories) {
		$out .= '<div class="category-list">';
			if($label) $out .= '<span class="icon-category"></span>';
			
			foreach($categories as $category) {
				$out .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", IT_TEXTDOMAIN ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
			}
			$out = substr_replace($out,"",-2);
			
		$out .= '</div>';
	}	
	return $out;
}

#html display of category with icon
/*
the theme assumes the icon will always be dark unless it's in the featured widgets area
or the footer area, so those two areas are hard-coded into the CSS in this function. 
the $white variable is an override variable and is only used if you want to force a 
white version of the icon in any area other than the featured and footer areas.
for instance, the category icon featured image overlays in the post loop which 
need white icons even though they're displayed in the light-colored main content area.
*/
function it_get_category($minisite, $white = false, $wrapper = false, $label = false) {
	$id = $minisite->id;
	$name = $minisite->name;
	$icon = $minisite->icon;
	$csswhite = '';
	if($white) $csswhite = ' white';
	$out='';
	if($wrapper) $out .= '<div class="minisite-wrapper">';
	if(!empty($icon)) $out .= '<span class="minisite-icon minisite-icon-' . $id . $csswhite . '"></span>';	
	if($label) $out .= $name;
	if($wrapper) $out .= '</div>';
	return $out;	
}

#get tags for the current post excluding template tags
function it_get_tags($postid, $separator = '') {	
	$tags = wp_get_post_tags($postid); #get all tag objects for this post
	$count=0;
	$tagcount=0;
	foreach($tags as $tag) {	#determine number of tags
		$tagcount++;
	}
	$out = '<div class="post-tags">';
	foreach($tags as $tag) {	#display tag list
		$count++;			
		$tag_link = get_tag_link($tag->term_id);
		$out .= '<a href="'.$tag_link.'" title="'.$tag->name.' Tag" class="'.$tag->slug.'">'.$tag->name.'</a>';
		if($count<$tagcount) {
			$out .= $separator; #add the separator if this is not the last tag
		}						
	}
	$out .= '<br class="clearer" /></div>';
	return $out;
}

#html display of likes
function it_get_likes($postid, $clickable = false, $label = false, $long_label = false) {
	$out = '';
	#determine if this post was already liked
	$ip=it_get_ip();
	$ips = get_post_meta($postid, IT_META_LIKE_IP_LIST, $single = true);
	$likeaction='like'; #default action is to like
	if(strpos($ips,$ip) !== false) $likeaction='unlike'; #already liked, need to unlike instead
	$likes = get_post_meta($postid, IT_META_TOTAL_LIKES, $single = true);	
	$label_text=__(' likes',IT_TEXTDOMAIN);
	if($long_label) $label_text=__(' people like this',IT_TEXTDOMAIN);
	if($likes==1) $label_text=__(' like',IT_TEXTDOMAIN);
	if($likes==1 && $long_label) $label_text=__(' person likes this',IT_TEXTDOMAIN);
	if(empty($likes) && $label) $likes=0; #display 0 if displaying the label
	if($clickable) {
		$out.='<a class="like-button do-like '.$postid.' info" data-postid="'.$postid.'" data-likeaction="'.$likeaction.'" title="'.__('Likes',IT_TEXTDOMAIN).'">';
	} else {
		$out='<span class="metric info" title="'.__('Likes',IT_TEXTDOMAIN).'">';
	}
	$out.='<span class="icon icon-liked '.$likeaction.'"></span>';
	$out.='<span class="numcount">'.$likes;
	if($label) $out.=$label_text;
	$out.='</span>';
	if($clickable) {
		$out.='</a>';
	} else {
		$out.='</span>';
	}
	return $out;
}

#html display of views
function it_get_views($postid, $label = false) {
	$views = get_post_meta($postid, IT_META_TOTAL_VIEWS, $single = true);
	$label_text=__(' views',IT_TEXTDOMAIN);
	if($views==1) $label_text=__(' view',IT_TEXTDOMAIN);
	$out='<span class="metric info" title="'.__('Views',IT_TEXTDOMAIN).'">';
	if(!empty($views)) {
		$out.='<span class="icon icon-viewed"></span>';
		$out.='<span class="numcount">'.$views;
		if($label) $out.=$label_text;
		$out.='</span>';
	}
	$out.='</span>';
	return $out;
}

#html display of comment count
function it_get_comments($postid, $showifempty = false, $label = false, $anchor_link = false) {
	$comments = get_comments_number($postid);
	$label_text=__(' comments',IT_TEXTDOMAIN);
	if($comments==1) $label_text=__(' comment',IT_TEXTDOMAIN);
	$out='<span class="metric info" title="'.__('Comments',IT_TEXTDOMAIN).'">';
	if($comments>0 || $showifempty) {
		if($anchor_link) $out.='<a href="#comments">';
			$out.='<span class="icon icon-commented"></span>';
			$out.='<span class="numcount">'.$comments;
			if($label) $out.=$label_text;
			$out.='</span>';
		if($anchor_link) $out.='</a>';
	}
	$out.='</span>';
	return $out;
}

#html display of author's latest article
function it_author_latest_article($authorid) {
	$authorargs = array( 'post_type' => 'any', 'author' => $authorid, 'showposts' => 1);
	$recentPost = new WP_Query($authorargs);
	if($recentPost->have_posts()) : while($recentPost->have_posts()) : $recentPost->the_post();	
		$out.=__( 'Latest Article: ', IT_TEXTDOMAIN );
		$out.='<a href="'.get_permalink().'">'.get_the_title().'</a>';
	endwhile;
	endif;
	return $out;	
}

#html display of author info
function it_get_author($postid) {
	$out = '<div class="author-info">';
		$out .= '<div class="author-image thumbnail">';
			$out .= '<a class="info" title="'.__('See all posts from this author',IT_TEXTDOMAIN).'" href="'.get_author_posts_url(get_the_author_meta('ID')).'">';
				$out .= get_avatar(get_the_author_meta('user_email'), 70);
			$out .= '</a>';
		$out .= '</div>';
		$out .= '<a class="info" title="'.__('See all posts from this author',IT_TEXTDOMAIN).'" href="'.get_author_posts_url(get_the_author_meta('ID')).'">';
			$out .= '<h3 itemprop="author">'.get_the_author_meta('display_name').'</h3>';
		$out .= '</a>';
		$out .= get_the_author_meta('description');
		$out .= it_author_profile_fields(get_the_author_meta('ID'));
		$out .= '<br class="clearer" />';
	$out .= '</div>';
	return $out;
}

#html display of post info panel
function it_get_post_info($postid) {
	$out = '';
	if(it_component_disabled('postinfo', $postid)) return false;	
	$out .= '<div class="postinfo-box-wrapper">';
		$out .= '<div class="postinfo-box">';	
			if(!it_component_disabled('likes', $postid)) $out .= it_get_likes($postid, true, true, true);
			if(!it_component_disabled('categories', $postid)) $out .= it_get_categories($postid, true);
			if(!(it_component_disabled('likes', $postid) && it_component_disabled('categories', $postid))) $out .= '<br class="clearer" />';
			if(!it_component_disabled('tags', $postid) && get_the_tags($postid)) $out .= '<div class="post-tags-wrapper"><span class="icon-tag"></span>'.it_get_tags($postid).'</div>';	
			$out .= '<br class="clearer" />';
			if(!it_component_disabled('author', $postid)) $out .= it_get_author($postid);	
		$out .= '</div>';
	$out .= '</div>';
		
	return $out;
}

#html display of related posts
function it_get_recommended($postid) {
	$out = '';
	if(it_component_disabled('recommended', $postid)) return false;	
	$minisite = it_get_minisite($postid);
	#theme options variables
	$label = it_get_setting('post_recommended_label');	
	$numarticles = it_get_setting('post_recommended_num');	
	$numfilters = it_get_setting('post_recommended_filters_num');
	$method = it_get_setting('post_recommended_method');	
	$method = ( !empty($method) ) ? $method : 'tags';
	#override with minisite-specific settings
	if($minisite) {
		$label = $minisite->recommended_label;
		$numarticles = $minisite->recommended_num;
		$numfilters = $minisite->recommended_filters_num;
		$method = $minisite->recommended_method;		
		$method = ( !empty($method) ) ? $method : 'tags';
	}
	#defaults
	$label = ( !empty($label) ) ? $label : __('You might also like',IT_TEXTDOMAIN);
	$numarticles = ( !empty($numarticles) ) ? $numarticles : 6;	
	$numfilters = ( !empty($numfilters) ) ? $numfilters : 3;	
	$loop = 'recommended';
	$location = 'widget';
	$thumbnail = true;
	$rating = true;
	$icon = true;
	$container = '#recommended';
	$cols = 3;
	#setup the query            
	$args=array('posts_per_page' => $numarticles, 'post__not_in' => array($postid));	
	#setup loop format
	$format = array('loop' => $loop, 'location' => $location, 'thumbnail' => $thumbnail, 'rating' => $rating, 'icon' => $icon, 'container' => $container, 'columns' => $cols);		
	#add minisite to args
	$targeted = '';	
	if($minisite && $minisite->recommended_targeted) {
		$args['post_type'] = $minisite->id;
		$targeted = $minisite->id;
	}	
	$filters = '';
	$count = 0;
	$testargs = $args;
	#recommended methods
	switch($method) {
		case 'tags':
			$terms = wp_get_post_terms($postid, 'post_tag');				
			foreach($terms as $term) {				
				$testargs['tag_id'] = $term->term_id;
				if($count==0) $args['tag_id'] = $term->term_id;
				query_posts ( $testargs );
				if(have_posts()) {
					$count++;
					
					$filters .= '<a data-sorter="'.$term->term_id.'" data-method="tags" class="info';
					if($count==1) $filters .= ' active';
					$filters .= '" title="' . __('More articles tagged: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';
					
					if($count == $numfilters) break;	
				}
			}
		break;
		case 'categories':
			$terms = wp_get_post_terms($postid, 'category');	
			foreach($terms as $term) {				
				$testargs['cat'] = $term->term_id;
				if($count==0) $args['cat'] = $term->term_id;
				query_posts ( $testargs );
				if(have_posts()) {
					$count++;
					
					$filters .= '<a data-sorter="'.$term->term_id.'" data-method="categories" class="info';
					if($count==1) $filters .= ' active';
					$filters .= '" title="' . __('More articles filed under: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';				
					
					if($count == $numfilters) break;
				}
			}
		break;
		case 'tags_categories':
			#tag			
			$terms = wp_get_post_terms($postid, 'post_tag');			
			foreach($terms as $term) {				
				$testargs['tag_id'] = $term->term_id;
				if($count==0) $args['tag_id'] = $term->term_id;	
				query_posts ( $testargs );
				$half = round($numfilters/2, 0);
				if(have_posts()) {	
					$count++;
					
					$filters .= '<a data-sorter="'.$term->term_id.'" data-method="tags" class="info';
					if($count==1) $filters .= ' active';
					$filters .= '" title="' . __('More articles tagged: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';
						
					if($count == $half) break;
				}
			}
			
			#category
			$testargs = $args;
			$terms = wp_get_post_terms($postid, 'category');			
			foreach($terms as $term) {				
				$testargs['cat'] = $term->term_id;
				if($count==0) $args['cat'] = $term->term_id;				
				query_posts ( $testargs );
				if(have_posts()) {	
					$count++;
				
					$filters .= '<a data-sorter="'.$term->term_id.'" data-method="categories" class="info';
					if($count==1) $filters .= ' active';
					$filters .= '" title="' . __('More articles filed under: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';
						
					if($count == $numfilters) break;					
				}
			}
		break;	
		case 'primary_taxonomy':
			$primary_taxonomy = $minisite->get_primary_taxonomy();
			$terms = wp_get_post_terms($postid, $primary_taxonomy->slug);			
			foreach($terms as $term) {
				$tax_query = array(array('taxonomy' => $primary_taxonomy->slug, 'field' => 'id', 'terms' => $term->term_id));
				$testargs['tax_query'] = $tax_query;
				if($count==0) $args['tax_query'] = $tax_query;	
				query_posts ( $testargs );
				if(have_posts()) {
					$count++;
					
					$filters .= '<a data-sorter="'.$term->term_id.'" data-taxonomy="'.$primary_taxonomy->slug.'" data-method="taxonomies" class="info';
					if($count==1) $filters .= ' active';
					$filters .= '" title="' . __('More articles from: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';				
					
					if($count == $numfilters) break;
				}
			}		
		break;	
		case 'taxonomies':
			$taxonomies = $minisite->taxonomies;			
			foreach($taxonomies as $taxonomy){
				$terms = wp_get_post_terms($postid, $taxonomy[0]->slug);
				foreach($terms as $term) {					
					$tax_query = array(array('taxonomy' => $taxonomy[0]->slug, 'field' => 'id', 'terms' => $term->term_id));
					$testargs['tax_query'] = $tax_query;					
					if($count==0) $args['tax_query'] = $tax_query;	
					query_posts ( $testargs );
					if(have_posts()) {					
						$count++;
					
						$filters .= '<a data-sorter="'.$term->term_id.'" data-taxonomy="'.$taxonomy[0]->slug.'" data-method="taxonomies" class="info';
						if($count==1) $filters .= ' active';
						$filters .= '" title="' . __('More articles from: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';
										
						break;						
					}
				}
				if($count == $numfilters) break;
			}
		break;
		case 'mixed':
			#tag			
			$terms = wp_get_post_terms($postid, 'post_tag');			
			foreach($terms as $term) {				
				$testargs['tag_id'] = $term->term_id;
				if($count==0) $args['tag_id'] = $term->term_id;	
				query_posts ( $testargs );
				if(have_posts()) {	
					$count++;
					
					$filters .= '<a data-sorter="'.$term->term_id.'" data-method="tags" class="info';
					if($count==1) $filters .= ' active';
					$filters .= '" title="' . __('More articles tagged: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';
						
					break;
				}
			}
			if($count == $numfilters) break;
			#category
			$testargs = $args;
			$terms = wp_get_post_terms($postid, 'category');			
			foreach($terms as $term) {				
				$testargs['cat'] = $term->term_id;
				if($count==0) $args['cat'] = $term->term_id;				
				query_posts ( $testargs );
				if(have_posts()) {	
					$count++;
				
					$filters .= '<a data-sorter="'.$term->term_id.'" data-method="categories" class="info';
					if($count==1) $filters .= ' active';
					$filters .= '" title="' . __('More articles filed under: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';
						
					break;					
				}
			}
			if($count == $numfilters) break;
			#taxonomies
			$testargs = $args;
			$taxonomies = $minisite->taxonomies;			
			foreach($taxonomies as $taxonomy){
				if(is_array($taxonomy)) {
					if(array_key_exists(0, $taxonomy)) {
						$terms = wp_get_post_terms($postid, $taxonomy[0]->slug);
						foreach($terms as $term) {					
							$tax_query = array(array('taxonomy' => $taxonomy[0]->slug, 'field' => 'id', 'terms' => $term->term_id));
							$testargs['tax_query'] = $tax_query;					
							if($count==0) $args['tax_query'] = $tax_query;	
							query_posts ( $testargs );
							if(have_posts()) {	
								$count++;
												
								$filters .= '<a data-sorter="'.$term->term_id.'" data-taxonomy="'.$taxonomy[0]->slug.'" data-method="taxonomies" class="info';
								if($count==1) $filters .= ' active';
								$filters .= '" title="' . __('More articles from: ', IT_TEXTDOMAIN) . $term->name . '"><span class="left-side"></span>'.$term->name.'<span class="right-side"></span></a>';				
								
								break;						
							}
						}
					}
				}
			}
			if($count == $numfilters) break;		
		break;
	}	
	if($count>0) {
		$out .= '<div id="recommended">';
			$out .= '<div class="filterbar">';
				$out .= '<div class="sort-buttons" data-loop="'.$loop.'" data-location="'.$location.'" data-thumbnail="'.$thumbnail.'" data-rating="'.$rating.'" data-numarticles="'.$numarticles.'" data-icon="'.$icon.'" data-targeted="'.$targeted.'" data-container="'.$container.'" data-columns="'.$cols.'">';
					$out .= '<div class="recommended-label"><span class="icon-thumbs-up"></span>'.$label.'</div>';
					if(!it_component_disabled('recommended_filters', $postid)) $out .= $filters;
				$out .= '</div>';
				$out .= '<br class="clearer" />';
			$out .= '</div>';
			
			$out .= '<div class="loading"><div>&nbsp;</div></div>';	
			
			$out .= '<div class="post-list-wrapper">';
				$out .= '<div class="post-list">';	
				
					#display the loop
					$loop = it_loop($args, $format); 
					$out .= $loop['content'];
				
				$out .= '</div>';
			$out .= '<br class="clearer" /></div>';
		$out .= '</div>';
	}
	wp_reset_query();
	return $out;
}

function it_comment($comment, $args, $depth) {
	global $post;	
	$GLOBALS['comment'] = $comment; ?>		
	
	<li id="li-comment-<?php comment_ID(); ?>">
		<div class="comment" id="comment-<?php comment_ID(); ?>">
			<div class="comment-avatar-wrapper">
            	<div class="comment-avatar">
					<?php echo get_avatar($comment, 50); ?>
				</div>
            </div>
			<div class="comment-content">
            
				<div class="comment-author"><?php printf(__('%s',IT_TEXTDOMAIN), get_comment_author_link()) ?></div>
                
				<a class="comment-meta" href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s',IT_TEXTDOMAIN), get_comment_date(),  get_comment_time()) ?></a>
				<?php $editlink = get_edit_comment_link();
				if(!empty($editlink)) { ?>
                	<a href="<?php echo $editlink; ?>"><span class="icon-pencil"></span></a>
                <?php } ?>
                
				<br class="clearer" />
                
                <?php if ($comment->comment_approved == '0') : ?>
                
                    <div class="comment-moderation">
                        <?php _e('Your comment is awaiting moderation.',IT_TEXTDOMAIN) ?>                               
                    </div>
                    
                <?php endif; ?>  
                
                <?php echo it_get_comment_rating($post->ID, $comment->comment_ID); ?>
                
                <?php if(strpos(get_comment_text(),'it_hide_this_comment')===false) { ?>
                
                    <div class="comment-text">
                    
                        <?php comment_text() ?>
                        
                    </div>    
                    
                <?php } ?>            
                    
				<?php echo get_comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>                    
                
			</div>
			<br class="clearer" />
       	</div>
	
<?php } 

#add container to comment form fields
function it_before_comment_fields() {
	$out = '';
	$width = '';
	global $post;					
	$minisite = it_get_minisite($post->ID);
	$disable_review = get_post_meta( $post->ID, IT_META_DISABLE_REVIEW, $single = true );
	if($minisite && !$minisite->user_rating_disable && $disable_review!='true') {
		if(!$minisite->user_comment_rating_disable) {
			$out .= '<div class="comment-ratings-container">';
			$out .= '<div class="comment-ratings-inner ratings">';
				$out .= it_get_comment_criteria($post->ID);					
			$out .= '</div>';
			$out .= '</div>';
		}			
	}
	if(!$minisite || $minisite->user_rating_disable || $disable_review=='true') $width=' full';
	$out .= '<div class="comment-fields-container'.$width.'">';
	$out .= '<div class="comment-fields-inner">';
	echo $out;	
}
function it_after_comment_fields() {
	$out = '';
	$out .= '</div>';
	$out .= '</div><br class="clearer" />';	
	echo $out;	
}
#html display of author's profile fields
function it_author_profile_fields($authorid) {
	$out='<div class="author-profile-fields">';
	if(get_the_author_meta('twitter', $authorid))
		$out.='<a class="icon-twitter info" title="'. __( 'Find me on Twitter', IT_TEXTDOMAIN ) .'" href="http://twitter.com/'. get_the_author_meta('twitter', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('facebook', $authorid))
		$out.='<a class="icon-facebook info" title="'. __( 'Find me on Facebook', IT_TEXTDOMAIN ) .'" href="http://www.facebook.com/'. get_the_author_meta('facebook', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('googleplus', $authorid))
		$out.='<a class="icon-googleplus info" title="'. __( 'Find me on Google+', IT_TEXTDOMAIN ) .'" href="http://plus.google.com/'. get_the_author_meta('googleplus', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('linkedin', $authorid))
		$out.='<a class="icon-linkedin info" title="'. __( 'Find me on LinkedIn', IT_TEXTDOMAIN ) .'" href="http://www.linkedin.com/in/'. get_the_author_meta('linkedin', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('pinterest', $authorid))
		$out.='<a class="icon-pinterest info" title="'. __( 'Find me on Pinterest', IT_TEXTDOMAIN ) .'" href="http://www.pinterest.com/'. get_the_author_meta('pinterest', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('flickr', $authorid))
		$out.='<a class="icon-flickr info" title="'. __( 'Find me on Flickr', IT_TEXTDOMAIN ) .'" href="http://www.flickr.com/photos/'. get_the_author_meta('flickr', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('youtube', $authorid))
		$out.='<a class="icon-youtube info" title="'. __( 'Find me on YouTube', IT_TEXTDOMAIN ) .'" href="http://www.youtube.com/user/'. get_the_author_meta('youtube', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('instagram', $authorid))
		$out.='<a class="icon-instagram info" title="'. __( 'Find me on Instagram', IT_TEXTDOMAIN ) .'" href="http://instagram.com/'. get_the_author_meta('instagram', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('vimeo', $authorid))
		$out.='<a class="icon-vimeo info" title="'. __( 'Find me on Vimeo', IT_TEXTDOMAIN ) .'" href="http://www.vimeo.com/'. get_the_author_meta('vimeo', $authorid) .'" target="_blank"></a>';	
	if(get_the_author_meta('stumbleupon', $authorid))
		$out.='<a class="icon-stumbleupon info" title="'. __( 'Find me on StumbleUpon', IT_TEXTDOMAIN ) .'" href="http://www.stumbleupon.com/stumbler/'. get_the_author_meta('stumbleupon', $authorid) .'" target="_blank"></a>';
	if(get_the_author_meta('user_email', $authorid))
		$out.='<a class="icon-email info" title="'. __( 'E-mail Me', IT_TEXTDOMAIN ) .'" href="mailto:'. get_the_author_meta('user_email', $authorid) .'"></a>';
	if(get_the_author_meta('user_url', $authorid))
		$out.='<a class="icon-globe info" title="'. __( 'My Website', IT_TEXTDOMAIN ) .'" href="'. get_the_author_meta('user_url', $authorid) .'" target="_blank"></a>';	
	$out.='</div>';
    return $out;	
}
#get footer layout
function it_footer_layout($l){
	$col1 = '';
	$col2 = '';
	$col3 = '';
	$col4 = '';
	$col5 = '';
	$col6 = '';
	switch($l) {
		case 'a':
			$col1=12;
		break;
		case 'b':
			$col1=6;
			$col2=6;
		break;
		case 'c':
			$col1=4;
			$col2=4;
			$col3=4;
		break;	
		case 'd':
			$col1=3;
			$col2=3;
			$col3=3;
			$col4=3;
		break;
		case 'e':
			$col1=2;
			$col2=2;
			$col3=2;
			$col4=2;
			$col5=2;
			$col6=2;
		break;
		case 'f':
			$col1=10;
			$col2=2;
		break;
		case 'g':
			$col1=9;
			$col2=3;
		break;
		case 'h':
			$col1=8;
			$col2=4;
		break;
		case 'i':
			$col1=6;
			$col2=3;
			$col3=3;
		break;
		case 'j':
			$col1=2;
			$col2=10;
		break;
		case 'k':
			$col1=3;
			$col2=9;
		break;
		case 'l':
			$col1=4;
			$col2=8;
		break;
		case 'm':
			$col1=3;
			$col2=3;
			$col3=6;
		break;
		case 'n':
			$col1=4;
			$col2=2;
			$col3=2;
			$col4=2;
			$col5=2;
		break;
		case 'o':
			$col1=4;
			$col2=4;
			$col3=2;
			$col4=2;
		break;
		case 'p':
			$col1=3;
			$col2=3;
			$col3=2;
			$col4=2;
			$col5=2;
		break;
		case 'q':
			$col1=2;
			$col2=2;
			$col3=2;
			$col4=2;
			$col5=4;
		break;
		case 'r':
			$col1=2;
			$col2=2;
			$col3=4;
			$col4=4;
		break;
		case 's':
			$col1=2;
			$col2=2;
			$col3=2;
			$col4=3;
			$col5=3;
		break;
		case 't':
			$col1=4;
			$col2=3;
			$col3=3;
			$col4=2;
		break;
		case 'u':
			$col1=4;
			$col2=2;
			$col3=3;
			$col4=3;
		break;
		case 'v':
			$col1=4;
			$col2=3;
			$col3=2;
			$col4=3;
		break;
		case 'w':
			$col1=2;
			$col2=3;
			$col3=3;
			$col4=4;
		break;
		case 'x':
			$col1=3;
			$col2=3;
			$col3=2;
			$col4=4;
		break;
		case 'y':
			$col1=3;
			$col2=2;
			$col3=3;
			$col4=4;
		break;
		case 'z':
			$col1=4;
			$col2=2;
			$col3=4;
			$col4=2;
		break;
		case 'aa':
			$col1=4;
			$col2=2;
			$col3=2;
			$col4=4;
		break;
		case 'ab':
			$col1=2;
			$col2=4;
			$col3=2;
			$col4=4;
		break;
		case 'ac':
			$col1=3;
			$col2=2;
			$col3=3;
			$col4=2;
			$col5=2;
		break;
		case 'ad':
			$col1=3;
			$col2=2;
			$col3=2;
			$col4=2;
			$col5=3;
		break;
	}
	$layout = array('col1' => $col1, 'col2' => $col2, 'col3' => $col3, 'col4' => $col4, 'col5' => $col5, 'col6' => $col6);
	return $layout;
}
#woocommerce actions
function it_wrapper_start() { 	
	$layout = it_get_setting('woo_layout');
	$sidebar = __('Page Sidebar',IT_TEXTDOMAIN);
	if(it_get_setting('woo_sidebar_unique')) $sidebar = __('WooCommerce Sidebar',IT_TEXTDOMAIN);
	?>

    <div class="row<?php echo $solo; ?> single-page<?php echo $minisitecss; ?> post-loop">

        <div class="span12">
        
            <div class="sortbar">
            
                <div class="row">
                
                    <div class="span12">
                    
                    	<?php $args = array('delimiter' => ' &raquo; '); ?>
                    
                       <div class="sortbar-title"><?php if(function_exists('woocommerce_breadcrumb')) woocommerce_breadcrumb($args); ?></div>
                       
                       <?php if(!it_get_setting('woo_signup_disable')) it_signup_form(); ?>
                    
                    </div>
                
                </div>
            
            </div>
            
            <div class="content-wrapper">
            
                <div class="row">
                
                    <div class="span12"> 
                    
						<?php if($layout=='sidebar-left') { ?>
                        
                            <?php it_sidebar($sidebar, $layout); ?>
                        
                        <?php } ?>
                    
                        <div class="main-loop <?php echo $layout; ?> <?php echo $view; ?>" data-location="single-page">
                        
                            <div class="main-loop-content">
                            
                            	<div class="main-content">
                    
<?php }

function it_wrapper_end() {	
	$layout = it_get_setting('woo_layout');
	$sidebar = __('Page Sidebar',IT_TEXTDOMAIN);
	if(it_get_setting('woo_sidebar_unique')) $sidebar = __('WooCommerce Sidebar',IT_TEXTDOMAIN);	
	
	?>
    
    							</div>
    
                            </div>   
                            
                        </div>
                        
                        <?php if($layout=='sidebar-right') { ?>
                        
                            <?php it_sidebar($sidebar, $layout); ?>
                        
                        <?php } ?>

                    </div>
                            
                </div>
                
            </div>
            
            <div class="pagination pagination-normal">
            
                <div class="row">
                
                    <div class="span12 pagination-inner">               
                        
                    	<?php if(function_exists('woocommerce_pagination')) woocommerce_pagination(); ?>
                    
                    </div>
                
                </div>
            
            </div>
        
        </div>
        
    </div>
    
<?php }
?>