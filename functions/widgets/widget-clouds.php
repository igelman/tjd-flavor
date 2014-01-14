<?php
class it_clouds extends WP_Widget {
	function it_clouds() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Clouds', 'description' => __( 'Displays your tags, categories, and/or minisite primary taxonomy items in a tag cloud format.',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'it_clouds' );
		/* Create the widget. */
		$this->WP_Widget( 'it_clouds', 'Clouds', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {
		
		global $itMinisites;		

		extract( $args );

		/* User-selected settings. */
		$numitems = $instance['numitems'];
		$tags = $instance['tags'];
		$categories = $instance['categories'];
		    
        #Before widget (defined by themes)
        echo $before_widget;

        #HTML output
		echo '<div class="it-widget-tabs it-clouds">';	
		echo '<ul class="sort-buttons">';
		if($tags) echo '<li><a href="#tag-cloud" class="info active icon-tag" title="' . __('Tag Cloud', IT_TEXTDOMAIN) . '"></a></li>';
		if($categories) echo '<li><a href="#category-cloud" class="info icon-category" title="' . __('Category Cloud', IT_TEXTDOMAIN) . '"></a></li>';                
        foreach($itMinisites->minisites as $minisite){
			if(is_array($instance)) {
				if(array_key_exists($minisite->id, $instance)) {
					if($minisite->enabled && $instance[$minisite->id]) {	
						$primary_taxonomy = $minisite->get_primary_taxonomy();		
						echo '<li><a href="#' . $primary_taxonomy->slug . '-cloud" class="info minisite-icon" title="' . $primary_taxonomy->name . ' ' . __('Cloud',IT_TEXTDOMAIN) . '">' . it_get_category($minisite) . '</a></li>';				
					}
				}
			}
		}
		
		echo '</ul>';
		
		if($tags) {
			echo '<div id="tag-cloud">';
			wp_tag_cloud(array('number' => $numitems, 'taxonomy' => 'post_tag', 'unit' => 'px', 'smallest' => '12', 'largest' => '27'));
			echo '</div>';
		}
       
	    if($categories) {
			echo '<div id="category-cloud">';
			wp_tag_cloud(array('number' => $numitems, 'taxonomy' => 'category', 'unit' => 'px', 'smallest' => '12', 'largest' => '27'));
			echo '</div>';
		}
		
		foreach($itMinisites->minisites as $minisite){
			if(is_array($instance)) {
				if(array_key_exists($minisite->id, $instance)) {
					if($minisite->enabled && $instance[$minisite->id]) {	
						$primary_taxonomy = $minisite->get_primary_taxonomy();	
						echo '<div id="' . $primary_taxonomy->slug . '-cloud">';
						wp_tag_cloud(array('number' => $numitems, 'taxonomy' => $primary_taxonomy->slug, 'unit' => 'px', 'smallest' => '12', 'largest' => '27'));
						echo '</div>';								
					}
				}
			}
		}
				
		echo '</div>'; #end it-widget-tabs div
		
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
		$instance['numitems'] = strip_tags( $new_instance['numitems'] );		
		$instance['tags'] = isset( $new_instance['tags'] );
		$instance['categories'] = isset( $new_instance['categories'] );	
		
		return $instance;
		
	}
	function form( $instance ) {
		
		global $itMinisites;	

		#set up some default widget settings.
		$defaults = array( 'numitems' => 50, 'tags' => true, 'categories' => true );
		#add minisite checkboxes to default array
		foreach($itMinisites->minisites as $minisite){
			if($minisite->enabled) {
				 $defaults[$minisite->id] = true;						             
			}
		}
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>	
	
		<p>                
			<?php _e( 'Display',IT_TEXTDOMAIN); ?>
			<input id="<?php echo $this->get_field_id( 'numitems' ); ?>" name="<?php echo $this->get_field_name( 'numitems' ); ?>" value="<?php echo $instance['numitems']; ?>" style="width:30px" />  
			<?php _e( 'items in each cloud',IT_TEXTDOMAIN); ?>
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['tags']) ? $instance['tags'] : 0  ); ?> id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Display Tag Cloud',IT_TEXTDOMAIN); ?></label>             
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['categories']) ? $instance['categories'] : 0  ); ?> id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Display Category Cloud',IT_TEXTDOMAIN); ?></label>             
		</p>
        
        <p><?php _e( 'Display Primary Taxonomy Cloud From:',IT_TEXTDOMAIN); ?></p>	
        
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
		
		<?php
	}
}
?>