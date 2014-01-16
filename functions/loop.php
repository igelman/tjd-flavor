<?php 
function it_loop($args, $format, $timeperiod = '') {
	global $itMinisites, $wp_query;
	if(!is_array($format)) $format = array();
	if(array_key_exists('loop', $format)) $loop = $format['loop'];
	if(array_key_exists('location', $format)) $location = $format['location'];
	if(empty($location)) $location = $loop; #a specified location overrides the loop parameter
	if(array_key_exists('view', $format)) $view = $format['view'];
	if(array_key_exists('columns', $format)) $cols = $format['columns'];
	if(array_key_exists('metric', $format)) $metric = $format['metric'];
	if(array_key_exists('thumbnail', $format)) $thumbnail = $format['thumbnail'];
	if(array_key_exists('morelink', $format)) $morelink = $format['morelink'];
	if(array_key_exists('rating', $format)) $rating = $format['rating'];
	if(array_key_exists('icon', $format)) $icon = $format['icon'];
	if(array_key_exists('container', $format)) $container = $format['container'];
	$nonajax = array_key_exists('nonajax', $format) ? $format['nonajax'] : '';
	#don't care about pagename if we're displaying a post loop on a content page
	$args['pagename'] = '';



/**
*  Customizations for tjd
*   * Use our custom post types (tmt-deal-posts in particular)
*   * Display our custom fields
*/

// Use our custom post types
//  (though maybe we can get away from this
//   and just use regular posts
//   along with our custom fields)
$args['post_type'] = "tmt-deal-posts";


// Add our custom content
//  to <div class="panel-wrapper">
/*******/	
	
	#add a filter if this loop needs a time constraint (can't add to query args directly)
	global $timewhere;
	$timewhere = $timeperiod;
	if(!empty($timeperiod)) {		
		add_filter( 'posts_where', 'filter_where' );
	}	
	#query the posts
	query_posts ( $args );
	#remove the filter after we're done
	if(!empty($timeperiod)) {				
		remove_filter( 'posts_where', 'filter_where' );
	}
	#setup ads array
	$ads=array();
	$ad1=it_get_setting('loop_ad_1');
	$ad2=it_get_setting('loop_ad_2');
	$ad3=it_get_setting('loop_ad_3');
	$ad4=it_get_setting('loop_ad_4');
	$ad5=it_get_setting('loop_ad_5');
	$ad6=it_get_setting('loop_ad_6');
	$ad7=it_get_setting('loop_ad_7');
	$ad8=it_get_setting('loop_ad_8');
	$ad9=it_get_setting('loop_ad_9');
	$ad10=it_get_setting('loop_ad_10');
	if(!empty($ad1)) array_push($ads,$ad1);
	if(!empty($ad2)) array_push($ads,$ad2);
	if(!empty($ad3)) array_push($ads,$ad3);
	if(!empty($ad4)) array_push($ads,$ad4);
	if(!empty($ad5)) array_push($ads,$ad5);
	if(!empty($ad6)) array_push($ads,$ad6);
	if(!empty($ad7)) array_push($ads,$ad7);
	if(!empty($ad8)) array_push($ads,$ad8);
	if(!empty($ad9)) array_push($ads,$ad9);
	if(!empty($ad10)) array_push($ads,$ad10);
	if(it_get_setting('ad_shuffle')) shuffle($ads);

	#counters
	$i=0;
	$p=0;
	$m=0;	
	$a=0;
	$out = '';
	$updatepagination=1;
	if (have_posts()) : while (have_posts()) : the_post(); $m++;	
		#minisite variables			
		$post_type = get_post_type(); #get post type
		if(!empty($post_type)) $minisite = $itMinisites->get_type_by_id($post_type); #get minisite object from post type
		
		#post-specific variables
		$more_link = '';
		if(isset($minisite->more_link))	$more_link = $minisite->more_link;
		
		#featured video
		$video = get_post_meta(get_the_ID(), "_featured_video", $single = true);
											
		if(!empty($video))
			$video = it_video( $args = array( 'url' => $video, 'video_controls' => it_get_setting('loop_video_controls'), 'parse' => true, 'width' => 320, 'height' => 205 ) );	
		
		switch ($location) {
			case 'trending': #TRENDING CAROUSEL	
			
				if($i % 4==0 && $i!=0) $out.='</div>';
                if($i % 4==0 || $i==0) $out.='<div class="row item';
				if($i==0) $out.=' active';
				if($i % 4==0 || $i==0) $out.='">';
                        
                $out.='<div class="span3">'; 
                
                	if($thumbnail) {                          
                        
                        $out.='<div class="article-image">';
                
                            $out.='<a class="thumbnail darken" href="'.get_permalink().'">'.it_featured_image(get_the_ID(), 'widget-post', 65, 65).'</a>';  
                        
                        $out.='</div>';
                        
                    }
                
                    $out.='<div class="article-excerpt';
					if(!$thumbnail) $out.=' full';
					$out.='">';
                                         
                        $out.='<a href="'.get_permalink().'">'.get_the_title().'</a>';

                        if(!empty($minisite) && $rating) $out.=it_show_rating(get_the_ID(), $minisite, false);
                            
                        if(!empty($minisite) && $icon) $out.=it_get_category($minisite); 
                        
                    $out.='</div>';
                     
                $out.='</div>';
            
            break;
			case 'sizzlin': #SIZZLIN CAROUSEL				
			
				$out.='<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
            
            break;
			case 'widget': #WIDGET			
            
            	$out.='<div class="list-item';
				if($i==0) $out.=' first';
				$out.='">';
                    
                    if($thumbnail) {
                    
                        $out.='<div class="article-image">';
                
                            $out.='<a class="thumbnail darken" href="'.get_permalink().'">'.it_featured_image(get_the_ID(), 'widget-post', 65, 65).'</a>';  
                        
                        $out.='</div>';
                        
                    }
                    
                    $out.='<div class="article-excerpt';
					if(!$thumbnail) $out.=' full';
					$out.='">';
                                         
                        $out.='<a href="'.get_permalink().'">'.get_the_title().'</a>';

                        if(!empty($minisite) && $rating) $out.=it_show_rating(get_the_ID(), $minisite, false);
                            
                        if(!empty($minisite) && $icon) $out.=it_get_category($minisite); 
                        
                    $out.='</div>';
                    
                    $out.='<br class="clearer" />';												
                    
                $out.='</div>';
				
				if(!empty($cols)) { #cols might be specified for some widget loops like the recommended area
					
					if($m % $cols==0) $out.='<br class="visible-desktop clearer" />';
					
					if($m % 2==0) $out.='<br class="hidden-desktop clearer two-panel" />'; #visible for tablets and down when sidebar is set to display
					
					if($m % 3==0) $out.='<br class="hidden-desktop clearer three-panel" />'; #visible for tablets and down when sidebar is hidden (full width)
					
				}
			
			break;
			case 'widget compact': #WIDGET COMPACT			
            
            	$out.='<div class="list-item';
				if($i==0) $out.=' first';
				$out.=' compact">';
                    
                    $out.='<div class="article-excerpt full compact">';

                        if(!empty($minisite) && $rating) $out.=it_show_rating(get_the_ID(), $minisite, false);
                        
                        $out.='<a href="'.get_permalink().'">'.get_the_title().'</a>';
                        
                    $out.='</div>';
                    
                    $out.='<br class="clearer" />';											
                    
                $out.='</div>';
			
			break;
			case 'top ten': #TOP TEN SCROLLER
			
				$number = $i+1;			
			
				$out.='<div class="panel item';
				
				if($i==0) $out.=' first active';
				
				$out .='">';
				
					$out.='<div class="top-ten-number-wrapper">';
				
						$out.='<div class="top-ten-number">' . $number . '</div>';
						
					$out.='</div>';
					
					$out.='<div class="top-ten-metric">';
					
						switch($metric) {
							case 'liked':						
								$out.=it_get_likes(get_the_ID(), false); 
								break;
							case 'viewed':
								$out.=it_get_views(get_the_ID());
								break;
							case 'commented':
								$out.=it_get_comments(get_the_ID(), true);
								break;
							case 'users':
								if(!empty($minisite) && $rating) $out.=it_show_rating(get_the_ID(), $minisite, false, true, false);
								break;
							case 'reviewed':
								if(!empty($minisite) && $rating) $out.=it_show_rating(get_the_ID(), $minisite, false, false, true);
								break;
						}
					
					$out.='</div>';
					
					$out.='<a href="'.get_permalink().'" class="title">'.it_title('85').'</a>';
					
				$out.='</div>';
            
			break;
			case 'directory': #MINISITE DIRECTORY			
            
            	$out.='<div class="listing">';
					
					if(!empty($minisite) && $rating) $out.=it_show_rating(get_the_ID(), $minisite, false);
					
					$out.='<a href="'.get_permalink().'">'.get_the_title().'</a>';										
                    
                $out.='</div>';
			
			break;
			case 'directory compact': #MINISITE DIRECTORY COMPACT		
            
            	$out.='<div class="listing compact">';
					
					$out.='<a href="'.get_permalink().'">'.get_the_title().'</a>';										
                    
                $out.='</div>';
			
			break;
			case 'main loop': #MAIN LOOP
				$len = '';
				if($view=='list') $len=520;	
								
				$the_ad = it_get_ad($ads, $m, $a, $cols, $nonajax); #show ads in the loop
				$m = $the_ad['postcount']; #get updated post count
				$a = $the_ad['adcount']; #get updated ad count	
				
				$out .= $the_ad['ad'];	
				                        
                $out.='<div class="panel-wrapper">';
                	
                    $out.='<div class="panel">';
                    
                    	$out.='<div class="article-image-wrapper">';
                
							if($thumbnail) {                         
                                
                                $out.='<div class="article-image darken">';
								
									if(!empty($video) && it_get_setting('loop_video')) {
										$out.=$video;
									} else {                        
                                    	$out.='<a href="'.get_permalink().'">'.it_featured_image(get_the_ID(), 'grid-post', 320, 205).'</a>';
									}
                                    
                                    if(!empty($minisite) && $icon) $out.=it_get_category($minisite, true, true, false); #show the minisite icon
                                
                                $out.='</div>';
                                
                            }
                            
                            if($view=='list' && !it_get_setting('loop_disable_tags')) {
                            
                            	$out.=it_get_tags(get_the_ID(), ' ');
                            
                            }
                        
                        $out.='</div>';
                        
                        $out.='<div class="article-excerpt-wrapper">';
                    
                            $out.='<div class="article-excerpt">';
                                                 
                                $out.='<h2><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
                                
                                $out.='<span class="award-grid';
								
								if(it_get_setting('loop_disable_excerpt')) $out.=' no-excerpt';
								
								$out.='">';
								
								if(!empty($minisite) && !it_get_setting('loop_disable_award')) $out.=it_get_awards(get_the_ID(), $minisite, 'main-loop', false); 
								
								$out.='</span>';
                                
                                if(!it_get_setting('loop_disable_excerpt')) $out.='<div class="excerpt">'.it_excerpt($len).'</div>';
                                
                                if(!empty($minisite) && !it_get_setting('loop_disable_badge')) $out.=it_get_awards(get_the_ID(), $minisite, 'main-loop', true);
                                
                            $out.='</div>';
                            
                        $out.='</div>';
                        
                        if($view=='list') $out.='<br class="clearer" />';
                        
                        if(!it_get_setting('loop_disable_meta')) {
                        
                            $out.='<div class="article-meta">';
                            
                                if(!it_get_setting('loop_disable_likes')) $out.=it_get_likes(get_the_ID(), true);
                                
                                if(!it_get_setting('loop_disable_views')) $out.=it_get_views(get_the_ID());
                                
                                if(!it_get_setting('loop_disable_comments')) $out.=it_get_comments(get_the_ID());
                                
                                if($view=='list' && !it_get_setting('loop_disable_date')) {
                                    $out.='<span class="meta-info">'.__('Posted ',IT_TEXTDOMAIN) . get_the_date() . __(' by ',IT_TEXTDOMAIN) . get_the_author() .'</span>';
                                }
                            
                                if(!empty($minisite) && $rating && !it_get_setting('loop_disable_rating')) $out.=it_show_rating(get_the_ID(), $minisite, false);
                                
                                $out.='<span class="award-list">';
								
									if($view=='list' && !empty($minisite) && !it_get_setting('loop_disable_award')) $out.=it_get_awards(get_the_ID(), $minisite, 'main-loop', false);
								$out.='</span>';
                                
                                $out.='<br class="clearer" />';
                            
                            $out.='</div>';
                            
                        }
                        
                    $out.='</div>';
                     
                $out.='</div>';
                
                if($m % $cols==0) $out.='<br class="visible-desktop clearer" />';
                
                if($m % 2==0) $out.='<br class="hidden-desktop clearer two-panel" />'; #visible for tablets and down when sidebar is set to display
                
                if($m % 3==0) $out.='<br class="hidden-desktop clearer three-panel" />'; #visible for tablets and down when sidebar is hidden (full width)
            
            break;
		} 
		
		$i++; endwhile; 
		else:
			
			$out.='<div class="filter-error">'.__('Nothing to see here.', IT_TEXTDOMAIN).'</div>';
			$updatepagination=0;
		
		endif;
    
	$pages = $wp_query->max_num_pages;
    if($location=='trending' && have_posts()) {
		$out.='</div>';    
    	$p=ceil($i/4);
		$pages = $p;
	}
	if($location=='minisite tabs') {
		if($morelink) $out .= '<div class="more-button"><a href="'.$more_link.'">'.__('More',IT_TEXTDOMAIN).'</a></div>';	
	}
	if($location=='main loop') {
		$out.='<br class="clearer" />';
	}
	
	return array('content' => $out, 'pages' => $pages, 'updatepagination' => $updatepagination);
	
} 
?>