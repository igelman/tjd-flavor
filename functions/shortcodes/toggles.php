<?php
/**
 *
 */
class itToggles {
	
	/**
	 *
	 */
	public static function toggles( $atts = null, $content = null, $code = null ) {
		if( $atts == 'generator' ) {
			$numbers = range(1,100);

			$option = array( 
				'name' => __( 'Toggle', IT_TEXTDOMAIN ),
				'value' => 'toggles',
				'options' => array(					
					array(
						'name' => __( 'Behavior', IT_TEXTDOMAIN ),
						'desc' => __( 'Should the toggles be individual or grouped together as an accordion style.', IT_TEXTDOMAIN ),
						'id' => 'behavior',
						'default' => '',
						'options' => array(
							'toggle' => __('Toggle', IT_TEXTDOMAIN ),
							'accordion' => __('Accordion', IT_TEXTDOMAIN ),
						),
						'type' => 'select',
						'shortcode_dont_multiply' => true
					),
					array(
						'name' => __( 'Number of toggles', IT_TEXTDOMAIN ),
						'desc' => __( 'Select the number of toggles you wish to display.', IT_TEXTDOMAIN ),
						'id' => 'multiply',
						'default' => '',
						'options' => $numbers,
						'type' => 'select',
						'shortcode_multiplier' => true
					),
					array(
						'name' => __( 'Toggle 1 Title', IT_TEXTDOMAIN ),
						'desc' => __( 'The text to use for the toggle selector', IT_TEXTDOMAIN ),
						'id' => 'title',
						'default' => '',
						'type' => 'text',
						'shortcode_multiply' => true
					),
					array(
						'name' => __( 'Toggle 1 Content', IT_TEXTDOMAIN ),
						'desc' => __( 'The content of the toggle container. Shortcodes are accepted.', IT_TEXTDOMAIN ),
						'id' => 'content',
						'default' => '',
						'type' => 'textarea',
						'shortcode_multiply' => true
					),
					array(
						'name' => __( 'Toggle 1 Default State', IT_TEXTDOMAIN ),
						'desc' => __( 'Should this toggle be expanded or collapsed by default.', IT_TEXTDOMAIN ),
						'id' => 'expanded',
						'default' => '',
						'options' => array(
							'' => __('Collapsed', IT_TEXTDOMAIN ),
							'in' => __('Expanded', IT_TEXTDOMAIN ),
						),
						'type' => 'select',
						'shortcode_multiply' => true
					),
					array(
						'value' => 'toggle',
						'nested' => true
					),
				'shortcode_has_atts' => true,
				)
			);

			return $option;
		}
		
		extract(shortcode_atts(array(
			'behavior'      => 'toggle',
	    ), $atts));
		
		if (!preg_match_all("/(.?)\[(toggle)\b(.*?)(?:(\/))?\](?:(.+?)\[\/toggle\])?(.?)/s", $content, $matches)) {
			return it_remove_wpautop( $content );
		} else {
			
			for($i = 0; $i < count($matches[0]); $i++) {
				$matches[3][$i] = shortcode_parse_atts( $matches[3][$i] );
			}
						
			$id = rand();			
			$out = '';
			
			if($behavior=='accordion') {	
			
				$accordionid = ' id="accordion_'.$id.'"';
				
				$parent = ' data-parent="#accordion_'.$id.'"';	
				
			}
								
			$out .= '<div class="accordion"'.$accordionid.'>';
			
			for($i = 0; $i < count($matches[0]); $i++) {
				
				if($behavior=='accordion') {
						
					$collapseid = 'collapse_'.$i.'_'.$id;
					
				} else {
					
					$collapseid = 'toggle_'.$i.'_'.$id;	
					
				}
				
				$out .= '<div class="accordion-group">';
				
					$out .= '<div class="accordion-heading">';
				
						$out .= '<a class="accordion-toggle" data-toggle="collapse"'.$parent.' href="#'.$collapseid.'">' . $matches[3][$i]['title'] . '</a>';
					
					$out .= '</div>';
					
					$out .= '<div id="'.$collapseid.'" class="accordion-body collapse '.$matches[3][$i]['expanded'].'">';
					
						$out .= '<div class="accordion-inner">' . it_remove_wpautop( $matches[5][$i] ) . '</div>';
					
					$out .= '</div>';
				
				$out .= '</div>';
			}
			
			$out .= '</div>';			
			
			return $out;
		}
		
	}
		
	/**
	 *
	 */
	public static function _options($class) {
		$shortcode = array();
		
		$class_methods = get_class_methods( $class );
		
		foreach( $class_methods as $method ) {
			if( $method[0] != '_' )
				$shortcode[] = call_user_func(array( &$class, $method ), $atts = 'generator' );
		}
		
		$options = array(
			'name' => __( 'Toggles & Accordions', IT_TEXTDOMAIN ),
			'value' => 'toggles',
			'options' => $shortcode,
		);
		
		return $options;
	}
	
}

?>
