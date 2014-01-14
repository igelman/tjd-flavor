<?php global $itMinisites, $wp; 
#get the current query to pass it to the ajax functions through the html data tag
#don't want this if we're viewing a single post
$current_query = array();
if(!is_single()) $current_query = $wp->query_vars;

#set variables from theme options
$topten_timeperiod = it_get_setting('topten_timeperiod');
$timeperiod_label = it_timeperiod_label($topten_timeperiod);
if(empty($timeperiod_label)) $timeperiod_label = __('This Month', IT_TEXTDOMAIN);
$topten_label = it_get_setting("topten_label");
$topten_label = ( !empty( $topten_label ) ) ? $topten_label : __('TOP 10', IT_TEXTDOMAIN);

#get CSS layout class to apply based on layout
if(it_get_setting('topten_login_disable') || it_get_setting('topten_social_numbers_disable')) {
	$layout = ' medium-social';
} elseif(!it_get_setting('topten_social_disable')) {
	$layout = ' large-social';
} else {
	$layout = ' no-social';
}

#setup wp_query args
$args = array('posts_per_page' => 10, 'order' => 'DESC', 'orderby' => 'meta_value_num');
#setup loop format
$format = array('loop' => 'top ten', 'thumbnail' => false, 'rating' => false, 'icon' => false);

#get array of disabled filters
$disabled = ( is_array( it_get_setting("topten_disable_filter") ) ) ? it_get_setting("topten_disable_filter") : array();
#determine default options
$default_metric = 'viewed';
$args['meta_key'] = IT_META_TOTAL_VIEWS;
$default_label = __('Most Views&nbsp;', IT_TEXTDOMAIN);
$default_icon = '';
#loop through each metric and set to default until one is found
if(!in_array('viewed', $disabled)) {
	$default_metric = 'viewed';
	$args['meta_key'] = IT_META_TOTAL_VIEWS;
	$default_label = __('Most Views&nbsp;', IT_TEXTDOMAIN);
} elseif (!in_array('liked', $disabled)) {
	$default_metric = 'liked';
	$args['meta_key'] = IT_META_TOTAL_LIKES;
	$default_label = __('Most Likes&nbsp;', IT_TEXTDOMAIN);
} elseif (!in_array('reviewed', $disabled)) {
	$default_metric = 'reviewed';
	$format['rating'] = true;
	$args['meta_key'] = IT_META_TOTAL_SCORE_NORMALIZED;
	$args['meta_query'] = array(array( 'key' => IT_META_DISABLE_REVIEW, 'value' => 'false', 'compare' => '=' ));
	$default_label = __('Best Reviewed', IT_TEXTDOMAIN);
} elseif (!in_array('rated', $disabled)) {
	$default_metric = 'users';
	$format['rating'] = true;
	$default_icon = 'rated';
	$args['meta_key'] = IT_META_TOTAL_USER_SCORE_NORMALIZED;
	$args['meta_query'] = array(array( 'key' => IT_META_DISABLE_REVIEW, 'value' => 'false', 'compare' => '=' ));
	$default_label = __('Highest Rated', IT_TEXTDOMAIN);
} elseif (!in_array('commented', $disabled)) {
	$default_metric = 'commented';
	$args['orderby'] = 'comment_count';
	$default_label = __('Commented On', IT_TEXTDOMAIN);
}
$format['metric'] = $default_metric;
if(empty($default_icon)) $default_icon = $default_metric;

#determine if we are on a minisite page
$disable_featured = false;
$minisite = it_get_minisite($post->ID);
if($minisite) {	
	#add post type to query args
	if($minisite->topten_targeted) {
		$args['post_type'] = $minisite->id;	
		#also add to current query for ajax purposes
		$current_query['post_type'] = $minisite->id;
	}
	if(is_single() || is_archive()) $disable_featured = true;
}

$solo = '';
if(it_component_disabled('featured', $post->ID) || $disable_featured) $solo=' solo';

#encode current query for ajax purposes
$current_query_encoded = json_encode($current_query);

if(!it_component_disabled('topten', $post->ID)) { ?>

<div class="row">

    <div class="span12<?php echo $layout; ?><?php echo $solo; ?>" id="top-ten" data-currentquery='<?php echo $current_query_encoded; ?>'>

        <div id="selector-wrapper">
        
            <div class="inner">
    
                <div class="top-ten-label"><?php echo $topten_label; ?></div>
                
                <ul id="top-ten-selector">
                
                	<li>
                		
                        <div id="top-ten-selected">
                        
                            <div class="selector-icon"><span class="icon-<?php echo $default_icon; ?>"></span></div>
                            
                            <a class="selector-button"><?php echo $default_label; ?> <?php echo $timeperiod_label; ?></a>
                            
                        </div>
                        
                        <div class="selector-arrow icon-arrow-down"></div>                        
                        
                        <ul data-loop="top ten" data-timeperiod="<?php echo $topten_timeperiod; ?>">
                        
                        	<?php if(!in_array('viewed', $disabled)) { ?>
                                                    
                            <li>
                            
                            	<div class="selector-icon icon-viewed"></div>
                        
                                <a class="selector-button clickable" data-sorter="viewed" data-label="<?php _e('Most Views', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?>"><?php _e('Most Views', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?></a>
                            
                            </li> 
                            
                            <?php } ?> 
                            
                            <?php if(!in_array('liked', $disabled)) { ?>
                            
                            <li>
                            
                            	<div class="selector-icon icon-liked"></div>
                        
                                <a class="selector-button clickable" data-sorter="liked" data-label="<?php _e('Most Likes', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?>"><?php _e('Most Likes&nbsp;', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?></a>
                            
                            </li>
                            
                            <?php } ?> 
                            
                            <?php if(!in_array('reviewed', $disabled)) { ?>                          
                            
                            <li>
                            
                            	<div class="selector-icon icon-reviewed"></div>
                        
                                <a class="selector-button clickable" data-sorter="reviewed" data-label="<?php _e('Best Reviewed', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?>"><?php _e('Best Reviewed', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?></a>
                            
                            </li>
                            
                            <?php } ?> 
                            
                            <?php if(!in_array('rated', $disabled)) { ?>
                            
                            <li>
                            
                            	<div class="selector-icon icon-users"></div>
                        
                                <a class="selector-button clickable" data-sorter="users" data-label="<?php _e('Highest Rated', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?>"><?php _e('Highest Rated', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?></a>
                            
                            </li>
                            
                            <?php } ?> 
                            
                            <?php if(!in_array('commented', $disabled)) { ?>
                            
                            <li>
                            
                            	<div class="selector-icon icon-commented"></div>
                        
                                <a class="selector-button clickable" data-sorter="commented" data-label="<?php _e('Commented On', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?>"><?php _e('Commented On', IT_TEXTDOMAIN); ?> <?php echo $timeperiod_label; ?></a>
                            
                            </li>
                            
                            <?php } ?> 
                        
                        </ul>
                        
                    </li>                    
                
                </ul>
                
            </div>
            
            <div id="selector-wrapper-right">&nbsp;</div>
            
        </div>
        
        <?php if(!it_get_setting('topten_social_disable')) { ?>
        
            <div id="top-ten-social" class="<?php echo $layout; ?>">
            
            	<?php if(!it_get_setting('topten_login_disable')) { ?>
            
                    <div class="login-form">
                
                        <?php global $user_ID, $user_identity; get_currentuserinfo(); if (!$user_ID) { ?>
                        
                        	<a class="register-link" href="<?php echo home_url(); ?>/wp-login.php?action=register"><?php _e('register',IT_TEXTDOMAIN); ?><span class="icon-right"></span></a>  
                            
                            <form method="post" action="<?php echo home_url(); ?>/wp-login.php" class="wp-user-form">
                                <div class="input-prepend">
                                    <span class="add-on icon-username"></span>
                                    <input type="text" name="log" value="<?php echo esc_attr(stripslashes($user_login)); ?>" class="input-medium" id="user_login" tabindex="11" placeholder="Username" />
                                </div>
                                <br class="clearer" />
                                <div class="input-prepend">
                                    <span class="add-on icon-password"></span>
                                    <input type="password" name="pwd" value="" id="user_pass" class="input-medium" tabindex="12" placeholder="Password" />
                                </div>                                
                                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                                <input type="hidden" name="user-cookie" value="1" />                  
                            </form>
                    
                        <?php } else { ?> 
                            
                            <div class="welcome-text"><?php _e('Welcome,', IT_TEXTDOMAIN); ?>&nbsp;<?php echo $user_identity; ?></div>
                            
                            <div class="user-links">
                            
                                <a href="<?php echo admin_url( 'profile.php' ); ?>" class="btn btn-inverse" title="Account"><?php _e('Account', IT_TEXTDOMAIN); ?></a>
                            
                                <a href="<?php echo wp_logout_url( home_url() ); ?>" class="btn btn-inverse" title="Logout"><?php _e('Logout', IT_TEXTDOMAIN); ?></a>
                                
                            </div>
                        
                        <?php } ?>
                    
                    </div> 
                    
                <?php } ?>
                
                <?php if(!it_get_setting('topten_social_numbers_disable')) { ?>
                
                    <div class="social-numbers<?php if(it_get_setting('topten_login_disable')) { ?> solo<?php } ?>">
                    
                    	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(__('Top Ten Bar Social', IT_TEXTDOMAIN)) ) : else : ?>
                                                            
                            <?php _e('Top Ten Bar Social', IT_TEXTDOMAIN); ?>
                        
                        <?php endif; ?>
                    
                    </div> 
                    
                <?php } ?>                
                
            </div>
            
        <?php } ?>
        
        <div class="loading white<?php echo $layout; ?>"><div>&nbsp;</div></div>
        
        <div id="top-ten-slider" class="slide">
        
        	<?php
			$week = date('W');
			$month = date('n');
			$year = date('Y');
			switch($topten_timeperiod) {
				case 'This Week':
					$args['year'] = $year;
					$args['w'] = $week;
					$topten_timeperiod='';
				break;	
				case 'This Month':
					$args['monthnum'] = $month;
					$args['year'] = $year;
					$topten_timeperiod='';
				break;
				case 'This Year':
					$args['year'] = $year;
					$topten_timeperiod='';
				break;
				case 'all':
					$topten_timeperiod='';
				break;			
			}
			?> 
        
        	<?php $loop = it_loop($args, $format, $topten_timeperiod); echo $loop['content']; ?>
            
        </div> 
        
    </div>

</div>

<?php } ?>

<?php wp_reset_query(); ?>