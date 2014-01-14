<div class="row">
        
    <div class="span12">
    
    	<div id="main-menu-wrapper">
    
			<?php //random article button
			global $itMinisites;
            if (!it_get_setting('random_disable')) { 
				$post_type = array('post');
				foreach($itMinisites->minisites as $minisite) {
					array_push($post_type, $minisite->id);
				}
				$args = array('posts_per_page' => 1, 'orderby' => 'rand', 'ignore_sticky_posts' => 1, 'post_type' => $post_type);
                $rand_loop = new WP_Query($args);
                if ($rand_loop->have_posts()) : while ($rand_loop->have_posts()) : $rand_loop->the_post();	
                    ?>
                
                    <div id="random-article">
                
                        <a title="<?php _e( 'Random Article', IT_TEXTDOMAIN ); ?>" class="info icon-random" href="<?php the_permalink(); ?>"></a>
                    
                    </div>
                    
                <?php endwhile; 
                endif; 
                wp_reset_query();?> 
                
            <?php } ?>
        
            <div class="row">
            
                <div class="span12 right-shadow">
                
                    <div id="main-menu" class="menu-inner">
                    
                        <div id="main-menu-full">
                                        
                            <?php 
                            //title attribute gets in the way - remove it
                            $menu = wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => '0', 'fallback_cb' => 'fallback_categories', 'echo' => '0' ) );
                            $menu = preg_replace('/title=\"(.*?)\"/','',$menu);
                            echo $menu;
                            ?> 
                            
                            <br class="clearer" />
                            
                        </div>
                        
                        <div id="main-menu-compact">
                        
                            <?php echo it_responsive_menu('main-menu'); ?> 
                            
                        </div>  
                        
                    </div>
                    
                </div>
            
            </div>
            
            <?php if (!it_component_disabled('submenu', $post->ID)) { ?>
            
                <div class="row">
                
                    <div class="span12 right-shadow submenu">
                    
                        <div id="sub-menu" class="menu-inner">
                
                            <?php
							global $post;
							$postid = $post->ID;
							$minisite = it_get_minisite($postid);							
                            #show taxonomy menu if this is a minisite page								
                            if($minisite && $minisite->taxonomy_submenu){
                                /*
								get list of all terms in the primary taxononmy for this review type
                                we do not want to hide empty just in case the user only selects children and does
                                not select the parent taxonomy when creating posts. If one of the taxonomies
                                is never actually assigned to any posts but rather only its children are assigned,
                                then, if we hid empty taxonomies, the parent taxonomy would never show in the menu
                                even though there are posts inside of it (assigned to its children)	
								*/	
								$primary_taxonomy = $minisite->get_primary_taxonomy();										
                                $terms = get_terms($primary_taxonomy->slug, array('parent' => 0, 'hide_empty' => 0));							
                                if (is_tax()) {
                                    #for taxonomy pages use get_term_by to find the current term name
                                    $current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                                    $current_term_name = $current_term->name;                                    
                                    #get top parent of the current taxonomy
                                    #only if the current taxonomy matches the primary taxonomy
                                    if($primary_taxonomy->id==get_query_var( 'taxonomy' )) {
                                        $top_parent = get_term_top_most_parent($current_term->term_id, $primary_taxonomy->slug); 
                                        $top_parent_name = $top_parent->name;
                                    }
                                } elseif (is_single()) {
                                    #for single pages use wp_get_object_terms to get all assigned terms for this page
                                    #use the first one in case there are several assigned
                                    $current_terms = wp_get_object_terms($postid,$primary_taxonomy->slug);
                                    $current_term_name = $current_terms[0]->name;							
                                }                                
                                $count = count($terms);						
                                if ( $count > 0 ){ ?> 
                                
                                    <div id="sub-menu-full">
                                    
                                        <ul>
                                            <?php foreach ( $terms as $term ) { 
                                                $term_name = $term->name;
                                                $term_slug = $term->slug;
                                                $term_link = get_term_link($term_slug, $primary_taxonomy->slug);
                                                $current_page_item=false;
                                                #is this the current top level term?
                                                if(is_single()) { #for single pages look at all assigned terms and highlight all of them											
                                                    foreach ($current_terms as $current_term) {
                                                        #echo "current_term_name=".$current_term->name;
                                                        $top_parent = get_term_top_most_parent($current_term->term_id, $primary_taxonomy->slug); 
                                                        $top_parent_name = $top_parent->name;
                                                        if($term_name==$current_term->name || $term_name==$top_parent_name) {
                                                            $current_page_item=true;
                                                        }
                                                    }
                                                } else { #for taxonomy pages there can be only one selected current term to highlight
                                                    if($term_name==$current_term_name || $term_name==$top_parent_name) {
                                                        $current_page_item=true;
                                                    }
                                                }
                                                ?>
                                                <li <?php if($current_page_item) { ?>class="current_page_item"<?php } ?>>
                                                    <a href="<?php echo $term_link; ?>"><?php echo $term_name; ?></a>
                                                </li>							
                                            <?php } ?>
                                        </ul> 
                                        
                                        <br class="clearer" />                              
                                        
                                    </div>
                                    
                                    <div id="sub-menu-compact">
                                    
                                        <?php  
										$menu_name = 'tax-menu';                         
										$menu_list = '<select id="select-menu-' . $menu_name . '" class="mobile_menu"><option>'.__( 'Sub Navigation',IT_TEXTDOMAIN).'</option>';    
										$flag = false;                                
										foreach ($terms as $term) {
											$term_name = $term->name;
											$term_slug = $term->slug;
											$termid = $term->term_id;
											$top_parent = get_term_top_most_parent($termid, $primary_taxonomy->slug); 
											$top_parent_name = $top_parent->name;
											$url = get_term_link($term_slug, $primary_taxonomy->slug);
											$indent = '';
											$selected = '';
											$selectedoption = '';
											#get selected term											
											if(($term_name==$current_term_name || $term_name==$top_parent_name) && !$flag) {
												$flag = true;
												$selected = "selected";
												$selectedoption = 'selected="selected"';
											}												
											#does this term have a parent?
											if($term_name!=$top_parent_name) {
												$indent .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';							
											}																						
											$menu_list .= '<option ';
											if($selectedoption!='') {
												$menu_list .= $selectedoption;
											}
											$menu_list .= ' ';
											if($selected!='') {
												$menu_list .= 'class="' . $selected . '"';
											}
											$menu_list .= ' value="' . $url . '">' . $indent . $term_name . '</option>';
										}
										$menu_list .= '</select>';	
										echo $menu_list;	
										?> 
                                    
                                    </div>
                                    
                                <?php } else { ?>
                                
                                    <div id="sub-menu-full"> 
                                    
                                        <ul>
                                            <li><a href="#"><?php _e('You have not yet created any primary taxonomy terms for this minisite (or you have but they are empty).',IT_TEXTDOMAIN); ?></a></li>
                                        </ul>  
                                        
                                        <br class="clearer" />                             
                                    
                                    </div>
                                    
                                    <div id="sub-menu-compact">&nbsp;</div>
                                    
                                <?php }
                            } elseif(!it_component_disabled('submenu', $post->ID)) { ?>
                            
                                <div id="sub-menu-full"> 
                                
                                    <?php 
                                    //title attribute gets in the way - remove it
                                    $menu = wp_nav_menu( array( 'theme_location' => 'sub-menu', 'container' => '0', 'fallback_cb' => 'fallback_footer_menu', 'echo' => '0' ) );
                                    $menu = preg_replace('/title=\"(.*?)\"/','',$menu);
                                    echo $menu;
                                    ?>  
                                    
                                    <br class="clearer" />                            
                                
                                </div>
                                    
                                <div id="sub-menu-compact">
                                
                                    <?php echo it_responsive_menu('sub-menu'); ?> 
                                
                                </div>                   
                                
                            <?php } ?>                   
                            
                        </div>
                        
                    </div> 
                    
                </div>
                
            <?php } ?>
                                   
        </div>
        
    </div>
    
</div>