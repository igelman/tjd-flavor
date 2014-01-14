<?php
/**
 *
 */
class itHero {
	
	/**
	 *
	 */
	public static function hero( $atts = null, $content = null ) {
		if( $atts == 'generator' ) {			
			$option = array( 
				'name' => __( 'Hero Unit', IT_TEXTDOMAIN ),
				'value' => 'hero',
				'options' => array(
				
					array(
						'name' => __( 'Heading', IT_TEXTDOMAIN ),
						'desc' => __( 'This is the main heading of the hero unit', IT_TEXTDOMAIN ),
						'id' => 'heading',
						'default' => '',
						'type' => 'text'
					),
					array(
						'name' => __( 'Tagline', IT_TEXTDOMAIN ),
						'desc' => __( 'This is the tagline of the hero unit', IT_TEXTDOMAIN ),
						'id' => 'tagline',
						'default' => '',
						'type' => 'text',
					),
					array(
						'name' => __( 'Content', IT_TEXTDOMAIN ),
						'desc' => __( 'Any additional content such as a call to action button that needs to display under the header and tagline.', IT_TEXTDOMAIN ),
						'id' => 'content',
						'default' => '',
						'type' => 'textarea'
					),
					
				'shortcode_has_atts' => true,
				)
			);
		
			return $option;
		}
			
		extract(shortcode_atts(array(
			'heading'      => '',
		    'tagline'      => '',
	    ), $atts));

		$heading = ( $heading ) ? '<h1>'.$heading.'</h1>' : '';
		
		$tagline = ( $tagline ) ? '<p>'.$tagline.'</p>' : '';
		
		$content = ( $content ) ? '<p><small>'.do_shortcode($content).'</small></p>' : '';
		
		$out = '<div class="hero-unit">'.$heading.' '.$tagline.' '.$content.'</div>';

	    return $out;
	}
	
	public static function _options( $class ) {
		$shortcode = array();
		
		$class_methods = get_class_methods( $class );
		
		foreach( $class_methods as $method ) {
			if( $method[0] != '_' )
				$shortcode[] = call_user_func(array( &$class, $method ), $atts = 'generator' );
		}
		
		$options = array(
			'name' => __( 'Hero Unit', IT_TEXTDOMAIN ),
			'value' => 'hero',
			'options' => $shortcode
		);
		
		return $options;
	}
	
}

?>
