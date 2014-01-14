<?php
class it_trending_articles extends WP_Widget {
	function it_trending_articles() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Trending Articles', 'description' => __( 'Displays articles with several available filtering options.',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'it_trending_articles' );
		/* Create the widget. */
		$this->WP_Widget( 'it_trending_articles', 'Trending Articles', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {
		
		global $itMinisites, $wp, $post;
		#get the current query to pass it to the ajax functions through the html data tag
		#don't want this if we're viewing a single post
		if(!is_single()) $current_query = $wp->query_vars;			

		extract( $args );

		/* User-selected settings. */
		$filter_recent = $instance['filter_recent'];
		$filter_liked = $instance['filter_liked'];
		$filter_viewed = $instance['filter_viewed'];
		$filter_rated = $instance['filter_rated'];
		$filter_commented = $instance['filter_commented'];
		$filter_awarded = $instance['filter_awarded'];
		$numarticles = $instance['numarticles'];
		$thumbnail = $instance['thumbnail'];
		$rating = $instance['rating'];
		$icon = $instance['icon'];
		
		#setup the query            
        $args=array('posts_per_page' => $numarticles);
		
		#setup loop format
		$format = array('loop' => 'trending', 'location' => 'widget', 'thumbnail' => $thumbnail, 'rating' => $rating, 'icon' => $icon);	
		
		#determine if we are on a minisite page
		$current_query = array();
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
		    
        #Before widget (defined by themes)
        echo $before_widget;

        #HTML output
		echo "<div class='trending-articles' data-currentquery='" . $current_query_encoded . "'>";		   
        echo '<div class="filterbar">';
		echo '<div class="sort-buttons" data-loop="trending" data-location="widget" data-thumbnail="'.$thumbnail.'" data-rating="'.$rating.'" data-numarticles="'.$numarticles.'" data-icon="'.$icon.'">';
		$active_shown=false;
		if($filter_recent) {
			echo '<a data-sorter="recent" class="icon-recent recent info';
			if(!$active_shown) echo ' active';
			echo '" title="' . __('most recent', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_liked) {
			echo '<a data-sorter="liked" class="icon-liked liked info';
			if(!$active_shown) {
				echo ' active';
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = IT_META_TOTAL_LIKES;
			}
			echo '" title="' . __('most liked', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_viewed) {
			echo '<a data-sorter="viewed" class="icon-viewed viewed info';
			if(!$active_shown) {
				echo ' active';
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = IT_META_TOTAL_VIEWS;	
			}
			echo '" title="' . __('most viewed', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_rated) {
			echo '<a data-sorter="rated" class="icon-reviewed rated info';
			if(!$active_shown) {
				echo ' active';
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = IT_META_TOTAL_SCORE_NORMALIZED;
				$args['meta_query'] = array(array( 'key' => IT_META_DISABLE_REVIEW, 'value' => 'false', 'compare' => '=' ));
			}
			echo '" title="' . __('highest rated', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_commented) {
			echo '<a data-sorter="commented" class="icon-commented commented info';
			if(!$active_shown) {
				echo ' active';
				$args['orderby'] = 'comment_count';	
			}
			echo '" title="' . __('most commented', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_awarded) {
			echo '<a data-sorter="awarded" class="icon-awarded awarded info';
			if(!$active_shown) {
				echo ' active';
				$args['orderby'] = 'date';
				$args['order'] = 'DESC';
				$args['meta_query'] = array( array( 'key' => IT_META_AWARDS, 'value' => array(''), 'compare' => 'NOT IN') );	
			}
			echo '" title="' . __('recently awarded', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		
		echo '</div>';
		echo '<br class="clearer" />';
		echo '</div>';
		
		echo '<div class="loading"><div>&nbsp;</div></div>';	
		
		echo '<div class="post-list">';	
        
        #display the loop
        $loop = it_loop($args, $format); 
		echo $loop['content'];
		
		echo '</div>'; #end post-list div 		
		echo '</div>'; #end trending-latest div	
		
		wp_reset_query();			
        
		# After widget (defined by themes)
        echo $after_widget; ?>		
		
	<?php
	}
	function update( $new_instance, $old_instance ) {
		
		global $itMinisites;
		
		$instance = $old_instance;
		
		$instance['filter_recent'] = isset( $new_instance['filter_recent'] );
		$instance['filter_liked'] = isset( $new_instance['filter_liked'] );
		$instance['filter_viewed'] = isset( $new_instance['filter_viewed'] );
		$instance['filter_rated'] = isset( $new_instance['filter_rated'] );
		$instance['filter_commented'] = isset( $new_instance['filter_commented'] );
		$instance['filter_awarded'] = isset( $new_instance['filter_awarded'] );	
		$instance['numarticles'] = strip_tags( $new_instance['numarticles'] );		
		$instance['thumbnail'] = isset( $new_instance['thumbnail'] );
		$instance['rating'] = isset( $new_instance['rating'] );
		$instance['icon'] = isset( $new_instance['icon'] );		
		
		return $instance;
		
	}
	function form( $instance ) {
		
		global $itMinisites;	

		#set up some default widget settings.
		$defaults = array( 'filter_recent' => true, 'filter_liked' => true, 'filter_viewed' => true, 'filter_rated' => true, 'filter_commented' => true, 'filter_awarded' => true, 'numarticles' => 4, 'thumbnail' => true, 'rating' => true, 'icon' => true );		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>	
		
		<p><?php _e( 'Filtering Options:',IT_TEXTDOMAIN); ?></p>	
        
        <div style="margin-left:10px;">			
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_recent']) ? $instance['filter_recent'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_recent'); ?>" id="<?php echo $this->get_field_id('filter_recent'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_recent'); ?>"><?php _e('Recent',IT_TEXTDOMAIN); ?></label>
            </p>   
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_liked']) ? $instance['filter_liked'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_liked'); ?>" id="<?php echo $this->get_field_id('filter_liked'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_liked'); ?>"><?php _e('Liked',IT_TEXTDOMAIN); ?></label>
            </p> 
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_viewed']) ? $instance['filter_viewed'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_viewed'); ?>" id="<?php echo $this->get_field_id('filter_viewed'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_viewed'); ?>"><?php _e('Viewed',IT_TEXTDOMAIN); ?></label>
            </p> 
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_rated']) ? $instance['filter_rated'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_rated'); ?>" id="<?php echo $this->get_field_id('filter_rated'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_rated'); ?>"><?php _e('Rated',IT_TEXTDOMAIN); ?></label>
            </p> 
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_commented']) ? $instance['filter_commented'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_commented'); ?>" id="<?php echo $this->get_field_id('filter_commented'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_commented'); ?>"><?php _e('Commented',IT_TEXTDOMAIN); ?></label>
            </p> 
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_awarded']) ? $instance['filter_awarded'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_awarded'); ?>" id="<?php echo $this->get_field_id('filter_awarded'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_awarded'); ?>"><?php _e('Awarded',IT_TEXTDOMAIN); ?></label>
            </p>              
        </div>			
	
		<p>                
			<?php _e( 'Display',IT_TEXTDOMAIN); ?>
			<input id="<?php echo $this->get_field_id( 'numarticles' ); ?>" name="<?php echo $this->get_field_name( 'numarticles' ); ?>" value="<?php echo $instance['numarticles']; ?>" style="width:30px" />  
			<?php _e( 'articles',IT_TEXTDOMAIN); ?>
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['thumbnail']) ? $instance['thumbnail'] : 0  ); ?> id="<?php echo $this->get_field_id( 'thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'thumbnail' ); ?>"><?php _e( 'Display Thumbnail',IT_TEXTDOMAIN); ?></label>             
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['rating']) ? $instance['rating'] : 0  ); ?> id="<?php echo $this->get_field_id( 'rating' ); ?>" name="<?php echo $this->get_field_name( 'rating' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'rating' ); ?>"><?php _e( 'Display Rating',IT_TEXTDOMAIN); ?></label>             
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['icon']) ? $instance['icon'] : 0  ); ?> id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Display Minisite Icon',IT_TEXTDOMAIN); ?></label>             
		</p>
		
		<?php
	}
}
?>