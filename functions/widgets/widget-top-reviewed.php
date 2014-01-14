<?php
class it_top_reviewed extends WP_Widget {
	function it_top_reviewed() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Top Reviewed', 'description' => __( 'Displays top reviewed articles from a designated recent time period for a specific minisite or all minisites together.',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'it_top_reviewed' );
		/* Create the widget. */
		$this->WP_Widget( 'it_top_reviewed', 'Top Reviewed', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {
		
		global $itMinisites;		

		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );	
		$selected_minisite = $instance['minisite'];
		$numarticles = $instance['numarticles'];
		$orderby = $instance['orderby'];
		$timeperiod = $instance['timeperiod'];
		    
        #Before widget (defined by themes)
        echo $before_widget;

        #Title of widget (before and after defined by themes)
        if ( $title ) { ?>                	
            <?php echo $before_title; ?>

                <h3><span class="icon-reviewed header-icon"></span><?php echo $title; ?></h3>
                
            <?php echo $after_title; ?>
        <?php } 
        
        #HTML output
        
		echo '<div class="post-list">';
		   
        #get post type variable                    
        if($selected_minisite=="All Minisites") {
            $post_types = array(); 
            foreach($itMinisites->minisites as $minisite){
                if($minisite->enabled) {
                     array_push($post_types, $minisite->id);						             
                }
            }
        } else {
            $post_types = $selected_minisite;
        }	
        
        #setup the query            
        $args=array('posts_per_page' => $numarticles, 'order' => 'DESC', 'meta_key' => $orderby, 'orderby' => 'meta_value_num', 'post_type' => $post_types, 'meta_query' => array(array( 'key' => IT_META_DISABLE_REVIEW, 'value' => 'false', 'compare' => '=' )));
		
		#setup loop format
		$format = array('loop' => 'widget compact', 'thumbnail' => false, 'rating' => true, 'icon' => false);
		
		$week = date('W');
		$month = date('n');
		$year = date('Y');
		switch($timeperiod) {
			case 'This Week':
				$args['year'] = $year;
				$args['w'] = $week;
				$timeperiod='';
			break;	
			case 'This Month':
				$args['monthnum'] = $month;
				$args['year'] = $year;
				$timeperiod='';
			break;
			case 'This Year':
				$args['year'] = $year;
				$timeperiod='';
			break;
			case 'all':
				$timeperiod='';
			break;			
		}
        
        #display the loop
        $loop = it_loop($args, $format, $timeperiod); 
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
		$instance['orderby'] = strip_tags( $new_instance['orderby'] );
		$instance['timeperiod'] = strip_tags( $new_instance['timeperiod'] );

		return $instance;
	}
	function form( $instance ) {
		
		global $itMinisites;		

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Top Rated This Week', 'minisite' => 'All Minisites', 'numarticles' => 8, 'orderby' => 'Total Score', 'timeperiod' => 'This Year' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:',IT_TEXTDOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:160px" />
		</p>
		
		<p>
			<?php _e( 'Type:',IT_TEXTDOMAIN); ?>
			<select name="<?php echo $this->get_field_name( 'minisite' ); ?>">
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
			<?php _e( 'Time Period:',IT_TEXTDOMAIN); ?>
			<select name="<?php echo $this->get_field_name( 'timeperiod' ); ?>">
                <option<?php if($instance['timeperiod']=='This Week') { ?> selected<?php } ?> value="This Week"><?php _e( 'This Week', IT_TEXTDOMAIN ); ?></option>
				<option<?php if($instance['timeperiod']=='This Month') { ?> selected<?php } ?> value="This Month"><?php _e( 'This Month', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='This Year') { ?> selected<?php } ?> value="This Year"><?php _e( 'This Year', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='-7 days') { ?> selected<?php } ?> value="-7 days"><?php _e( 'Within Past Week', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='-30 days') { ?> selected<?php } ?> value="-30 days"><?php _e( 'Within Past Month', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='-60 days') { ?> selected<?php } ?> value="-60 days"><?php _e( 'Within Past 2 Months', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='-90 days') { ?> selected<?php } ?> value="-90 days"><?php _e( 'Within Past 3 Months', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='-180 days') { ?> selected<?php } ?> value="-90 days"><?php _e( 'Within Past 6 Months', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='-365 days') { ?> selected<?php } ?> value="-365 days"><?php _e( 'Within Past Year', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='all') { ?> selected<?php } ?> value="all"><?php _e( 'All Time', IT_TEXTDOMAIN ); ?></option>
			</select>
		</p>
        
        <p>
			<?php _e( 'Rank By:',IT_TEXTDOMAIN); ?>
        </p>
        
        <p>          
            <input class="radio" type="radio" <?php if($instance['orderby']==IT_META_TOTAL_SCORE_NORMALIZED) { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'orderby' ); ?>" value="<?php echo IT_META_TOTAL_SCORE_NORMALIZED; ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>_editor" />                
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>_editor"><?php _e( 'Editor Rating',IT_TEXTDOMAIN); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;   
            <input class="radio" type="radio" <?php if($instance['orderby']==IT_META_TOTAL_USER_SCORE_NORMALIZED) { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'orderby' ); ?>" value="<?php echo IT_META_TOTAL_USER_SCORE_NORMALIZED; ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>_user" />
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>_user"><?php _e( 'User Rating',IT_TEXTDOMAIN); ?></label>              
        </p>		
	
		<p>                
			<?php _e( 'Display',IT_TEXTDOMAIN); ?>
			<input id="<?php echo $this->get_field_id( 'numarticles' ); ?>" name="<?php echo $this->get_field_name( 'numarticles' ); ?>" value="<?php echo $instance['numarticles']; ?>" style="width:30px" />  
			<?php _e( 'reviews',IT_TEXTDOMAIN); ?>
		</p>
		
		<?php
	}
}
?>