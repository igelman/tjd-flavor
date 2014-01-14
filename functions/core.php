<?php 
#enqueue scripts
function it_enqueue_scripts() {
	wp_enqueue_script("jquery");
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-effects-core');
	#color picker for demo panel
	$show_demo = it_get_setting('show_demo_panel');
	if($show_demo) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script(
			'iris',
			admin_url( 'js/iris.min.js' ),
			array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
			false,
			1
		);
		wp_enqueue_script(
			'wp-color-picker',
			admin_url( 'js/color-picker.min.js' ),
			array( 'iris' ),
			false,
			1
		); 
		$colorpicker_l10n = array(
			'clear' => __( 'Clear', IT_TEXTDOMAIN ),
			'defaultString' => __( 'Default', IT_TEXTDOMAIN ),
			'pick' => __( 'Select Color', IT_TEXTDOMAIN )
		);
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n ); 
	}
}
#include minisites in the loop
#without this code, only "post" post types will display in the loop
function it_query_posts($query) {	
	#get minisite object
	global $itMinisites;
	$allowed = explode(',',it_get_setting('allowed_post_types'));
	#see if the current page is a BuddyPress page
	$is_buddypress = false;
	if(function_exists('bp_current_component') && bp_current_component()) $is_buddypress = true;
	#suppress filters argument can override this	
	if(empty($query->query_vars['suppress_filters']) && !is_page() && !is_preview() && !is_attachment() && !is_admin() && !is_search() && !$is_buddypress) {
		#get the current post type
		$post_type = get_query_var('post_type');		
		if(empty($post_type) || in_array($post_type, $allowed)) {	
			#if the post type is empty, create array of all minisites and include standard posts as well		
			if(empty($post_type)) {
				$post_type = array('post');
			#if post type is not empty, check and see if it's in the allowed list
			} else {
				$post_type = array('post', $post_type);	
			}			
			if(is_array($itMinisites->minisites)) {
				foreach($itMinisites->minisites as $minisite) {
					#add each minisite to the array
					if(!$minisite->excluded) array_push($post_type, $minisite->id);
				}
			}			
			$post_type = array_filter($post_type); #get rid of empty arrays
			$query->set('post_type',$post_type);
			#woocommerce uses the pre_get_posts filter as well and adjusts the query after
			#this function, so the product_cat gets added back in. This removes it for the
			#rest of the theme's loops so that sliders and widgets still display all posts
			$query->set('product_cat',''); 
			#var_export($query);
			return $query;					
		}				
	}
}

#adjust minisite article links
function filterslash_post_type_link( $post_link, $post ) { 
	$no_minisite_urls = it_get_setting('no_minisite_urls');
	if($no_minisite_urls) {
		$post_link = home_url("/$post->post_name/");		
	}
	return $post_link; 
}

#get fallback categories
function fallback_categories() {	
	echo "<ul>";
	$menu = wp_list_categories('title_li=&depth=0&echo=0');
	$menu = preg_replace('/title=\"(.*?)\"/','',$menu);
	echo $menu;
	echo "</ul>";
}

#get fallback footer menu
function fallback_footer_menu() {	
	echo "<ul>";
	$menu = wp_list_pages('title_li=&depth=1&echo=0');
	$menu = preg_replace('/title=\"(.*?)\"/','',$menu);
	echo $menu;
	echo "</ul>";
}
#if more than one page exists, return true
function show_posts_nav($total_comments) {    
	$page_comments = get_option('page_comments');
	$comments_per_page = get_option('comments_per_page');
	if ($page_comments && ($total_comments>$comments_per_page)) {
		return true;
	} else {
		return false;
	}
}
#get a category id from a category name
function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
}
#get a tag id from a tag name
function get_tag_id($tag_name){
	$term = get_term_by('name', $tag_name, 'post_tag');
	return $term->term_id;
}
#returns page ID from page slug
function get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}
#determine the topmost parent of a term
function get_term_top_most_parent($term_id, $taxonomy){
    #start from the current term
    $parent  = get_term_by( 'id', $term_id, $taxonomy);
	if($parent->parent) {
		#climb up the hierarchy until we reach a term with parent = '0'
		while ($parent->parent != '0'){
			$term_id = $parent->parent;	
			$parent  = get_term_by( 'id', $term_id, $taxonomy);
		}
	}
    return $parent;
}
#get template parts from inc/ folder
function it_get_template_part($template_part) {
	do_action( 'it_get_template_part' );
	if ( file_exists( get_template_directory() . '/inc/' . $template_part . '.php') )
		load_template( get_template_directory() . '/inc/' . $template_part . '.php');
}
#which template file is currently being displayed
function it_get_template_file() {
	global $wp_query;
	$page_id = $wp_query->get_queried_object_id();
	$template_file = get_post_meta( $page_id, '_wp_page_template', true );	
	return $template_file;
}
#determine if a component should be disabled for a specific part of the theme
function it_component_disabled($component, $postid = NULL) {
	$disable = false;
	$template = it_get_template_file();
	$minisite = it_get_minisite($postid);
	#the default setting
	if(it_get_setting($component . '_disable')) $disable = true;
	#archive-specific setting
	if(is_archive()) {
		if(it_get_setting('archive_' . $component . '_disable')) {
			$disable = true;
		} else {
			$disable = false;	
		}
	}
	#search-specific setting
	if(is_search()) {
		if(it_get_setting('search_' . $component . '_disable')) {
			$disable = true;
		} else {
			$disable = false;	
		}
	}
	#404-specific setting
	if(is_404()) {
		if(it_get_setting('404_' . $component . '_disable')) {
			$disable = true;
		} else {
			$disable = false;	
		}
	}
	#page-specific setting
	if(is_page() && !$minisite) {
		if(it_get_setting('page_' . $component . '_disable')) {
			$disable = true;
		} else {
			$disable = false;	
		}
	}
	#post-specific setting
	if(is_single()) {
		if(it_get_setting('post_' . $component . '_disable')) {
			$disable = true;
		} else {
			$disable = false;	
		}
	}
	#template-specific setting
	if($template=='template-directory.php') {
		if(it_get_setting('directory_' . $component . '_disable')) {
			$disable = true;
		} else {
			$disable = false;	
		}
	}
	#minisite-specific setting	
	if($minisite) {
		$component = $component . '_disable';
		if(property_exists('ITMinisite',$component)) $disable = $minisite->$component;	
	}
	#global setting overrides all other settings
	if(it_get_setting($component . '_disable_global')) $disable = true;
		
	return $disable;
}
#turn a name into a safe slug
function it_get_slug($slug, $name) {
	$safe_slug = ( !empty( $slug ) ) ? stripslashes(preg_replace('/[^a-z0-9_]/i', '', strtolower($slug))) : preg_replace('/[^a-z0-9_]/i', '', strtolower($name));
	return $safe_slug;	
}
#get the minisite object for any page or post
function it_get_minisite($postid, $access = false) {
	global $itMinisites;
	$minisite = it_get_minisite_by_meta($postid);
	if(empty($minisite) && (is_tax() || is_single() || $access)) {
		$post_type = get_post_type($postid); #get post type
		$minisite = $itMinisites->get_type_by_id($post_type); #get minisite object from post type		
	}
	return $minisite;
}
#get minisite object from meta field
function it_get_minisite_by_meta($postid) {
	global $itMinisites;
	$minisite = false;	
	$minisite_id = it_minisite_id($postid);
	if(!empty($minisite_id) && ($itMinisites->has_type($minisite_id, true))) {
		$minisite = $itMinisites->get_type_by_id($minisite_id);
	}
	return $minisite;
}
#get the mninisite name from the page custom meta field
function it_minisite_id($postid) {	
	$minisite_id = get_post_meta($postid, "_select_minisite", $single = true);		
	return $minisite_id;
}
#setup time period labels
function it_timeperiod_label($timeperiod) {
	switch($timeperiod) {
		case '-7 days':
			$timeperiod = __('Within Past Week',IT_TEXTDOMAIN);
		break;	
		case '-30 days':
			$timeperiod = __('Within Past Month',IT_TEXTDOMAIN);
		break;	
		case '-60 days':
			$timeperiod = __('Within Past 2 Months',IT_TEXTDOMAIN);
		break;	
		case '-90 days':
			$timeperiod = __('Within Past 3 Months',IT_TEXTDOMAIN);
		break;
		case '-180 days':
			$timeperiod = __('Within Past 6 Months',IT_TEXTDOMAIN);
		break;
		case '-365 days':
			$timeperiod = __('Within Past Year',IT_TEXTDOMAIN);
		break;	
		case 'all':
			$timeperiod = __('All Time',IT_TEXTDOMAIN);
		break;	
		default:
			$timeperiod = $timeperiod;
		break;		
	}
	return $timeperiod;
}
#get theme options
function it_get_setting( $option = '' ) {
	$settings = '';

	if ( !$option )
		return false;

	$settings = str_replace('\"', '"', get_option( IT_SETTINGS ));
	$settings = str_replace("\'", "'", $settings);
	
	if( !empty( $settings[$option] ) )
		return $settings[$option];
		
	return false;
}
#get object of authors 
function it_get_authors($display_admins, $order_by, $role, $hide_empty, $manual_exclude) {
	if(empty($order_by)) $order_by = 'display_name';
	$args = array('orderby' => $order_by, 'role' => $role);
	$exclude = array();
	#exclude subscribers
	$subscribers = get_users('role=subscriber');
	foreach($subscribers as $sub) {
		$exclude[] = $sub->ID;	
	}
	#exclude admins
	if(empty($display_admins)) {		
		$admins = get_users('role=administrator');		
		foreach($admins as $ad) {
			$exclude[] = $ad->ID;
		}				
	}
	#add manual excludes
	if(!empty($manual_exclude)) {		
		$manual_exclude = explode(',', $manual_exclude);
		foreach($manual_exclude as $username) {
			$user = get_userdatabylogin($username);	
			$exclude[] = $user->ID;
		}		
	}
	$args['exclude'] = $exclude;
	$users = get_users($args);
	$authors = array();
	foreach ($users as $user) {
		$thisuser = get_userdata($user->ID);
		if(!empty($hide_empty)) {
			$numposts = count_user_posts($thisuser->ID);
			if($numposts < 1) continue;
		}
		$authors[] = (array) $thisuser;
	}	
	return $authors;
}
#used by the where filter in the post loop
function filter_where( $where = '' ) {	
	global $timewhere;	
	$where .= " AND post_date > '" . date('Y-m-d', strtotime($timewhere)) . "'";
	return $where;
}	
#adds all post types to standard WordPress archives widget
function it_archives_where( $where , $r ) { 
	$args = array( 'public' => true , '_builtin' => false ); $output = 'names'; $operator = 'and';
	$post_types = get_post_types( $args , $output , $operator ); $post_types = array_merge( $post_types , array( 'post' ) ); $post_types = "'" . implode( "' , '" , $post_types ) . "'";
	return str_replace( "post_type = 'post'" , "post_type IN ( $post_types )" , $where );
}
#open comment author's links in new windows
function author_link_new_window() {
	$url = get_comment_author_url();
	$author = get_comment_author();
	if (empty( $url ) || 'http://' == $url)
		$return = $author;
	else
		$return = "<a href='$url' rel='external nofollow' class='url' target='_blank'>$author</a>";
	return $return;
}
#if comment is empty add specific text to use to target and hide it later
function it_hide_comment($postid){
	$minisite = it_get_minisite($postid, true);
	if($minisite->allow_blank_comments) {
		if($minisite && !($minisite->user_rating_disable && $minisite->user_comment_procon_disable && $minisite->user_comment_rating_disable)) {
			$val = rand(0, 384534);
			if(empty($_POST['comment'])) $_POST['comment'] = $val."_it_hide_this_comment";
		}
	}
}


#used for exporting/importing theme options
function it_encode( $content, $serialize = false ) {	
	if( $serialize )
		$encode = rtrim(strtr(base64_encode(gzdeflate(htmlspecialchars(serialize( $content )), 9)), '+/', '-_'), '=');
	else
		$encode = rtrim(strtr(base64_encode(gzdeflate(htmlspecialchars( $content ), 9)), '+/', '-_'), '=');		
	
	return $encode;
}
#used for exporting/importing theme options
function it_decode( $content, $unserialize = false ) {
	$decode = @gzinflate(base64_decode(strtr( $content, '-_', '+/')));
	
	if( !$unserialize )
		$decode = htmlspecialchars_decode( $decode );
	else
		$decode = unserialize(htmlspecialchars_decode( $decode ) );
	
	return $decode;
}
#determine if current call is AJAX
function it_ajax_request() {
	if( ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) && ( strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) )
		return true;
		
	return false;
}
#removes the auto paragraph tag added by WordPress
function it_remove_wpautop( $content ) { 
	$content = do_shortcode( shortcode_unautop( $content ) ); 
	$content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
	return $content;
}
#removes line breaks from column shortcodes
function it_format_text($content) {
	$new_content = '';
	/* Matches the contents and the open and closing tags */
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	/* Matches just the contents */
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	/* Divide content into pieces */
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
	/* Loop over pieces */
	foreach ($pieces as $piece) {
		/* Look for presence of the shortcode */
		if (preg_match($pattern_contents, $piece, $matches)) {
			/* Append to content (no formatting) */
			$new_content .= $matches[1];
		} else {
			/* Format and append to content */
			$new_content .= wptexturize(wpautop($piece));
		}
	}
	return $new_content;
}
#obfuscates email addresses
function it_nospam( $email, $filterLevel = 'normal' ) {
	$email = strrev( $email );
	$email = preg_replace( '[@]', '//', $email );
	$email = preg_replace( '[\.]', '/', $email );

	if( $filterLevel == 'low' ) 	{
		$email = strrev( $email );
	}
	
	return $email;
}
#get list of shortcodes
function it_shortcodes() {
	$shortcodes = array();
	if ( is_dir( THEME_SHORTCODES ) ) {
		if ( $dh = opendir( THEME_SHORTCODES ) ) {
			while ( false !== ( $file = readdir( $dh ) ) ) {
				if( $file != '.' && $file != '..' && stristr( $file, '.php' ) !== false )
					$shortcodes[] = $file;
			}
			
			closedir( $dh );
		}
	}
	asort( $shortcodes );
	return $shortcodes;
}
#initialize shortcodes
if ( !function_exists( 'it_shortcodes_init' ) ) :
	function it_shortcodes_init() {
		foreach( it_shortcodes() as $shortcodes )
			require_once THEME_SHORTCODES . '/' . $shortcodes;
			
		if( is_admin() )
			return;		
			
		# Long posts should require a higher limit, see http://core.trac.wordpress.org/ticket/8553
		//@ini_set('pcre.backtrack_limit', 9000000);
			
		foreach( it_shortcodes() as $shortcodes ) {
			$class = 'it' . ucfirst( preg_replace( '/[0-9-_]/', '', str_replace( '.php', '', $shortcodes ) ) );
			$class_methods = get_class_methods( $class );
	
			foreach( $class_methods as $shortcode )
				if( $shortcode[0] != '_' )
					add_shortcode( $shortcode, array( $class, $shortcode ) );
		}
	}
endif;
#used by twitter/facebook/rss functions
function feedReader($source, $method) {
	if (function_exists('curl_init')) {
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_URL, $source);
		$data = curl_exec($ch);
		curl_close($ch);
	} else {
		$data = file_get_contents($source);
        }
	if ($method == "xml") {
		$resource = new SimpleXMLElement($data, LIBXML_NOCDATA);
	}
	else if ($method == "json") {
		$resource = json_decode($data, true);
        }
	return $resource;
}
#debugging purposes to trace and print the redirects done by wordpress
function wpse12721_wp_redirect( $location ) {
    #get a backtrace of who called us
    debug_print_backtrace();
    #cancel the redirect
    return false;
}
#custom Login Logo
function it_custom_login_logo() {
	if (it_get_setting("login_logo_url")) {
		$out = '<style type="text/css">#login h1 a { background:url('.it_get_setting("login_logo_url").') no-repeat center center !important; background-size: auto auto !important;width:auto; }
    </style>';
    print $out;
	}
}
#polish up the title
function it_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	# add the site name.
	$title .= get_bloginfo( 'name' );

	# add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	# add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', IT_TEXTDOMAIN ), max( $paged, $page ) );

	return $title;
}
#custom menus
function it_custom_menus() {
	register_nav_menus(array( 'top-menu' => __( 'Top Menu',IT_TEXTDOMAIN ), 'main-menu' => __( 'Main Menu',IT_TEXTDOMAIN ), 'sub-menu' => __( 'Sub Menu',IT_TEXTDOMAIN )));
}
#adds css class for active minisite menu item
function current_type_nav_class($classes, $item){
	$post_type = str_replace('it_','',get_post_type());
	if((is_single() || is_tax()) && $item->attr_title == $post_type){
		$classes[] = "current_page_ancestor";
	}
	#var_dump($item);		
	return $classes;
}
#LEGACY - google rich snippet structured data for reviews
function it_structured_data() {
	global $post;
	$postid = $post->ID;
	$minisite = it_get_minisite($postid);
	if(!$minisite) return;
	$metric = $minisite->rating_metric;
	$disable_review = get_post_meta( $postid, IT_META_DISABLE_REVIEW, $single = true );
	if($disable_review=='true') return;
	$total_rating = get_post_meta( $postid, IT_META_TOTAL_SCORE, $single = true );
	$total_rating_normalized = get_post_meta( $postid, IT_META_TOTAL_SCORE_NORMALIZED, $single = true );	
	switch($metric) {
		case 'number':
			$best_rating = 10;
		break;
		case 'percentage':	
			$best_rating = 100;
		break;
		case 'letter':
			#use the normalized rating since letter grades don't play well with structured data display
			$best_rating = 100;
			$total_rating = $total_rating_normalized;
		break;
		case 'stars':
			$best_rating = 5;
		break;
	}	
	$out = '';
	$out .= '<div class="structured-data" itemscope itemtype="http://schema.org/Review">';
		$out .= '<img class="rich-snippet-photo" itemprop="image" src="'.it_structured_data_image().'" alt="'.get_the_title().'" />';
		$out .= '<span itemprop="itemReviewed">'.get_the_title().'</span>';
		$out .= '<meta itemprop="datePublished" content="'.get_the_date().'">';
		$out .= '<span itemprop="author">'.get_the_author().'</span>';
		$out .= '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';		
			$out .= '<meta itemprop="worstRating" content="0">';
			$out .= '<span itemprop="ratingValue">'.$total_rating.'</span>';
      		$out .= '<meta itemprop="bestRating" content="'.$best_rating.'">';	
		$out .= '</div>';
	$out .= '</div>';
	echo $out;
}
#get the best rating for rich snippet purposes
function it_get_best_rating($postid = NULL) {
	if(empty($postid)) {
		global $post;
		$postid = $post->ID;
	}
	$minisite = it_get_minisite($postid);
	$metric = $minisite->rating_metric;
	switch($metric) {
		case 'number':
			$best_rating = 10;
		break;
		case 'percentage':	
			$best_rating = 100;
		break;
		case 'letter':
			$best_rating = 100;
		break;
		case 'stars':
			$best_rating = 5;
		break;
	}	
	return $best_rating;
}
#structured data image
function it_structured_data_image() {
	global $post;
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'widget-post' ); 			
	$out = $image[0]; 
	return $out;
}
#facebook thumbnail image
function it_facebook_image() {
	global $post;
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'widget-post' ); 			
	$out = '<meta property="og:image" content="'.$image[0].'" />'; 
	echo $out;
}
#add editor stylesheets
function it_add_editor_styles() {
	add_editor_style('css/editor-style.css');
}
#check for personal woocommerce pages
function it_woocommerce_personal() {
	if((function_exists('is_cart') && is_cart()) || (function_exists('is_account_page') && is_account_page()) || (function_exists('is_checkout') && is_checkout()) || is_page('order-received')) {
		return true;
	} else {
		return false;
	}	
}
#display like button on pages
function it_page_like_button() {
	global $post;
	if(get_post_type($post->ID)=='page' && !it_woocommerce_personal())
		if(!it_component_disabled('likes', $post->ID)) echo it_get_likes($post->ID, true, true, true);	
}
#display post pagination
function it_post_pagination() {
	global $post;
	$args = array('echo' => 1);			
	wp_link_pages($args);	
}
#increase page/post view count
function it_user_view() {
	
	#don't count bots and crawlers as views
	if(it_is_bot()) return false;
	
	global $post;
	
	#get the user's ip address
	$ip=it_get_ip();
	
	#get meta info
	$ips = get_post_meta($post->ID, IT_META_VIEW_IP_LIST, $single = true);
	if(!metadata_exists('post', $post->ID, IT_META_VIEW_IP_LIST)) $addipmeta=true;
	
	$views = get_post_meta($post->ID, IT_META_TOTAL_VIEWS, $single = true);
	if(!metadata_exists('post', $post->ID, IT_META_TOTAL_VIEWS)) $addviewsmeta=true;
	
	$do_update=true;
	if(strpos($ips,$ip) !== false && !it_get_setting('unique_views_disable')) $do_update=false;
	
	#$do_update=true; #testing purposes only
	
	if($do_update) {
		$ip.=';'; #add delimiter	
		$ips.=$ip; #add ip to string
		$views+=1; #increase views	
	
		#figure out whether to add or update the ip address meta field
		if($addipmeta) {
			add_post_meta($post->ID, IT_META_VIEW_IP_LIST, $ips);
		} else {
			update_post_meta($post->ID, IT_META_VIEW_IP_LIST, $ips);
		}
		
		#figure out whether to add or update the total views meta field
		if($addviewsmeta) {
			add_post_meta($post->ID, IT_META_TOTAL_VIEWS, $views);
		} else {
			update_post_meta($post->ID, IT_META_TOTAL_VIEWS, $views);
		}	
	}
}
#check if user is bot/crawler
function it_is_bot(){ 
	$bot_list= array("Ask Jeeves","Baiduspider","Butterfly","FAST","Feedfetcher-Google","Firefly","Gigabot","Googlebot","InfoSeek","Me.dium","Mediapartners-Google","NationalDirectory","Rankivabot","Scooter","Slurp","Sogou web spider","Spade","TECNOSEEK","TechnoratiSnoop","Teoma","TweetmemeBot","Twiceler","Twitturls","URL_Spider_SQL","WebAlta Crawler","WebBug","WebFindBot","ZyBorg","alexa","appie","crawler","froogle","girafabot","inktomi","looksmart","msnbot","rabaz","www.galaxy.com");
	$user_agent= $_SERVER["HTTP_USER_AGENT"];	 
	 
	foreach($bot_list as $bot){	 
		if(strpos($user_agent,$bot)!== false){		 
			return true;		 
		} 	 
		return false;	 
	} 
}
#get the user's ip address
function it_get_ip() {
	if (empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ip_address = $_SERVER["REMOTE_ADDR"];
	} else {
		$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	if(strpos($ip_address, ',') !== false) {
		$ip_address = explode(',', $ip_address);
		$ip_address = $ip_address[0];
	}
	return esc_attr($ip_address);
}
#save author profile fields
function it_save_profile_fields($userID) {
	if (!current_user_can('edit_user', $userID)) return false;
	update_user_meta($userID, 'twitter', $_POST['twitter']);
	update_user_meta($userID, 'facebook', $_POST['facebook']);
	update_user_meta($userID, 'googleplus', $_POST['googleplus']);
	update_user_meta($userID, 'linkedin', $_POST['linkedin']);
	update_user_meta($userID, 'pinterest', $_POST['pinterest']);
	update_user_meta($userID, 'flickr', $_POST['flickr']);
	update_user_meta($userID, 'youtube', $_POST['youtube']);
	update_user_meta($userID, 'instagram', $_POST['instagram']);
	update_user_meta($userID, 'vimeo', $_POST['vimeo']);	
	update_user_meta($userID, 'stumbleupon', $_POST['stumbleupon']);
}
#display author profile fields
function it_user_profile_fields($user) {
?>
	<h3><?php _e( 'Social Profiles', IT_TEXTDOMAIN ); ?></h3>

	<table class='form-table'>
		<tr>
			<th><label for='twitter'><?php _e( 'Twitter', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='twitter' id='twitter' value='<?php echo esc_attr(get_the_author_meta('twitter', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your Twitter username.', IT_TEXTDOMAIN ); ?> http://www.twitter.com/<strong>username</strong></span>
			</td>
		</tr>
		<tr>
			<th><label for='facebook'><?php _e( 'Facebook', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='facebook' id='facebook' value='<?php echo esc_attr(get_the_author_meta('facebook', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your Facebook username/alias.', IT_TEXTDOMAIN ); ?> http://www.facebook.com/<strong>username</strong></span>
			</td>
		</tr>
        <tr>
			<th><label for='googleplus'><?php _e( 'Google Plus', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='googleplus' id='googleplus' value='<?php echo esc_attr(get_the_author_meta('googleplus', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your Google Plus account ID.', IT_TEXTDOMAIN ); ?> http://plus.google.com/<strong>account ID</strong></span>
			</td>
		</tr>
		<tr>
			<th><label for='linkedin'><?php _e( 'LinkedIn', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='linkedin' id='linkedin' value='<?php echo esc_attr(get_the_author_meta('linkedin', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your LinkedIn username.', IT_TEXTDOMAIN ); ?> http://www.linkedin.com/in/<strong>username</strong></span>
			</td>
		</tr>
        <tr>
			<th><label for='pinterest'><?php _e( 'Pinterest', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='pinterest' id='pinterest' value='<?php echo esc_attr(get_the_author_meta('pinterest', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your Pinterest username.', IT_TEXTDOMAIN ); ?> http://www.pinterest.com/<strong>username</strong>/</span>
			</td>
		</tr>        
		<tr>
			<th><label for='flickr'><?php _e( 'Flickr', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='flickr' id='flickr' value='<?php echo esc_attr(get_the_author_meta('flickr', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your Flickr username.', IT_TEXTDOMAIN ); ?> http://www.flickr.com/photos/<strong>username</strong>/</span>
			</td>
		</tr>
        <tr>
			<th><label for='youtube'><?php _e( 'YouTube', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='youtube' id='youtube' value='<?php echo esc_attr(get_the_author_meta('youtube', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your YouTube username.', IT_TEXTDOMAIN ); ?> http://www.youtube.com/user/<strong>username</strong>/</span>
			</td>
		</tr>
        <tr>
			<th><label for='instagram'><?php _e( 'Instagram', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='instagram' id='instagram' value='<?php echo esc_attr(get_the_author_meta('instagram', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your Instagram username.', IT_TEXTDOMAIN ); ?> http://instagram.com/<strong>username</strong></span>
			</td>
		</tr>
        <tr>
			<th><label for='vimeo'><?php _e( 'Vimeo', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='vimeo' id='vimeo' value='<?php echo esc_attr(get_the_author_meta('vimeo', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your Vimeo username.', IT_TEXTDOMAIN ); ?> http://www.vimeo.com/<strong>username</strong>/</span>
			</td>
		</tr>
        <tr>
			<th><label for='stumbleupon'><?php _e( 'StumbleUpon', IT_TEXTDOMAIN ); ?></label></th>
			<td>
				<input type='text' name='stumbleupon' id='stumbleupon' value='<?php echo esc_attr(get_the_author_meta('stumbleupon', $user->ID)); ?>' class='regular-text' />
				<br />
				<span class='description'><?php _e( 'Enter your StumbleUpon username.', IT_TEXTDOMAIN ); ?> http://www.stumbleupon.com/stumbler/<strong>username</strong>/</span>
			</td>
		</tr>
	</table>
<?php }
#get twitter couns with API 1.1 (new in Flavor 1.3)
function twitterCounts($username){
	// WordPress Transient API Caching
	$cacheKey = $username . '-cache';
	$cached = get_transient($cacheKey);
	if (false !== $cached)
		{return $cached;}
	
	// Call and instantiate tmhOAuth
	require 'tmhOAuth.php';
	require 'tmhUtilities.php';
	
	$tmhOAuth = new tmhOAuth(array(
	  'consumer_key'    => IT_TWITTER_CONSUMER_KEY,
	  'consumer_secret' => IT_TWITTER_CONSUMER_SECRET,
	  'user_token'      => IT_TWITTER_USER_TOKEN,
	  'user_secret'     => IT_TWITTER_USER_SECRET,
	));
	
	// Send the API request
	$json = json_decode($tmhOAuth->request(
		'GET', 
		$tmhOAuth->url('1.1/users/show.json'),
			array('screen_name' => $username )
			));
	
	// Extract the follower and tweet counts
	$followerCount = json_decode($tmhOAuth->response['response'])->followers_count;
	#$tweetCount = json_decode($tmhOAuth->response['response'])->statuses_count;
	
	#$output = $followerCount . " followers, " . $tweetCount . " tweets";
	$output = $followerCount;
	set_transient($cacheKey,$output,3600);
	return $output;
}
#add custom taxonomies and custom post types counts to dashboard
function it_add_counts_to_dashboard() {
    $showTaxonomies = 0;
    // Custom taxonomies counts
    if ($showTaxonomies) {
        $taxonomies = get_taxonomies( array( '_builtin' => false ), 'objects' );
        foreach ( $taxonomies as $taxonomy ) {
            $num_terms  = wp_count_terms( $taxonomy->name );
            $num = number_format_i18n( $num_terms );
            $text = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name, $num_terms );
            $associated_post_type = $taxonomy->object_type;
            if ( current_user_can( 'manage_categories' ) ) {
                $num = '<a href="edit-tags.php?taxonomy=' . $taxonomy->name . '&post_type=' . $associated_post_type[0] . '">' . $num . '</a>';
                $text = '<a href="edit-tags.php?taxonomy=' . $taxonomy->name . '&post_type=' . $associated_post_type[0] . '">' . $text . '</a>';
            }
            echo '<td class="first b b-' . $taxonomy->name . 's">' . $num . '</td>';
            echo '<td class="t ' . $taxonomy->name . 's">' . $text . '</td>';
            echo '</tr><tr>';
        }
    }
    // Custom post types counts
    $post_types = get_post_types( array( '_builtin' => false ), 'objects' );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, $num_posts->publish );
        if ( current_user_can( 'edit_posts' ) ) {
            $num = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . '</a>';
            $text = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
        }
        echo '<td class="first b b-' . $post_type->name . 's">' . $num . '</td>';
        echo '<td class="t ' . $post_type->name . 's">' . $text . '</td>';
        echo '</tr>';

        if ( $num_posts->pending > 0 ) {
            $num = number_format_i18n( $num_posts->pending );
            $text = _n( $post_type->labels->singular_name . ' pending', $post_type->labels->name . ' pending', $num_posts->pending );
            if ( current_user_can( 'edit_posts' ) ) {
                $num = '<a href="edit.php?post_status=pending&post_type=' . $post_type->name . '">' . $num . '</a>';
                $text = '<a href="edit.php?post_status=pending&post_type=' . $post_type->name . '">' . $text . '</a>';
            }
            echo '<td class="first b b-' . $post_type->name . 's">' . $num . '</td>';
            echo '<td class="t ' . $post_type->name . 's">' . $text . '</td>';
            echo '</tr>';
        }
    }
}
?>