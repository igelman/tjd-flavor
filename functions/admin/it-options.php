<?php
$option_tabs = array(
	'it_generalsettings_tab' => __( 'General Settings', IT_TEXTDOMAIN ),
	'it_style_tab' => __( 'Style', IT_TEXTDOMAIN ),		
	'it_carousel_tab' => __( 'Content Carousels', IT_TEXTDOMAIN ),
	'it_loop_tab' => __( 'Post Loop', IT_TEXTDOMAIN ),
	'it_pages_tab' => __( 'Page Layouts', IT_TEXTDOMAIN ),
	'it_posts_tab' => __( 'Single Articles', IT_TEXTDOMAIN ),
	'it_minisite_tab' => __( 'Minisite Setup', IT_TEXTDOMAIN ),
	'it_sidebar_tab' => __( 'Custom Sidebars', IT_TEXTDOMAIN ),
	'it_signoff_tab' => __( 'Signoffs', IT_TEXTDOMAIN ),
	'it_advertising_tab' => __( 'Advertising', IT_TEXTDOMAIN ),
	'it_footer_tab' => __( 'Footer', IT_TEXTDOMAIN ),
	'it_sociable_tab' => __( 'Social', IT_TEXTDOMAIN ),
	'it_advanced_tab' => __( 'Advanced', IT_TEXTDOMAIN )
);

#add woocommerce tab
if(function_exists('is_woocommerce')) {
	$option_tabs['it_woocommerce_tab'] = __( 'WooCommerce', IT_TEXTDOMAIN );
}

# add minisite tabs
$minisite = it_get_setting('minisite');

if ( isset($minisite['keys']) && $minisite['keys'] != '#' ) {
	$minisite_keys = explode(',',$minisite['keys']);
	foreach ($minisite_keys as $mkey) {
		if ( $mkey != '#') {
			$minisite_name = ( !empty( $minisite[$mkey]['name'] ) ) ? $minisite[$mkey]['name'] : '#';
			$minisite_slug = it_get_slug($minisite[$mkey]['slug'], $minisite_name);				
			$option_tabs['it_'.$minisite_slug.'_tab'] = $minisite_name . __( ' Minisite', IT_TEXTDOMAIN );
		}
	}
}

$options = array(
	
	/**
	 * Navigation
	 */
	array(
		'name' => $option_tabs,
		'type' => 'navigation'
	),
	
	/**
	 * General Settings
	 */
	array(
		'name' => array( 'it_generalsettings_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Logo Settings', IT_TEXTDOMAIN ),
			'desc' => __( 'You can choose whether you wish to display a custom logo or your site title.', IT_TEXTDOMAIN ),
			'id' => 'display_logo',
			'options' => array(
				'true' => __( 'Custom Image Logo', IT_TEXTDOMAIN ),
				'' => sprintf( __( 'Display Site Title <small><a href="%1$s/wp-admin/options-general.php" target="_blank">(click here to edit site title)</a></small>', IT_TEXTDOMAIN ), esc_url( get_option('siteurl') ) )
			),
			'type' => 'radio'
		),
		array(
			'name' => __( 'Hide Tagline', IT_TEXTDOMAIN ),
			'desc' => __( 'This disables the tagline (site description) from displaying without requiring you to actually delete the Tagline from Settings >> General (good for SEO purposes).', IT_TEXTDOMAIN ),
			'id' => 'description_disable',
			'options' => array( 'true' => __( 'Hide the site Tagline', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Logo Bar Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'You can choose whether or not your logo should float next to the sizzlin slider or not.', IT_TEXTDOMAIN ),
			'id' => 'logobar_layout',
			'options' => array(
				'large' => THEME_ADMIN_ASSETS_URI . '/images/logo_large.png',
				'small' => THEME_ADMIN_ASSETS_URI . '/images/logo_small.png',
			),
			'default' => 'small',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Custom Logo', IT_TEXTDOMAIN ),
			'desc' => __( 'Upload an image to use as your logo.', IT_TEXTDOMAIN ),
			'id' => 'logo_url',
			'type' => 'upload'
		),
		array(
			'name' => __( 'Custom HD Logo', IT_TEXTDOMAIN ),
			'desc' => __( 'Upload an image to use as your logo for retina displays.', IT_TEXTDOMAIN ),
			'id' => 'logo_url_hd',
			'type' => 'upload'
		),
		array(
			'name' => __( 'Logo Width (optional)', IT_TEXTDOMAIN ),
			'desc' => __( 'This adds a width attribute to your logo image tag for page performance purposes. Do not include the "px" part, just the number itself.', IT_TEXTDOMAIN ),
			'id' => 'logo_width',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),		
		array(
			'name' => __( 'Logo Height (optional)', IT_TEXTDOMAIN ),
			'desc' => __( 'This adds a height attribute to your logo image tag for page performance purposes. Do not include the "px" part, just the number itself.', IT_TEXTDOMAIN ),
			'id' => 'logo_height',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),
		array(
			'name' => __( 'Always Link To Home', IT_TEXTDOMAIN ),
			'desc' => __( 'By default the minisite logos link to the minisite homepage. Use this option to force the logo to always take the user to the main site home page instead.', IT_TEXTDOMAIN ),
			'id' => 'logo_always_home',
			'options' => array( 'true' => __( 'Minisite logos should link to main site home page', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Login Logo', IT_TEXTDOMAIN ),
			'desc' => __( 'Upload an image to use as your logo for login page.', IT_TEXTDOMAIN ),
			'id' => 'login_logo_url',
			'type' => 'upload'
		),
		array(
			'name' => __( 'Custom Favicon', IT_TEXTDOMAIN ),
			'desc' => __( 'Upload an image to use as your favicon.', IT_TEXTDOMAIN ),
			'id' => 'favicon_url',
			'type' => 'upload'
		), 
		array(
			'name' => __( 'Disable Top Menu', IT_TEXTDOMAIN ),
			'desc' => __( 'This disables the top menu but causes it to be opt in, meaning you can enable it in other areas of the theme and it will override this setting (such as for a specific minisite).', IT_TEXTDOMAIN ),
			'id' => 'topmenu_disable',
			'options' => array( 'true' => __( 'Disable the top menu by default', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Top Menu Globally', IT_TEXTDOMAIN ),
			'desc' => __( 'This globally disables the top menu, even if you have it turned on in other areas of the theme (such as for a specific minisite)', IT_TEXTDOMAIN ),
			'id' => 'topmenu_disable_global',
			'options' => array( 'true' => __( 'Completely disable the top menu for the entire site', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Random Article Button', IT_TEXTDOMAIN ),
			'id' => 'random_disable',
			'options' => array( 'true' => __( 'Disable the random article button that appears in the main menu', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Disable Submenu', IT_TEXTDOMAIN ),
			'desc' => __( 'This disables the submenu but causes it to be opt in, meaning you can enable it in other areas of the theme and it will override this setting (such as for a specific minisite or archive pages).', IT_TEXTDOMAIN ),
			'id' => 'submenu_disable',
			'options' => array( 'true' => __( 'Disable the submenu by default', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Disable Submenu Globally', IT_TEXTDOMAIN ),
			'desc' => __( 'This globally disables the submenu, even if you have it turned on in other areas of the theme (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'id' => 'submenu_disable_global',
			'options' => array( 'true' => __( 'Completely disable the submenu for the entire site', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Default Page Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'The default page layout for all pages and posts.', IT_TEXTDOMAIN ),
			'id' => 'layout',
			'options' => array(
				'sidebar-right' => THEME_ADMIN_ASSETS_URI . '/images/footer_g.png',
				'sidebar-left' => THEME_ADMIN_ASSETS_URI . '/images/footer_k.png',
				'full' => THEME_ADMIN_ASSETS_URI . '/images/footer_a.png',				
			),
			'default' => 'sidebar-right',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Force Global Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'This globally forces the above layout to be used even if specific sections or pages dictate a different layout.', IT_TEXTDOMAIN ),
			'id' => 'layout_global',
			'options' => array( 'true' => __( 'The above layout should override all other settings.', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Featured Image Size', IT_TEXTDOMAIN ),
			'desc' => __( 'The default featured image size for all pages and posts', IT_TEXTDOMAIN ),
			'id' => 'featured_image_size',
			'options' => array(
				'none' => THEME_ADMIN_ASSETS_URI . '/images/image_none.png',
				'180' => THEME_ADMIN_ASSETS_URI . '/images/image_small.png',
				'360' => THEME_ADMIN_ASSETS_URI . '/images/image_medium.png',
				'790' => THEME_ADMIN_ASSETS_URI . '/images/image_large.png',
			),
			'default' => '360',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Force Global Featured Image Size', IT_TEXTDOMAIN ),
			'desc' => __( 'This globally forces the above featured image size to be used even if specific sections or pages dictate a different size.', IT_TEXTDOMAIN ),
			'id' => 'featured_image_size_global',
			'options' => array( 'true' => __( 'The above featured image size should override all other settings.', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Disable Comments Globally', IT_TEXTDOMAIN ),
			'desc' => __( 'This globally disables comments from displaying, even if you have it turned on in other areas of the theme (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'id' => 'comments_disable_global',
			'options' => array( 'true' => __( 'Completely disable the comments for the entire site', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),		
		array(
			'name' => __( 'Google Analytics Code', IT_TEXTDOMAIN ),
			'desc' =>  __( 'After signing up with Google Analytics paste the code that it gives you here.', IT_TEXTDOMAIN ),
			'id' => 'analytics_code',
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Custom CSS', IT_TEXTDOMAIN ),
			'desc' => __( 'This is a great place for doing quick custom styles.  For example if you wanted to change the site title color then you would paste this:<br /><br /><code>#logo a { color: blue; }</code>', IT_TEXTDOMAIN ),
			'id' => 'custom_css',
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Custom JavaScript', IT_TEXTDOMAIN ),
			'desc' => __( 'In case you need to add some custom javascript you may insert it here.', IT_TEXTDOMAIN ),
			'id' => 'custom_js',
			'type' => 'textarea'
		),
		
	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Styles
	 */
	array(
		'name' => array( 'it_style_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
		
		array(
			'name' => __( 'Background Container Overlay', IT_TEXTDOMAIN ),
			'desc' => __( 'This is useful if you have a busy background image or pattern', IT_TEXTDOMAIN ),
			'id' => 'bg_overlay',
			'options' => array( 'true' => __( 'Display transparent overlay container around main theme wrapper', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Accent Color Background', IT_TEXTDOMAIN ),
			'desc' => __( 'Used for thumbnail border hovers, tag list hovers, and other various accent styles', IT_TEXTDOMAIN ),
			'id' => 'color_accent_bg',
			'type' => 'color'
		),		
		array(
			'name' => __( 'Accent Color Foreground', IT_TEXTDOMAIN ),
			'desc' => __( 'Used to style text or icons that appear in an area that is styled with the accent background color specified above', IT_TEXTDOMAIN ),
			'id' => 'color_accent_fg',
			'type' => 'color'
		),
		array(
			'name' => __( 'Menu Background', IT_TEXTDOMAIN ),
			'desc' => __( 'The menu color', IT_TEXTDOMAIN ),
			'id' => 'color_menu_bg',
			'type' => 'color'
		),
		array(
			'name' => __( 'Menu Foreground', IT_TEXTDOMAIN ),
			'desc' => __( 'The menu text', IT_TEXTDOMAIN ),
			'id' => 'color_menu_fg',
			'type' => 'color'
		),
		array(
			'name' => __( 'Submenu Foreground', IT_TEXTDOMAIN ),
			'desc' => __( 'The submenu text', IT_TEXTDOMAIN ),
			'id' => 'color_submenu_fg',
			'type' => 'color'
		),
		array(
			'name' => __( 'Random Button', IT_TEXTDOMAIN ),
			'desc' => __( 'The color of the random article button', IT_TEXTDOMAIN ),
			'id' => 'color_random_button',
			'type' => 'color'
		),
		array(
			'name' => __( 'Flatten Main Menu', IT_TEXTDOMAIN ),
			'desc' => __( 'This is useful if you are using a very light background color for the main menu', IT_TEXTDOMAIN ),
			'id' => 'menu_flatten',
			'options' => array( 'true' => __( 'Remove gradient effects from main menu', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),		
		array(
			'name' => __( 'Top Ten Selector Background', IT_TEXTDOMAIN ),
			'desc' => __( 'The background color for the top ten selector area', IT_TEXTDOMAIN ),
			'id' => 'color_topten_bg',
			'type' => 'color'
		),	
		array(
			'name' => __( 'Top Ten Selector Foreground', IT_TEXTDOMAIN ),
			'desc' => __( 'The foreground color for the top ten selector area', IT_TEXTDOMAIN ),
			'id' => 'color_topten_fg',
			'type' => 'color'
		),
		array(
			'name' => __( 'Top Ten Social Background', IT_TEXTDOMAIN ),
			'desc' => __( 'The background color for the right social area for the top ten bar', IT_TEXTDOMAIN ),
			'id' => 'color_topten_social_bg',
			'type' => 'color'
		),
		array(
			'name' => __( 'Top Ten Social Foreground', IT_TEXTDOMAIN ),
			'desc' => __( 'The foreground color for the top ten social area', IT_TEXTDOMAIN ),
			'id' => 'color_topten_social_fg',
			'type' => 'color'
		),	
		array(
			'name' => __( 'Flatten Top Ten', IT_TEXTDOMAIN ),
			'desc' => __( 'This is useful if you are using a very light background color for the top ten area', IT_TEXTDOMAIN ),
			'id' => 'topten_flatten',
			'options' => array( 'true' => __( 'Remove gradient effects from top ten bar', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Responsiveness', IT_TEXTDOMAIN ),
			'desc' => __( 'When you view the site on a tablet or mobile, it will look and function exactly as it does on a large desktop display.', IT_TEXTDOMAIN ),
			'id' => 'responsive_disable',
			'options' => array( 'true' => __( 'Disable responsive layout behavior', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Fonts', IT_TEXTDOMAIN ),
			'desc' => __( 'You can override the default fonts for several parts of the theme by selecting them below. Leave the font unselected to use the default font, or if you have already made a selection and want to set it back to the default, select "Choose One..." For performance reasons only selected fonts will be imported from Google, which means we cannot display all the actual font faces in this list. To preview what each font looks like without having to activate each one, go to Google Fonts and take a look at it: http://www.google.com/fonts/', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Menus Font Face', IT_TEXTDOMAIN ),
			'desc' => __( 'Select which font to use for the menus.', IT_TEXTDOMAIN ),
			'id' => 'font_menus',
			'target' => 'fonts',
			'type' => 'select'
		),
		array(
			'name' => __( 'Body Font Face', IT_TEXTDOMAIN ),
			'desc' => __( 'Select which font to use for the body text.', IT_TEXTDOMAIN ),
			'id' => 'font_body',
			'target' => 'fonts',
			'type' => 'select'
		),
		array(
			'name' => __( 'Body Font Size', IT_TEXTDOMAIN ),
			'desc' => __( 'Select which font size to use for the body text.', IT_TEXTDOMAIN ),
			'id' => 'font_body_size',
			'target' => 'font_size',
			'type' => 'select'
		),
		array(
			'name' => __( 'Widgets Font Face', IT_TEXTDOMAIN ),
			'desc' => __( 'Select which font to use for the widget text.', IT_TEXTDOMAIN ),
			'id' => 'font_widgets',
			'target' => 'fonts',
			'type' => 'select'
		),
		array(
			'name' => __( 'Widgets Font Size', IT_TEXTDOMAIN ),
			'desc' => __( 'Select which font size to use for the widget text.', IT_TEXTDOMAIN ),
			'id' => 'font_widgets_size',
			'target' => 'font_size',
			'type' => 'select'
		),
		array(
			'name' => __( 'Add Subsets', IT_TEXTDOMAIN ),
			'desc' => __( 'Leave this unselected unless you specifically want to add subsets beyond Latin. This will only work for fonts that actually have the specific subset (refer to Google Fonts to see which ones have subsets). This also adds the character sets to the default theme fonts. Be careful! Adding subsets will impact page load times.', IT_TEXTDOMAIN ),
			'id' => 'font_subsets',
			'options' => array(
				'latin' => 'Latin',
				'latin-ext' => 'Latin Extended',
				'cyrillic' => 'Cyrillic',
				'cyrillic-ext' => 'Cyrillic Extended',
				'greek' => 'Greek',
				'greek-ext' => 'Greek Extended'
			),
			'type' => 'checkbox'
		),

	
	array(
		'type' => 'tab_end'
	),	
	
	/**
	 * Carousels
	 */
	array(
		'name' => array( 'it_carousel_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Trending Slider', IT_TEXTDOMAIN ),
			'desc' => __( 'The carousel that displays in the theme header directly below the top menu.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		array(
			'name' => __( 'Disable', IT_TEXTDOMAIN ),
			'id' => 'trending_disable',
			'options' => array( 'true' => __( 'Disable the slider by default', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This disables the slider but causes it to be opt in, meaning you can enable it in other areas of the theme and it will override this setting (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Globally', IT_TEXTDOMAIN ),
			'id' => 'trending_disable_global',
			'options' => array( 'true' => __( 'Completely disable the slider', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This globally disables the slider, even if you have it turned on in other areas of the theme (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Title Text', IT_TEXTDOMAIN ),
			'desc' => __( 'This is the title text that will display at the bottom of the carousel next to the arrow navigation.', IT_TEXTDOMAIN ),
			'id' => 'trending_title',
			'htmlentities' => true,
			'type' => 'text'
		),	
		array(
			'name' => __( 'Mobile Title Text (optional)', IT_TEXTDOMAIN ),
			'desc' => __( 'This is the title text that will display at the bottom of the carousel next to the arrow navigation only for mobile devices.', IT_TEXTDOMAIN ),
			'id' => 'trending_title_mobile',
			'htmlentities' => true,
			'type' => 'text'
		),		
		array(
			'name' => __( 'Collapsible', IT_TEXTDOMAIN ),
			'desc' => __( 'Add a button at top right of carousel that allows the user to toggle display of the carousel.', IT_TEXTDOMAIN ),
			'id' => 'trending_collapsible',
			'options' => array( 'true' => __( 'Allow this carousel to be collapsible', IT_TEXTDOMAIN ) ), 
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Start Collapsed', IT_TEXTDOMAIN ),
			'desc' => __( 'Carousel should be hidden by default requiring the user to click the toggle button to view it.', IT_TEXTDOMAIN ),
			'id' => 'trending_hidden',
			'options' => array( 'true' => __( 'Hidden by default', IT_TEXTDOMAIN ) ), 
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Scrolling', IT_TEXTDOMAIN ),
			'id' => 'trending_disable_scrolling',
			'options' => array( 'true' => __( 'Do not scroll between sets of posts', IT_TEXTDOMAIN ) ), 
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Interval', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of seconds to pause between auto-scrolling the carousel.', IT_TEXTDOMAIN ),
			'id' => 'trending_interval',
			'target' => 'seconds',
			'type' => 'select'
		),
		array(
			'name' => __( 'Disable Filtering', IT_TEXTDOMAIN ),
			'desc' => __( 'Complately disable the ability for the user to change filter views via the filter buttons', IT_TEXTDOMAIN ),
			'id' => 'trending_disable_filtering',
			'options' => array( 'true' => __( 'Completely hide all filter buttons', IT_TEXTDOMAIN ) ), 
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Filter Buttons', IT_TEXTDOMAIN ),
			'desc' => __( 'You can disable individual filter buttons.', IT_TEXTDOMAIN ),
			'id' => 'trending_disable_filter',
			'options' => array(
				'liked' => 'Liked',
				'viewed' => 'Viewed',
				'rated' => 'Rated',
				'commented' => 'Commented',
				'awarded' => 'Awarded'
			),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Tooltips', IT_TEXTDOMAIN ),
			'desc' => __( 'Hide the descriptive tooltips that display on mouse hover for the sort buttons', IT_TEXTDOMAIN ),
			'id' => 'trending_disable_tooltips',
			'options' => array( 'true' => __( 'Hide sort button tooltips', IT_TEXTDOMAIN ) ), 
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Number of Posts', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of total posts to display in the carousel.', IT_TEXTDOMAIN ),
			'id' => 'trending_number',
			'target' => 'trending_number',
			'type' => 'select'
		),		
		array(
			'name' => __( 'Limit To Category', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose a category to use to populate the trending slider. Only posts within this category will be shown.', IT_TEXTDOMAIN ),
			'id' => 'trending_limit_cat',
			'target' => 'cat',
			'type' => 'select'
		),
		array(
			'name' => __( 'Limit To Tag', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose a tag to use to populate the trending slider. Only posts within this tag will be shown.', IT_TEXTDOMAIN ),
			'id' => 'trending_limit_tag',
			'target' => 'tag',
			'type' => 'select'
		),
		array(
			'name' => __( 'Exclude Category', IT_TEXTDOMAIN ),
			'desc' => __( 'Exclude this category from the trending slider. Posts within this category will not be shown.', IT_TEXTDOMAIN ),
			'id' => 'trending_exclude_cat',
			'target' => 'cat',
			'type' => 'select'
		),
		array(
			'name' => __( 'Exclude Tag', IT_TEXTDOMAIN ),
			'desc' => __( 'Exclude this tag from the trending slider. Posts within this tag will not be shown.', IT_TEXTDOMAIN ),
			'id' => 'trending_exclude_tag',
			'target' => 'tag',
			'type' => 'select'
		),
		array(
			'name' => __( 'Exclude Minisite(s)', IT_TEXTDOMAIN ),
			'desc' => __( 'Exclude selected minisites from the trending slider. Posts within these minisites will not be shown.', IT_TEXTDOMAIN ),
			'id' => 'trending_exclude_minisites',
			'target' => 'minisites',
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( "Sizzlin' Slider", IT_TEXTDOMAIN ),
			'desc' => __( 'The carousel that displays the sizzlin text slider and social icons to the right of the main site logo', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		array(
			'name' => __( 'Disable', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_disable',
			'options' => array( 'true' => __( 'Disable the slider by default', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This disables the slider but causes it to be opt in, meaning you can enable it in other areas of the theme and it will override this setting (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Globally', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_disable_global',
			'options' => array( 'true' => __( 'Completely disable the slider', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This globally disables the slider, even if you have it turned on in other areas of the theme (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Disable Social Icons', IT_TEXTDOMAIN ),
			'desc' => __( 'Hide the social icons that appear to the right of the content carousel.', IT_TEXTDOMAIN ),
			'id' => 'social_top_disable',
			'options' => array( "true" => __( "Disable the social icons", IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Icon', IT_TEXTDOMAIN ),
			'desc' => __( 'Hide the icon that displays to the left of the content carousel title text', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_icon_disable',
			'options' => array( "true" => __( "Hide the sizzlin' slider icon", IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Title Text', IT_TEXTDOMAIN ),
			'desc' => __( 'This is the title text that will display at the left of the content carousel', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_title',
			'htmlentities' => true,
			'type' => 'text'
		),	
		array(
			'name' => __( 'Category', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose one of your categories to use for the sizzlin slider, or leave blank to use a different method.', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_category',
			'target' => 'cat',
			'type' => 'select'
		),
		array(
			'name' => __( 'Use Tag Instead', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose one of your tags to use for the sizzlin slider, or leave blank to use a different method.', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_tag',
			'target' => 'tag',
			'type' => 'select'
		),
		array(
			'name' => __( 'Number of Posts', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of total posts to display in the carousel.', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_number',
			'target' => 'sizzlin_number',
			'type' => 'select'
		),
		array(
			'name' => __( 'Interval', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of seconds to display each item in the carousel before rotating.', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_interval',
			'target' => 'seconds',
			'nodisable' => true,
			'type' => 'select'
		),
		array(
			'name' => __( 'Effect', IT_TEXTDOMAIN ),
			'desc' => __( 'The two possible effects are to fade from item to item, or scroll the next item from the bottum up on top of the previous item', IT_TEXTDOMAIN ),
			'id' => 'sizzlin_effect',
			'options' => array( 
				'slide' => __( 'Slide', IT_TEXTDOMAIN ),
				'fade' => __( 'Fade', IT_TEXTDOMAIN )
			),
			'type' => 'radio'
		),
		
		array(
			'name' => __( 'Featured Slider', IT_TEXTDOMAIN ),
			'desc' => __( 'The carousel that displays in the main featured area underneath the main menu.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		array(
			'name' => __( 'Disable', IT_TEXTDOMAIN ),
			'id' => 'featured_disable',
			'options' => array( 'true' => __( 'Disable the slider by default', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This disables the slider but causes it to be opt in, meaning you can enable it in other areas of the theme and it will override this setting (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Globally', IT_TEXTDOMAIN ),
			'id' => 'featured_disable_global',
			'options' => array( 'true' => __( 'Completely disable the slider', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This globally disables the slider, even if you have it turned on in other areas of the theme (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'There are three available layouts: carousel with two widgets, carousel with one widget, and full-width carousel with no widgets.', IT_TEXTDOMAIN ),
			'id' => 'featured_layout',
			'options' => array(
				'small' => THEME_ADMIN_ASSETS_URI . '/images/featured_small.png',
				'medium' => THEME_ADMIN_ASSETS_URI . '/images/featured_medium.png',
				'large' => THEME_ADMIN_ASSETS_URI . '/images/featured_large.png',
			),
			'default' => 'small',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Category', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose one of your categories to use for the featured slider, or leave blank to use a different method.', IT_TEXTDOMAIN ),
			'id' => 'featured_category',
			'target' => 'cat',
			'type' => 'select'
		),
		array(
			'name' => __( 'Use Tag Instead', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose one of your tags to use for the featured slider, or leave blank to use a different method.', IT_TEXTDOMAIN ),
			'id' => 'featured_tag',
			'target' => 'tag',
			'type' => 'select'
		),
		array(
			'name' => __( 'Number of Posts', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of total posts to display in the carousel.', IT_TEXTDOMAIN ),
			'id' => 'featured_number',
			'target' => 'sizzlin_number',
			'type' => 'select'
		),
		array(
			'name' => __( 'Interval', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of seconds to display each item in the carousel before rotating.', IT_TEXTDOMAIN ),
			'id' => 'featured_interval',
			'target' => 'seconds',
			'nodisable' => true,
			'type' => 'select'
		),
		array(
			'name' => __( 'Transition', IT_TEXTDOMAIN ),
			'desc' => __( 'The effect used to transition from one slide to the next', IT_TEXTDOMAIN ),
			'id' => 'featured_transition',
			'target' => 'featured_transition',
			'type' => 'select'
		),
		array(
			'name' => __( 'Timer Disable', IT_TEXTDOMAIN ),
			'id' => 'featured_timer_disable',
			'options' => array( 'true' => __( 'Hide the timer bar at the bottom of the carousel', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The timer is the white progressing bar at the bottom of the carousel that indicates the time left for the current post.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Category Disable', IT_TEXTDOMAIN ),
			'id' => 'featured_category_disable',
			'options' => array( 'true' => __( 'Hide the category caption', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The category caption displays in the upper left corner of the featured carousel', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Category Effect', IT_TEXTDOMAIN ),
			'desc' => __( 'The effect used to display the category caption in the upper left corner', IT_TEXTDOMAIN ),
			'id' => 'featured_category_effect',
			'target' => 'featured_caption_effect',
			'type' => 'select'
		),
		array(
			'name' => __( 'Category Delay', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of seconds to delay before showing this caption. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_category_delay',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Category Animation Duration', IT_TEXTDOMAIN ),
			'desc' => __( 'The duration of the animation of this caption in seconds. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_category_speed',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Rating Disable', IT_TEXTDOMAIN ),
			'id' => 'featured_rating_disable',
			'options' => array( 'true' => __( 'Hide the rating caption (if article has a rating)', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The rating caption displays directly below the category caption in the featured carousel', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Rating Effect', IT_TEXTDOMAIN ),
			'desc' => __( 'The effect used to display the rating caption below the category caption', IT_TEXTDOMAIN ),
			'id' => 'featured_rating_effect',
			'target' => 'featured_caption_effect',
			'type' => 'select'
		),
		array(
			'name' => __( 'Rating Delay', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of seconds to delay before showing this caption. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_rating_delay',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Rating Animation Duration', IT_TEXTDOMAIN ),
			'desc' => __( 'The duration of the animation of this caption in seconds. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_rating_speed',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Award Disable', IT_TEXTDOMAIN ),
			'id' => 'featured_award_disable',
			'options' => array( 'true' => __( 'Hide the award caption (if article has an award)', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The award caption displays in the upper right corner of the featured carousel', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Award Effect', IT_TEXTDOMAIN ),
			'desc' => __( 'The effect used to display the award caption in the upper right corner', IT_TEXTDOMAIN ),
			'id' => 'featured_award_effect',
			'target' => 'featured_caption_effect',
			'type' => 'select'
		),
		array(
			'name' => __( 'Award Delay', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of seconds to delay before showing this caption. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_award_delay',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Award Animation Duration', IT_TEXTDOMAIN ),
			'desc' => __( 'The duration of the animation of this caption in seconds. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_award_speed',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Title Disable', IT_TEXTDOMAIN ),
			'id' => 'featured_title_disable',
			'options' => array( 'true' => __( 'Hide the post title caption', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The post title caption displays in the lower left corner of the featured carousel', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Title Effect', IT_TEXTDOMAIN ),
			'desc' => __( 'The effect used to display the title caption in the lower left corner', IT_TEXTDOMAIN ),
			'id' => 'featured_title_effect',
			'target' => 'featured_caption_effect',
			'type' => 'select'
		),
		array(
			'name' => __( 'Title Delay', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of seconds to delay before showing this caption. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_title_delay',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Title Animation Duration', IT_TEXTDOMAIN ),
			'desc' => __( 'The duration of the animation of this caption in seconds. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_title_speed',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),		
		array(
			'name' => __( 'Video Disable', IT_TEXTDOMAIN ),
			'id' => 'featured_video_disable',
			'options' => array( 'true' => __( 'Hide the featured video', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The featured video (if any) displays in the center of the featured carousel', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Video Effect', IT_TEXTDOMAIN ),
			'desc' => __( 'The effect used to display the featured video', IT_TEXTDOMAIN ),
			'id' => 'featured_video_effect',
			'target' => 'featured_caption_effect',
			'type' => 'select'
		),
		array(
			'name' => __( 'Video Delay', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of seconds to delay before showing the featured video. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_video_delay',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Video Animation Duration', IT_TEXTDOMAIN ),
			'desc' => __( 'The duration of the animation of the featured video in seconds. Lower is faster.', IT_TEXTDOMAIN ),
			'id' => 'featured_video_speed',
			'target' => 'seconds_decimal',
			'type' => 'select',
			'nodisable' => true,
		),
		array(
			'name' => __( 'Video Controls', IT_TEXTDOMAIN ),
			'id' => 'featured_video_controls',
			'options' => array( 'true' => __( 'Show video controls for featured videos (youtube only)', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'When the slide has a featured video, display the controls at the bottom of the video (only applies to Youtube videos).', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Top Ten Slider', IT_TEXTDOMAIN ),
			'desc' => __( 'The entire content area that contains the Top Ten slider and the social widget area on the right.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		array(
			'name' => __( 'Disable', IT_TEXTDOMAIN ),
			'id' => 'topten_disable',
			'options' => array( 'true' => __( 'Disable the slider by default', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This disables the slider but causes it to be opt in, meaning you can enable it in other areas of the theme and it will override this setting (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Globally', IT_TEXTDOMAIN ),
			'id' => 'topten_disable_global',
			'options' => array( 'true' => __( 'Completely disable the slider', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This globally disables the slider, even if you have it turned on in other areas of the theme (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Label', IT_TEXTDOMAIN ),
			'desc' => __( 'You can overwrite the "Top 10" label that displays by default above the selector drop down filter.', IT_TEXTDOMAIN ),
			'id' => 'topten_label',
			'htmlentities' => true,
			'type' => 'text'
		),	
		array(
			'name' => __( 'Time Period', IT_TEXTDOMAIN ),
			'desc' => __( 'Limit posts in the Top Ten slider to this time period.', IT_TEXTDOMAIN ),
			'id' => 'topten_timeperiod',
			'target' => 'timeperiod',
			'nodisable' => true,
			'type' => 'select'
		),
		array(
			'name' => __( 'Disable Filter Options', IT_TEXTDOMAIN ),
			'desc' => __( 'You can disable individual filter options.', IT_TEXTDOMAIN ),
			'id' => 'topten_disable_filter',
			'options' => array(
				'viewed' => 'Most Views',
				'liked' => 'Most Likes',
				'reviewed' => 'Best Reviewed',
				'rated' => 'Highest Rated',
				'commented' => 'Commented On'
			),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Right Panel', IT_TEXTDOMAIN ),
			'id' => 'topten_social_disable',
			'options' => array( 'true' => __( 'Hide the colored social/login panel on the right', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'You can selectively disable just the colored social/login panel on the right of the bar without actually disabling the entire bar.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Login Form', IT_TEXTDOMAIN ),
			'id' => 'topten_login_disable',
			'options' => array( 'true' => __( 'Hide the login form within the right panel', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'You can selectively disable just the login form within the right panel without actually disabling the panel itself.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Social Widget', IT_TEXTDOMAIN ),
			'id' => 'topten_social_numbers_disable',
			'options' => array( 'true' => __( 'Hide the social widget within the right panel', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'You can selectively disable just the login form within the right panel without actually disabling the panel itself.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		
	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Post Loop
	 */
	array(
		'name' => array( 'it_loop_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Control Bar', IT_TEXTDOMAIN ),
			'desc' => __( 'Layout controls, sort buttons, and email signup bar at the top of the post loop.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		array(
			'name' => __( 'Disable Layout Switcher', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_layout',
			'options' => array( 'true' => __( 'Disable the grid/list layout switcher', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The layout switcher lets the user switch between grid and list view', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Title Text', IT_TEXTDOMAIN ),
			'desc' => __( 'This is the title text that will display to the right of the layout switcher.', IT_TEXTDOMAIN ),
			'id' => 'loop_title',
			'htmlentities' => true,
			'type' => 'text'
		),
		array(
			'name' => __( 'Disable Filtering', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_filtering',
			'options' => array( 'true' => __( 'Disable the filter buttons', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This will disable the filter buttons for all post loops', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Filter Buttons', IT_TEXTDOMAIN ),
			'desc' => __( 'You can disable individual filter buttons.', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_filter',
			'options' => array(
				'liked' => 'Liked',
				'viewed' => 'Viewed',
				'rated' => 'Rated',
				'commented' => 'Commented',
				'awarded' => 'Awarded'
			),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Filter Tooltips', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_tooltips',
			'options' => array( 'true' => __( 'Disable the filter button tooltips', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This will disable the tooltips that display when you hover over the filter buttons', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Email Signup', IT_TEXTDOMAIN ),
			'desc' => __( 'This disables the signup form but causes it to be opt in, meaning you can enable it in other areas of the theme and it will override this setting (such as for a specific minisite or archive pages).', IT_TEXTDOMAIN ),
			'id' => 'signup_disable',
			'options' => array( 'true' => __( 'Disable the email signup form to the right of the control bar', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Disable Email Signup Globally', IT_TEXTDOMAIN ),
			'desc' => __( 'This globally disables the signup form, even if you have it turned on in other areas of the theme (such as for a specific minisite or for archive pages)', IT_TEXTDOMAIN ),
			'id' => 'signup_disable_global',
			'options' => array( 'true' => __( 'Completely disable the signup form for the entire site', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Email Signup Label', IT_TEXTDOMAIN ),
			'desc' => __( 'This is the label text that appears to the left of the email signup input field', IT_TEXTDOMAIN ),
			'id' => 'loop_email_label',
			'htmlentities' => true,
			'type' => 'text'
		),		
		array(
			'name' => __( 'Content', IT_TEXTDOMAIN ),
			'desc' => __( 'Main settings for the articles and layout for the loop.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		array(
			'name' => __( 'Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'You can select between four main layouts: grid style with and without sidebar, and list style with and without sidebar.', IT_TEXTDOMAIN ),
			'id' => 'loop_layout',
			'options' => array(
				'a' => THEME_ADMIN_ASSETS_URI . '/images/loop_a.png',
				'b' => THEME_ADMIN_ASSETS_URI . '/images/loop_b.png',
				'e' => THEME_ADMIN_ASSETS_URI . '/images/loop_e.png',
				'f' => THEME_ADMIN_ASSETS_URI . '/images/loop_f.png',
				'c' => THEME_ADMIN_ASSETS_URI . '/images/loop_c.png',
				'd' => THEME_ADMIN_ASSETS_URI . '/images/loop_d.png',
			),
			'default' => 'a',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Disable Tags', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_tags',
			'options' => array( 'true' => __( 'Tags will only display on single post pages', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'This only applies to list layouts because tags do not appear in grid layouts', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Award', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_award',
			'options' => array( 'true' => __( 'Awards will only display on single post pages', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Excerpt', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_excerpt',
			'options' => array( 'true' => __( 'Only the title will display, no excerpt', IT_TEXTDOMAIN ) ),
			'desc' => __( 'It is helpful to hide this if you have a lot of badges and awards you want to display for your posts in order to present a cleaner layout to the user', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Badges', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_badge',
			'options' => array( 'true' => __( 'Badges will only display on single post pages', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Likes', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_likes',
			'options' => array( 'true' => __( 'Likes will only display on single post pages', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Views', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_views',
			'options' => array( 'true' => __( 'Views will only display on single post pages', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Comments', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_comments',
			'options' => array( 'true' => __( 'Comments will only display on single post pages', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The number of comments will not display in the main loop. Keep in mind the comments will automatically be hidden if there are no comments for the post even if this option is unselected.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Date/Author', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_date',
			'options' => array( 'true' => __( 'Date/Author will only display on single post pages', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The date and author will not display in the main loop. This only applies to list layouts because the date/author does not appear in grid layouts.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Rating', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_rating',
			'options' => array( 'true' => __( 'Ratings will only display on single post pages', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable All Meta Info', IT_TEXTDOMAIN ),
			'id' => 'loop_disable_meta',
			'options' => array( 'true' => __( 'Disregard above options and disable entire meta bar', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'The bar at the bottom of each post in the loop containing the views, likes, date, rating, etc. will be completely hidden.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Show Featured Videos', IT_TEXTDOMAIN ),
			'id' => 'loop_video',
			'options' => array( 'true' => __( 'Show featured videos in place of images', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'When the post has a featured video assigned, display it instead of the featured image.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Show Video Controls', IT_TEXTDOMAIN ),
			'id' => 'loop_video_controls',
			'options' => array( 'true' => __( 'Show video controls for featured videos (youtube only)', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'When the slide has a featured video, display the controls at the bottom of the video (only applies to Youtube videos).', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Pagination', IT_TEXTDOMAIN ),
			'desc' => __( 'The page number buttons and navigation that appear below the post loop.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		array(
			'name' => __( 'Range', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of pages to display to the right and left of the current page. Setting this to 3 for example will result in 7 total possible page number buttons generated (3 on the left, 3 on the right, plus the current page) in addition to the arrow navigation (if enabled).', IT_TEXTDOMAIN ),
			'id' => 'page_range',
			'target' => 'range_number',
			'type' => 'select',
			'nodisable' => true,
		),	
		array(
			'name' => __( 'Range (Mobile)', IT_TEXTDOMAIN ),
			'desc' => __( 'You can set a different range for mobile views so that the pagination fits into one row. If you want the pagination to fit into one row set this to 2.', IT_TEXTDOMAIN ),
			'id' => 'page_range_mobile',
			'target' => 'range_number',
			'type' => 'select',
			'nodisable' => true,
		),	
		array(
			'name' => __( 'Disable Prev/Next Navigation', IT_TEXTDOMAIN ),
			'id' => 'prev_next_disable',
			'options' => array( 'true' => __( 'Hide the next and previous navigation arrows.', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'These arrows display on the right and left of the pagination. They do not navigate to the next and previous pages, but rather the next and previous blocks of page numbers. For instance, if the range is set to 6 the next arrow will increase the current page 8 slots (range + current page + 1).', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable First/Last Navigation', IT_TEXTDOMAIN ),
			'id' => 'first_last_disable',
			'options' => array( 'true' => __( 'Hide the first and last navigation arrows.', IT_TEXTDOMAIN ) ), 
			'desc' => __( 'These arrows display on the right and left of the pagination and they are used for quickly navigating to the first or last page.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Limit Posts', IT_TEXTDOMAIN ),
			'desc' => __( 'Instead of displaying all posts in the loop you can limit to or exclude certain posts based on categories, tags, and minisites.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		array(
			'name' => __( 'Limit To Category', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose a category to use to populate the post loop. Only posts within this category will be shown.', IT_TEXTDOMAIN ),
			'id' => 'loop_limit_cat',
			'target' => 'cat',
			'type' => 'select'
		),
		array(
			'name' => __( 'Limit To Tag', IT_TEXTDOMAIN ),
			'desc' => __( 'Choose a tag to use to populate the post loop. Only posts within this tag will be shown.', IT_TEXTDOMAIN ),
			'id' => 'loop_limit_tag',
			'target' => 'tag',
			'type' => 'select'
		),
		array(
			'name' => __( 'Exclude Category', IT_TEXTDOMAIN ),
			'desc' => __( 'Exclude this category from the post loop. Posts within this category will not be shown.', IT_TEXTDOMAIN ),
			'id' => 'loop_exclude_cat',
			'target' => 'cat',
			'type' => 'select'
		),
		array(
			'name' => __( 'Exclude Tag', IT_TEXTDOMAIN ),
			'desc' => __( 'Exclude this tag from the post loop. Posts within this tag will not be shown.', IT_TEXTDOMAIN ),
			'id' => 'loop_exclude_tag',
			'target' => 'tag',
			'type' => 'select'
		),
		array(
			'name' => __( 'Exclude Minisite(s)', IT_TEXTDOMAIN ),
			'desc' => __( 'Exclude selected minisites from the post loop. Posts within these minisites will not be shown.', IT_TEXTDOMAIN ),
			'id' => 'loop_exclude_minisites',
			'target' => 'minisites',
			'type' => 'checkbox'
		),
	
	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Page Layouts
	 */
	array(
		'name' => array( 'it_pages_tab' => $option_tabs ),
		'type' => 'tab_start'
	),

		array(
			'name' => __( 'Archives', IT_TEXTDOMAIN ),
			'desc' => __( 'This includes all category, tag, and date listing archive pages', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Disable Trending Slider', IT_TEXTDOMAIN ),
			'id' => 'archive_trending_disable',
			'options' => array( 'true' => __( 'Disable the trending slider for all archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Sizzlin Slider', IT_TEXTDOMAIN ),
			'id' => 'archive_sizzlin_disable',
			'options' => array( 'true' => __( 'Disable the sizzlin slider for all archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Submenu', IT_TEXTDOMAIN ),
			'id' => 'archive_submenu_disable',
			'options' => array( 'true' => __( 'Disable the submenu for archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Featured Area', IT_TEXTDOMAIN ),
			'id' => 'archive_featured_disable',
			'options' => array( 'true' => __( 'Disable the entire featured area for all archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Top Ten Bar', IT_TEXTDOMAIN ),
			'id' => 'archive_topten_disable',
			'options' => array( 'true' => __( 'Disable the entire top ten bar for all archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Layout Switcher', IT_TEXTDOMAIN ),
			'id' => 'archive_layout_disable',
			'options' => array( 'true' => __( 'Disable the grid/list layout switcher for all archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Title', IT_TEXTDOMAIN ),
			'id' => 'archive_loop_title_disable',
			'options' => array( 'true' => __( 'Disable the post loop title for all archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Filtering', IT_TEXTDOMAIN ),
			'id' => 'archive_filtering_disable',
			'options' => array( 'true' => __( 'Disable the filter buttons for all archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Email Signup', IT_TEXTDOMAIN ),
			'id' => 'archive_signup_disable',
			'options' => array( 'true' => __( 'Disable the email signup form for all archive pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Search Results', IT_TEXTDOMAIN ),
			'desc' => __( 'This includes all search results listing pages', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Disable Trending Slider', IT_TEXTDOMAIN ),
			'id' => 'search_trending_disable',
			'options' => array( 'true' => __( 'Disable the trending slider for all search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Sizzlin Slider', IT_TEXTDOMAIN ),
			'id' => 'search_sizzlin_disable',
			'options' => array( 'true' => __( 'Disable the sizzlin slider for all search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Submenu', IT_TEXTDOMAIN ),
			'id' => 'search_submenu_disable',
			'options' => array( 'true' => __( 'Disable the submenu for search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Featured Area', IT_TEXTDOMAIN ),
			'id' => 'search_featured_disable',
			'options' => array( 'true' => __( 'Disable the entire featured area for all search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Top Ten Bar', IT_TEXTDOMAIN ),
			'id' => 'search_topten_disable',
			'options' => array( 'true' => __( 'Disable the entire top ten bar for all search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Layout Switcher', IT_TEXTDOMAIN ),
			'id' => 'search_layout_disable',
			'options' => array( 'true' => __( 'Disable the grid/list layout switcher for all search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Title', IT_TEXTDOMAIN ),
			'id' => 'search_loop_title_disable',
			'options' => array( 'true' => __( 'Disable the post loop title for all search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Filtering', IT_TEXTDOMAIN ),
			'id' => 'search_filtering_disable',
			'options' => array( 'true' => __( 'Disable the filter buttons for all search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Email Signup', IT_TEXTDOMAIN ),
			'id' => 'search_signup_disable',
			'options' => array( 'true' => __( 'Disable the email signup form for all search pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Pages', IT_TEXTDOMAIN ),
			'desc' => __( 'This includes all standard pages created in WordPress with the Default Template as the page template.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Disable Trending Slider', IT_TEXTDOMAIN ),
			'id' => 'page_trending_disable',
			'options' => array( 'true' => __( 'Disable the trending slider for all standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Sizzlin Slider', IT_TEXTDOMAIN ),
			'id' => 'page_sizzlin_disable',
			'options' => array( 'true' => __( 'Disable the sizzlin slider for all standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Submenu', IT_TEXTDOMAIN ),
			'id' => 'page_submenu_disable',
			'options' => array( 'true' => __( 'Disable the submenu for standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Featured Area', IT_TEXTDOMAIN ),
			'id' => 'page_featured_disable',
			'options' => array( 'true' => __( 'Disable the entire featured area for all standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Top Ten Bar', IT_TEXTDOMAIN ),
			'id' => 'page_topten_disable',
			'options' => array( 'true' => __( 'Disable the entire top ten bar for all standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Disable Email Signup', IT_TEXTDOMAIN ),
			'id' => 'page_signup_disable',
			'options' => array( 'true' => __( 'Disable the email signup form for all standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'You can specify a layout with or without a sidebar.', IT_TEXTDOMAIN ),
			'id' => 'page_layout',
			'options' => array(
				'sidebar-right' => THEME_ADMIN_ASSETS_URI . '/images/footer_g.png',
				'sidebar-left' => THEME_ADMIN_ASSETS_URI . '/images/footer_k.png',
				'full' => THEME_ADMIN_ASSETS_URI . '/images/footer_a.png',				
			),
			'default' => 'sidebar-right',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Featured Image Size', IT_TEXTDOMAIN ),
			'desc' => __( 'The default featured image size for all standard pages only', IT_TEXTDOMAIN ),
			'id' => 'page_featured_image_size',
			'options' => array(
				'none' => THEME_ADMIN_ASSETS_URI . '/images/image_none.png',
				'180' => THEME_ADMIN_ASSETS_URI . '/images/image_small.png',
				'360' => THEME_ADMIN_ASSETS_URI . '/images/image_medium.png',
				'790' => THEME_ADMIN_ASSETS_URI . '/images/image_large.png',
			),
			'default' => '360',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Disable View Count', IT_TEXTDOMAIN ),
			'id' => 'page_sortbar_views_disable',
			'options' => array( 'true' => __( 'Disable the view count for all standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Like Count', IT_TEXTDOMAIN ),
			'id' => 'page_sortbar_likes_disable',
			'options' => array( 'true' => __( 'Disable the like count for all standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Comment Count', IT_TEXTDOMAIN ),
			'id' => 'page_sortbar_comments_disable',
			'options' => array( 'true' => __( 'Disable the comment count for all standard pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Like Button', IT_TEXTDOMAIN ),
			'id' => 'page_likes_disable',
			'options' => array( 'true' => __( 'Disable the like button at the end of the page content', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Enable Comments', IT_TEXTDOMAIN ),
			'id' => 'page_comments',
			'options' => array( 'true' => __( 'Enable comments on regular pages', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Author Listing Page', IT_TEXTDOMAIN ),
			'desc' => __( 'These settings apply to any page given the Author Listing page template', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Enable Admins', IT_TEXTDOMAIN ),
			'id' => 'author_admin_enable',
			'options' => array( 'true' => __( 'Allow the admin user role to display in the list', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Hide Empty', IT_TEXTDOMAIN ),
			'id' => 'author_empty_disable',
			'options' => array( 'true' => __( 'Hide authors with zero posts', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Manual Exclude', IT_TEXTDOMAIN ),
			'desc' => __( 'Enter a comma-separated list of usernames to exclude', IT_TEXTDOMAIN ),
			'id' => 'author_exclude',
			'htmlentities' => true,
			'type' => 'text'
		),
		array(
			'name' => __( 'User Role', IT_TEXTDOMAIN ),
			'desc' => __( 'Select a user role to display', IT_TEXTDOMAIN ),
			'id' => 'author_role',
			'target' => 'author_role',
			'type' => 'select'
		),
		array(
			'name' => __( 'Order', IT_TEXTDOMAIN ),
			'desc' => __( 'Select how to order the list', IT_TEXTDOMAIN ),
			'id' => 'author_order',
			'target' => 'author_order',
			'type' => 'select'
		),
		
		array(
			'name' => __( 'Minisite Directory Page', IT_TEXTDOMAIN ),
			'desc' => __( 'These settings apply to any page given the Minisite Directory page template', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Disable Likes', IT_TEXTDOMAIN ),
			'id' => 'directory_likes_disable',
			'options' => array( 'true' => __( 'Disable the ability to like the overall directory page', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Trending Slider', IT_TEXTDOMAIN ),
			'id' => 'directory_trending_disable',
			'options' => array( 'true' => __( 'Disable the trending slider for all directory pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Sizzlin Slider', IT_TEXTDOMAIN ),
			'id' => 'directory_sizzlin_disable',
			'options' => array( 'true' => __( 'Disable the sizzlin slider for all directory pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Submenu', IT_TEXTDOMAIN ),
			'id' => 'directory_submenu_disable',
			'options' => array( 'true' => __( 'Disable the submenu for directory pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Featured Area', IT_TEXTDOMAIN ),
			'id' => 'directory_featured_disable',
			'options' => array( 'true' => __( 'Disable the entire featured area for all directory pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Top Ten Bar', IT_TEXTDOMAIN ),
			'id' => 'directory_topten_disable',
			'options' => array( 'true' => __( 'Disable the entire top ten bar for all directory pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Sortbar Title', IT_TEXTDOMAIN ),
			'id' => 'directory_loop_title_disable',
			'options' => array( 'true' => __( 'Disable the post loop title for all directory pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),		
		array(
			'name' => __( 'Disable Email Signup', IT_TEXTDOMAIN ),
			'id' => 'directory_signup_disable',
			'options' => array( 'true' => __( 'Disable the email signup form for all directory pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Content Title', IT_TEXTDOMAIN ),
			'id' => 'directory_page_title_disable',
			'options' => array( 'true' => __( 'Disable the title of the page that displays above the content', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Unique Sidebar', IT_TEXTDOMAIN ),
			'id' => 'directory_sidebar_unique',
			'options' => array( 'true' => __( 'Use the Directory Sidebar instead of the Page Sidebar', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
				
		array(
			'name' => __( '404 Page', IT_TEXTDOMAIN ),
			'desc' => __( 'This is the page that displays to the user when they attempt to access a page that does not exist', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Disable Trending Slider', IT_TEXTDOMAIN ),
			'id' => '404_trending_disable',
			'options' => array( 'true' => __( 'Disable the trending slider for all 404 pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Sizzlin Slider', IT_TEXTDOMAIN ),
			'id' => '404_sizzlin_disable',
			'options' => array( 'true' => __( 'Disable the sizzlin slider for all 404 pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Submenu', IT_TEXTDOMAIN ),
			'id' => '404_submenu_disable',
			'options' => array( 'true' => __( 'Disable the submenu for 404 pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Featured Area', IT_TEXTDOMAIN ),
			'id' => '404_featured_disable',
			'options' => array( 'true' => __( 'Disable the entire featured area for all 404 pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Top Ten Bar', IT_TEXTDOMAIN ),
			'id' => '404_topten_disable',
			'options' => array( 'true' => __( 'Disable the entire top ten bar for all 404 pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Title', IT_TEXTDOMAIN ),
			'id' => '404_loop_title_disable',
			'options' => array( 'true' => __( 'Disable the post loop title for all 404 pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),		
		array(
			'name' => __( 'Disable Email Signup', IT_TEXTDOMAIN ),
			'id' => '404_signup_disable',
			'options' => array( 'true' => __( 'Disable the email signup form for all 404 pages only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Unique Sidebar', IT_TEXTDOMAIN ),
			'id' => '404_sidebar_unique',
			'options' => array( 'true' => __( 'Use the 404 Sidebar instead of the Page Sidebar', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'You can specify a layout with or without a sidebar.', IT_TEXTDOMAIN ),
			'id' => '404_layout',
			'options' => array(
				'sidebar-right' => THEME_ADMIN_ASSETS_URI . '/images/footer_g.png',
				'sidebar-left' => THEME_ADMIN_ASSETS_URI . '/images/footer_k.png',
				'full' => THEME_ADMIN_ASSETS_URI . '/images/footer_a.png',				
			),
			'default' => 'sidebar-right',
			'type' => 'layout'
		),
		

	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Single Articles
	 */
	array(
		'name' => array( 'it_posts_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Standard Posts', IT_TEXTDOMAIN ),
			'desc' => __( 'This includes all standard posts created in the Posts section of WordPress', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Disable Trending Slider', IT_TEXTDOMAIN ),
			'id' => 'post_trending_disable',
			'options' => array( 'true' => __( 'Disable the trending slider for all standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Sizzlin Slider', IT_TEXTDOMAIN ),
			'id' => 'post_sizzlin_disable',
			'options' => array( 'true' => __( 'Disable the sizzlin slider for all standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Submenu', IT_TEXTDOMAIN ),
			'id' => 'post_submenu_disable',
			'options' => array( 'true' => __( 'Disable the submenu for standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Featured Area', IT_TEXTDOMAIN ),
			'id' => 'post_featured_disable',
			'options' => array( 'true' => __( 'Disable the entire featured area for all standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Top Ten Bar', IT_TEXTDOMAIN ),
			'id' => 'post_topten_disable',
			'options' => array( 'true' => __( 'Disable the entire top ten bar for all standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Disable Date/Author', IT_TEXTDOMAIN ),
			'id' => 'post_date_disable',
			'options' => array( 'true' => __( 'Disable the post date and author for all standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'You can specify a layout with or without a sidebar.', IT_TEXTDOMAIN ),
			'id' => 'post_layout',
			'options' => array(
				'sidebar-right' => THEME_ADMIN_ASSETS_URI . '/images/footer_g.png',
				'sidebar-left' => THEME_ADMIN_ASSETS_URI . '/images/footer_k.png',
				'full' => THEME_ADMIN_ASSETS_URI . '/images/footer_a.png',				
			),
			'default' => 'sidebar-right',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Featured Image Size', IT_TEXTDOMAIN ),
			'desc' => __( 'The default featured image size for all standard posts only', IT_TEXTDOMAIN ),
			'id' => 'post_featured_image_size',
			'options' => array(
				'none' => THEME_ADMIN_ASSETS_URI . '/images/image_none.png',
				'180' => THEME_ADMIN_ASSETS_URI . '/images/image_small.png',
				'360' => THEME_ADMIN_ASSETS_URI . '/images/image_medium.png',
				'790' => THEME_ADMIN_ASSETS_URI . '/images/image_large.png',
			),
			'default' => '360',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Disable View Count', IT_TEXTDOMAIN ),
			'id' => 'post_sortbar_views_disable',
			'options' => array( 'true' => __( 'Disable the view count for all standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Like Count', IT_TEXTDOMAIN ),
			'id' => 'post_sortbar_likes_disable',
			'options' => array( 'true' => __( 'Disable the like count for all standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Comment Count', IT_TEXTDOMAIN ),
			'id' => 'post_sortbar_comments_disable',
			'options' => array( 'true' => __( 'Disable the comment count for all standard posts only', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Hide Post Info Box', IT_TEXTDOMAIN ),
			'id' => 'post_postinfo_disable',
			'options' => array( 'true' => __( 'Hide the post info area (tags, categories, author info...)', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Like Button', IT_TEXTDOMAIN ),
			'id' => 'post_likes_disable',
			'options' => array( 'true' => __( 'Disable the like button in the post info box', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Categories', IT_TEXTDOMAIN ),
			'id' => 'post_categories_disable',
			'options' => array( 'true' => __( 'Disable the category list in the post info box', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Tags', IT_TEXTDOMAIN ),
			'id' => 'post_tags_disable',
			'options' => array( 'true' => __( 'Disable the tag list in the post info box', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Author Info', IT_TEXTDOMAIN ),
			'id' => 'post_author_disable',
			'options' => array( 'true' => __( 'Disable the author information in the post info box', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Recommended', IT_TEXTDOMAIN ),
			'id' => 'post_recommended_disable',
			'options' => array( 'true' => __( 'Disable the recommended posts section', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Recommended Method', IT_TEXTDOMAIN ),
			'id' => 'post_recommended_method',
			'desc' => __( 'For the "Same tags OR same categories" method, use the "Number of Recommended Filters" option below to set how many of EACH will display, rather than how many TOTAL as is applied to the rest of the methods. So setting this to "2" will cause the first two tags and the first two categories to display, resulting in four total filters.', IT_TEXTDOMAIN ),
			'options' => array( 
				'tags' => __( 'Same tags', IT_TEXTDOMAIN ),
				'categories' => __( 'Same categories', IT_TEXTDOMAIN ),
				'tags_categories' => __( 'Same tags OR same categories (tags will appear first in order)', IT_TEXTDOMAIN ),
			),
			'default' => 'tags',
			'type' => 'radio'
		),	
		array(
			'name' => __( 'Recommended Label', IT_TEXTDOMAIN ),
			'desc' => __( 'The title text to display in the title of the recommended section', IT_TEXTDOMAIN ),
			'id' => 'post_recommended_label',
			'htmlentities' => true,
			'type' => 'text'
		),
		array(
			'name' => __( 'Number of Recommended Filters', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of filter buttons to display in the recommended filter bar.', IT_TEXTDOMAIN ),
			'id' => 'post_recommended_filters_num',
			'target' => 'recommended_filters_number',
			'type' => 'select'
		),
		array(
			'name' => __( 'Disable Recommended Filters', IT_TEXTDOMAIN ),
			'id' => 'post_recommended_filters_disable',
			'options' => array( 'true' => __( 'Disable the filter buttons from the recommended section', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Number of Recommended Posts', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of total posts to display in the recommended section.', IT_TEXTDOMAIN ),
			'id' => 'post_recommended_num',
			'target' => 'recommended_number',
			'type' => 'select'
		),
		array(
			'name' => __( 'Disable Comments', IT_TEXTDOMAIN ),
			'id' => 'post_comments_disable',
			'options' => array( 'true' => __( 'Disable the comments (useful for Facebook comment plugins)', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
	
	array(
		'type' => 'tab_end'
	),

	/**
	 * Minisites
	 */
	array(
		'name' => array( 'it_minisite_tab' => $option_tabs ),
		'type' => 'tab_start'
	),

		array(
			'name' => __( 'Create your minisites here. When you add, edit, or delete minisites, click Confirm Minisites in order to see the changes reflected in the control panel main menu on the left.', IT_TEXTDOMAIN ),
			'id' => 'minisite',
			'type' => 'minisite'
		),
		
		array(
			'name' => __( 'Legacy', IT_TEXTDOMAIN ),
			'desc' => __( 'This will cause your review types and taxonomies that you created using Made or Swagger to remain prefixed in such a way that Flavor recognizes them. You must click Confirm Minisites if you change this setting in order for it to be applied.', IT_TEXTDOMAIN ),
			'id' => 'legacy',
			'options' => array( 'true' => __( 'Turn this on if you are upgrading from a previous I.T. theme', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Standard Permalink Method', IT_TEXTDOMAIN ),
			'desc' => __( 'Selecting this will cause the permalinks of your minisite articles to work the same as normal WordPress posts, meaning the name of the minisite will not be included in the URL before the name of the post (only applies to non-default permalinks structure). You should refresh your permalinks after changing this option (go to Settings >> Permalinks and click Save)', IT_TEXTDOMAIN ),
			'id' => 'no_minisite_urls',
			'options' => array( 'true' => __( 'Do not include the name of the minisite in the URL', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),

	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Sidebar
	 */
	array(
		'name' => array( 'it_sidebar_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Create New Sidebar', IT_TEXTDOMAIN ),
			'desc' => __( 'You can create additional sidebars to use. To display your new sidebar then you will need to select it in the &quot;Custom Sidebar&quot; dropdown when editing a post or page.', IT_TEXTDOMAIN ),
			'id' => 'custom_sidebars',
			'type' => 'sidebar'
		),
	
	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Signoff
	 */
	array(
		'name' => array( 'it_signoff_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Create New Signoff', IT_TEXTDOMAIN ),
			'desc' => __( 'You can create an unlimited number of signoff text areas and then choose the one you want to use for each post.', IT_TEXTDOMAIN ),
			'id' => 'signoff',
			'type' => 'signoff'
		),
	
	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Advertising
	 */
	array(
		'name' => array( 'it_advertising_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Header Ad', IT_TEXTDOMAIN ),
			'desc' => __( 'Displays to the right of the logo in the main site header.', IT_TEXTDOMAIN ),
			'id' => 'ad_header',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Footer Ad', IT_TEXTDOMAIN ),
			'desc' => __( 'Displays above the dark footer container but below the main content wrapper.', IT_TEXTDOMAIN ),
			'id' => 'ad_footer',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Background Ad URL', IT_TEXTDOMAIN ),
			'desc' => __( 'The URL to direct the user to when they click anywhere on the background. Leave this blank to disable it. For the image to use for the ad, use the page background image URL options.', IT_TEXTDOMAIN ),
			'id' => 'ad_background',
			'htmlentities' => true,
			'type' => 'text'
		),
		array(
			'name' => __( 'Post Loops', IT_TEXTDOMAIN ),
			'desc' => __( 'These ads will get injected into your post loops (article listing pages)', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'AJAX Ads', IT_TEXTDOMAIN ),
			'desc' => __( 'You should turn this off if you are using an ad vendor that does not allow ads to display on dynamically-generated pages such as Google Adsense.', IT_TEXTDOMAIN ),
			'id' => 'ad_ajax',
			'options' => array( 'true' => __( 'Display ads on AJAX (dynamically-loaded) pages.', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Shuffle', IT_TEXTDOMAIN ),
			'id' => 'ad_shuffle',
			'options' => array( 'true' => __( 'Shuffle the display of the ads', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),	
		array(
			'name' => __( 'Number of Ads', IT_TEXTDOMAIN ),
			'desc' => __( 'The total number of ads to display in the loop regardless of how many ad slots are filled out below', IT_TEXTDOMAIN ),
			'id' => 'ad_num',
			'target' => 'ad_number',
			'type' => 'select'
		),
		array(
			'name' => __( 'Increment', IT_TEXTDOMAIN ),
			'desc' => __( 'Display an ad every Nth post. For instance, if "3" is selected, every 3rd post will be an ad.', IT_TEXTDOMAIN ),
			'id' => 'ad_increment',
			'target' => 'ad_number',
			'nodisable' => true,
			'type' => 'select'
		),
		array(
			'name' => __( 'Off-set', IT_TEXTDOMAIN ),
			'desc' => __( 'Number of posts to display before the first ad appears', IT_TEXTDOMAIN ),
			'id' => 'ad_offset',
			'target' => 'ad_number',
			'type' => 'select'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'desc' => __( 'Enter the HTML for the ad here', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_1',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_2',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_3',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_4',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_5',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_6',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_7',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_8',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_9',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Ad Slot', IT_TEXTDOMAIN ),
			'id' => 'loop_ad_10',
			'htmlentities' => true,
			'type' => 'textarea'
		),
		
		
	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Footer
	 */
	array(
		'name' => array( 'it_footer_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'You can select any type of layout you want for the footer columns.', IT_TEXTDOMAIN ),
			'id' => 'footer_layout',
			'options' => array(
				'a' => THEME_ADMIN_ASSETS_URI . '/images/footer_a.png',
				'b' => THEME_ADMIN_ASSETS_URI . '/images/footer_b.png',
				'c' => THEME_ADMIN_ASSETS_URI . '/images/footer_c.png',
				'd' => THEME_ADMIN_ASSETS_URI . '/images/footer_d.png',
				'e' => THEME_ADMIN_ASSETS_URI . '/images/footer_e.png',
				'f' => THEME_ADMIN_ASSETS_URI . '/images/footer_f.png',
				'g' => THEME_ADMIN_ASSETS_URI . '/images/footer_g.png',
				'h' => THEME_ADMIN_ASSETS_URI . '/images/footer_h.png',
				'i' => THEME_ADMIN_ASSETS_URI . '/images/footer_i.png',
				'j' => THEME_ADMIN_ASSETS_URI . '/images/footer_j.png',
				'k' => THEME_ADMIN_ASSETS_URI . '/images/footer_k.png',
				'l' => THEME_ADMIN_ASSETS_URI . '/images/footer_l.png',
				'm' => THEME_ADMIN_ASSETS_URI . '/images/footer_m.png',
				'n' => THEME_ADMIN_ASSETS_URI . '/images/footer_n.png',
				'o' => THEME_ADMIN_ASSETS_URI . '/images/footer_o.png',
				'p' => THEME_ADMIN_ASSETS_URI . '/images/footer_p.png',
				'q' => THEME_ADMIN_ASSETS_URI . '/images/footer_q.png',
				'r' => THEME_ADMIN_ASSETS_URI . '/images/footer_r.png',
				's' => THEME_ADMIN_ASSETS_URI . '/images/footer_s.png',
				't' => THEME_ADMIN_ASSETS_URI . '/images/footer_t.png',
				'u' => THEME_ADMIN_ASSETS_URI . '/images/footer_u.png',
				'v' => THEME_ADMIN_ASSETS_URI . '/images/footer_v.png',
				'w' => THEME_ADMIN_ASSETS_URI . '/images/footer_w.png',
				'x' => THEME_ADMIN_ASSETS_URI . '/images/footer_x.png',
				'y' => THEME_ADMIN_ASSETS_URI . '/images/footer_y.png',
				'z' => THEME_ADMIN_ASSETS_URI . '/images/footer_z.png',
				'aa' => THEME_ADMIN_ASSETS_URI . '/images/footer_aa.png',
				'ab' => THEME_ADMIN_ASSETS_URI . '/images/footer_ab.png',
				'ac' => THEME_ADMIN_ASSETS_URI . '/images/footer_ac.png',
				'ad' => THEME_ADMIN_ASSETS_URI . '/images/footer_ad.png',
			),
			'default' => 'd',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Disable Footer', IT_TEXTDOMAIN ),
			'id' => 'footer_disable',
			'options' => array( 'true' => __( 'Completely disable the entire footer area', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Disable Back To Top', IT_TEXTDOMAIN ),
			'id' => 'backtotop_disable',
			'options' => array( 'true' => __( 'Disable the "Back To Top" Button', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Copyright Text', IT_TEXTDOMAIN ),
			'desc' => __( 'This will overwrite the default automatic copyright text in the left of the subfooter.', IT_TEXTDOMAIN ),
			'id' => 'copyright_text',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),		
		array(
			'name' => __( 'Credits Text', IT_TEXTDOMAIN ),
			'desc' => __( 'This will overwrite the default automatic credits text in the right of the subfooter.', IT_TEXTDOMAIN ),
			'id' => 'credits_text',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),
		array(
			'name' => __( 'Disable Subfooter', IT_TEXTDOMAIN ),
			'id' => 'subfooter_disable',
			'options' => array( 'true' => __( 'Disable the subfooter area which holds the copyright info', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
	
	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Sociable
	 */
	array(
		'name' => array( 'it_sociable_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Feedburner Feed Name', IT_TEXTDOMAIN ),
			'desc' => __( 'Necessary for the newsletter signup form to function properly. This article explains how to find your feedburner feed name: http://netprofitstoday.com/blog/how-to-find-your-feedburner-id/', IT_TEXTDOMAIN ),
			'id' => 'feedburner_name',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),
		
		array(
			'name' => __( 'RSS Feed URL', IT_TEXTDOMAIN ),
			'desc' => __( 'Necessary to connect an RSS button to your actual RSS feed URL.', IT_TEXTDOMAIN ),
			'id' => 'rss_url',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),
		
		array(
			'name' => __( 'Twitter Username', IT_TEXTDOMAIN ),
			'desc' => __( 'Not a full URL, just your Twitter username.', IT_TEXTDOMAIN ),
			'id' => 'twitter_username',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),
		
		array(
			'name' => __( 'Pinterest User URL', IT_TEXTDOMAIN ),
			'desc' => __( 'The URL for your user profile on Pinterest', IT_TEXTDOMAIN ),
			'id' => 'pinterest_url',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),	
		
		array(
			'name' => __( 'Google+ URL', IT_TEXTDOMAIN ),
			'desc' => __( "URL of the page that you want to get a count of +1's for, not your actual Google+ profile URL. Usually it is just your website's homepage URL. This is the URL that is used to generate a plus count. Enter your actual profile URL into the next box below.", IT_TEXTDOMAIN ),
			'id' => 'googleplus_url',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),	
		
		array(
			'name' => __( 'Google+ Profile URL', IT_TEXTDOMAIN ),
			'desc' => __( "Your actual Google+ profile URL. This is the link users are taken to when they click on the Google+ social count.", IT_TEXTDOMAIN ),
			'id' => 'googleplus_profile_url',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),	
		
		array(
			'name' => __( 'Youtube Username', IT_TEXTDOMAIN ),
			'desc' => __( 'Not a full URL, just your Youtube username.', IT_TEXTDOMAIN ),
			'id' => 'youtube_username',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),	
		
		array(
			'name' => __( 'Facebook Widget Settings', IT_TEXTDOMAIN ),
			'desc' => __( 'These settings apply to the Facebook tab in the Social Tabs widget.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		
		array(
			'name' => __( 'Facebook Page URL', IT_TEXTDOMAIN ),
			'desc' => __( 'The URL of your Facebook page', IT_TEXTDOMAIN ),
			'id' => 'facebook_url',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),
		
		array(
			'name' => __( 'Color Scheme', IT_TEXTDOMAIN ),
			'desc' => __( 'Light is better for light backgrounds, dark is better for dark backgrounds', IT_TEXTDOMAIN ),
			'id' => 'facebook_color_scheme',
			'options' => array( 
				'light' => __( 'Light', IT_TEXTDOMAIN ),
				'dark' => __( 'Dark', IT_TEXTDOMAIN )
			),
			'type' => 'radio'
		),
		
		array(
			'name' => __( 'Show Faces', IT_TEXTDOMAIN ),
			'id' => 'facebook_show_faces',
			'options' => array( 'true' => __( 'Show profile photos at the bottom', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Show Stream', IT_TEXTDOMAIN ),
			'id' => 'facebook_stream',
			'options' => array( 'true' => __( 'Show the profile stream for the public profile', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Show Header', IT_TEXTDOMAIN ),
			'id' => 'facebook_show_header',
			'options' => array( 'true' => __( 'Show the "Find us on Facebook" bar at the top', IT_TEXTDOMAIN ) ),
			'desc' => __( 'Note: this only displays if either the stream or faces are displayed.', IT_TEXTDOMAIN ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Border Color', IT_TEXTDOMAIN ),
			'desc' => __( 'The color of the borders around the Like Box', IT_TEXTDOMAIN ),
			'id' => 'facebook_border_color',
			'type' => 'color'
		),			
		
		array(
			'name' => __( 'Twitter Widget Settings', IT_TEXTDOMAIN ),
			'desc' => __( 'These settings apply to the Twitter tab in the Social Tabs widget as well as the Twitter followers count in the Social Counts widget.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),
		array(
			'name' => __( 'Twitter Widget Code', IT_TEXTDOMAIN ),
			'desc' => __( 'Go to https://twitter.com/settings/widgets and create a new widget. Then put the generated widget code into this box.', IT_TEXTDOMAIN ),
			'id' => 'twitter_widget_code',
			'default' => '',
			'type' => 'textarea'
		),	
		
		array(
			'name' => __( 'Flickr Widget Settings', IT_TEXTDOMAIN ),
			'desc' => __( 'These settings apply to the Flickr tab in the Social Tabs widget.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
		
		array(
			'name' => __( 'Flickr Account ID', IT_TEXTDOMAIN ),
			'desc' => __( 'Your Flickr Account ID. Use this service to find it: http://idgettr.com/', IT_TEXTDOMAIN ),
			'id' => 'flickr_id',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),
		
		array(
			'name' => __( 'Number of Photos', IT_TEXTDOMAIN ),
			'desc' => __( 'The number of photos to display in the widget.', IT_TEXTDOMAIN ),
			'id' => 'flickr_number',
			'target' => 'flickr_number',
			'type' => 'select'
		),
		
		array(
			'name' => __( 'Social Badges', IT_TEXTDOMAIN ),
			'desc' => __( 'These social badges appear in the header of your site next to the logo.', IT_TEXTDOMAIN ),
			'type' => 'heading'
		),	
	
		array(
			'name' => __( 'Social Badges', IT_TEXTDOMAIN ),
			'desc' => __( 'Social Badges', IT_TEXTDOMAIN ),
			'id' => 'sociable',
			'type' => 'sociable'
		),
		
	array(
		'type' => 'tab_end'
	),
	
	/**
	 * Advanced
	 */
	array(
		'name' => array( 'it_advanced_tab' => $option_tabs ),
		'type' => 'tab_start'
	),
	
		array(
			'name' => __( 'Custom Admin Logo', IT_TEXTDOMAIN ),
			'desc' => __( 'Upload an image to replace the default Flavor logo.', IT_TEXTDOMAIN ),
			'id' => 'admin_logo_url',
			'type' => 'upload'
		),
		array(
			'name' => __( 'Disable Image Sizes', IT_TEXTDOMAIN ),
			'desc' => __( 'If you are not using an image size anywhere in your theme and you want to block WordPress from creating an additional image for that size, you can selectively turn off creation of these images here.', IT_TEXTDOMAIN ),
			'id' => 'image_size_disable',
			'options' => array(
				"widget-post" => __("Widgets, Sliders, Facebook thumbnail images, etc.",IT_TEXTDOMAIN),
				"grid-post" => __("Post Loops (careful, you're likely using this size)",IT_TEXTDOMAIN),
				'featured-small' => __('Small Featured Slider',IT_TEXTDOMAIN),
				'featured-medium' => __('Medium Featured Slider',IT_TEXTDOMAIN),
				'featured-large' => __('Large Featured Slider',IT_TEXTDOMAIN),
				'single-180' => __('Small Single Post/Page Featured Image',IT_TEXTDOMAIN),
				'single-360' => __('Medium Single Post/Page Featured Image',IT_TEXTDOMAIN),
				'single-790' => __('Large Single Post/Page Featured Image',IT_TEXTDOMAIN),
				'single-1095' => __('Full-Width Single Post/Page Featured Image',IT_TEXTDOMAIN)
			),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Import Options', IT_TEXTDOMAIN ),
			'desc' => __( 'Copy your export code here to import your theme settings.', IT_TEXTDOMAIN ),
			'id' => 'import_options',
			'type' => 'textarea'
		),
		array(
			'name' => __( 'Export Options', IT_TEXTDOMAIN ),
			'desc' => __( 'When moving your site to a new Wordpress installation you can export your theme settings here.', IT_TEXTDOMAIN ),
			'id' => 'export_options',
			'type' => 'export_options'
		),
		
		array(
			'name' => __( 'Demo Panel', IT_TEXTDOMAIN ),
			'desc' => __( 'This is for theme preview purposes only and allows users to manipulate select parts of the style from the front-end.', IT_TEXTDOMAIN ),
			'id' => 'show_demo_panel',
			'options' => array( 'true' => __( 'Show the demo settings toggle panel', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Custom Homepage Content', IT_TEXTDOMAIN ),
			'desc' => __( 'You can add some custom content to your homepage. This will display above the post loop on the homepage.', IT_TEXTDOMAIN ),
			'id' => 'homepage_content',
			'type' => 'editor'
		),
		
		array(
			'name' => __( 'Turn Off Unique Views', IT_TEXTDOMAIN ),
			'desc' => __( 'This turns off the ip address check so that every time a page is accessed the view count increments by one.', IT_TEXTDOMAIN ),
			'id' => 'unique_views_disable',
			'options' => array( 'true' => __( 'Post views will increment on every page view', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Allow Unlimited User Ratings', IT_TEXTDOMAIN ),
			'desc' => __( 'This is only for development/testing purposes and will continually add user ratings and re-average the total score each time a user rates a criteria.', IT_TEXTDOMAIN ),
			'id' => 'rating_limit_disable',
			'options' => array( 'true' => __( 'TESTING PURPOSES ONLY', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'name' => __( 'Integrated Custom Post Types', IT_TEXTDOMAIN ),
			'desc' => __( 'Add a list of comma-separated custom post types here in order to retain the pre_get_posts minisite injection when viewing the custom post type. For instance if you are using WooCommerce and you are viewing your shop and product archive pages and you want the sliders and widgets to include your minisites, you would need to add the WooCommerce custom post type to this list (WooCommerce uses "product" as the custom post type for reference - no quotes)', IT_TEXTDOMAIN ),
			'id' => 'allowed_post_types',
			'default' => '',
			'htmlspecialchars' => true,
			'type' => 'text'
		),	
			
	array(
		'type' => 'tab_end'
	),
	
);

# add woocommerce options
if(function_exists('is_woocommerce')) {
	$woocommerce_options = array(			
		array(
			'name' => array( 'it_woocommerce_tab' => $option_tabs ),
			'type' => 'tab_start'
		),
		array(
			'name' => __( 'Disable Email Signup', IT_TEXTDOMAIN ),
			'id' => 'woo_signup_disable',
			'options' => array( 'true' => __( 'Disable the email signup form for all woocommerce pages', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		array(
			'name' => __( 'Layout', IT_TEXTDOMAIN ),
			'desc' => __( 'You can specify a layout with or without a sidebar.', IT_TEXTDOMAIN ),
			'id' => 'woo_layout',
			'options' => array(
				'sidebar-right' => THEME_ADMIN_ASSETS_URI . '/images/footer_g.png',
				'sidebar-left' => THEME_ADMIN_ASSETS_URI . '/images/footer_k.png',
				'full' => THEME_ADMIN_ASSETS_URI . '/images/footer_a.png',				
			),
			'default' => 'sidebar-right',
			'type' => 'layout'
		),
		array(
			'name' => __( 'Use "WooCommerce" Sidebar', IT_TEXTDOMAIN ),
			'id' => 'woo_sidebar_unique',
			'options' => array( 'true' => __( 'Use the "WooCommerce" instead of the "Page" sidebar', IT_TEXTDOMAIN ) ),
			'type' => 'checkbox'
		),
		
		array(
			'type' => 'tab_end'
		)
		
	);
	
	$options = array_merge($options,$woocommerce_options);
}

# add minisite options to each minisite tab
if ( isset($minisite['keys']) && $minisite['keys'] != '#' ) {
	$minisite_keys = explode(',',$minisite['keys']);
	foreach ($minisite_keys as $mkey) {
		if ( $mkey != '#') {
			$minisite_name = ( !empty( $minisite[$mkey]['name'] ) ) ? $minisite[$mkey]['name'] : '#';
			$minisite_slug = it_get_slug($minisite[$mkey]['slug'], $minisite_name);				
			
			$minisite_options = array(
			
				array(
					'name' => array( 'it_'.$minisite_slug.'_tab' => $option_tabs ),
					'type' => 'tab_start'
				),
				
				array(
					'name' => __( 'Taxonomies', IT_TEXTDOMAIN ),
					'desc' => __( 'A taxonomy is a way of classifying your minisite articles. WordPress comes with two taxonomies created for you by default: Tags and Categories. But neither of those are specific to an individual minisite because they can be assigned to any type of WordPress post. Think of a taxonomy as a minisite-specific type of grouping mechanism, and you can create as many of them as you want. A lot of times you will only need one taxonomy (for instance "Type" if your minisite is "Products"), but other times you may need more (for instance "Genre", "Developer", and "Platform" if your minisite is "Video Games"). This is not where you actually create and assign your various taxonomy items - do that when you are writing your individual articles. If you are not clear on how to use a taxonomy, take a quick look at the Codex article: http://codex.wordpress.org/Taxonomies.', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
				
				array(
					'name' => '',
					'id' => 'taxonomies_'.$minisite_slug,
					'type' => 'taxonomies',
					'minisite' => $minisite_slug
				),	
				
				array(
					'name' => __( 'Details', IT_TEXTDOMAIN ),
					'desc' => __( 'Details are additional descriptive data about the article that you want to list in the overview area. They are different than Taxonomies because they are not so much classification data as they are describing data. For instance, if you were writing an article on a movie, a taxonomy might be Director and a detail might be Plot Synopsis, because a director can be assigned to multiple movies but a plot synopsis is descriptive only for a single movie. You can of course dchoose how to use Taxonomies and Details however you want for your articles, these are just suggestions to help you understand the difference between them. It is completely up to you.', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
					
				array(
					'name' => '',
					'id' => 'details_'.$minisite_slug,
					'type' => 'details',
					'minisite' => $minisite_slug
				),	
				
				array(
					'name' => __( 'Ratings', IT_TEXTDOMAIN ),
					'desc' => __( 'These settings dictate how you want to rate the articles within this minisite, if at all. Each minisite can have unique rating options, but articles within a minisite all must share the same rating options.', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
				
				array(
					'name' => __( 'Rating Metric', IT_TEXTDOMAIN ),
					'desc' => __( 'The type of rating metric to use for this minisite', IT_TEXTDOMAIN ),
					'id' => 'rating_metric_'.$minisite_slug,
					'options' => array( 
						'stars' => __( 'Stars', IT_TEXTDOMAIN ),
						'number' => __( 'Numbers', IT_TEXTDOMAIN ),
						'percentage' => __( 'Percentages', IT_TEXTDOMAIN ),
						'letter' => __( 'Letter Grades', IT_TEXTDOMAIN )
					),
					'default' => 'stars',
					'type' => 'radio'
				),
				
				array(
					'name' => __( 'Rating Criteria (automatically averaged into the total score)', IT_TEXTDOMAIN ),
					'id' => 'criteria_'.$minisite_slug,
					'type' => 'criteria',
					'minisite' => $minisite_slug
				),	
				
				array(
					'name' => __( 'Star Color (Editor Ratings)', IT_TEXTDOMAIN ),
					'desc' => __( 'The color of the stars for the editor ratings. Only applies if Rating Metric is set to Stars.', IT_TEXTDOMAIN ),
					'id' => 'editor_star_color_'.$minisite_slug,
					'options' => array( 
						'yellow' => __( 'Yellow', IT_TEXTDOMAIN ),
						'orange' => __( 'Orange', IT_TEXTDOMAIN ),
						'red' => __( 'Red', IT_TEXTDOMAIN ),
						'green' => __( 'Green', IT_TEXTDOMAIN ),
						'blue' => __( 'Blue', IT_TEXTDOMAIN )
					),
					'default' => 'yellow',
					'type' => 'radio'
				),
				
				array(
					'name' => __( 'Star Color (User Ratings)', IT_TEXTDOMAIN ),
					'desc' => __( 'The color of the stars for the user ratings. Only applies if Rating Metric is set to Stars.', IT_TEXTDOMAIN ),
					'id' => 'user_star_color_'.$minisite_slug,
					'options' => array( 
						'yellow' => __( 'Yellow', IT_TEXTDOMAIN ),
						'orange' => __( 'Orange', IT_TEXTDOMAIN ),
						'red' => __( 'Red', IT_TEXTDOMAIN ),
						'green' => __( 'Green', IT_TEXTDOMAIN ),
						'blue' => __( 'Blue', IT_TEXTDOMAIN )
					),
					'default' => 'orange',
					'type' => 'radio'
				),
				
				array(
					'name' => __( 'Disable Editor Ratings', IT_TEXTDOMAIN ),
					'desc' => __( 'This will disable the editor ratings from appearing anywhere in this minisite.', IT_TEXTDOMAIN ),
					'id' => 'editor_rating_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Do not use editor ratings at all', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				
				array(
					'name' => __( 'Disable User Ratings', IT_TEXTDOMAIN ),
					'desc' => __( 'This will disable the user rating from appearing anywhere in this minisite.', IT_TEXTDOMAIN ),
					'id' => 'user_rating_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Do not use user ratings at all', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				
				array(
					'name' => __( 'Hide Editor Ratings', IT_TEXTDOMAIN ),
					'desc' => __( 'This should be used if you DO want to use editor ratings but ONLY want them to be visible on the full review page.', IT_TEXTDOMAIN ),
					'id' => 'editor_rating_hide_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hides editor rating from image overlays', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				
				array(
					'name' => __( 'Hide User Ratings', IT_TEXTDOMAIN ),
					'desc' => __( 'This should be used if you DO want to use user ratings but ONLY want them to be visible on the full review page.', IT_TEXTDOMAIN ),
					'id' => 'user_rating_hide_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hides user rating from image overlays', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				
				array(
					'name' => __( 'Disable Color Ranges', IT_TEXTDOMAIN ),
					'desc' => __( 'This will disable the various background colors used for each color range.', IT_TEXTDOMAIN ),
					'id' => 'color_ranges_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Do not use background color ranges', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				
				array(
					'name' => __( 'Awards & Badges', IT_TEXTDOMAIN ),
					'desc' => __( 'Each minisite has its own unique award system. Create the award here and then it will be selectable to assign to your articles on the article edit screen.', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
				
				array(
					'name' => '',
					'id' => 'awards_'.$minisite_slug,
					'type' => 'awards',
					'minisite' => $minisite_slug
				),
				
				array(
					'name' => __( 'Style', IT_TEXTDOMAIN ),
					'desc' => __( 'The look and feel of this minisite.', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
				
				array(
					'name' => __( 'Minisite Logo', IT_TEXTDOMAIN ),
					'desc' => __( 'Upload an image to use as your logo.', IT_TEXTDOMAIN ),
					'id' => 'logo_url_'.$minisite_slug,
					'type' => 'upload'
				),	
				array(
					'name' => __( 'Logo Width (optional)', IT_TEXTDOMAIN ),
					'desc' => __( 'This adds a width attribute to your logo image tag for page performance purposes. Do not include the "px" part, just the number itself.', IT_TEXTDOMAIN ),
					'id' => 'logo_width_'.$minisite_slug,
					'default' => '',
					'htmlspecialchars' => true,
					'type' => 'text'
				),		
				array(
					'name' => __( 'Logo Height (optional)', IT_TEXTDOMAIN ),
					'desc' => __( 'This adds a height attribute to your logo image tag for page performance purposes. Do not include the "px" part, just the number itself.', IT_TEXTDOMAIN ),
					'id' => 'logo_height_'.$minisite_slug,
					'default' => '',
					'htmlspecialchars' => true,
					'type' => 'text'
				),
				array(
					'name' => __( 'HD Minisite Logo', IT_TEXTDOMAIN ),
					'desc' => __( 'Upload an image to use as your logo for use on HD (hiDPI/retina) displays.', IT_TEXTDOMAIN ),
					'id' => 'logo_url_hd_'.$minisite_slug,
					'type' => 'upload'
				),				
				array(
					'name' => __( 'Icon (16px)', IT_TEXTDOMAIN ),
					'desc' => __( 'Choose a 16px by 16px square image to use as the main icon for this minisite.', IT_TEXTDOMAIN ),
					'id' => 'icon_'.$minisite_slug,
					'type' => 'upload'
				),				
				array(
					'name' => __( 'HD Icon (32px)', IT_TEXTDOMAIN ),
					'desc' => __( 'Choose a 32px by 32px square image to use as the main icon for this minisite on HD (hiDPI/retina) displays.', IT_TEXTDOMAIN ),
					'id' => 'iconhd_'.$minisite_slug,
					'type' => 'upload'
				),				
				array(
					'name' => __( 'Optional White Icon (16px)', IT_TEXTDOMAIN ),
					'desc' => __( 'Optional white version of the 16px icon for use in places with a dark background such as the featured slider. If you leave this blank the icon above will be used.', IT_TEXTDOMAIN ),
					'id' => 'iconwhite_'.$minisite_slug,
					'type' => 'upload'
				),				
				array(
					'name' => __( 'Optional White HD Icon (32px)', IT_TEXTDOMAIN ),
					'desc' => __( 'Optional white version of the 32px icon for use in places with a dark background such as the featured slider. If you leave this blank the icon above will be used.', IT_TEXTDOMAIN ),
					'id' => 'iconhdwhite_'.$minisite_slug,
					'type' => 'upload'
				),		
				array(
					'name' => __( 'Background Color', IT_TEXTDOMAIN ),
					'desc' => __( 'Use a specific background color for this minisite', IT_TEXTDOMAIN ),
					'id' => 'bg_color_'.$minisite_slug,
					'type' => 'color'
				),
				array(
					'name' => __( 'Override Site Background', IT_TEXTDOMAIN ),
					'desc' => __( 'This is useful if you have an image as your main site background but you want this color to show instead for this minisite', IT_TEXTDOMAIN ),
					'id' => 'bg_color_override_'.$minisite_slug,
					'options' => array( 'true' => __( 'Display this color instead of your main site background image', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox'
				),		
				array(
					'name' => __( 'Background Image', IT_TEXTDOMAIN ),
					'desc' => __( 'Use an image for the background of this minisite', IT_TEXTDOMAIN ),
					'id' => 'bg_image_'.$minisite_slug,
					'type' => 'upload'
				),	
				array(
					'name' => __( 'Background Position', IT_TEXTDOMAIN ),
					'id' => 'bg_position_'.$minisite_slug,
					'options' => array( 
						'left' => __( 'Left', IT_TEXTDOMAIN ),
						'center' => __( 'Center', IT_TEXTDOMAIN ),
						'right' => __( 'Right', IT_TEXTDOMAIN )
					),
					'default' => 'center',
					'type' => 'radio'
				),		
				array(
					'name' => __( 'Background Repeat', IT_TEXTDOMAIN ),
					'id' => 'bg_repeat_'.$minisite_slug,
					'options' => array( 
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
					'id' => 'bg_attachment_'.$minisite_slug,
					'options' => array( 
						'scroll' => __( 'Scroll', IT_TEXTDOMAIN ),
						'fixed' => __( 'Fixed', IT_TEXTDOMAIN )
					),
					'default' => 'scroll',
					'type' => 'radio'
				),		
				array(
					'name' => __( 'Background Container Overlay', IT_TEXTDOMAIN ),
					'desc' => __( 'This is useful if you have a busy background image or pattern', IT_TEXTDOMAIN ),
					'id' => 'bg_overlay_'.$minisite_slug,
					'options' => array( 'true' => __( 'Display transparent overlay container around main theme wrapper', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox'
				),	
				array(
					'name' => __( 'Accent Color Background', IT_TEXTDOMAIN ),
					'desc' => __( 'Used for thumbnail border hovers, tag list hovers, and other various accent styles', IT_TEXTDOMAIN ),
					'id' => 'color_accent_bg_'.$minisite_slug,
					'type' => 'color'
				),		
				array(
					'name' => __( 'Accent Color Foreground', IT_TEXTDOMAIN ),
					'desc' => __( 'Used to style text or icons that appear in an area that is styled with the accent background color specified above', IT_TEXTDOMAIN ),
					'id' => 'color_accent_fg_'.$minisite_slug,
					'type' => 'color'
				),
				array(
					'name' => __( 'Menu Background', IT_TEXTDOMAIN ),
					'desc' => __( 'The menu color', IT_TEXTDOMAIN ),
					'id' => 'color_menu_bg_'.$minisite_slug,
					'type' => 'color'
				),
				array(
					'name' => __( 'Menu Foreground', IT_TEXTDOMAIN ),
					'desc' => __( 'The menu text', IT_TEXTDOMAIN ),
					'id' => 'color_menu_fg_'.$minisite_slug,
					'type' => 'color'
				),
				array(
					'name' => __( 'Submenu Foreground', IT_TEXTDOMAIN ),
					'desc' => __( 'The submenu text', IT_TEXTDOMAIN ),
					'id' => 'color_submenu_fg_'.$minisite_slug,
					'type' => 'color'
				),
				array(
					'name' => __( 'Random Button', IT_TEXTDOMAIN ),
					'desc' => __( 'The color of the random article button', IT_TEXTDOMAIN ),
					'id' => 'color_random_button_'.$minisite_slug,
					'type' => 'color'
				),
				array(
					'name' => __( 'Flatten Main Menu', IT_TEXTDOMAIN ),
					'desc' => __( 'This is useful if you are using a very light background color for the main menu', IT_TEXTDOMAIN ),
					'id' => 'menu_flatten_'.$minisite_slug,
					'options' => array( 'true' => __( 'Remove gradient effects from main menu', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox'
				),		
				array(
					'name' => __( 'Top Ten Selector Background', IT_TEXTDOMAIN ),
					'desc' => __( 'The background color for the top ten selector area', IT_TEXTDOMAIN ),
					'id' => 'color_topten_bg_'.$minisite_slug,
					'type' => 'color'
				),	
				array(
					'name' => __( 'Top Ten Selector Foreground', IT_TEXTDOMAIN ),
					'desc' => __( 'The foreground color for the top ten selector area', IT_TEXTDOMAIN ),
					'id' => 'color_topten_fg_'.$minisite_slug,
					'type' => 'color'
				),
				array(
					'name' => __( 'Top Ten Social Background', IT_TEXTDOMAIN ),
					'desc' => __( 'The background color for the right social area for the top ten bar', IT_TEXTDOMAIN ),
					'id' => 'color_topten_social_bg_'.$minisite_slug,
					'type' => 'color'
				),
				array(
					'name' => __( 'Top Ten Social Foreground', IT_TEXTDOMAIN ),
					'desc' => __( 'The foreground color for the top ten social area', IT_TEXTDOMAIN ),
					'id' => 'color_topten_social_fg_'.$minisite_slug,
					'type' => 'color'
				),	
				array(
					'name' => __( 'Flatten Top Ten', IT_TEXTDOMAIN ),
					'desc' => __( 'This is useful if you are using a very light background color for the top ten area', IT_TEXTDOMAIN ),
					'id' => 'topten_flatten_'.$minisite_slug,
					'options' => array( 'true' => __( 'Remove gradient effects from top ten bar', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox'
				),
				
				array(
					'name' => __( 'Content Carousels', IT_TEXTDOMAIN ),
					'desc' => __( 'Settings that apply to the various sliders and content carousels throughout the minisite.', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
				array(
					'name' => __( 'Disable Trending', IT_TEXTDOMAIN ),
					'id' => 'trending_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Completely disable the trending slider for this minisite', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Targeted Trending Slider', IT_TEXTDOMAIN ),
					'desc' => __( 'If you do not check this option the trending slider for this minisite will be the same as the rest of your site.', IT_TEXTDOMAIN ),
					'id' => 'trending_targeted_'.$minisite_slug,
					'options' => array( 'true' => __( 'Display only articles from this minisite in the trending slider', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Disable Sizzlin', IT_TEXTDOMAIN ),
					'id' => 'sizzlin_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Completely disable the sizzlin slider for this minisite', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Targeted Sizzlin Slider', IT_TEXTDOMAIN ),
					'desc' => __( 'If you do not check this option the sizzlin slider for this minisite will be the same as the rest of your site.', IT_TEXTDOMAIN ),
					'id' => 'sizzlin_targeted_'.$minisite_slug,
					'options' => array( 'true' => __( 'Display only articles from this minisite in the sizzlin slider', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Targeted Featured Slider', IT_TEXTDOMAIN ),
					'desc' => __( 'If you do not check this option the featured slider for this minisite will be the same as the rest of your site.', IT_TEXTDOMAIN ),
					'id' => 'featured_targeted_'.$minisite_slug,
					'options' => array( 'true' => __( 'Display only articles from this minisite in the featured slider', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Disable Featured Slider', IT_TEXTDOMAIN ),
					'id' => 'featured_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Completely disable the featured slider for this minisite', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Featured Slider Layout', IT_TEXTDOMAIN ),
					'desc' => __( 'There are three available layouts: carousel with two widgets, carousel with one widget, and full-width carousel with no widgets.', IT_TEXTDOMAIN ),
					'id' => 'featured_layout_'.$minisite_slug,
					'options' => array(
						'small' => THEME_ADMIN_ASSETS_URI . '/images/featured_small.png',
						'medium' => THEME_ADMIN_ASSETS_URI . '/images/featured_medium.png',
						'large' => THEME_ADMIN_ASSETS_URI . '/images/featured_large.png',
					),
					'default' => 'small',
					'type' => 'layout'
				),
				array(
					'name' => __( 'Unique Widget Panels', IT_TEXTDOMAIN ),
					'id' => 'featured_unique_sidebar_'.$minisite_slug,
					'options' => array( 'true' => __( 'Use minisite-specific widget panels instead of the defaults', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Top Ten Slider', IT_TEXTDOMAIN ),
					'id' => 'topten_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Completely disable the top ten slider for this minisite', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Targeted Top Ten Slider/Widgets', IT_TEXTDOMAIN ),
					'desc' => __( 'If you do not check this option the top ten slider and widgets for this minisite will be the same as the rest of your site.', IT_TEXTDOMAIN ),
					'id' => 'topten_targeted_'.$minisite_slug,
					'options' => array( 'true' => __( 'Display only articles from this minisite in the top ten slider/widgets', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				
				
				
				array(
					'name' => __( 'Post Loops', IT_TEXTDOMAIN ),
					'desc' => __( 'Settings that apply to the post loop on the front page and any taxonomy listing pages within this minisite.', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
				array(
					'name' => __( 'Disable Layout', IT_TEXTDOMAIN ),
					'id' => 'layout_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Disable the grid/list layout switcher', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Title', IT_TEXTDOMAIN ),
					'id' => 'loop_title_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Disable the post loop title next to the filtering buttons', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Filtering', IT_TEXTDOMAIN ),
					'id' => 'filtering_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Disable the filter buttons in the control bar', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Email Signup', IT_TEXTDOMAIN ),
					'id' => 'signup_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Disable the email signup form in the control bar', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Layout', IT_TEXTDOMAIN ),
					'desc' => __( 'You can select between four main layouts: grid style with and without sidebar, and list style with and without sidebar.', IT_TEXTDOMAIN ),
					'id' => 'layout_'.$minisite_slug,
					'options' => array(
						'a' => THEME_ADMIN_ASSETS_URI . '/images/loop_a.png',
						'b' => THEME_ADMIN_ASSETS_URI . '/images/loop_b.png',
						'e' => THEME_ADMIN_ASSETS_URI . '/images/loop_e.png',
						'f' => THEME_ADMIN_ASSETS_URI . '/images/loop_f.png',
						'c' => THEME_ADMIN_ASSETS_URI . '/images/loop_c.png',
						'd' => THEME_ADMIN_ASSETS_URI . '/images/loop_d.png',
					),
					'default' => 'a',
					'type' => 'layout'
				),
				
				array(
					'name' => __( 'Single Article Pages', IT_TEXTDOMAIN ),
					'desc' => __( 'Settings that apply to the detail view of a single article', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
				array(
					'name' => __( 'Disable Minisite Label', IT_TEXTDOMAIN ),
					'id' => 'sortbar_label_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the name of the minisite and icon from the control bar', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Awards', IT_TEXTDOMAIN ),
					'id' => 'sortbar_awards_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the awards from the control bar', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Views', IT_TEXTDOMAIN ),
					'id' => 'sortbar_views_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the view count from the control bar', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Like Count', IT_TEXTDOMAIN ),
					'desc' => __( 'NOTE: This does not hide the like button from the post info box, just the count at the top', IT_TEXTDOMAIN ),
					'id' => 'sortbar_likes_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the like count from the control bar', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Comments', IT_TEXTDOMAIN ),
					'desc' => __( 'NOTE: This does not hide the comment area below the article, just the count at the top', IT_TEXTDOMAIN ),
					'id' => 'sortbar_comments_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the comment count from the control bar', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Date/Author', IT_TEXTDOMAIN ),
					'id' => 'date_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the date and author from the control bar', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				
				array(
					'name' => __( 'Positives Label', IT_TEXTDOMAIN ),
					'desc' => __( 'Used as the title for the positives section', IT_TEXTDOMAIN ),
					'id' => 'positives_label_'.$minisite_slug,
					'htmlentities' => true,
					'type' => 'text'
				),
				array(
					'name' => __( 'Negatives Label', IT_TEXTDOMAIN ),
					'desc' => __( 'Used as the title for the negatives section', IT_TEXTDOMAIN ),
					'id' => 'negatives_label_'.$minisite_slug,
					'htmlentities' => true,
					'type' => 'text'
				),
				array(
					'name' => __( 'Bottom Line Label', IT_TEXTDOMAIN ),
					'desc' => __( 'Used as the title for the bottom line section', IT_TEXTDOMAIN ),
					'id' => 'bottomline_label_'.$minisite_slug,
					'htmlentities' => true,
					'type' => 'text'
				),
				array(
					'name' => __( 'Total Score Label', IT_TEXTDOMAIN ),
					'desc' => __( 'Used as the label for the total editor score', IT_TEXTDOMAIN ),
					'id' => 'total_score_label_'.$minisite_slug,
					'htmlentities' => true,
					'type' => 'text'
				),
				array(
					'name' => __( 'User Score Label', IT_TEXTDOMAIN ),
					'desc' => __( 'Used as the label for the total user score', IT_TEXTDOMAIN ),
					'id' => 'total_user_score_label_'.$minisite_slug,
					'htmlentities' => true,
					'type' => 'text'
				),
				array(
					'name' => __( 'Hide Number of User Ratings', IT_TEXTDOMAIN ),
					'id' => 'user_ratings_number_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide number of user ratings next to the total user score label', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Disable Rating Animations', IT_TEXTDOMAIN ),
					'id' => 'rating_animations_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the slightly darker animation color that slides in from the left', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Disable Top User Ratings', IT_TEXTDOMAIN ),
					'id' => 'user_ratings_top_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Select this to only allow user rating submissions via comments', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Hide Badges', IT_TEXTDOMAIN ),
					'desc' => __( 'If taxonomies, details, and badges are all hidden the entire details box will not be displayed', IT_TEXTDOMAIN ),
					'id' => 'badges_hide_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the listing of badges in the details box', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Hide Taxonomies', IT_TEXTDOMAIN ),
					'desc' => __( 'If taxonomies, details, and badges are all hidden the entire details box will not be displayed', IT_TEXTDOMAIN ),
					'id' => 'taxonomies_hide_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the listing of taxonomies in the details box', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Hide Details', IT_TEXTDOMAIN ),
					'desc' => __( 'If taxonomies, details, and badges are all hidden the entire details box will not be displayed', IT_TEXTDOMAIN ),
					'id' => 'details_hide_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the listing of details in the details box', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Details Label', IT_TEXTDOMAIN ),
					'desc' => __( 'The title text to display next to the minisite icon in the details section', IT_TEXTDOMAIN ),
					'id' => 'details_label_'.$minisite_slug,
					'htmlentities' => true,
					'type' => 'text'
				),
				array(
					'name' => __( 'Hide Post Info Box', IT_TEXTDOMAIN ),
					'id' => 'postinfo_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the post info area (tags, categories, author info...)', IT_TEXTDOMAIN ) ),
					'type' => 'checkbox',
					'minisite' => $minisite_slug
				),
				array(
					'name' => __( 'Disable Like Button', IT_TEXTDOMAIN ),
					'id' => 'likes_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the like button from the post info box', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Categories', IT_TEXTDOMAIN ),
					'id' => 'categories_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the category list from the post info box', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Tags', IT_TEXTDOMAIN ),
					'id' => 'tags_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the tag list from the post info box', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),				
				array(
					'name' => __( 'Disable Author Info', IT_TEXTDOMAIN ),
					'id' => 'author_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the author information from the post info box', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Recommended', IT_TEXTDOMAIN ),
					'id' => 'recommended_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Hide the recommended posts section', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Recommended Method', IT_TEXTDOMAIN ),
					'id' => 'recommended_method_'.$minisite_slug,
					'desc' => __( 'For the "Same tags OR same categories" method, use the "Number of Recommended Filters" option below to set how many of EACH will display, rather than how many TOTAL as is applied to the rest of the methods. So setting this to "2" will cause the first two tags and the first two categories to display, resulting in four total filters.', IT_TEXTDOMAIN ),
					'options' => array( 
						'tags' => __( 'Same tags', IT_TEXTDOMAIN ),
						'categories' => __( 'Same categories', IT_TEXTDOMAIN ),
						'tags_categories' => __( 'Same tags OR same categories (tags will appear first in order)', IT_TEXTDOMAIN ),
						'primary_taxonomy' => __( 'Same taxonomy terms (all assigned terms from primary taxonomy)', IT_TEXTDOMAIN ),
						'taxonomies' => __( 'Same taxonomy terms (first assigned term from each taxonomy)', IT_TEXTDOMAIN ),
						'mixed' => __( 'Mixed: one tab for tag, category, and each taxonomy', IT_TEXTDOMAIN )
					),
					'default' => 'tags',
					'type' => 'radio'
				),	
				array(
					'name' => __( 'Recommended Label', IT_TEXTDOMAIN ),
					'desc' => __( 'The title text to display in the title of the recommended section', IT_TEXTDOMAIN ),
					'id' => 'recommended_label_'.$minisite_slug,
					'htmlentities' => true,
					'type' => 'text'
				),
				array(
					'name' => __( 'Number of Recommended Filters', IT_TEXTDOMAIN ),
					'desc' => __( 'The number of filter buttons to display in the recommended filter bar.', IT_TEXTDOMAIN ),
					'id' => 'recommended_filters_num_'.$minisite_slug,
					'target' => 'recommended_filters_number',
					'type' => 'select'
				),
				array(
					'name' => __( 'Disable Recommended Filters', IT_TEXTDOMAIN ),
					'id' => 'recommended_filters_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Disable the filter buttons from the recommended section', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Number of Recommended Posts', IT_TEXTDOMAIN ),
					'desc' => __( 'The number of total posts to display in the recommended section.', IT_TEXTDOMAIN ),
					'id' => 'recommended_num_'.$minisite_slug,
					'target' => 'recommended_number',
					'type' => 'select'
				),
				array(
					'name' => __( 'Targeted Recommended', IT_TEXTDOMAIN ),
					'id' => 'recommended_targeted_'.$minisite_slug,
					'options' => array( 'true' => __( 'Show only recommended articles from this minisite', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Comment Ratings', IT_TEXTDOMAIN ),
					'id' => 'user_comment_rating_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Do not allow users to rate articles in the comments', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Comment Pros/Cons', IT_TEXTDOMAIN ),
					'id' => 'user_comment_procon_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Do not allow users to enter pros and cons with their comment', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Allow Blank Comments', IT_TEXTDOMAIN ),
					'desc' => __( 'Use this if you want your users to be able to submit ratings and/or pros/cons without having to additionally enter standard comment text. Only applies if user comment ratings are enabled.', IT_TEXTDOMAIN ),
					'id' => 'allow_blank_comments_'.$minisite_slug,
					'options' => array( 'true' => __( 'Allow users to post comments without actual comment text', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				
				array(
					'name' => __( 'General', IT_TEXTDOMAIN ),
					'desc' => __( 'Settings that apply to all areas of the minisite', IT_TEXTDOMAIN ),
					'type' => 'heading'
				),
				array(
					'name' => __( 'Disable Top Menu', IT_TEXTDOMAIN ),
					'id' => 'topmenu_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Disable the top menu for this minisite', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Submenu', IT_TEXTDOMAIN ),
					'id' => 'submenu_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Disable the submenu for this minisite', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Taxonomy Submenu', IT_TEXTDOMAIN ),
					'id' => 'taxonomy_submenu_'.$minisite_slug,
					'options' => array( 'true' => __( 'Display primary taxonomy terms in place of standard submenu', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Unique Sidebar', IT_TEXTDOMAIN ),
					'id' => 'unique_sidebar_'.$minisite_slug,
					'options' => array( 'true' => __( 'Use minisite-specific sidebars instead of defaults', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Disable Frontpage Content', IT_TEXTDOMAIN ),
					'id' => 'content_disable_'.$minisite_slug,
					'options' => array( 'true' => __( 'Disable the content on the home page and only show the post loop', IT_TEXTDOMAIN ) ), 
					'type' => 'checkbox'
				),
				array(
					'name' => __( 'Background Ad URL', IT_TEXTDOMAIN ),
					'desc' => __( 'The URL to direct the user to when they click anywhere on the background. Leave this blank to disable it. For the image to use for the ad, use the page background image URL options.', IT_TEXTDOMAIN ),
					'id' => 'ad_background_'.$minisite_slug,
					'htmlentities' => true,
					'type' => 'text'
				),						
					
				array(
					'type' => 'tab_end'
				)
				
			);
			
			$options = array_merge($options,$minisite_options);
			
		}
	}
}

return array(
	'load' => true,
	'name' => 'options',
	'options' => $options
);
	
?>
