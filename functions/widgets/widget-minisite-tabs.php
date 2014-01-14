<?php
class it_minisite_tabs extends WP_Widget {
	function it_minisite_tabs() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Minisite Articles (Tabbed)', 'description' => __( 'Displays the latest articles from selected minisites in tabbed format using the minisite icons as the tab navigation.',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'it_minisite_tabs' );
		/* Create the widget. */
		$this->WP_Widget( 'it_minisite_tabs', 'Minisite Articles (Tabbed)', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {
		
		global $itMinisites;		

		extract( $args );

		/* User-selected settings. */
		$numarticles = $instance['numarticles'];
		$thumbnail = $instance['thumbnail'];
		$rating = $instance['rating'];
		$more = $instance['more'];
		    
        #Before widget (defined by themes)
        echo $before_widget;

        #HTML output
		echo '<div class="minisite-tabs">';		   
        echo '<div class="filterbar">';
		echo '<div class="sort-buttons" data-loop="minisite tabs" data-location="widget" data-thumbnail="'.$thumbnail.'" data-rating="'.$rating.'" data-numarticles="'.$numarticles.'" data-morelink="'.$more.'">';
		$post_types = array();
		$i=0;                   
        foreach($itMinisites->minisites as $minisite){			
			if(array_key_exists($minisite->id, $instance)) {
				if($minisite->enabled && $instance[$minisite->id]) {
					$i++;
					$activecss = '';
					array_push($post_types, $minisite->id);	
					if($i==1) {
						$activecss = ' active';	
						$post_type = $minisite->id;
						$more_link = $minisite->more_link;
					}
					echo '<a data-sorter="' . $minisite->id . '" class="info' . $activecss . '" title="' . __('Latest from', IT_TEXTDOMAIN) . ' ' . $minisite->name . '">' . it_get_category($minisite) . '</a>';
					
				}	
			}
		}
		
		echo '</div>';
		echo '<br class="clearer" />';
		echo '</div>';
		
		echo '<div class="loading"><div>&nbsp;</div></div>';	
		
		echo '<div class="post-list">';	
        
        #setup the query            
        $args=array('posts_per_page' => $numarticles, 'post_type' => $post_type);
		
		#setup loop format
		$format = array('loop' => 'minisite tabs', 'location' => 'widget', 'thumbnail' => $thumbnail, 'rating' => $rating, 'icon' => false, 'morelink' => $more);		
        
        #display the loop
        $loop = it_loop($args, $format); 
		echo $loop['content'];
		
		echo '</div>'; #end post-list div 		
		echo '</div>'; #end minisite-tabs div	
		
		wp_reset_query();			
        
		# After widget (defined by themes)
        echo $after_widget; ?>		
		
	<?php
	}
	function update( $new_instance, $old_instance ) {
		
		global $itMinisites;
		
		$instance = $old_instance;
		
		foreach($itMinisites->minisites as $minisite){
            if($minisite->enabled) {
				$instance[$minisite->id] = isset( $new_instance[$minisite->id] );
			}			
		}		
		$instance['numarticles'] = strip_tags( $new_instance['numarticles'] );		
		$instance['thumbnail'] = isset( $new_instance['thumbnail'] );
		$instance['rating'] = isset( $new_instance['rating'] );
		$instance['more'] = isset( $new_instance['more'] );		
		
		return $instance;
		
	}
	function form( $instance ) {
		
		global $itMinisites;	

		#set up some default widget settings.
		$defaults = array( 'numarticles' => 4, 'thumbnail' => true, 'rating' => true, 'more' => true );
		#add minisite checkboxes to default array
		foreach($itMinisites->minisites as $minisite){
			if($minisite->enabled) {
				 $defaults[$minisite->id] = true;						             
			}
		}
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>	
		
		<p><?php _e( 'Display These Minisites:',IT_TEXTDOMAIN); ?></p>	
        
        <div style="margin-left:10px;">
			<?php 
            foreach($itMinisites->minisites as $minisite){
                if($minisite->enabled) { ?> 
                    <p>                   
                        <input class="checkbox" type="checkbox" <?php checked(isset( $instance[ $minisite->id ]) ? $instance[ $minisite->id ] : 0  ); ?> name="<?php echo $this->get_field_name( $minisite->id ); ?>" id="<?php echo $this->get_field_id( $minisite->id ); ?>" />
                        <label for="<?php echo $this->get_field_id( $minisite->id ); ?>"><?php echo $minisite->name; ?></label>
                    </p>
                <?php
                }
            }?>
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
        
        <?php /*
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['more']) ? $instance['more'] : 0  ); ?> id="<?php echo $this->get_field_id( 'more' ); ?>" name="<?php echo $this->get_field_name( 'more' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'more' ); ?>"><?php _e( 'Display "More" Button',IT_TEXTDOMAIN); ?></label>             
		</p>		
		*/ ?>
		
		<?php
	}
}
?>