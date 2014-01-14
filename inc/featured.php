<?php global $itMinisites; 

#set variables from theme options
$featured_layout=it_get_setting('featured_layout');
$featured_category=it_get_setting('featured_category');
$featured_tag=it_get_setting('featured_tag');
$category_disable=it_get_setting('featured_category_disable');
$award_disable=it_get_setting('featured_award_disable');
$rating_disable=it_get_setting('featured_rating_disable');
$title_disable=it_get_setting('featured_title_disable');
$video_disable=it_get_setting('featured_video_disable');
$sidebar1=__('Featured Column 1',IT_TEXTDOMAIN);
$sidebar2=__('Featured Column 2',IT_TEXTDOMAIN);

#setup query args
$featuredargs = array('posts_per_page' => it_get_setting('featured_number'));
if(!empty($featured_category)) {
	#add category to query args
	$featuredargs['cat'] = $featured_category;	
} else {	
	#add tag to query args
	$featuredargs['tag_id'] = $featured_tag;	
}

#get the current minisite
$disable = false;
$minisite = it_get_minisite($post->ID);
if($minisite) {
	#add post type to query args
	if($minisite->featured_targeted) $featuredargs['post_type'] = $minisite->id;
	#override general theme options with minisite-specific options
	$featured_layout = $minisite->featured_layout;
	$unique_sidebar = $minisite->featured_unique_sidebar;
	if($unique_sidebar) {
		$sidebar1 = $minisite->name . __(' Featured Column 1',IT_TEXTDOMAIN);
		$sidebar2 = $minisite->name . __(' Featured Column 2',IT_TEXTDOMAIN);
	}
	if(is_single() || is_archive()) $disable = true;
}

$solo = '';
if(it_component_disabled('topten', $post->ID)) $solo='solo';

# setup featured layout variables
switch ($featured_layout) {
	case 'small':
		$left_span=7;
		$right_span=5;
		$length=110;
		$image='featured-small';
		$width=430;
		$height=200;
		$video_x=105;
		$video_y=50;
		$category_x=15;
		$category_y=15;
		$rating_x=15;
		$rating_y=50;		
		$award_x=500;
		$award_y=15;
		$title_x=15;
		$title_y=270;
		
	break;
	case 'medium':
		$left_span=9;
		$right_span=3;
		$length=138;
		$image='featured-medium';
		$width=600;
		$height=250;
		$video_x=100;
		$video_y=50;
		$category_x=15;
		$category_y=15;
		$rating_x=15;
		$rating_y=50;		
		$award_x=650;
		$award_y=15;
		$title_x=15;
		$title_y=320;
	
	break;
	case 'large':
		$left_span=12;
		$right_span=0;
		$length=150;
		$image='featured-large';
		$width=840;
		$height=410;
		$video_x=130;
		$video_y=60;
		$category_x=15;
		$category_y=15;
		$rating_x=15;
		$rating_y=50;		
		$award_x=910;
		$award_y=15;
		$title_x=15;
		$title_y=500;
	
	break;	
	default:
		$left_span=7;
		$right_span=5;
		$length=110;
		$image='featured-small';
		$width=430;
		$height=200;
		$video_x=105;
		$video_y=50;
		$category_x=15;
		$category_y=15;
		$rating_x=15;
		$rating_y=50;		
		$award_x=500;
		$award_y=15;
		$title_x=15;
		$title_y=270;
		
	break;
}

if(!it_component_disabled('featured', $post->ID) && !$disable) { ?>

<div class="row">

	<div class="span12">
    
    	<div id="featured-bar-wrapper" class="<?php echo $solo; ?>">
        
        	<div id="featured-bar-shadow">

                <div class="row">
                
                    <div class="span<?php echo $left_span; ?>">
                        
                        <?php query_posts( $featuredargs );
                        if(have_posts()) { ?>
                        
                            <div id="featured-wrapper">
                            
                                <div id="featured" class="<?php echo $featured_layout; ?> featured">
                                
                                	<ul>
                                
										<?php # loop through featured slider panels
                                        $postcount=0;
                                        while (have_posts()) : the_post();  $postcount++;	
											$award_disable=false;	
											$category_disable = it_get_setting('featured_category_disable');
											$rating_disable = false;			
                                            $category = get_the_category();	
											if(is_array($category)) {
												if(array_key_exists(0, $category)) {
													if($category[0]) $category_name=$category[0]->cat_name;	
												}
											}
											#minisite variables			
											$post_type = get_post_type(); #get post type
											$minisite = $itMinisites->get_type_by_id($post_type); #get minisite object from post type
											if(!empty($minisite)) {
												#determine if rating should be shown
												$review_disable = get_post_meta( get_the_ID(), IT_META_DISABLE_REVIEW, $single = true );
												$editor_rating_disable = $minisite->editor_rating_disable;
												$user_rating_disable = $minisite->user_rating_disable;
												if($review_disable=='true' || ($editor_rating_disable && $user_rating_disable)) $rating_disable = true;
												$category_name = it_get_category($minisite, true, true, true);
												$award = it_get_awards(get_the_ID(), $minisite, 'featured', false);	
											} else {
												$rating_disable=true;
												$award_disable=true;	
											}	
											if(empty($category_name)) $category_disable=true;
											if(empty($award)) $award_disable=true;	
											
											#featured video
											$video_disable = false;
											$video = get_post_meta( get_the_ID(), '_featured_video', $single = true );											
											if( !empty( $video ) )
												$video = it_video( $args = array( 'url' => $video, 'video_controls' => it_get_setting('featured_video_controls'), 'parse' => true, 'width' => $width, 'height' => $height ) );												
											if(empty($video) || it_get_setting('featured_video_disable')) $video_disable = true;	
                                            ?>
                                            
                                            <li data-transition="<?php echo it_get_setting('featured_transition'); ?>" data-slotamount="7" data-link="<?php the_permalink(); ?>">
                                            
                                            	<?php echo it_featured_image(get_the_ID(), $image, $width, $height); ?>
                                                
                                                <?php if(!$video_disable) { ?>
                                                
                                                	<div class="caption <?php echo it_get_setting('featured_video_effect'); ?> video" style="z-index:100;" data-x="<?php echo $video_x; ?>" data-y="<?php echo $video_y; ?>" data-speed="<?php echo it_get_setting('featured_video_speed'); ?>00" data-start="<?php echo it_get_setting('featured_video_delay'); ?>00" data-easing="easeInOutExpo">
                                                         <?php echo $video; ?>
                                                    </div>
                                                
                                                <?php } ?> 
                                                
												<?php if(!$category_disable) { ?>
                                                
                                                    <div class="caption <?php echo it_get_setting('featured_category_effect'); ?> category" data-x="<?php echo $category_x; ?>" data-y="<?php echo $category_y; ?>" data-speed="<?php echo it_get_setting('featured_category_speed'); ?>00" data-start="<?php echo it_get_setting('featured_category_delay'); ?>00" data-easing="easeInOutExpo" data-captionhidden="on"><?php echo $category_name; ?></div>
                                                    
                                                <?php } ?>
                                                
                                                <?php if(!$rating_disable) { ?>
                                                
                                                    <div class="caption <?php echo it_get_setting('featured_rating_effect'); ?> rating-wrapper" data-x="<?php echo $rating_x; ?>" data-y="<?php echo $rating_y; ?>" data-speed="<?php echo it_get_setting('featured_rating_speed'); ?>00" data-start="<?php echo it_get_setting('featured_rating_delay'); ?>00" data-easing="easeInOutExpo" data-captionhidden="on"><?php echo it_show_rating(get_the_ID(), $minisite, false); ?></div>
                                                    
                                                <?php } ?>
                                                
                                                <?php if(!$award_disable) { ?>
                                                
                                                    <div class="caption <?php echo it_get_setting('featured_award_effect'); ?> award" data-x="<?php echo $award_x; ?>" data-y="<?php echo $award_y; ?>" data-speed="<?php echo it_get_setting('featured_award_speed'); ?>00" data-start="<?php echo it_get_setting('featured_award_delay'); ?>00" data-easing="easeOutBounce" data-captionhidden="on"><?php echo $award; ?></div>
                                                    
                                                <?php } ?>
                                                
                                                <?php if(!$title_disable) { ?>
                                                
                                                    <div class="caption <?php echo it_get_setting('featured_title_effect'); ?> title" data-x="<?php echo $title_x; ?>" data-y="<?php echo $title_y; ?>" data-speed="<?php echo it_get_setting('featured_title_speed'); ?>00" data-start="<?php echo it_get_setting('featured_title_delay'); ?>00" data-easing="easeInOutExpo"><?php echo it_title($length); ?></div>  
                                                    
                                                <?php } ?>  
                                                    
                                            </li>
                                                
                                            <?php                                
                                        endwhile; ?>
                                    
                                        <?php wp_reset_query(); ?>  
                                    
                                    </ul>
                                    
                                    <?php if(!it_get_setting('featured_timer_disable')) { ?>
                                    
                                    	<div class="tp-bannertimer tp-bottom"></div> 
                                        
                                    <?php } ?>          
                                
                                </div>
                                
                            </div>
                            
                        <?php } else { ?>
                        
                            <span style="color:#FFF;"><?php _e('No articles found. Check your settings in Theme Options >> Content Carousels >> Featured Slider', IT_TEXTDOMAIN); ?></span>
                        
                        <?php } ?>
                        
                        <?php wp_reset_query(); ?>
                        
                    </div>
                    
                    <?php if($right_span>0) { ?>
                    
                        <div class="span<?php echo $right_span; ?> featured-widgets">
                        
                        	<div class="featured-widget<?php if($right_span==3) { ?> full<?php } ?>">
                        
								<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar($sidebar1) ) : else : ?>
                                
                                    <div class="widget">
                                
                                        <div class="header">
                                        
                                        	<h3><?php echo $sidebar1; ?></h3>
                                            
                                        </div>                                        
                                        
                                    </div>
                                
                                <?php endif; ?>
                                
                            </div>
                            
                            <?php if($right_span>3) { ?>
                            
                                <div class="featured-widget right">
                                
                                    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar($sidebar2) ) : else : ?>
                                    
                                        <div class="widget">
                                    
                                            <div class="header">
                                            
                                                <h3><?php echo $sidebar2; ?></h3>
                                                
                                            </div>
                                            
                                        </div>
                                    
                                    <?php endif; ?>
                                    
                                </div>
                                
                            <?php } ?>
                        
                        </div>
                        
                    <?php } ?>
                    
                </div>
                
            </div>
        
        </div>
        
    </div>
    
</div>
	
<?php } ?>

<?php wp_reset_query(); ?>