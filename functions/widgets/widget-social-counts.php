<?php
class it_social_counts extends WP_Widget {
	function it_social_counts() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Social Counts', 'description' => __( 'Displays social counts for the most popular social networks.',IT_TEXTDOMAIN) );
		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'it_social_counts' );
		/* Create the widget. */
		$this->WP_Widget( 'it_social_counts', 'Social Counts', $widget_ops, $control_ops );
	}	
	function widget( $args, $instance ) {

		extract( $args );

		/* User-selected settings. */
	
		$twitter = $instance['twitter'];
		$facebook = $instance['facebook'];
		$gplus = $instance['gplus'];
		$youtube = $instance['youtube'];
		    
        #Before widget (defined by themes)
        echo $before_widget;
		        
        #HTML output
		?>
        
        <div class="social-counts">
        
			<?php if($twitter) { ?>
                        
                <div class="panel">	
                
                    <span class="icon-twitter"></span>
                    
                    <?php $followers = twitterCounts(it_get_setting('twitter_username')); ?>
                    
                    <a target="_blank" href="https://twitter.com/<?php echo it_get_setting('twitter_username'); ?>" class="info" data-placement="bottom" title="<?php _e('Twitter Followers', IT_TEXTDOMAIN); ?>"><?php echo $followers; ?></a>
                
                </div>
                
            <?php } ?>
            
            <?php if($facebook) { ?>
            
                <div class="panel">	
                
                    <span class="icon-facebook"></span>
                    
                    <?php $fb_id = basename(it_get_setting('facebook_url'));
                    $query = 'http://graph.facebook.com/'.$fb_id;
                    $result = feedReader($query, "json");
					$likes = 0;
					if(is_array($result)) {
						if(array_key_exists('likes',$result)) {
                    		$likes = $result["likes"];
						}
					}
                    ?>
                    
                    <a target="_blank" href="<?php echo it_get_setting('facebook_url'); ?>" class="info" data-placement="bottom" title="<?php _e('Facebook Fans', IT_TEXTDOMAIN); ?>"><?php echo $likes; ?></a>
                
                </div>
                
            <?php } ?>
            
            <?php if($gplus) { ?>
            
                <div class="panel">	
                
                    <span class="icon-googleplus"></span>
                    
                    <?php 
                    $url = it_get_setting('googleplus_url');
                    
                    $ch = curl_init();  
                    curl_setopt($ch, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                    
                    $curl_results = curl_exec ($ch);
                    curl_close ($ch);
                    
                    $parsed_results = json_decode($curl_results, true);
                    
                    $gplus = $parsed_results[0]['result']['metadata']['globalCounts']['count'];
                    ?>
                    
                    <a target="_blank" href="<?php echo it_get_setting('googleplus_profile_url'); ?>" class="info" data-placement="bottom" title="<?php _e("Google +1's", IT_TEXTDOMAIN); ?>"><?php echo $gplus; ?></a>
                
                </div>
                
            <?php } ?>
            
            <?php if($youtube) { ?>
            
                <div class="panel">	
                
                    <span class="icon-youtube"></span>
                    
                    <?php 
                    $username = it_get_setting('youtube_username'); 
					if(empty($username)) $username = 'ScreenJunkies';                   
                    $data = file_get_contents('http://gdata.youtube.com/feeds/api/users/' . strtolower($username)); 
					$xml = new SimpleXMLElement($data);
					$stats_data = (array)$xml->children('yt', true)->statistics->attributes();
					$stats_data = $stats_data['@attributes'];
					$subs = $stats_data['subscriberCount'];
                    ?>
                    
                    <a target="_blank" href="http://www.youtube.com/<?php echo it_get_setting('youtube_username'); ?>" class="info" data-placement="bottom" title="<?php _e("Youtube Subscribers", IT_TEXTDOMAIN); ?>"><?php echo $subs; ?></a>
                
                </div>
                
            <?php } ?>
            
            <br class="clearer" />
            
        </div>
					
        <?php 		
		wp_reset_query();	
		
		# After widget (defined by themes)
        echo $after_widget; ?>		
		
	<?php
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['twitter'] = isset( $new_instance['twitter'] );
		$instance['facebook'] = isset( $new_instance['facebook'] );
		$instance['gplus'] = isset( $new_instance['gplus'] );		
		$instance['youtube'] = isset( $new_instance['youtube'] );

		return $instance;
	}
	function form( $instance ) {	

		/* Set up some default widget settings. */
		$defaults = array( 'twitter' => true, 'facebook' => true, 'gplus' => true, 'youtube' => false );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['twitter']) ? $instance['twitter'] : 0  ); ?> id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Display Twitter follower count', IT_TEXTDOMAIN); ?> </label>
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['facebook']) ? $instance['facebook'] : 0  ); ?> id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Display Facebook fan count', IT_TEXTDOMAIN); ?> </label>
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['gplus']) ? $instance['gplus'] : 0  ); ?> id="<?php echo $this->get_field_id( 'gplus' ); ?>" name="<?php echo $this->get_field_name( 'gplus' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'gplus' ); ?>"><?php _e( 'Display Google +1 count', IT_TEXTDOMAIN); ?> </label>
		</p>
        
        <p>
			<input class="checkbox" type="checkbox" <?php checked(isset( $instance['youtube']) ? $instance['youtube'] : 0  ); ?> id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Display Youtube subscriber count', IT_TEXTDOMAIN); ?> </label>
		</p>
		
		<?php
	}
}
?>