<?php global $itMinisites, $wp;
#get the current query to pass it to the ajax functions through the html data tag
#don't want this if we're viewing a single post
$current_query = array();
if(!is_single()) $current_query = $wp->query_vars;
#setup wp_query args
$args = array('posts_per_page' => it_get_setting("trending_number"));
#setup loop format
$format = array('loop' => 'trending', 'thumbnail' => true, 'rating' => true, 'icon' => true);
#limits
$limit_cat = it_get_setting('trending_limit_cat');
if(!empty($limit_cat)) $current_query['cat'] = $limit_cat;
$limit_tag = it_get_setting('trending_limit_tag');	
if(!empty($limit_tag)) $current_query['tag_id'] = $limit_tag;	
#excludes
$exclude_cat = it_get_setting('trending_exclude_cat');
if(!empty($exclude_cat)) $current_query['category__not_in'] = array($exclude_cat);
$exclude_tag = it_get_setting('trending_exclude_tag');	
if(!empty($exclude_tag)) $current_query['tag__not_in'] = array($exclude_tag);
$exclude_minisites = it_get_setting('trending_exclude_minisites');	
if(!empty($exclude_minisites)) {
	$all_minisites = array();
	$include_minisites = array();
	#get all minisites into array
	if(is_array($itMinisites->minisites)) {
		foreach($itMinisites->minisites as $minisite) {
			$all_minisites[] = $minisite->id;
		}
	}	
	#include minisites that are not excluded
	if(is_array($exclude_minisites)) $include_minisites = array_diff($all_minisites, $exclude_minisites);
	#if all minisites are excluded, need to set var to 'post' so it's not empty, or else pre_get_posts will inject all minisites
	if(empty($include_minisites)) $include_minisites = 'post';
	$current_query['post_type'] = $include_minisites;
}
#get new page number count
query_posts ($current_query);
$numpages = $wp_query->max_num_pages;
wp_reset_query();

#determine if we are on a minisite page
$minisite = it_get_minisite($post->ID);
if($minisite) {		
	#add post type to query args
	if($minisite->trending_targeted) {
		$args['post_type'] = $minisite->id;	
		#also add to current query for ajax purposes
		$current_query['post_type'] = $minisite->id;
	}
}
#encode current query for ajax purposes
$current_query_encoded = json_encode($current_query);

#add current query to main args array
$args = array_merge($args, $current_query);

if(!it_get_setting("trending_disable_scrolling")) $carouselclass=" carousel";

if(!it_component_disabled('trending', $post->ID)) { ?>

    <div id="trending-row">
    
        <div id="trending-wrapper" class="post-list<?php if(it_get_setting('trending_hidden')) { ?> collapsed<?php } ?>" data-currentquery='<?php echo $current_query_encoded; ?>'>
            
            <div class="loading"><div>&nbsp;</div></div>
            
            <div class="row">	
            
                <div id="trending" class="span12<?php echo $carouselclass; ?> slide">
                
                    <div class="carousel-inner">
        
                        <?php $loop = it_loop($args, $format); echo $loop['content']; ?>
                        
                    </div>
                    
                </div>
                
            </div> 
            
            <div class="row"> 
            
                <div class="span12 sortbar">           	
                        
                    <?php if(!it_get_setting("trending_disable_scrolling") && $loop['pages'] > 1) { ?>  
                      
                        <div class="carousel-control-wrapper">
            
                            <a class="carousel-control left" href="#trending" data-slide="prev">&lsaquo;</a>
                            <a class="carousel-control right" href="#trending" data-slide="next">&rsaquo;</a>
                                                
                        </div>
                        
                    <?php } ?>
                    
                    <div class="trending-title"><span class="hidden-phone"><?php echo it_get_setting("trending_title"); ?></span><span class="visible-phone"><?php echo it_get_setting("trending_title_mobile"); ?></span></div>
                    
                    <?php if(!it_get_setting("trending_disable_filtering")) { ?>
                    
                        <?php if(!it_get_setting("trending_disable_tooltips")) { $infoclass="info"; } ?>
                        
                        <?php $disabled = ( is_array( it_get_setting("trending_disable_filter") ) ) ? it_get_setting("trending_disable_filter") : array(); ?>
                    
                        <div class="sort-buttons" data-loop="trending" data-location="trending" data-thumbnail="1" data-rating="1" data-icon="1" data-numarticles="<?php echo it_get_setting("trending_num"); ?>">
                        
                            <a href="#" data-sorter="recent" class="icon-recent recent active <?php echo $infoclass; ?>" title="<?php _e('most recent', IT_TEXTDOMAIN); ?>">&nbsp;</a>
                            
                            <?php if(!in_array('liked', $disabled)) { ?><a href="#" data-sorter="liked" class="icon-liked liked <?php echo $infoclass; ?>" title="<?php _e('most liked', IT_TEXTDOMAIN); ?>">&nbsp;</a><?php } ?>
                            
                            <?php if(!in_array('viewed', $disabled)) { ?><a href="#" data-sorter="viewed" class="icon-viewed viewed <?php echo $infoclass; ?>" title="<?php _e('most viewed', IT_TEXTDOMAIN); ?>">&nbsp;</a><?php } ?>
                             
                            <?php if(!in_array('rated', $disabled)) { ?><a href="#" data-sorter="rated" class="icon-reviewed rated <?php echo $infoclass; ?>" title="<?php _e('highest rated', IT_TEXTDOMAIN); ?>">&nbsp;</a><?php } ?>
                            
                            <?php if(!in_array('commented', $disabled)) { ?><a href="#" data-sorter="commented" class="icon-commented commented <?php echo $infoclass; ?>" title="<?php _e('most commented', IT_TEXTDOMAIN); ?>">&nbsp;</a><?php } ?>
                            
                            <?php if(!in_array('awarded', $disabled)) { ?><a href="#" data-sorter="awarded" class="icon-awarded awarded <?php echo $infoclass; ?>" title="<?php _e('recently awarded', IT_TEXTDOMAIN); ?>">&nbsp;</a><?php } ?>
                        
                        </div>
                        
                    <?php } ?>
                    
                </div>              
                
            </div> 
                    
        </div>
    
        <?php if(it_get_setting("trending_collapsible")) { ?><div id="trending-toggle"><a href="#" data-toggle="tooltip" title="toggle trending articles" class="info-bottom icon-trending">&nbsp;</a></div> <?php } ?>   
    
    </div>
	
<?php } ?>

<?php wp_reset_query(); ?>