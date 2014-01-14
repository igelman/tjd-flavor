<?php
$meta_boxes = array(
	'title' => sprintf( __( 'Layout Options', IT_TEXTDOMAIN ), THEME_NAME ),
	'id' => 'it_page_meta_box',
	'pages' => array( 'page' ),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __( 'Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'You can choose a left, right, or full layout for this specific page', IT_TEXTDOMAIN ),
			'id' => '_layout',
			'options' => array(
				'sidebar-right' => THEME_ADMIN_ASSETS_URI . '/images/footer_g.png',
				'sidebar-left' => THEME_ADMIN_ASSETS_URI . '/images/footer_k.png',
				'full' => THEME_ADMIN_ASSETS_URI . '/images/footer_a.png',	
			),
			'type' => 'layout'
		),
		array(
			'name' => __( 'Featured Image Size', IT_TEXTDOMAIN ),
			'desc' => __( 'You can set the featured image size for this specific page', IT_TEXTDOMAIN ),
			'id' => '_featured_image_size',
			'options' => array(
				'none' => THEME_ADMIN_ASSETS_URI . '/images/image_none.png',
				'180' => THEME_ADMIN_ASSETS_URI . '/images/image_small.png',
				'360' => THEME_ADMIN_ASSETS_URI . '/images/image_medium.png',
				'790' => THEME_ADMIN_ASSETS_URI . '/images/image_large.png',
			),
			'type' => 'layout'
		),
		array(
			'name' => __( 'Disable Title', IT_TEXTDOMAIN ),
			'id' => IT_META_DISABLE_TITLE,
			'options' => array( 'true' => __( 'Do not display the main page title for this page.', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Custom Sidebar', IT_TEXTDOMAIN ),
			'desc' => __( "Select the custom sidebar that you'd like to be displayed on this page. Note: You will need to first create a custom sidebar under the Sidebars tab in your theme's option panel before it will show up here.", IT_TEXTDOMAIN ),
			'id' => '_custom_sidebar',
			'target' => 'custom_sidebars',
			'type' => 'select'
		),
		array(
			'name' => __( 'Front Page For A Minisite', IT_TEXTDOMAIN ),
			'desc' => __( "This page will be used as the selected minisite's front page. It is not necessary to enter any content for this page - just a title. You can only have one front page for each minisite.", IT_TEXTDOMAIN ),
			'id' => '_select_minisite',
			'target' => 'minisites',
			'type' => 'select'
		),
		array(
			'name' => __( 'Subtitle', IT_TEXTDOMAIN ),
			'desc' => __( 'You can specify a subtitle for this page which will display in the container bar above the page title and content.', IT_TEXTDOMAIN ),
			'id' => '_subtitle',
			'type' => 'text'
		),
		array(
			'name' => __( 'Background Color', IT_TEXTDOMAIN ),
			'desc' => __( 'Use a specific background color for this page', IT_TEXTDOMAIN ),
			'id' => '_bg_color',
			'type' => 'color'
		),
		array(
			'name' => __( 'Override Site Background', IT_TEXTDOMAIN ),
			'desc' => __( 'This is useful if you have an image as your main site background but you want this color to show instead for this page', IT_TEXTDOMAIN ),
			'id' => '_bg_color_override',
			'options' => array( 'true' => __( 'Display this color instead of your main site background image', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Background Image', IT_TEXTDOMAIN ),
			'desc' => __( 'Use an image for the background of this specific page', IT_TEXTDOMAIN ),
			'id' => '_bg_image',
			'type' => 'upload'
		),	
		array(
			'name' => __( 'Background Position', IT_TEXTDOMAIN ),
			'id' => '_bg_position',
			'options' => array( 
				'' => __( 'Not Set (use value from theme options)', IT_TEXTDOMAIN),
				'left' => __( 'Left', IT_TEXTDOMAIN ),
				'center' => __( 'Center', IT_TEXTDOMAIN ),
				'right' => __( 'Right', IT_TEXTDOMAIN )
			),
			'default' => 'center',
			'type' => 'radio'
		),		
		array(
			'name' => __( 'Background Repeat', IT_TEXTDOMAIN ),
			'id' => '_bg_repeat',
			'options' => array( 
				'' => __( 'Not Set (use value from theme options)', IT_TEXTDOMAIN),
				'no-repeat' => __( 'No Repeat', IT_TEXTDOMAIN ),
				'repeat' => __( 'Tile', IT_TEXTDOMAIN ),
				'repeat-x' => __( 'Tile Horizontally', IT_TEXTDOMAIN ),
				'repeat-y' => __( 'Tile Vertically', IT_TEXTDOMAIN )
			),
			'default' => 'no-repeat',
			'type' => 'radio'
		),	
		array(
			'name' => __( 'Background Attachment', IT_TEXTDOMAIN ),
			'id' => '_bg_attachment',
			'options' => array( 
				'' => __( 'Not Set (use value from theme options)', IT_TEXTDOMAIN),
				'scroll' => __( 'Scroll', IT_TEXTDOMAIN ),
				'fixed' => __( 'Fixed', IT_TEXTDOMAIN )
			),
			'default' => 'scroll',
			'type' => 'radio'
		),		
		array(
			'name' => __( 'Background Container Overlay', IT_TEXTDOMAIN ),
			'desc' => __( 'This is useful if you have a busy background image or pattern', IT_TEXTDOMAIN ),
			'id' => '_bg_overlay',
			'options' => array( 'true' => __( 'Display transparent overlay container around main theme wrapper', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		)
	)
);
return array(
	'load' => true,
	'options' => $meta_boxes
);

?>
