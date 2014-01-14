<?php
class it_social_tabs extends WP_Widget {
	function it_social_tabs() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Social Tabs', 'description' => __( 'Displays Latest Tweets, Facebook Like Box, Flickr Photos, and Recent Comments',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 290, 'height' => 350, 'id_base' => 'it_social_tabs' );
		/* Create the widget. */
		$this->WP_Widget( 'it_social_tabs', 'Social Tabs', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {
		
		global $itMinisites;		

		extract( $args );

		/* User-selected settings. */
		$order = $instance['order'];
		$twitter = $instance['twitter'];
		$numtweets = $instance['numtweets'];
		$facebook = $instance['facebook'];
		$pinterest = $instance['pinterest'];
		$flickr = $instance['flickr'];
		$numphotos = $instance['numphotos'];
		$comments = $instance['comments'];
		$numcomments = $instance['numcomments'];
		    
        #Before widget (defined by themes)
        echo $before_widget;

        #HTML output
		echo '<div class="it-widget-tabs it-social-tabs">';	
		echo '<ul class="sort-buttons">';
		
		if($twitter && ($order=='twitter' || empty($order))) {
			$twitter_shown=true;
			echo '<li><a href="#twitter-social-tab" class="info active icon-twitter" title="' . __('Twitter', IT_TEXTDOMAIN) . '"></a></li>';
		}		
		if($pinterest && $order=='pinterest') {
			$pinterest_shown=true;
			echo '<li><a href="#pinterest-social-tab" class="info active icon-pinterest" title="' . __('Pinterest', IT_TEXTDOMAIN) . '"></a></li>';
		}
		if($facebook && $order=='facebook') {
			$facebook_shown=true;
			echo '<li><a href="#facebook-social-tab" class="info icon-facebook" title="' . __('Facebook', IT_TEXTDOMAIN) . '"></a></li>';
		}		
		if($flickr && $order=='flickr') {
			$flickr_shown=true;
			echo '<li><a href="#flickr-social-tab" class="info icon-flickr" title="' . __('Flickr', IT_TEXTDOMAIN) . '"></a></li>';
		}
		if($comments && $order=='comments') {
			$comments_shown=true;
			echo '<li><a href="#comments-social-tab" class="info icon-commented" title="' . __('Recent Comments', IT_TEXTDOMAIN) . '"></a></li>';
		}
		if($twitter && !$twitter_shown) echo '<li><a href="#twitter-social-tab" class="info active icon-twitter" title="' . __('Twitter', IT_TEXTDOMAIN) . '"></a></li>';
		if($pinterest && !$pinterest_shown) echo '<li><a href="#pinterest-social-tab" class="info icon-pinterest" title="' . __('Pinterest', IT_TEXTDOMAIN) . '"></a></li>';
		if($facebook && !$facebook_shown) echo '<li><a href="#facebook-social-tab" class="info icon-facebook" title="' . __('Facebook', IT_TEXTDOMAIN) . '"></a></li>';
		
		if($flickr && !$flickr_shown) echo '<li><a href="#flickr-social-tab" class="info icon-flickr" title="' . __('Flickr', IT_TEXTDOMAIN) . '"></a></li>';
		if($comments && !$comments_shown) echo '<li><a href="#comments-social-tab" class="info icon-commented" title="' . __('Recent Comments', IT_TEXTDOMAIN) . '"></a></li>';
		echo '</ul>';		
		
		if($twitter) {
			echo '<div id="twitter-social-tab">';
			echo it_get_setting('twitter_widget_code');
			echo '</div>';
		}		
		if($pinterest) {
			echo '<div id="pinterest-social-tab">';
			echo '<a data-pin-do="embedUser" href="' . it_get_setting('pinterest_url') . '" data-pin-scale-height="300"></a>';
			echo '</div>';
		}
		if($facebook) {   
			$url = it_get_setting('facebook_url');
			$colorscheme = it_get_setting('facebook_color_scheme');
			$showfaces = it_get_setting('facebook_show_faces');
			if($showfaces) { 
				$showfaces = 'true';
			} else { 
				$showfaces = 'false'; 
			}
			$stream = it_get_setting('facebook_stream');
			if($stream) { 
				$stream = 'true';
			} else { 
				$stream = 'false'; 
			}			
			$header = it_get_setting('facebook_show_header');
			if($header) { 
				$header = 'true';
			} else { 
				$header = 'false'; 
			}	
			$bordercolor = it_get_setting('facebook_border_color');
			echo '<div id="facebook-social-tab">';
			echo '<div class="fb-like-box" data-href="' . $url . '" data-colorscheme="' . $colorscheme . '" data-show-faces="' . $showfaces . '" data-stream="' . $stream . '" data-header="' . $header . '" data-border-color="' . $bordercolor . '"></div>';
			echo '</div>';
		}		
		if($flickr) {
			echo '<div id="flickr-social-tab">';
			echo '<ul class="flickr"></ul><br class="clearer" /><a class="more" href="http://www.flickr.com/photos/'. it_get_setting('flickr_id') . '" target="_blank">' . __( 'View more photos', IT_TEXTDOMAIN ) . ' &raquo;</a>';            
			echo '</div>';
		}
		if($comments) {
			echo '<div id="comments-social-tab">';
			echo '<ul>';
			$args = array(
				'status' => 'approve',
				'number' => $numcomments
			);
			$comments = get_comments($args);
			foreach($comments as $comment) :								
				$commentcontent = strip_tags($comment->comment_content);			
				if (mb_strlen($commentcontent)>110) {
					$commentcontent = mb_substr($commentcontent, 0, 107) . "...";
				}
				$commentauthor = $comment->comment_author;
				if (mb_strlen($commentauthor)>50) {
					$commentauthor = mb_substr($commentauthor, 0, 47) . "...";			
				}
				$commentid = $comment->comment_ID;
				$commenturl = get_comment_link($commentid);
				echo '<li><a href="' . $commenturl . '">"' . $commentcontent . '"<span> -&nbsp;' . $commentauthor . '</span></a></li>';
			endforeach;
			echo '</ul>';
			echo '</div>';
		}
		echo '</div>'; #end it-widget-tabs div	
		
		wp_reset_query();			
        
		# After widget (defined by themes)
        echo $after_widget; ?>		
		
	<?php
	}
	function update( $new_instance, $old_instance ) {
			
		$instance = $old_instance;		
		
		$instance['order'] = strip_tags( $new_instance['order'] );
		$instance['twitter'] = isset( $new_instance['twitter'] );
		$instance['numtweets'] = strip_tags( $new_instance['numtweets'] );
		$instance['facebook'] = isset( $new_instance['facebook'] );
		$instance['pinterest'] = isset( $new_instance['pinterest'] );
		$instance['flickr'] = isset( $new_instance['flickr'] );
		$instance['numphotos'] = strip_tags( $new_instance['numphotos'] );		
		$instance['comments'] = isset( $new_instance['comments'] );
		$instance['numcomments'] = strip_tags( $new_instance['numcomments'] );	
		
		return $instance;
		
	}
	function form( $instance ) {
		
		global $itMinisites;	

		#set up some default widget settings.
		$defaults = array( 'numtweets' => 5, 'twitter' => true, 'facebook' => true, 'pinterest' => true, 'flickr' => true, 'numphotos' => 9, 'comments' => true, 'numcomments' => 5);
		#add minisite checkboxes to default array		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['twitter']) ? $instance['twitter'] : 0  ); ?> id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Display ', IT_TEXTDOMAIN); ?> <input id="<?php echo $this->get_field_id( 'numtweets' ); ?>" name="<?php echo $this->get_field_name( 'numtweets' ); ?>" value="<?php echo $instance['numtweets']; ?>" style="width:30px" /> <?php _e( ' Latest Tweets',IT_TEXTDOMAIN); ?></label>&nbsp;->&nbsp;
            <input class="radio" type="radio" <?php if($instance['twitter']=='comments') { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'order' ); ?>" value="twitter" id="<?php echo $this->get_field_id( 'order' ); ?>_twitter" />                
            <label for="<?php echo $this->get_field_id( 'order' ); ?>_twitter"> <?php _e( ' as first tab',IT_TEXTDOMAIN); ?></label>           
		</p>		
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['facebook']) ? $instance['facebook'] : 0  ); ?> id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Display Facebook Like Box',IT_TEXTDOMAIN); ?></label>&nbsp;->&nbsp;   
            <input class="radio" type="radio" <?php if($instance['order']=='facebook') { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'order' ); ?>" value="facebook" id="<?php echo $this->get_field_id( 'order' ); ?>_facebook" />                
            <label for="<?php echo $this->get_field_id( 'order' ); ?>_facebook"> <?php _e( ' as first tab',IT_TEXTDOMAIN); ?></label>          
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['pinterest']) ? $instance['pinterest'] : 0  ); ?> id="<?php echo $this->get_field_id( 'pinterest' ); ?>" name="<?php echo $this->get_field_name( 'pinterest' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'pinterest' ); ?>"><?php _e( 'Display Pinterest Profile Box',IT_TEXTDOMAIN); ?></label>&nbsp;->&nbsp;  
            <input class="radio" type="radio" <?php if($instance['order']=='pinterest') { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'order' ); ?>" value="pinterest" id="<?php echo $this->get_field_id( 'order' ); ?>_pinterest" />                
            <label for="<?php echo $this->get_field_id( 'order' ); ?>_pinterest"> <?php _e( ' as first tab',IT_TEXTDOMAIN); ?></label>              
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['flickr']) ? $instance['flickr'] : 0  ); ?> id="<?php echo $this->get_field_id( 'flickr' ); ?>" name="<?php echo $this->get_field_name( 'flickr' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'flickr' ); ?>"><?php _e( 'Display ', IT_TEXTDOMAIN); ?> <input id="<?php echo $this->get_field_id( 'numphotos' ); ?>" name="<?php echo $this->get_field_name( 'numphotos' ); ?>" value="<?php echo $instance['numphotos']; ?>" style="width:30px" /> <?php _e( 'Flickr Photos',IT_TEXTDOMAIN); ?></label>&nbsp;->&nbsp;   
            <input class="radio" type="radio" <?php if($instance['order']=='flickr') { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'order' ); ?>" value="flickr" id="<?php echo $this->get_field_id( 'order' ); ?>_flickr" />                
            <label for="<?php echo $this->get_field_id( 'order' ); ?>_flickr"> <?php _e( ' as first tab',IT_TEXTDOMAIN); ?></label>              
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['comments']) ? $instance['comments'] : 0  ); ?> id="<?php echo $this->get_field_id( 'comments' ); ?>" name="<?php echo $this->get_field_name( 'comments' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'comments' ); ?>"><?php _e( 'Display ', IT_TEXTDOMAIN); ?> <input id="<?php echo $this->get_field_id( 'numcomments' ); ?>" name="<?php echo $this->get_field_name( 'numcomments' ); ?>" value="<?php echo $instance['numcomments']; ?>" style="width:30px" /> <?php _e( ' Recent Comments',IT_TEXTDOMAIN); ?></label>&nbsp;->&nbsp;
            <input class="radio" type="radio" <?php if($instance['order']=='comments') { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'order' ); ?>" value="comments" id="<?php echo $this->get_field_id( 'order' ); ?>_comments" />                
            <label for="<?php echo $this->get_field_id( 'order' ); ?>_comments"> <?php _e( ' as first tab',IT_TEXTDOMAIN); ?></label>                     
		</p>
		
		<?php
	}
}
?>