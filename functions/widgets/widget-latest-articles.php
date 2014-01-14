<?php
class it_latest_articles extends WP_Widget {
	function it_latest_articles() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Latest Articles', 'description' => __( 'Displays latest articles from a specific minisite, all minisites, or all articles.',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'it_latest_articles' );
		/* Create the widget. */
		$this->WP_Widget( 'it_latest_articles', 'Latest Articles', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {
		
		global $itMinisites;		

		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );	
		$selected_minisite = $instance['minisite'];
		$numarticles = $instance['numarticles'];
		$thumbnail = $instance['thumbnail'];
		$rating = $instance['rating'];
		$icon = $instance['icon'];
		    
        #Before widget (defined by themes)
        echo $before_widget;

        #Title of widget (before and after defined by themes)
        if ( $title ) { ?>                	
            <?php echo $before_title; ?>

                <h3><span class="icon-recent header-icon"></span><?php echo $title; ?></h3>
                
            <?php echo $after_title; ?>
        <?php } 
        
        #HTML output
        
		echo '<div class="post-list">';
		   
        #get post type variable                    
        if($selected_minisite=="All Minisites" || $selected_minisite=="Everything") {
            $post_types = array(); 
            foreach($itMinisites->minisites as $minisite){
                if($minisite->enabled) {
                     array_push($post_types, $minisite->id);						             
                }
            }
        } else {
            $post_types = $selected_minisite;
        }
        #add in 'post' type for all articles
        if($selected_minisite=="Everything") {
            array_push($post_types, 'post');
        }			
        
        #setup the query            
        $args=array('suppress_filters' => true, 'posts_per_page' => $numarticles, 'order' => 'DESC', 'order_by' => 'date', 'post_type' => $post_types);
        
		#setup loop format
		$format = array('loop' => 'widget', 'thumbnail' => $thumbnail, 'rating' => $rating, 'icon' => $icon, 'container' => '#featured-bar-wrapper');

        #display the loop
        $loop = it_loop($args, $format); 
		echo $loop['content'];
		
		echo '<div class="clearer"></div></div>';
		
		wp_reset_query();				
        
		# After widget (defined by themes)
        echo $after_widget; ?>		
		
	<?php
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['minisite'] = strip_tags( $new_instance['minisite'] );
		$instance['numarticles'] = strip_tags( $new_instance['numarticles'] );
		$instance['thumbnail'] = isset( $new_instance['thumbnail'] );
		$instance['rating'] = isset( $new_instance['rating'] );
		$instance['icon'] = isset( $new_instance['icon'] );

		return $instance;
	}
	function form( $instance ) {
		
		global $itMinisites;		

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Latest Articles', 'minisite' => 'Everything', 'numarticles' => 4, 'thumbnail' => true, 'rating' => true, 'icon' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:',IT_TEXTDOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:160px" />
		</p>
		
		<p>
			<?php _e( 'Type:',IT_TEXTDOMAIN); ?>
			<select name="<?php echo $this->get_field_name( 'minisite' ); ?>">
				<option<?php if($instance['minisite']=='Everything') { ?> selected<?php } ?> value="Everything"><?php _e( 'Everything', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['minisite']=='All Minisites') { ?> selected<?php } ?> value="All Minisites"><?php _e( 'All Minisites', IT_TEXTDOMAIN ); ?></option>
				<?php 
				foreach($itMinisites->minisites as $minisite){
					if($minisite->enabled) { ?>
						<option<?php if($instance['minisite']==$minisite->id) { ?> selected<?php } ?> value="<?php echo $minisite->id ?>"><?php echo $minisite->name; ?></option>
				<?php
					}
				}?>
			</select>
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
	
		<p>                
			<?php _e( 'Display',IT_TEXTDOMAIN); ?>
			<input id="<?php echo $this->get_field_id( 'numarticles' ); ?>" name="<?php echo $this->get_field_name( 'numarticles' ); ?>" value="<?php echo $instance['numarticles']; ?>" style="width:30px" />  
			<?php _e( 'articles',IT_TEXTDOMAIN); ?>
		</p>
		
		<?php
	}
}
?>