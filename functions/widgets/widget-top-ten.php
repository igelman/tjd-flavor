<?php
class it_top_ten extends WP_Widget {
	function it_top_ten() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Top 10', 'description' => __( 'Widget version of the Top 10 slider.',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'it_top_ten' );
		/* Create the widget. */
		$this->WP_Widget( 'it_top_ten', 'Top 10', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {
		
		global $itMinisites, $wp, $post;
		#get the current query to pass it to the ajax functions through the html data tag
		#don't want this if we're viewing a single post
		if(!is_single()) $current_query = $wp->query_vars;	

		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$filter_liked = $instance['filter_liked'];
		$filter_viewed = $instance['filter_viewed'];
		$filter_reviewed = $instance['filter_reviewed'];
		$filter_rated = $instance['filter_rated'];
		$filter_commented = $instance['filter_commented'];
		$timeperiod = $instance['timeperiod'];
		$timeperiod_label = it_timeperiod_label($timeperiod);
		if(empty($timeperiod_label)) $timeperiod_label = 'This Month';
		$topten_label = it_get_setting("topten_label");
		$topten_label = ( !empty( $topten_label ) ) ? $topten_label : __('TOP 10', IT_TEXTDOMAIN);
		$topten_label .= ' ' . $timeperiod_label;
		if(empty($title)) $title = $topten_label;
		
		#setup the query            
        $args=array('posts_per_page' => 10, 'order' => 'DESC', 'meta_key' => IT_META_TOTAL_VIEWS, 'orderby' => 'meta_value_num');
		
		#setup loop format
		$format = array('loop' => 'top ten', 'location' => 'top ten', 'metric' => 'viewed', 'thumbnail' => false, 'rating' => false, 'icon' => false);	
		
		#determine if we are on a minisite page
		$current_query = array();
		$minisite = it_get_minisite($post->ID);
		if($minisite) {	
			#add post type to query args
			if($minisite->topten_targeted) {
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
		echo "<div class='topten-articles' data-currentquery='" . $current_query_encoded . "'>";
		echo '<div class="header"><h3>'.$title.'</h3></div>';		   
        echo '<div class="filterbar">';
		echo '<div class="sort-buttons" data-loop="top ten" data-location="widget" data-timeperiod="'.$timeperiod.'">';
		$active_shown=false;
		if($filter_viewed) {
			echo '<a data-sorter="viewed" class="icon-viewed viewed info';
			if(!$active_shown) echo ' active';
			echo '" title="' . __('most views', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_liked) {
			echo '<a data-sorter="liked" class="icon-liked liked info';
			if(!$active_shown) echo ' active';
			echo '" title="' . __('most likes', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_reviewed) {
			echo '<a data-sorter="reviewed" class="icon-reviewed rated info';
			if(!$active_shown) echo ' active';
			echo '" title="' . __('best reviewed', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_rated) {
			echo '<a data-sorter="users" class="icon-users info';
			if(!$active_shown) echo ' active';
			echo '" title="' . __('highest rated', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		if($filter_commented) {
			echo '<a data-sorter="commented" class="icon-commented commented info';
			if(!$active_shown) echo ' active';
			echo '" title="' . __('most commented', IT_TEXTDOMAIN) . '"></a>';
			$active_shown=true;
		}
		
		echo '</div>';
		echo '<br class="clearer" />';
		echo '</div>';
		
		echo '<div class="loading"><div>&nbsp;</div></div>';	
		
		echo '<div class="post-list">';	
        
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
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['filter_liked'] = isset( $new_instance['filter_liked'] );
		$instance['filter_viewed'] = isset( $new_instance['filter_viewed'] );
		$instance['filter_reviewed'] = isset( $new_instance['filter_reviewed'] );
		$instance['filter_rated'] = isset( $new_instance['filter_rated'] );
		$instance['filter_commented'] = isset( $new_instance['filter_commented'] );
		$instance['timeperiod'] = strip_tags( $new_instance['timeperiod'] );	
		
		return $instance;
		
	}
	function form( $instance ) {
		
		global $itMinisites;	

		#set up some default widget settings.
		$defaults = array( 'filter_liked' => true, 'filter_viewed' => true, 'filter_reviewed' => true, 'filter_rated' => true, 'filter_commented' => true, 'timeperiod' => 'This Year' );		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>	
        
        <p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:',IT_TEXTDOMAIN); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:160px" />
            <span style="font-style:italic;font-size:11px;color:#888;"><?php _e( 'Automatically generated if left blank',IT_TEXTDOMAIN); ?></span>
		</p>
		
		<p><?php _e( 'Filtering Options:',IT_TEXTDOMAIN); ?></p>	
        
        <div style="margin-left:10px;">  
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_liked']) ? $instance['filter_liked'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_liked'); ?>" id="<?php echo $this->get_field_id('filter_liked'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_liked'); ?>"><?php _e('Liked',IT_TEXTDOMAIN); ?></label>
            </p> 
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_viewed']) ? $instance['filter_viewed'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_viewed'); ?>" id="<?php echo $this->get_field_id('filter_viewed'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_viewed'); ?>"><?php _e('Viewed',IT_TEXTDOMAIN); ?></label>
            </p> 
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_reviewed']) ? $instance['filter_reviewed'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_reviewed'); ?>" id="<?php echo $this->get_field_id('filter_reviewed'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_reviewed'); ?>"><?php _e('Reviewed',IT_TEXTDOMAIN); ?></label>
            </p> 
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_rated']) ? $instance['filter_rated'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_rated'); ?>" id="<?php echo $this->get_field_id('filter_rated'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_rated'); ?>"><?php _e('Rated',IT_TEXTDOMAIN); ?></label>
            </p> 
            <p>                   
                <input class="checkbox" type="checkbox" <?php checked(isset( $instance['filter_commented']) ? $instance['filter_commented'] : 0  ); ?> name="<?php echo $this->get_field_name('filter_commented'); ?>" id="<?php echo $this->get_field_id('filter_commented'); ?>" />
                <label for="<?php echo $this->get_field_id('filter_commented'); ?>"><?php _e('Commented',IT_TEXTDOMAIN); ?></label>
            </p>             
        </div>
        
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
                <option<?php if($instance['timeperiod']=='-180 days') { ?> selected<?php } ?> value="-180 days"><?php _e( 'Within Past 6 Months', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='-365 days') { ?> selected<?php } ?> value="-365 days"><?php _e( 'Within Past Year', IT_TEXTDOMAIN ); ?></option>
                <option<?php if($instance['timeperiod']=='all') { ?> selected<?php } ?> value="all"><?php _e( 'All Time', IT_TEXTDOMAIN ); ?></option>
			</select>
		</p>
		
		<?php
	}
}
?>