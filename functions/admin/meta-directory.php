<?php
$meta_boxes = array(
	'title' => sprintf( __( 'Minisite Directory Options', IT_TEXTDOMAIN ), THEME_NAME ),
	'id' => 'it_page_directory',
	'pages' => array( 'page' ),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(		
		array(
			'name' => __( 'Included Minisites', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose which minisites should be included in this review directory page.', IT_TEXTDOMAIN ),
			'id' => '_directory_minisites',
			'target' => 'minisites',
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'List Type', IT_TEXTDOMAIN ),
			'id' => '_directory_type',
			'options' => array( 
				'merged' => __( 'Merged into one list', IT_TEXTDOMAIN ),
				'separated' => __( 'Separated into multiple lists', IT_TEXTDOMAIN ),
			),
			'default' => 'merged',
			'type' => 'radio'
		),
		array(
			'name' => __( 'List Style', IT_TEXTDOMAIN ),
			'id' => '_directory_style',
			'options' => array( 
				'directory' => __( 'Compact List', IT_TEXTDOMAIN ),
				'directory compact' => __( 'Super Compact List', IT_TEXTDOMAIN ),
				'main loop' => __( 'Standard Post Grid', IT_TEXTDOMAIN ),
				'main loop list' => __( 'Standard Post List', IT_TEXTDOMAIN ),
			),
			'default' => 'directory',
			'type' => 'radio'
		),
		array(
			'name' => __( 'Column Width', IT_TEXTDOMAIN ),
			'desc' => __( 'Only applies to compact and super compact list styles.', IT_TEXTDOMAIN ),
			'id' => '_directory_layout',
			'options' => array( 
				'wide' => __( 'Wide (2 columns, 3 for full-width)', IT_TEXTDOMAIN ),
				'narrow' => __( 'Narrow (3 columns, 4 for full-width)', IT_TEXTDOMAIN ),
			),
			'default' => 'narrow',
			'type' => 'radio'
		),
		array(
			'name' => __( 'Order By', IT_TEXTDOMAIN ),
			'desc' => __( 'Only applies to separated list type.', IT_TEXTDOMAIN ),
			'id' => '_directory_sort',
			'options' => array( 
				'recent' => __( 'Recent', IT_TEXTDOMAIN ),
				'title' => __( 'Alphabetical', IT_TEXTDOMAIN ),
				'liked' => __( 'Most Liked', IT_TEXTDOMAIN ),
				'viewed' => __( 'Most Viewed', IT_TEXTDOMAIN ),
				'users' => __( 'Highest User Rated', IT_TEXTDOMAIN ),
				'reviewed' => __( 'Highest Editor Reviewed', IT_TEXTDOMAIN ),
				'commented' => __( 'Most Commented', IT_TEXTDOMAIN ),
				'awarded' => __( 'Awarded', IT_TEXTDOMAIN ),
			),
			'default' => 'recent',
			'type' => 'radio'
		),
		array(
			'name' => __( 'Only Display Reviews', IT_TEXTDOMAIN ),
			'id' => '_directory_reviews',
			'options' => array( 'true' => __( 'Hide posts that do not have ratings associated', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Number of Posts', IT_TEXTDOMAIN ),
			'desc' => __( 'When the list type is set to merged, this is the number of posts per page. When the list type is set to separated, this is the total number of posts to display for each minisite.', IT_TEXTDOMAIN ),
			'id' => '_directory_number',
			'target' => 'minisite_num',
			'nodisable' => true,
			'type' => 'select'
		),
	)
);
return array(
	'load' => true,
	'options' => $meta_boxes
);

?>
