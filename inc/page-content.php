<?php #setup minisites
global $itMinisites;

#default settings
$sidebar = __('Page Sidebar',IT_TEXTDOMAIN);
$thumbnail = it_get_setting('featured_image_size');
$layout = it_get_setting('layout');
$disable_view_count = it_component_disabled('sortbar_views', $post->ID);
$disable_like_count = it_component_disabled('sortbar_likes', $post->ID);
$disable_comment_count = it_component_disabled('sortbar_comments', $post->ID);
$disable_signup = it_component_disabled('signup', $post->ID);
$disable_date = it_component_disabled('date', $post->ID);
$disable_comments = it_component_disabled('comments', $post->ID);
$template = it_get_template_file();
$disable_postinfo = false;
$disable_recommended = false;
$disable_author = false;
$view = '';
$full = '';
$reviewcss = '';
$item_type = 'http://schema.org/Article';
$item_reviewed = '';

#some setup logic for variables
if(!is_single()) $disable_date=true; #only show date/author on posts

#section-specific settings
if(is_404()) {
	#settings for 404 pages
	$title = __('404 Error - Page Not Found', IT_TEXTDOMAIN);
	$content_title = __('We could not find the page you were looking for. Try searching for it:', IT_TEXTDOMAIN);
	if(it_get_setting('404_sidebar_unique')) $sidebar = __('404 Sidebar',IT_TEXTDOMAIN);
	$layout_specific = it_get_setting('404_layout');
	$disable_view_count = true;
	$disable_like_count = true;
	$disable_comment_count = true;
	$disable_postinfo = true;
	$disable_recommended = true;
	$disable_comments = true;
} elseif(is_page()) {
	#settings for all standard WordPress pages
	$layout_specific = it_get_setting('page_layout');
	$thumbnail_specific = it_get_setting('page_featured_image_size');
	$title = get_post_meta($post->ID, "_subtitle", $single = true);
	$disable_postinfo = true;
	$disable_recommended = true;
	$page_comments = it_get_setting('page_comments');
	if(!$page_comments) $disable_comments = true;
} elseif(is_single()) {
	#settings for single posts
	$layout_specific = it_get_setting('post_layout');
	$thumbnail_specific = it_get_setting('post_featured_image_size');
	$disable_signup = true;
}
#settings for buddypress pages
if(function_exists('bp_current_component') && bp_current_component()) {
	$disable_postinfo = true;	
	$disable_like_count = true;
	$disable_comment_count = true;
	$disable_author = true;
}
#settings for personal woocommerce pages
if(it_woocommerce_personal()) {
	$disable_postinfo = true;	
	$disable_like_count = true;
	$disable_comment_count = true;
	$disable_view_count = true;
	$disable_author = true;
	$layout = it_get_setting('woo_layout');
	if(it_get_setting('woo_sidebar_unique')) $sidebar = __('WooCommerce Sidebar',IT_TEXTDOMAIN);	
}
#settings for general woocommerce pages
if((function_exists('is_woocommerce') && is_woocommerce())) {
	$disable_comment_count = true;
	$disable_postinfo = true;
	$disable_author = true;
	$layout = it_get_setting('woo_layout');
	if(it_get_setting('woo_sidebar_unique')) $sidebar = __('WooCommerce Sidebar',IT_TEXTDOMAIN);	
}
#specific template files
switch($template) {
	case 'template-authors.php':
		$disable_view_count = true;
		$disable_like_count = true;
		$disable_comment_count = true;
		$disable_date = true;
		$disable_postinfo = true;
		$disable_recommended = true;
		$disable_comments = true;
		$disable_author = true;
		$avatar_size = 80;
		$view = 'list';		
		$display_admins = it_get_setting('author_admin_enable');
		$hide_empty = it_get_setting('author_empty_disable');
		$manual_exclude = it_get_setting('author_exclude');		
		$order_by = it_get_setting('author_order');
		$role = it_get_setting('author_role');	
		if($role=='all') $role='';
	break;	
}

$disable_review = get_post_meta( $post->ID, IT_META_DISABLE_REVIEW, $single = true );
if($disable_review=='true') $reviewcss = ' non-review';

#minisite sidebar
$post_type = get_post_type();
$minisite = $itMinisites->get_type_by_id($post_type);
$editor_rating_disable = $minisite->editor_rating_disable;
if($minisite) {	
	$unique_sidebar = $minisite->unique_sidebar;
	if($unique_sidebar) {
		$sidebar = $minisite->name . __(' Sidebar',IT_TEXTDOMAIN);
	}
}

#don't use specific settings if they are not set
if(!empty($layout_specific) && $layout_specific!='') $layout = $layout_specific;
if(!empty($thumbnail_specific) && $thumbnail_specific!='') $thumbnail = $thumbnail_specific;

#page-specific settings
$layout_meta = get_post_meta($post->ID, "_layout", $single = true);
if(!empty($layout_meta) && $layout_meta!='') $layout = $layout_meta;
$thumbnail_meta = get_post_meta($post->ID, "_featured_image_size", $single = true);
if(!empty($thumbnail_meta) && $thumbnail_meta!='') $thumbnail = $thumbnail_meta;
$sidebar_meta = get_post_meta($post->ID, "_custom_sidebar", $single = true);
if(!empty($sidebar_meta) && $sidebar_meta!='') $sidebar = $sidebar_meta;
$disable_title = get_post_meta($post->ID, IT_META_DISABLE_TITLE, $single = true);

if($minisite && $disable_review!='true' && !$editor_rating_disable) {
	$item_type = 'http://schema.org/Review';
	$item_reviewed = ' itemprop="itemReviewed"';
}

#global settings (override all other settings)
if(it_get_setting('featured_image_size_global')) $thumbnail = it_get_setting('featured_image_size');
if(it_get_setting('layout_global')) $layout = it_get_setting('layout');
if(it_get_setting('comments_disable_global')) $disable_comments = it_get_setting('comments_disable_global');

#minisite thumbnail
if($minisite && $disable_review!='true') {	
	if($thumbnail=='790') {
		$full = ' full';
	} else {
		if($thumbnail=='180' || $thumbnail=='360') $thumbnail = '790';	
	}		
	$minisitecss = ' minisite-page';		
}

#full width layout needs large full width featured image
if($layout == 'full' && $thumbnail == '790') $thumbnail = '1095';

#determine if solo css class needs to be applied
$solo = '';
#only applies if top ten is hidden
if(it_component_disabled('topten', $post->ID)) {
	#featured is disabled for this page or the front page of the current minisite
	if(it_component_disabled('featured', $post->ID)) $solo = ' solo';
	#this is a singl epost and featured is disabled for single posts 
	if (is_single() && it_get_setting('post_featured_disable')) {
		$solo = ' solo';
	#this is a single page and featured is disabled for single pages
	} elseif (is_page() && it_get_setting('page_featured_sidable')) {
		$solo = ' solo';
	}
}

?>

<?php do_action('it_before_content_page'); ?>

<div class="row<?php echo $solo; ?> single-page<?php echo $minisitecss; ?> post-loop">

    <div class="span12">
    
    	<div class="sortbar">
        
        	<div class="row">
            
            	<div class="span12">
                
                	<?php if($minisite && !is_404()) { ?>
                    
                    	<?php if(!it_component_disabled('sortbar_label', $post->ID)) { ?>
                    
                    		<?php echo it_get_category($minisite, false, true, true); ?>
                            
                        <?php } ?>
                        
                        <?php if(!it_component_disabled('sortbar_awards', $post->ID)) { ?>
                        
                        	<?php echo '<span class="award-grid">' . it_get_awards($post->ID, $minisite, 'post-loop', false) . '</span>'; ?>
                            
                        <?php } ?>
                    
                    <?php } else { ?>
                    
						<?php if(!it_component_disabled('loop_title', $post->ID) && !empty($title) && $title!='') { ?>
                        
                            <div class="sortbar-title"><?php echo $title; ?></div>
                            
                        <?php } ?>
                        
                    <?php } ?>
                    
                    <?php if(!$disable_view_count) echo it_get_views($post->ID, true); ?>
                    
                    <?php if(!$disable_like_count) echo it_get_likes($post->ID, true, true); ?>
                    
                    <?php if(!$disable_comment_count) echo it_get_comments($post->ID, true, true, true); ?>
                    
                    <?php if(!$disable_signup) it_signup_form(); ?>
                    
                    <div class="sortbar-right<?php if($disable_date) { ?> hidden<?php } ?>">
                        
                        <div class="meta-data">
                        
                            <span class="icon-recent"></span>
                            
                            <span class="date">                                
                                <?php _e('Posted',IT_TEXTDOMAIN); ?> 
                                <?php echo get_the_date(); ?>
                            </span>
                            
                        </div>
                    
                    </div>
                
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
                        
							<?php if (is_404()) : ?>
                            
                                <div class="main-content">
                            
                                    <h1><?php echo $content_title; ?></h1>
                                
                                    <form method="get" class="form-search" action="<?php echo home_url(); ?>/"> 
                                        <div class="input-append">
                                            <input class="span6 search-query" name="s" id="s" type="text" placeholder="<?php _e('keyword(s)',IT_TEXTDOMAIN); ?>">
                                            <button class="btn icon-search" type="button"></button>
                                        </div>        
                                    </form>  
                                    
                                </div>                          
                        
                            <?php elseif($template=='template-authors.php') : ?>
                            
                                <div class="template-authors">
                            
                                    <h1><?php the_title(); ?></h1>
                                    
                                    <div class="inner-content"><?php the_content(); ?></div>
                                    
                                    <?php #get authors and display loop							
                                    $authors = it_get_authors($display_admins, $order_by, $role, $hide_empty, $manual_exclude);								
                                    foreach($authors as $author) {
                                        $authorid = $author['ID'];
                                        ?>
                                        
                                        <div class="panel-wrapper">
                                        
                                            <div class="panel">
                                            
                                                <div class="article-image-wrapper">
                                            
                                                    <div class="article-image darken">
                                        
                                                        <a href="<?php echo get_author_posts_url($authorid); ?>"><?php echo get_avatar($authorid, $avatar_size); ?></a>
                                                        
                                                    </div>
                                                    
                                                    <?php echo it_author_profile_fields($authorid); ?>
                                                
                                                </div>
                                                
                                                <div class="article-excerpt-wrapper">
                                                
                                                    <div class="article-excerpt">
                                                    
                                                        <a class="articles-link" href="<?php echo get_author_posts_url($authorid); ?>"><?php _e('All articles from this author &raquo;',IT_TEXTDOMAIN); ?></a> 
                                        
                                                        <h2><a href="<?php echo get_author_posts_url($authorid); ?>" class="contributor-link"><?php echo the_author_meta('display_name', $authorid); ?></a></h2>
                                                        
                                                        <div class="excerpt">
                                                        
                                                            <p><?php echo the_author_meta('description', $authorid); ?></p>
                                                                              
                                                            <p class="articles-link"><?php echo it_author_latest_article($authorid); ?></p>
                                                        
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                </div>
                                                
                                                <br class="clearer" />
                                        
                                            </div>
                                            
                                        </div>
                                        
                                    <?php } ?>
                                
                                </div><br class="clearer" />
                            
                            <?php elseif (have_posts()) : ?>
                            
                                <?php do_action('it_before_main_content'); ?>
                            
                                <?php while (have_posts()) : the_post(); ?>
                                
                                    <?php #featured video
                                    $video = get_post_meta(get_the_ID(), "_featured_video", $single = true);
                                                                        
                                    if(!empty($video))
                                        $video = it_video( $args = array( 'url' => $video, 'video_controls' => it_get_setting('loop_video_controls'), 'parse' => true ) );
                                    ?>
                                
                                    <div class="main-content<?php echo $reviewcss; ?>" id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="<?php echo $item_type; ?>">
                                    
                                    	<meta itemprop="datePublished" content="<?php echo get_the_date(); ?>">
                            
                                        <?php do_action('it_before_the_title'); ?>
                                        
                                        <?php if(!$disable_title) { ?>
                                            <h1<?php echo $item_reviewed; ?>>								
                                                <?php 
                                                if(!empty($content_title)) {
                                                    echo $content_title;
                                                } else { 
                                                    the_title();
                                                } 
                                                ?>                                    
                                            </h1>
                                        <?php } ?>
                                        
                                        <?php if(!$disable_author) { ?>
                                            <span class="author">
                                                <?php _e('by',IT_TEXTDOMAIN); ?>
                                                <span itemprop="author"><?php echo get_the_author(); ?></span>
                                            </span>
                                        <?php } ?>
                                        
                                        <?php do_action('it_after_the_title'); ?>
                                        
                                        <?php if(($minisite && ($disable_review!='true' || ($thumbnail!='none' && has_post_thumbnail()))) || ($thumbnail!='none' && has_post_thumbnail())) { ?>
                                        
                                            <div class="featured-panel<?php if((!$minisite || $disable_review=='true') && $thumbnail!='790' && $thumbnail!='1095') { ?> floated<?php } ?>">
                                            
                                                <?php 
                                                if(!empty($video)) {
													echo '<div';
													if(!empty($video) && $thumbnail!='790' && $thumbnail!='1095') echo ' style="width:' . $thumbnail . 'px;"';
													echo '>' . $video . '</div>';
													$full = ' full';
                                                } elseif($thumbnail!='none' && has_post_thumbnail()) {
                                                    $featured_image = it_featured_image(get_the_ID(), 'single-'.$thumbnail, 818, 450, true, true); 
                                                } else {
                                                    $full = ' full';
                                                }
                                                ?>
                                                
                                                <?php if(($minisite && $disable_review=='false') || empty($video)) { ?>
                                                
                                                    <div class="inner-content<?php echo $full; ?>">
                                                    
                                                        <?php echo $featured_image; ?>
                                                        
                                                        <?php echo it_get_pros_cons(get_the_ID()); ?>
                                                        
                                                        <?php echo it_get_criteria(get_the_ID()); ?>
                                                        
                                                    </div>
                                                    
                                                    <?php echo it_get_bottom_line(get_the_ID()); ?>
                                                    
                                                <?php } ?>
                                            
                                            </div>
                                            
                                        <?php } ?>
                                            
                                        <div class="the-content">
                                        
                                            <?php do_action('it_before_the_content'); ?>  
                            
                                            <?php the_content(); ?>
                                            
                                            <br class="clearer" />
                                    
                                            <?php do_action('it_after_the_content'); ?>
                                                    
                                        </div> 
                                        
                                        <br class="clearer" />                                
                                        
                                    </div>
                                    
                                    <?php echo it_get_details(get_the_ID()); ?>
                                    
                                    <?php if(!$disable_postinfo) echo it_get_post_info(get_the_ID()); ?>
                                
                                <?php endwhile; ?>
                            
                                <?php if(!$disable_recommended) echo it_get_recommended(get_the_ID()); #don't put this in the loop because it contains a loop! ?>
                                
                                <?php if(!$disable_comments && comments_open()) comments_template(); // show comments ?>
                                                            
                                <?php do_action('it_after_main_content'); ?>
                            
                            <?php endif; ?> 
                            
                        </div>   
                        
                    </div>
                    
                    <?php if($layout=='sidebar-right') { ?>
                    
                        <?php it_sidebar($sidebar, $layout); ?>
                    
                    <?php } ?>
                    
                    <?php wp_reset_query(); ?>
                    
                </div>
                            
            </div>
            
        </div>
        
        <div class="pagination pagination-normal">
        
        	<div class="row">
            
            	<div class="span12 pagination-inner">                
                	
                
                </div>
            
            </div>
        
        </div>
    
    </div>
    
</div>

<?php wp_reset_query(); ?>

<?php do_action('it_after_content_page'); ?>