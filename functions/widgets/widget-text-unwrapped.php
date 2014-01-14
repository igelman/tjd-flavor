<?php
class it_text_unwrapped extends WP_Widget {
	function it_text_unwrapped() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Text (Unwrapped)', 'description' => __( 'Same as the Text widget except without a title or content wrapper.',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'it_text_unwrapped' );
		/* Create the widget. */
		$this->WP_Widget( 'it_text_unwrapped', 'Text (Unwrapped)', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {

		extract( $args );
		
        #HTML output
		
		$text = $instance['text'];	
		
		#show the widget content without any header or wrapper markup
		echo '<div class="widget-unwrapped">'.do_shortcode($text).'</div>';
	
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['text'] = $new_instance['text'];		

		return $instance;
	}
	function form( $instance ) {	

		/* Set up some default widget settings. */
		$defaults = array( 'text' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text/HTML:',IT_TEXTDOMAIN); ?></label>
			<textarea rows="16" cols="20" class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $instance['text']; ?></textarea>
		</p>
		
		<?php
	}
}
?>