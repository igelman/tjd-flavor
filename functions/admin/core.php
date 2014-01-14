<?php
/**
 *
 */
function it_options_init() {
	register_setting( IT_SETTINGS, IT_SETTINGS );
	
	# Add default options if they don't exist
	add_option( IT_SETTINGS, it_options( 'settings', 'default' ) );
	add_option( IT_INTERNAL_SETTINGS, it_options( 'internal', 'default' ) );
	# delete_option(IT_SETTINGS);
	# delete_option(IT_INTERNAL_SETTINGS);
	
	if( it_ajax_request() ) {
		# Ajax option save
		if( isset( $_POST['it_option_save'] ) ) {
			it_ajax_option_save();
			
		# Sidebar option save
		} elseif( isset( $_POST['it_sidebar_save'] ) ) {
			it_sidebar_option_save();
			
		} elseif( isset( $_POST['it_sidebar_delete'] ) ) {
			it_sidebar_option_delete();
						
		} elseif( isset( $_POST['action'] ) && $_POST['action'] == 'add-menu-item' ) {
			add_filter( 'nav_menu_description', create_function('','return "";') );
		}
	}
	
	# Option import
	if( ( !it_ajax_request() ) && ( isset( $_POST['it_import_options'] ) ) ) {
		it_import_options( $_POST[IT_SETTINGS]['import_options'] );

	# Reset options
	} elseif( ( !it_ajax_request() ) && ( isset( $_POST[IT_SETTINGS]['reset'] ) ) ) {
		it_load_defaults();
		wp_redirect( admin_url( 'admin.php?page=it-options&reset=true' ) );
		exit;
		
	# load demo settings
	} elseif( ( !it_ajax_request() ) && ( isset( $_POST[IT_SETTINGS]['load_demo'] ) ) ) {
		it_load_demo();
		wp_redirect( admin_url( 'admin.php?page=it-options&demo=true' ) );
		exit;
		
	# Confirm minisites
	} elseif( ( !it_ajax_request() ) && ( isset( $_POST[IT_SETTINGS]['confirm_minisites'] ) ) ) {		
		it_confirm_minisites();
		
	# Confirm taxonomies
	} elseif( ( !it_ajax_request() ) && ( isset( $_POST[IT_SETTINGS]['confirm_taxonomies'] ) ) ) {			
		it_confirm_taxonomies();
		
	# $_POST option save
	} elseif( ( !it_ajax_request() ) && ( isset( $_POST['it_admin_wpnonce'] ) ) ) {
		unset(  $_POST[IT_SETTINGS]['export_options'] );
	}
	
}

/**
 *
 */
function it_sidebar_option_delete() {
	check_ajax_referer( IT_SETTINGS . '_wpnonce', 'it_admin_wpnonce' );
	
	$data = $_POST;
	
	$saved_sidebars = get_option( IT_SIDEBARS );
	
	$msg = array( 'success' => false, 'sidebar_id' => $data['sidebar_id'], 'message' => sprintf( __( 'Error: Sidebar &quot;%1$s&quot; not deleted, please try again.', IT_TEXTDOMAIN ), $data['it_sidebar_delete'] ) );
	
	unset( $saved_sidebars[$data['sidebar_id']] );
	
	if( update_option( IT_SIDEBARS, $saved_sidebars ) ) {
		$msg = array( 'success' => 'deleted_sidebar', 'sidebar_id' => $data['sidebar_id'], 'message' => sprintf( __( 'Sidebar &quot;%1$s&quot; Deleted.', IT_TEXTDOMAIN ), $data['it_sidebar_delete'] ) );
	}
	
	$echo = json_encode( $msg );

	@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
	echo $echo;
	exit;
}

/**
 *
 */
function it_sidebar_option_save() {
	check_ajax_referer( IT_SETTINGS . '_wpnonce', 'it_admin_wpnonce' );
	
	$data = $_POST;
	
	$saved_sidebars = get_option( IT_SIDEBARS );
	
	$msg = array( 'success' => false, 'sidebar' => $data['custom_sidebars'], 'message' => sprintf( __( 'Error: Sidebar &quot;%1$s&quot; not saved, please try again.', IT_TEXTDOMAIN ), $data['custom_sidebars'] ) );
	
	if( empty( $saved_sidebars ) ) {
		$update_sidebar[$data['it_sidebar_id']] = $data['custom_sidebars'];
		
		if( update_option( IT_SIDEBARS, $update_sidebar ) )
			$msg = array( 'success' => 'saved_sidebar', 'sidebar' => $data['custom_sidebars'], 'sidebar_id' => $data['it_sidebar_id'], 'message' => sprintf( __( 'Sidebar &quot;%1$s&quot; Added.', IT_TEXTDOMAIN ), $data['custom_sidebars'] ) );
		
	} elseif( is_array( $saved_sidebars ) ) {
		
		if( in_array( $data['custom_sidebars'], $saved_sidebars ) ) {
			$msg = array( 'success' => false, 'sidebar' => $data['custom_sidebars'], 'message' => sprintf( __( 'Sidebar &quot;%1$s&quot; Already Exists.', IT_TEXTDOMAIN ), $data['custom_sidebars'] ) );
			
		} elseif( !in_array( $data['custom_sidebars'], $saved_sidebars ) ) {
			$sidebar[$data['it_sidebar_id']] = $data['custom_sidebars'];
			$update_sidebar = $saved_sidebars + $sidebar;
			
			if( update_option( IT_SIDEBARS, $update_sidebar ) )
				$msg = array( 'success' => 'saved_sidebar', 'sidebar' => $data['custom_sidebars'], 'sidebar_id' => $data['it_sidebar_id'], 'message' => sprintf( __( 'Sidebar &quot;%1$s&quot; Added.', IT_TEXTDOMAIN ), $data['custom_sidebars'] ) );
			
		}
	}
		
	$echo = json_encode( $msg );

	@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
	echo $echo;
	exit;
}

/**
 *
 */
function it_ajax_option_save() {
	check_ajax_referer( IT_SETTINGS . '_wpnonce', 'it_admin_wpnonce' );
	
	$data = it_prep_data($_POST);
	
	$count = count($_POST, COUNT_RECURSIVE);
	
	unset( $data['_wp_http_referer'], $data['_wpnonce'], $data['action'], $data['it_full_submit'], $data[IT_SETTINGS]['export_options'] );
	unset( $data['it_admin_wpnonce'], $data['it_option_save'], $data['option_page'] );
	
	$msg = array( 'success' => false, 'message' => __( 'Error: Options not saved, please try again.', IT_TEXTDOMAIN ) );
	
	if( get_option( IT_SETTINGS ) != $data[IT_SETTINGS] ) {
		
		if( update_option( IT_SETTINGS, $data[IT_SETTINGS] ) )
			$msg = array( 'success' => 'options_saved', 'message' => $count . __( ' Total Options Saved.', IT_TEXTDOMAIN ) );
			
	} else {
		$msg = array( 'success' => true, 'message' => $count . __( ' Total Options Saved.', IT_TEXTDOMAIN ) );
	}
	
	$echo = json_encode( $msg );

	@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
	echo $echo;
	exit;
}

function it_confirm_minisites() {
	$data = it_prep_data($_POST);
	
	#die(var_export($data));
	
	flush_rewrite_rules();
	
	unset( $data['_wp_http_referer'], $data['_wpnonce'], $data['action'], $data['it_full_submit'], $data[IT_SETTINGS]['export_options'] );
	unset( $data['it_admin_wpnonce'], $data['it_option_save'], $data['option_page'] );
	
	$msg = array( 'success' => false, 'message' => __( 'Error: Options not saved, please try again.', IT_TEXTDOMAIN ) );
	
	if( get_option( IT_SETTINGS ) != $data[IT_SETTINGS] ) {
		
		if( update_option( IT_SETTINGS, $data[IT_SETTINGS] ) )
			$msg = array( 'success' => 'options_saved', 'message' => __( 'Options Saved.', IT_TEXTDOMAIN ) );
			
	} else {
		$msg = array( 'success' => true, 'message' => __( 'Options Saved.', IT_TEXTDOMAIN ) );
	}
	wp_redirect( admin_url( 'admin.php?page=it-options&confirm_minisites=true' ) );
	exit;	
}

function it_confirm_taxonomies() {
	$data = it_prep_data($_POST);
	
	#die(var_export($data));
	
	flush_rewrite_rules();
	
	unset( $data['_wp_http_referer'], $data['_wpnonce'], $data['action'], $data['it_full_submit'], $data[IT_SETTINGS]['export_options'] );
	unset( $data['it_admin_wpnonce'], $data['it_option_save'], $data['option_page'] );
	
	$msg = array( 'success' => false, 'message' => __( 'Error: Options not saved, please try again.', IT_TEXTDOMAIN ) );
	
	if( get_option( IT_SETTINGS ) != $data[IT_SETTINGS] ) {
		
		if( update_option( IT_SETTINGS, $data[IT_SETTINGS] ) )
			$msg = array( 'success' => 'options_saved', 'message' => __( 'Options Saved.', IT_TEXTDOMAIN ) );
			
	} else {
		$msg = array( 'success' => true, 'message' => __( 'Options Saved.', IT_TEXTDOMAIN ) );
	}
	wp_redirect( admin_url( 'admin.php?page=it-options&confirm_taxonomies=true' ) );
	exit;	
}

/**
 * 
 */
function it_shortcode_generator() {

	$shortcodes = it_shortcodes();
	
	$options = array();
	
	foreach( $shortcodes as $shortcode ) {
		$shortcode = str_replace( '.php', '',$shortcode );
		$shortcode = preg_replace( '/[0-9-]/', '', $shortcode );
		
		if( $shortcode[0] != '_' ) {
			$class = 'it' . ucwords( $shortcode );
			$options[] = call_user_func( array( &$class, '_options'), $class );
		}
	}
	
	return $options;
}

/**
 *
 */
function it_check_wp_version(){
	global $wp_version;
	
	$check_WP = '3.0';
	$is_ok = version_compare($wp_version, $check_WP, '>=');
	
	if ( ($is_ok == FALSE) ) {
		return false;
	}
	
	return true;
}

/**
 * 
 */
function it_sociable_option() {
	$sociables = array(
		'twitter' => 'Twitter',
		'facebook' => 'Facebook',
		'googleplus' => 'Google+',
		'pinterest' => 'Pinterest',
		'vimeo' => 'Vimeo',
		'tumblr' => 'Tumblr',
		'instagram' => 'Instagram',
		'flickr' => 'Flickr',
		'youtube' => 'Youtube',
		'linkedin' => 'LinkedIn',
		'stumbleupon' => 'StumbleUpon',
		'skype' => 'Skype'
		);
	
	return array( 'sociables' => $sociables );
}

/**
 *
 */
function it_signoffs() {
	$signoff = it_get_setting('signoff');
	if ( isset($signoff['keys']) && $signoff['keys'] != '#' ) {
		$signoff_keys = explode(',',$signoff['keys']);
		foreach ($signoff_keys as $skey) {
			if ( $skey != '#') {
				$signoff_name = ( !empty( $signoff[$skey]['name'] ) ) ? $signoff[$skey]['name'] : '#';	
				$options[$signoff_name] = $signoff_name;	
			}
		}
	}
	return $options;
}

/**
 * 
 */
function it_tinymce_init_size() {
	if( isset( $_GET['page'] ) ) {
		if( $_GET['page'] == 'it-options' ) {
			$tinymce = 'TinyMCE_' . IT_SETTINGS . '_content_size';
			if( !isset( $_COOKIE[$tinymce] ) )
				setcookie($tinymce, 'cw=577&ch=251');
		}
	}
}

/**
 *
 */
function it_import_options( $import ) {
	
	$imported_options = it_decode( $import, $serialize = true );
	
	if( is_array( $imported_options ) ) {
		
		if( array_key_exists( 'it_options_export', $imported_options ) ) {
			if( get_option( IT_SETTINGS ) != $imported_options ) {

				if( update_option( IT_SETTINGS, $imported_options ) )
					wp_redirect( admin_url( 'admin.php?page=it-options&import=true' ) );
				else
					wp_redirect( admin_url( 'admin.php?page=it-options&import=false' ) );

			} else {
				wp_redirect( admin_url( 'admin.php?page=it-options&import=true' ) );
			}
			
		} else {
			wp_redirect( admin_url( 'admin.php?page=it-options&import=false' ) );
		}
		
	} else {
		wp_redirect( admin_url( 'admin.php?page=it-options&import=false' ) );
	}
	
	exit;
}

/**
 *
 */
function it_load_defaults() {
	update_option( IT_SETTINGS, it_options( 'settings', 'default' ) );
	update_option( IT_WIDGETS, it_options( 'widgets', 'default' ) );
	update_option( IT_MODS, it_options( 'mods', 'default' ) );
	delete_option( IT_SIDEBARS );	
}

/**
 *
 */
function it_load_demo() {
	#load the theme options
	update_option( IT_SETTINGS, it_options( 'settings', 'demo' ) );
	#load the sidebar_widgets array
	update_option( IT_WIDGETS, it_options( 'widgets', 'demo' ) );
	#load the theme_mods array
	update_option( IT_MODS, it_options( 'mods', 'demo' ) );
	
	#load each individual widget
	$options = it_decode( it_options( 'widget', 'demo' ), $serialize = true );
	foreach ($options as $option_name => $option_value) {
		update_option($option_name, $option_value);
	}
}

/**
 *
 */
function it_options( $type, $state ) {	

	$options = '';
	
	switch($type) {
		case "settings":
		
			if($state=='demo') {
				
				#demo settings code
				$options = '7V1tj9s2tv4rugiw_TKTsfwi2ymKu2maZnORpkFmtsUuFhBoibbZyKIqUTNxiv73S8kWJfFFomzJbrcM2iKd4Tk8PHx4-KrzgBfjxfzFb8kLe_zib7-mmHztoyQKwN4N8AYffvJ18mJ6_CWJU8h-aC8LEZh4MYoIwqFLxcEqKAqBF_aL39CLkVzF71RH8eOsuhWIXVo1TgmrY3b8dbIDQcB-uqgIuWlc_mJeqNsSEr24u3t6enqOQj9NSIxAQLZwB5PnHt7drQPwiOO7p-jWwyGBIblLowADP7kbj-zJ3Wh2l-l-HoWbsrk2V6u79cuK571W_Pfx53rdo2rdT8gnW_a7yfFX49lEYesWos2WCAL2dFoKVPoBha7g2MWoz_ah8Fbt3jV4RFS2Vn1RO_uBU5hbh4tdtC1BPqRwuo1rLWeIXUNA0hj6LtqBDXQT9AUK7pk4I9E9IATBniAvcT3sQ7V9rMM86iK8c70kURde1sv-0lDULgaEhwMcu8DzqIvdVenGAofPpitnshq1CK4lguv8j-jSg-AOhqm0vvnImc_tRrFKbWLDnFr5JF3xIqymFVyNVmuxUw-SMQh96sRVSggOJcIz4K8clVsIjihmT_DnUVDDn-OxTDDBHh1HOhU3yuv0Z-H6NR2feb9oIDMvu8L-vqEDp3zZ-rASBcZVARrTNpA0IX8uKd5WRTnpwNBH4cYliARQNOHh4-v33719_8Z6_-PPpadtqbS7wytUUbLgdJTyI16edlkAogR1mSDnvBJEw2r8CMrgWJg5E8HJhMJ0t4KxILKQRc4vXwI6BdQ9VTTy_u2___3u7fuvxHFbyHmAwA2Om5Bi81WBjWDZXOzEoriiLRO1TUqXLdS1wPUaekRYAa1BJewzP7P5RGv5UkK5ENNw2piXkXnNaTCt3W2CWTp-E6ZUQiNwgrLFoDBbH2JzOUCmKj_w3mfgXJe2jCdKaR_SfmiAVINoEkHoN4kK_RADko0wHYtthWzd3qIG226XbDVXEARPIPbV1hIxfHGicmPH41ZBua2LBlsP8ZazlY2EA5piTGjfNVR-UKJw8axVUG71ssHqR7r2xFrxQ6zzIKswdtwqWDeWCY5E5x3XDQFYwUAYpw8_frCqQvO6EEE7GMEYYV8AUC3MlZsWHPEzbxGe3r18eH3_YL38-PD21bvX92IMz4XhDqCAM5b54D5dZfvPFbQeEbBe32ZlZTshqoffLhRWK2zOnao7U7NoBmJvix6hy_qm456YRacEZqpO11M0L8r2OKdqsWtauu23mEvqFuhuuoqpBaRkm-1Z_F02L4edfDCq64C7iOxPPqQolHz2grRx92fXBWIcwOahMq4L4NivTNXF4itEdNMGdpX44bBDm5gGm2z-OhksRSOmo-npWKkq6QgVBjOckB7Ammk5Faw1CzTBygZtLk17A-_o3sqnCnaQdqkvWZuDTVKsOxBMxBWGoKke_9jw-BdOrV3WFAsECbYC9KmCkIVK2RoFdHGXZMtCIR5OxSAryMvkHAGydMQiuhgsO3CWdaBN_zLJTh0Ll9dQXYD6B_xY9QtboAfpRii7q5ctJqxDsBCn72Jl9Tt6MW60hQHqp2wqsN7Q37VYxMZAPnlsagIdzJo0mlUoegPqu2apRUXhDVdY25ryR89YP6ptE6Kh1CihVJs1o9KaQuEnuBdbY9-MbyY3z8rSczbqNyFmByF5Cw5IHCubwtCfQ9H6COl_n6z7mqKyguOBZxkmWES5hz5dmzzCOAZWgoMAeYikdF9u4dhDFiRWFEOC0t1z6z6lSzi6ZU8SaFF9CY3qkMahG-vXFOwsKvEIQ-ohghIr2gIqFYMbKwERgqEVAC9NLLrThEkKfEADwSYN6K9DHBYVWAQGAS3kI6oNUjRQfQEitF5qIZ0_6D9WiFbbGyui_wNy0SD1CBWho9iiviE31hqkHlrRH1FxQJv83PoBxytEVW2RR2u0siFvAVIo3GUtTurGH01O6HwM4-fWW6pmA2OLas4lWBtIjBKCfk3h8yYwjvXBqOosrqAKZezsvIowtk7x3S0E1Znbdgpj_xbQ31vbGK6_qR2k50fnaxzDhDwPIbn736zEW3ay_pCfrB9VbA5q0G5jJbH3TV8H8gnYRQG8BX55Hk9nEvJN0ajjj-6K-u9A_jf-jJC2fo0xqbRefSpOyybbdL3Wn9mdUrJ5xiqXUtn5hRdTR4Sk4aysYj4d1AkkDXu9ZXVzQAUqx93O7E_U0beT0ei8zi48MP7Le2Dyl_fA9C_vgVn7DUpR1NEvOtcvutAvutQIzyy-jRoKszPUPOirjlZ88YTdw9E-33q5BH4mGkfeNIj7iCRtxcsTb-iv0jikRumtBeIkab5tZm2N8hNxCt5aebbJOoKTlcoRCcNHQPCd6IcNxpsARnQ9Vtdmi1ftBx2ZOsmFAPDgCuNPNSWTqpLkqKUoWTFLcoxZqDtexHrZ2BIuNALFvXohnGzxk5v9X6K9dZ8JOkgMwa7zAUbdhtqCTP_IjClZ5UcxB2do3F6TJ0Sy0ZAmMK5vJIvmccFOcld5VKG4r5Hd8h0laCylqBNfhjijkeRS8iiUDSrXh3RRH-gMrppYZZvW7pJcAoVNbwAWMpGYDpF913oahzNDK92IfYpd5IuDajZ27Lk9m_39_UhyR3oUVPTQUjgFyW_na8dYU-kxSPbuRYhOx3YJRx6H1yLt2-4AhZ9KSCzE4HKsII8KcnTKjklqthYeRWFCwCYGu36sHXOvqph6MYLJjkxqJi64kd2TP7vFWWrltNFKNgWXk0M_hs54QzPVzw-15Hbao_l4MRsvp8vl1J469tgZj6Z32bFfUho_azR-xk6-dhD3Yza_16c2OFpIZHNwT0jsMMVTG-d6I5vGj6CvkS3x1OIaY3Y6ESPMIV7mroq2mODk7okuwGN4KzhuqeW4bKWZruBgjrNHWqEkU0NXpuFwobmoIXcd_TcC4V6IKHbzPFI-tzngLY1wOJznxloRIvm0j4bqvtbT6uFnWc3DRBZUDmfW05vZjXMzv1ncLG_s0Y1t39jj6jFjeQ2ZX4QKb3TVz9_QLjvOdXH-RDvRWEtt8Q7md7Ytx6TVtwHgMw7xDsHErV3G5Fc-5Zix-d9OlWe3BVjewDCGzbcJRdFNrWhhWhSjHYj3yguObEfwYzn1IvJwaMtRgmLht0q4TD60qCsfXlRWlnILO7S7qD8Ba-ie6ie2vj10glu3puij7G17ffNSF7utiv3-eyk45jtXfUtUiHx3vLlutnvB3XP30XGrfB8o6SpFZV0bou6wbm1nW69jnwlmqbutLnnLSVZ7bqLfc2w--eHDy5fWx_wdmuaVKJ2_QFwVGKYD1fWd0iR1P9bEXC1PzOt9KTFQ3Zt12VtBttqfz_j-HPd7X9v5FpbNRT4kAAUdJ4lx62OBdzDcsI9iDrBaMFR9l9d5xNSkm64mALA3anKBHa3Wld7buzWJxhCqbjo7jvgQYGLd70McJSg5zwWNOhvHAjsipYJuohIUXcKA4cokG2NUg2sKmQ8x9lMvW_ZY99mVOz7XP62KG53EXuMwaTdRSEs8tWSeUok3hgBbNwR0HtzsUPH4KpnaHiPP5R7lsLAcwTj7-oiuLyWf08SILsMR4I13GuND-5h-6dWibPHjp_qncZwjhOnp1dG6I06mw1SrE3KAJ52kGkJOTeLEVdu0Eh0u5sszKm3y5LQSsDT8OKuGqfNWUEWXvMLZlhxezJHn1dvky_JVTV2z2p2F29y6SNWjU32PLmsr6ksO9HNrbvLqsrYR0RvurA0uL9XX8lCvXao5ZMkdeTyTPKDPbnuzq0cCjhdv_ExSmLCHQYCfxJez2e2bhjiOQbiRLGHyz1WEFardvER11GuGwqxvYUIsvLaylwhCGKqfMI57-9w6b8wtiXG03dc-uHYqFVe-ZF_MBqma_6J9Wan9aYsqX_AsFoMYcJvXIv_k_OCCuhlLe0Az6t44RJ85Cz4vs_LVHbCBlYFVd1iV0zJKvs3eflfOTRrXfxqY-z63zqog9WKYO5S7CuYO5a6IuUO5q2OuakavoczAysDqnFA26RDKJuzbDjp93pM0DGXHt3XQ9d3z-TO6rF4t3C2Gql0fer33ObOhM_qmw1rSS1wzGDMY08aYbpCbdghy0yoAX_o-8gh6hC0I7LvVO7AJIUGeFgDnA1XeAX-jgUzoDL_JoIb0EuEMwAzA9ACmG95mHcJb0cev8xPD_3yVWK-2GHnwwnsHLwZf9rYW-qZDVN1h6zAfov7OuBsNZ0UvUc3gyuCqEVe6wczhg9lcCbpJsfB_HT6iGIfZd8sgCPbWOkYw9Olfymv_Fhz27YYAgrUWCicDVNwBhM4A1XfF4GI5mBG8JwrArCoAVL1u7RoCDRoNGruhUR4QJelkKuFxrh8e2dup-_yz0DzX23tI1gH63Iy_ee8XP49a6Ov9ouexA_amvVfeGXnzgUy4ZBQ0oDOg6y3YLToEu0LPAwTx7S8w_gTjllm2755_Atk3wdfYeuQ1X3Hrkdd_9a1HxYqLRjyDPIM8JfJOCXvLDmGv6Og3dLYllk8NsXLhC-8wnlAIr7LDyCq-4g4jq_7qO4zSiIvGPQM9Az019LoEPvbEeaQf-VjioSwTZpzlyMQhCKwY0v12culj502AVyC4ylOoQ9VXfAp1MODqT6GqZlwyDBocGhzq4vCkmGh3iInF3Po92KFgb4EoivEj9K01CnaXfugOEwL96zx0z6u-5kP33IDrP3SvmHHRkGhgaGCoB8OTIuK4Q0QsfvqAvE-QJNbLR4CCLLOg9WMY0MVrCxz73jOQgxlaeHSGqbsDIJfDWNAZkeMh7bhoZDRwNHDsBseTIuSkQ4Qs-vX-E4qsJxx_sgi2EggtYO2y5BQQXvj5fWZDFAAPXuX5Pav9is_vmQ1Xf37PWXLRWGmAaYB5KjBPuXZ5Js2w1S0DWA2AykQQHFSUCS7EHlUT58m8rkvRY7dn1bTruclYVvtjSk0-YUSPV5xZFbcH9XW8zas2PCGfbHkrGO3cciY-UMnFtnlKDpWcPZ0q5LImb32h1dMhWs1ju9blA5pQMNEdzKibMKmhTjBiPpARgiscfqTwpvR411s3RRYBF-JYFOyZDGqP4CA2Q2_kmV0Y6f0o_yMVPBA68oL97QdWm9tw7z3_JZK4klYf4QM5tiopTZYZrcYgVorGMIJAGN6F7sNvb_fiwRUVBYQAb5s94uXFGe0G-lxl3ZuXsvgRxgHYS9LJNVJpMPaWvKOAlzXMpepUXeavpqvxREzmU5Nfq-XX-R-x9UegwDDtUrtEukPdk5p0kq66KnBqCg5M1-4qJUQEjlJH3YFH7uYGF8DF0q8w_sjlO7RhKZPPqTCCLmY0qelgTYnSjF8x3LgExBtIoN8V1yWL7JcvAQpP1jMV-MZPVCQw0h-omFQBZgd9VGHQmwhU4mmYES66R5bcU60puMJPaxSbkuVtYTQ9UtZclH2CmhzocnlJYcnJBEO4AZ0ECxNXmA7LXXbepSvJ2owJBXHi4VhbdFkTPSQ66yI_4tLwaorN2CTDExsLXePUs667pJbSudp8gdtY0FW09mNZ0lrj2KKYEAGsYDfWRwBHb9wuOC-pvPGTuwpAmNFmZSpIcmpYKdzFZo2Oeoqhcd4otsWs_gKf8XFLyZL32LIi09YM8B8CQGif7vQywEd86T9Skn-lkd0c0J45Xs9nbPiU_eIKpqnzjYvSt5x0dt7Aun8s636NlJ3wEQY4gi1Z8FkuTK74MBnkVbV1bkt7ck_N9o9FWnFXtE7ZmRLxW1681puTbr15JlfHML34B-HccMSe0-XdEEUF7g3WY89kPXb1tP8Lbr0hsdFpn0U0kuC_oT-l4WlvPaAd7CkJvkynVhL8zVHQJUgrG3KZBF8m2R5mx62zbD_0AAptOlNWB0oAludeQgbQHKDGGt-1UL9aH2CMsH8mStQatUhDsg52I7mYBCFFu11RruaXaUe_sHspqhEmd9_HIPS2KDl3CLWobfRQIZscZNdKWYmbWLp7hXB7yDybJkEnxXWdKEGwotyt8xyjS54n4bSA2j7Ei9h3sUzqZ1asE4M2vGqNGMTJnLPkZS2MQbRFXnJ5155WsZZredU6rq3LnLP-ZDPvPR1WAQ3HtMckbH5DObiX6rVWFkleQbaN20u2GU0rC5lk-8yhwwOWBgRFF3b4-ZVrTdI7iXqdSVqU63WtPhwHw0LJwdAwSfE8DI6Ch6FBBc_FMK9zMcjmaY3TJ43v_TLI0Rj0n6-uk-LLZND_7yNmMNAy0DqXnKF5jWlS6ZtU-oahwcDqz8PQ0LyxawBesab9PxwQLdiZ_PmGo6FLZDMAMwDrh6Ch-SSlYxL9Vp4Qk0TfsDScw9JgAGYAdkaMm50Y4_4BqUFbTK6yhTAJ9f_LiBoMqgyqzqBpYNHMkUUzjUSVP6bExzi2hEtrkwrfEDMMm6jSQM9A77xPxVn0m58Y_V7FOEms4jF6W6JokxPfEDH0cKdqIGcgd1a0W3SMdoWr_xn6MM6To-vOuSYzvuFk6CP4GQQaBPbKzcBi4bJbLGTfAWfws15RUwEKE-tl4OEtDqx_JiZdvkmXP3QmNYNBg8GeKRvY83p71DEgjsV36NYrEIEVChDZX_gg2iTM_-sSNxggGiAOwdxQhka74765qOVnaH2bcSo9bFGS71tM0nyTNP9CO2eDQYPBnokbyoA47hYQWWLaD2nsbUGSJ8rPvhTNvui33qA1oRN27Ccma77Jmn-5EGlQaVA5PJdDGTQnHVeRMz4XD53A77__2STLN8nyr7CeNGg0aByMuqE5PYThb1DyNyx5MgNJoom-ORyodv6TLZY1tcLhILFExuPAju-qPA4NslUuh7rskctB5oHpEB5Q5ujP-RxkZgzAGZDVIKfTOFI6yOxwhrGDdwhLvVmyOsisWQ5jjSQ-MrxUiR0kJvW4mpSYJOBmyXM7SExi2d0nk_FsvJAKH_gdJMLqxM9VWoaGHDUcNUOVX-FIzSDzYhGx8e2hkJgHu87P0GBA4sU4CMR4UyFpkCe0a8z17CiIGpr8v16D0Xiq4isoyRqadcgz9NcJG5o0OMspmC0bNXS0Ya4ibuigZDJqIG_oYsyEJZrKbAgoRmB4fgeXRBCnd3BJBtHFL5NmQogO5jSq6tjlszphwRl-nvMUEz7Kj1NO0bVQ0lWcYZhAWXGGYSJtxRnI5KkrJP1XdFOyA9UQ6CjoJrobUzJwKW0o4i0Q6-dpJ7TmH0dBPaElPFfRT2hJL5QUFDribPwpaCi0TJhKqSi0RJdqOgpZt40ENgPRESIZhUzThH1IHUPrDcZ-CJPK6nzaQkXRCRU8HYVWx9hNlBTnxDSeluKEAbYQSSU22aaYcJtclp9Q-LU6ISSjIt5HLfnYmXHVkn8kFgmpgfqNVme11PcRO4k4doBbs0Wdr54Tu62IVRl9x0LHTtozT_0AwnQNvGyeiDWt34kiwzANNNV4UrsaUpN29wfbqBS9KbWynTui6FWJeLV3J116l90-xciD1sdaElB5547KoerBatbQgbpWWd8pTdJIOZuLuTqeWHDdKrFQPVQ54VtBuNqlz4QuvTqxxIxbPfAGOs0zybiVyiTvh_Oy4MtU6bCKRNLyYr7hYqXgVgWaA-24PZfCfQQ9tEYeyM5DziSMaFbaOB4YbJolJVmYZyzltUy0OVSNWwmLim8Pz_SMSp0OZ1CklJF4Y8RAwklVHTHtApRiUP4LgtiqTEPnEmq06W2Ey5zlos7YzpqEJT4qbHVV0s3BcFDKCFtKGVE3obI9pjtgCRkfo4vgTZ83h8l2MoPvD1v3y5MZnFixDpnBmletQWbAyZy83C3M_S6N-bfbgyfVP7vuxhHKqCdF7Q1Ds4C_K4idvOYsNvGv8C6ig-rSXu6jei2uCE9agQ5XhEyyebpoXwT9BIIUXszL59Sqs0R7rOnVWKJVBaqunJ0Qc_8BYv8JxPDiMffEinVi7pZXrRFzOZnedizn0WvY4gOG6nw-UzJs8HO6il5joqDX4OXZ_WIVqmyQH5k1hLXMtHlFoJEj_FuYEAuvrez213AeGM6DnlLPG1gZWJ2S5E-9DDd8B4bvwNBoGFj9eWg01PtdDdDdpxHMvvGJN9BwHBiOg80Acc1AzEDsXBoN9SGTDsPBMbe91tRqGA4MhUZnCg0DMAOwsyg01Ce_GvB7laW6p9iDCd7B_zFEB4booI-gZlBlUHVGJHPkzwqarz_fxBCGVhRjP_WIIS8w5AUDf7pvcGdw1wNpxpJxZpwQ814mVpKhD4fWw0-GusCwZQwc6wzeDN7OiHGLDjGOfUz_c85PEMMEJQSExPLp3zah4SkwPAUXzIJsQGhA2BdZxpJxZXRY8RWK3qWf03hvURN2hpbA0BIMvO4zqDOoOz8N3agkw-iw_iuA_fIRoCDPIouydElh_vEdCALDQWA4CC5NEWSwaLDYNx_GqOTD6LAeLLr1Ic9CT-F4u8VpYmgIDA3BpVMXGwQaBPZDhDEqc7p3WCc67KkoIvnUvMaxRZthsUzKJpu2yaZ9hUWjAaYB5iXSvKs_Uf6rpHhnSReVKd7ruZ4cPsE5_6Vzj8uUY2rzXH8dbwsxuztvhyy1ez07_TG1u0qwmtddTGu_9cWWzwZpuZCdu5ozXDRikFTqki6Y1rO6C3YsB7NDcMhcyOrOWzNM_vRDtjZJOFxKsroLJs0GNklwk8NndedNEgOMw-dybxdZShK5q7IpcFncWUeWWdwFr6lTuNvSFO6qqvn87RNF5nVenuWuttdgAvwWBesGBXzya1uac10pPlktnIXTKN6l9qkq27q2hnlDqnVtJSNZnnVJjq_GRLkTRZJ1pRVg4a_AQtWXZYZ1pYLRarpwVqq080J6daUeb70eLaGeng72sLt4LrF6V8fOlJnQu2qaqtKgn2qSmAP9VJOYpiJl_Knw4zOpq_LEBZnFIvj4NOodzWATtKJ2Fi_EmvkE6q1Bn0ny2dPbJaeq1OntojNl3vRWWTawFEnT2yu3pRnT2-Ucdbp0oYeacqXPlLnS2xtvt6RGV0HFETucT4yuIbpoSot-6qAVcqJ3HS3lEYVLx12Wo9SFnyMcE_adhf317_8P';	
				
			} else {
				
				#default settings code
				$options = 'rVlba9w4FP4rA4Xdp4LtuWbyVHbDEgjp0gSW9sXIluzRRra8kpx0UvrfV75IvsiWNUPzEpC_7-jonKNz0YCjH-yOP7j8d_ztv5KKW4h5QcA5JDSlzcotP3rtR73gb9qVChYBFkoKLYX-vm0_8wwQolcPPVJYMuImH-ehA8FvVxLwimOa29E7JX2otb9WamOI5Kk-Mpyeep9v1B4IiJIhGOIMpCjk-B1pkBKx3nnmaUAOyFngmIcxhchyGrUSl1zQLIw5nwffDLH_WqC-8ktMCWUhiGOUizBKNWDffv-wiXbryFsgJhPEpP4zTdoQM5SXk_vtvd1-71tpvd3Mg-0GeF5GY4reKUKRFyWmUxsmAzmURoxKIWg-Qd4CGO3mzCJoIVB-hT1booM9g2CKyGmMAXHZ2Mp38acyfUJlAFRGdojMGhtReHa48Bo7vFYmIegT3jBMkbBF_n4CvrSF0kkwlEOcp6HAgiBThecvd49_3j_-tXr8_E9naX-SHWY0wj0hh5GMju-N-dJlBBQcR5oOjv7xBz56Et7pWqqvP3vH1kJwLhB7BV1yVGpuzeDUpLzMIsQMymEqc76_E5mxh5ZSh3y6__bt4f7xd_PeKl4MBEopOztker0VSB18qNAzR1nPqzRrscP8LihJUNxVDh3coJf1tZl1OXEqol0kK5qDzYIxx240Q7NlqxlauZjNKKhC5l-OBe7lXuWOJjN312MzZ4ax8XVoJp0uwXqWDZF0g6H03oXKC4SgjWq4gQFR3S8Xjf0Z7lBftYPvLzMX1TWI4A0wOK-tMJPXiDqtbBAsEqd1PVh0bbLtSFd9EZpoYlRI31k2b4TMmHi7SJzW-sai9avsPKlT-jD3bLgzygaLxKGymuiZxmu7BgIiRIx7-vz571WftB-SBM5QgRim0AigQZbz9ABAi3HdVenp4dPz3dPz6tOX5_s_Hu6ezBRek1EGMBkpq23wVEY8ZjhCq1cMVncfK6xZamo542FBaT2jc21U1zqtsxlg8Qm_olD7Rs5j4IKCr7MTR5Wo6-Wo4xXVhHOtFH8g5bJpS5tkqIHryKVKCyjFqZpYYFaV5fwiG3hDGSgrxPliE2xGQr7HpLTOfv6QwGg_9H3dvudChm0pKDPvZ8ukDPZqturBcixnN5D1EslOj_5MZp2qkF0dNeo0G29zfdD0hVwYMzreKBe_IGorKddG7UADx6jVt7dmS2_QTI5YUArIkHQpnGjRQcpVA4IRN1sNQ9IwEep78pWWq6w6ygoQTlcEv_Qi5DAnLMFEdnm86g-NxLgxs63Bn-LtjJCVVxfLrrBzYFA_WakRVS-vq2XlgEGMG7dMN-yktDTCqnY1ecOs5F4XNErgCzpzA_ehw-11-KQ51YP17JEC9yPte6kB5WIOeIGuOofC8IRAP5nMjvoSmlAqbNBdB51y_0S_AappImYosxxroII0LEfCIvqmX6klwV8Wq6CBO3TtDt24Q7fu0J07dO8OPbhDbxzeOLUPPAtYD111cM31YtCcyGNanOsUHQr0XTiMyDLOIBZ8Cd6NyAhGJculUm7Xk3G-8JSti0A9QiOZMt3eylNKU4IKUi5toMdQEKOI0hc7vBtSFDyqO4uwfkJ0eJMVb1hUTis5YnYbdS9uLWX07jDvBkVgKJEWO1lO440YlZ_l0JSAkogLNqppvZKwfPyaIeduC-UwRWHSpedL97F6VD-7EBy_sBBDl4vXYJccok5Qvyj3ey6Xkl39cGPcl_ZYRvlofu5YrvAE5y-_oBZ2fXw9SVzwExbOCspEKMdfTHPu4MgTlSNy1S8uVPJBDpXqvGEoTg6DRQ0-oUHfOv9gjrXqcnapTtJCoqN_-_N_';
				
			}
			
			# Decode options and unserialize			
			$options = it_decode( $options, $serialize = true );
			if(is_array($options))
				foreach( $options as $key => $value )
					if( is_array( $value ) )
						foreach( $value as $key2 => $value2 )
							$options[$key][$key2] = str_replace( '%site_url%', THEME_IMAGES . '/activation', $value2 );
		
		break;
		case "widgets":
		
			if($state=='demo') {
				
				#demo widgets code
				$options = 'pZXRboMwDEX_Zm9IJQTapR8TpZChSJCwxLSrqv37YA1NoATo9szB9rWvHUZQSm6GxO_k7bNVcLw0VEiWgzhzehFFycHcPxwZ2ZHbd4cii1ZKNZERBT8x_WAwuQmyO3bY3mICKKiGan4W_MKLKLGsIHGPHRxWCymMAE6BnUyEHhzqOIS8cJrLQsiSMg0ir7jPJkdPTM_yL6CtvGjWNF3yAfR1NKzkYR1o54JVDLgBl_YwlhJSvH9BCd6qJHFKUGapoYFRITTPQenrky47w9j-gXc4hAxqPjiDVncZc1W1tYziBxhv6ZHf8VBI9Bwy2Ew_HrZMB0TAZWRULlg1E8wz2Z2huWoljKtLh-qUAq7_KDdZDejEovX6JssSOy6vVFtsEZDM9CN7yjdauXAw_Fo38GrAdGq7eSybYsPoa9X5wjx5OFv1Ubptd_F4dxe0ZuPNReOAvT3dlcILQ3AjTxfvo38AJt3458qm2yOj6VyGg3XuBqJoyeqZ4dxRNIeGK3_hJxTyVMl-H7RQQVNstZi1H4Ld6fe3NaBqakuhLvZhGNHgj_7we95ZfjXxC29NsurYZOTYJc_sx64NeNtVl82fM5dwv7SVmXdXEsswrdmVnrk2QklP2fcP';
				
			} else {
				
				#default widgets code
				$options = 'jc_dCoMwDAXgt9ldwf-x-jCSaSYBbVwbFRHffcK6G1mh1_nOOQR0Wurd6fShb--ZpV6nhgy0Qgs2K3U9ivseatCJ3o-TZp4OzJNy1OETbMhM0GPAZJU3IxlyJKg6stgK2y3UmvpEkRQhcvfkhSCzxU61PMyjUWkszK6w8FDObwWNctwSDFdV_uqYBW1w9T_L4lgex4o4Vsax6spyz8Ba2JoFrSM2HpHO6-MD';
				
			}
			
			# Decode options and unserialize
			$options = it_decode( $options, $serialize = true );
			foreach( $options as $key => $value )
				if( is_array( $value ) )
					foreach( $value as $key2 => $value2 )
						$options[$key][$key2] = str_replace( '%site_url%', THEME_IMAGES . '/activation', $value2 );
		
		break;
		case 'widget':
		
			if($state=='demo') {
				
				#demo individual widget code
				$options = '7Vptb6Q2EP4r_pRPzcIuy74QVdU1uvYq9dTqmqq6TysvGLDWYGqb0FyU_17zYljzsiFRSKJLpJUW4fHYzzPjmbENdOZr55Y7c9s5-zel4iLDXoDEDjI3xNeIly8voDMvpBaV1C5KicClbCWCnfnFHc_VaYpcKFBAGX6MqpWuCktthKZeo8lybrGzkA-rXOW2EpdyEb1uRtxLdVLbsmm-xh6iuwBGbRmzkQlgPqjevqma4zTCAkWqkTsKjG3Wr9RwAgYDg3SoydvvsGN9X4BG2Xphd2xNpDaee6LALjlyn41mdNVPYEFQPdfaCz9RAf7wfXAVIvAnQ7xBqKBHOMZcgm_6KjgfrxG7ESGOg6Zt3hDWmphsrNqs-o0yoAjTaB9DTDTelHszKJoxyqbatC6NB5xjALga8veCPfALoxH4VbP8GOgddxkJfTkl9OU90BWsD4SAL8hFsZB_ctlkY5BbR50_V62vCbx9GvzC1O3-OY8X4-EPRZqRyO0pka_uMbvVg3x6yJMae30PZFuH_KE9yaePbSqCz80pcW9eHe5nMPe4_Gh18qMCuRNw32THpcqO6-epIJ6bvYgy1M2IrwmsNSXY5SsDu5kIrPn4dcGpiyHZuTSNRc9WwcrZU-WhyLAQiPXWxD500Z7Sg9aoIlCQkLSnbJ9I-Tgq5kNU9AeI7XGgpcyrZzqE4DSybeMtIkNa5dipEk6yoBQlOJZjyDjf60g-we5hcAZJSAXtzmDbmYFLowjFJzy_JdBFU1n-BJtDgN75HOBzeZrPgbHe6ewt1EYGj2UneAj0n9ilccZgkiCvG0iLY5z6UEAKN3hWynBnRFxAEDLk_1i-CIVIHMMQIYqQT3P6ZjESxk-5xG-xl3LBZMS6ypsrTGdBqQZHAeDM1fVkWTbDdbdCK59JTgyfwGvKjCw5l9WWkAwZaUIo9LixMOeWYdoGh1FC0Dn0zi3TnCUqKQGpphoDVqCBoeZgwOJJW_hvnoZx_rXo-hdNdqzYoB5511p5lz1io39FE7kKA5Dv2S9DWaM800Z_1Yk4RYDa3zSrUI0iUQqZgbkrjbyLKYsgwd9qvEdFmMARShDDtGlTOiAhusud4Kbm-edmLwyuQszlcyzCybfFmxekZnkPNUrfJxyEOTsfPSwoA1_kZtL7romxR64nRczfHL0iWjY6Lamc3BNys7qHm20Ta0pKytX0D0KHaYNNncjtF_Sc9cgl1WJnZKx5ilj8TOuqdoMc3lcE2YOyX_tSrMp-MiF3y6r1iFuNnO652cd1PRkfE1me7gg-1Air6tHSJbQEXInYukgrS1dCrZHYUbyoJFa6RFWrtqUekwFPkKTUvR1KxhVgPS7IUOzJ-ql7xVafEciZ3Hb5YcXlyknojyP5qfhb6lIwgzImeC96btl_k_fG-d1MfV347r8vemsxbx1JRkjAB38G0t46ltY7b518POCzkm2vvoTyxygzW8p4o0P63t2R31QSHOXf1jx8oBaVRycNUNaKx4fbA8WDsuRlygWNwF_YQ3vIup93aEcY9uboCCNc5vv-yxC5B4AFoKn4AYi8BpE_CNxSL43ROfV9wEv9IIIeAjxBLvaxK7PWDfDlpqvolsAAzcBXmgIXxlLwgEAaE5x_iuIpdZUaXvSC8U3RCcjn3F7gRvbNYCxmxbFEOb968vpae-DVwt3_';
				
			} else {
				
				#default individual widget code
				$options = 'pdHBCsIwDIDht_EmrBVR04cZpYYZsFabzAlj7-7mBhLxYj0Vws_X0HrYQ89gtrC6tUlcR8cGpfY5nOiOPA-dB_Oq7FLVsT0Lze2SEBg3jMlOQ8ELNilTCWU0FVH8z4i1GskY8CLrkGIcz4KlDl-9a-ISrPrA-G1U0E_FRheM08_8_ZSCD1E3DU8';
				
			}
		
		break;
		case 'mods':
		
			if($state=='demo') {
				
				#demo theme_mods code
				$options = 'S7QysqrOtDKwTgLiYitDCyu1wtL8Euu8xLL43NS80vic_OTEksz8vGKIuHWilbFVdbEVTF1JfoEuSB1UNtPKCGiKJVQyNzEzD1XW2Bqhtbg0CVXSxLq2FgA';
				
			} else {
				
				#default theme_mods code
				$options = 'S7QysqrOtDKwTgLiYitDCyu1wtL8Euu8xLL43NS80vic_OTEksz8vGKIuHWilbFVdbEVTF1JfoEuSB1UNtPKCGiKJVQyNzEzD1XW2Bqhtbg0CVXSxLq2FgA';
				
			}
			
			# Decode options and unserialize
			$options = it_decode( $options, $serialize = true );
			foreach( $options as $key => $value )
				if( is_array( $value ) )
					foreach( $value as $key2 => $value2 )
						$options[$key][$key2] = str_replace( '%site_url%', THEME_IMAGES . '/activation', $value2 );
		
		break;
		case "internal":
		
			$options = array();
			
			if( defined( 'FRAMEWORK_VERSION' ) )
				$options['framework_version'] = FRAMEWORK_VERSION;
				
			if( defined( 'DOCUMENTATION_URL' ) )
				$options['documentation_url'] = DOCUMENTATION_URL;
				
			if( defined( 'SUPPORT_URL' ) )
				$options['support_url'] = SUPPORT_URL;
		
		break;	
	}
	
	return $options;
}

# turn variables into proper class types
function it_prep_data( $data ) {
	#loop through minisites
	$minisite = it_get_setting('minisite');
	if ( isset($minisite['keys']) && $minisite['keys'] != '#' ) {
		$minisite_keys = explode(',',$minisite['keys']);
		foreach ($minisite_keys as $mkey) {
			if ( $mkey != '#') {
				$minisite_name = ( !empty( $minisite[$mkey]['name'] ) ) ? $minisite[$mkey]['name'] : '#';
				$minisite_slug = it_get_slug($minisite[$mkey]['slug'], $minisite_name);								
				#create itCriteria objects based on entered details	
				$criteria = $data[IT_SETTINGS]['criteria_'.$minisite_slug];		
				if ( isset($criteria['keys']) && $criteria['keys'] != '#' ) {
					$criteria_keys = explode(',',$criteria['keys']);
					foreach ($criteria_keys as $tkey) {
						$id = $tkey . '_' . $minisite_slug;
						$val = ( ( $id != '#_' . $minisite_slug ) && ( isset( $data[$id] ) ) ) ? $data[$id] : '';
						if ( $id != '#_'.$minisite_slug) {
							$criteria_name = ( !empty( $criteria[$id]['name'] ) ) ? $criteria[$id]['name'] : '';
							$criteria_weight = ( !empty( $criteria[$id]['weight'] ) ) ? $criteria[$id]['weight'] : '';
							if(is_array($data)) array_push($data[IT_SETTINGS]['criteria_'.$minisite_slug][$id],new itCriteria($criteria_name, $criteria_weight));						
						}
					}
				}
				#create itDetail objects based on entered details	
				$details = $data[IT_SETTINGS]['details_'.$minisite_slug];		
				if ( isset($details['keys']) && $details['keys'] != '#' ) {
					$details_keys = explode(',',$details['keys']);
					foreach ($details_keys as $tkey) {
						$id = $tkey . '_' . $minisite_slug;
						$val = ( ( $id != '#_' . $minisite_slug ) && ( isset( $data[$id] ) ) ) ? $data[$id] : '';
						if ( $id != '#_'.$minisite_slug) {
							$details_name = ( !empty( $details[$id]['name'] ) ) ? $details[$id]['name'] : '';
							if(is_array($data)) array_push($data[IT_SETTINGS]['details_'.$minisite_slug][$id],new itDetail($details_name));						
						}
					}
				}
				#create itTaxonomy objects based on entered taxonomies	
				$taxonomies = $data[IT_SETTINGS]['taxonomies_'.$minisite_slug];			
				if ( isset($taxonomies['keys']) && $taxonomies['keys'] != '#' ) {
					$taxonomies_keys = explode(',',$taxonomies['keys']);
					foreach ($taxonomies_keys as $tkey) {
						$id = $tkey . '_' . $minisite_slug;
						$val = ( ( $id != '#_' . $minisite_slug ) && ( isset( $data[$id] ) ) ) ? $data[$id] : '';
						if ( $id != '#_'.$minisite_slug) {
							$taxonomy_name = ( !empty( $taxonomies[$id]['name'] ) ) ? $taxonomies[$id]['name'] : '';							
							$taxonomy_slug = it_get_slug($taxonomies[$id]['slug'], $taxonomy_name);
							$taxonomy_primary = ( !empty( $taxonomies[$id]['primary'] ) ) ? $taxonomies[$id]['primary'] : false;
							if(is_array($data)) array_push($data[IT_SETTINGS]['taxonomies_'.$minisite_slug][$id],new itTaxonomy($minisite_slug, $taxonomy_name, $taxonomy_slug, $taxonomy_primary));						
						}
					}
				}
				#create itAward objects based on entered awards
				$awards = $data[IT_SETTINGS]['awards_'.$minisite_slug];			
				if ( isset($awards['keys']) && $awards['keys'] != '#' ) {
					$awards_keys = explode(',',$awards['keys']);
					foreach ($awards_keys as $akey) {
						$id = $akey . '_' . $minisite_slug;
						$val = ( ( $id != '#_' . $minisite_slug ) && ( isset( $data[$id] ) ) ) ? $data[$id] : '';
						if ( $id != '#_'.$minisite_slug) {
							$award_name = ( !empty( $awards[$id]['name'] ) ) ? $awards[$id]['name'] : '';
							$award_icon = ( !empty( $awards[$id]['icon'] ) ) ? $awards[$id]['icon'] : '';
							$award_iconhd = ( !empty( $awards[$id]['iconhd'] ) ) ? $awards[$id]['iconhd'] : '';
							$award_iconwhite = ( !empty( $awards[$id]['iconwhite'] ) ) ? $awards[$id]['iconwhite'] : '';
							$award_iconhdwhite = ( !empty( $awards[$id]['iconhdwhite'] ) ) ? $awards[$id]['iconhdwhite'] : '';
							$award_badge = ( !empty( $awards[$id]['badge'] ) ) ? $awards[$id]['badge'] : false;
							if(is_array($data)) array_push($data[IT_SETTINGS]['awards_'.$minisite_slug][$id],new itAward($award_name, $award_icon, $award_iconhd, $award_iconwhite, $award_iconhdwhite, $award_badge));						
						}
					}
				}
			}
		}
	}
	#die (var_export($data));
	return $data;
}

/**
 * 
 */
function it_icons() {
	$icons = array(		
		'icon-flag' => __( 'Flag', IT_TEXTDOMAIN ),
		'icon-cog' => __( 'Gear', IT_TEXTDOMAIN ),
		'icon-cog-alt' => __( 'Gears', IT_TEXTDOMAIN ),
		'icon-wrench' => __( 'Wrench', IT_TEXTDOMAIN ),
		'icon-gauge' => __( 'Gauge', IT_TEXTDOMAIN ),
		'icon-tools' => __( 'Tools', IT_TEXTDOMAIN ),
		'icon-window' => __( 'Window', IT_TEXTDOMAIN ),
		'icon-folder-open' => __( 'Folder', IT_TEXTDOMAIN ),
		'icon-search' => __( 'Search', IT_TEXTDOMAIN ),
		'icon-username' => __( 'User', IT_TEXTDOMAIN ),
		'icon-users' => __( 'Users', IT_TEXTDOMAIN ),
		'icon-email' => __( 'Envelope', IT_TEXTDOMAIN ),
		'icon-bookmark' => __( 'Bookmark', IT_TEXTDOMAIN ),
		'icon-book' => __( 'Book', IT_TEXTDOMAIN ),
		'icon-commented' => __( 'Comment', IT_TEXTDOMAIN ),
		'icon-globe' => __( 'Globe', IT_TEXTDOMAIN ),
		'icon-suitcase' => __( 'Suitcase', IT_TEXTDOMAIN ),
		'icon-target' => __( 'Target', IT_TEXTDOMAIN ),
		'icon-pin' => __( 'Pin', IT_TEXTDOMAIN ),
		'icon-attach' => __( 'Paperclip', IT_TEXTDOMAIN ),
		'icon-home' => __( 'Home', IT_TEXTDOMAIN ),
		'icon-key' => __( 'Key', IT_TEXTDOMAIN ),
		'icon-zoom-in' => __( 'Zoom In', IT_TEXTDOMAIN ),
		'icon-zoom-out' => __( 'Zoom Out', IT_TEXTDOMAIN ),
		'icon-doc' => __( 'Document', IT_TEXTDOMAIN ),
		'icon-floppy' => __( 'Floppy Disk', IT_TEXTDOMAIN ),
		'icon-picture' => __( 'Picture', IT_TEXTDOMAIN ),
		'icon-link' => __( 'Link', IT_TEXTDOMAIN ),
		'icon-video' => __( 'Video', IT_TEXTDOMAIN ),
		'icon-battery' => __( 'Battery', IT_TEXTDOMAIN ),
		'icon-monitor' => __( 'Monitor', IT_TEXTDOMAIN ),
		'icon-mobile' => __( 'Mobile Phone', IT_TEXTDOMAIN ),
		'icon-tablet' => __( 'Tablet', IT_TEXTDOMAIN ),
		'icon-laptop' => __( 'Laptop', IT_TEXTDOMAIN ),
		'icon-signal' => __( 'Signal', IT_TEXTDOMAIN ),
		'icon-wifi' => __( 'Wifi', IT_TEXTDOMAIN ),
		'icon-camera' => __( 'Camera', IT_TEXTDOMAIN ),
		'icon-list' => __( 'List', IT_TEXTDOMAIN ),
		'icon-tag' => __( 'Tag', IT_TEXTDOMAIN ),
		'icon-pencil' => __( 'Pencil', IT_TEXTDOMAIN ),
		'icon-category' => __( 'Folder', IT_TEXTDOMAIN ),							
		'icon-random' => __( 'Shuffle', IT_TEXTDOMAIN ),
		'icon-loop' => __( 'Loop', IT_TEXTDOMAIN ),
		'icon-play' => __( 'Play', IT_TEXTDOMAIN ),
		'icon-stop' => __( 'Stop', IT_TEXTDOMAIN ),
		'icon-pause' => __( 'Pause', IT_TEXTDOMAIN ),
		'icon-grid' => __( 'Grid', IT_TEXTDOMAIN ),
		'icon-x' => __( 'Circled X', IT_TEXTDOMAIN ),
		'icon-help-circled' => __( 'Circled Help', IT_TEXTDOMAIN ),
		'icon-quote-circled' => __( 'Circled Quote', IT_TEXTDOMAIN ),
		'icon-info-circled' => __( 'Circled Info', IT_TEXTDOMAIN ),							
		'icon-attention' => __( 'Circled Attention', IT_TEXTDOMAIN ),
		'icon-plus' => __( 'Plus', IT_TEXTDOMAIN ),
		'icon-minus' => __( 'Minus', IT_TEXTDOMAIN ),
		'icon-plus-squared' => __( 'Plus Squared', IT_TEXTDOMAIN ),
		'icon-minus-squared' => __( 'Minus Squared', IT_TEXTDOMAIN ),
		'icon-alert' => __( 'Alert', IT_TEXTDOMAIN ),
		'icon-viewed' => __( 'Eye', IT_TEXTDOMAIN ),
		'icon-star' => __( 'Star', IT_TEXTDOMAIN ),
		'icon-liked' => __( 'Heart', IT_TEXTDOMAIN ),
		'icon-check' => __( 'Check', IT_TEXTDOMAIN ),
		'icon-lock' => __( 'Locked', IT_TEXTDOMAIN ),
		'icon-lock-open' => __( 'Unlocked', IT_TEXTDOMAIN ),
		'icon-password' => __( 'Password', IT_TEXTDOMAIN ),
		'icon-right' => __( 'Right Arrow', IT_TEXTDOMAIN ),
		'icon-left' => __( 'Left Arrow', IT_TEXTDOMAIN ),
		'icon-up' => __( 'Up Arrow', IT_TEXTDOMAIN ),
		'icon-down' => __( 'Down Arrow', IT_TEXTDOMAIN ),
		'icon-right-open' => __( 'Right Arrow Open', IT_TEXTDOMAIN ),
		'icon-left-open' => __( 'Left Arrow Open', IT_TEXTDOMAIN ),
		'icon-up-open' => __( 'Up Arrow Open', IT_TEXTDOMAIN ),
		'icon-arrow-down' => __( 'Down Arrow Open', IT_TEXTDOMAIN ),
		'icon-up-bold' => __( 'Up Arrow Bold', IT_TEXTDOMAIN ),
		'icon-down-bold' => __( 'Down Arrow Bold', IT_TEXTDOMAIN ),							
		'icon-right-thin' => __( 'Right Arrow Thin', IT_TEXTDOMAIN ),
		'icon-forward' => __( 'Right Arrow Curved', IT_TEXTDOMAIN ),
		'icon-left-hand' => __( 'Left Hand', IT_TEXTDOMAIN ),
		'icon-right-hand' => __( 'Right Hand', IT_TEXTDOMAIN ),
		'icon-awarded' => __( 'Trophy', IT_TEXTDOMAIN ),
		'icon-beaker' => __( 'Beaker', IT_TEXTDOMAIN ),
		'icon-scissors' => __( 'Scissors', IT_TEXTDOMAIN ),
		'icon-thumbs-up' => __( 'Thumbs Up', IT_TEXTDOMAIN ),
		'icon-thumbs-down' => __( 'Thumbs Down', IT_TEXTDOMAIN ),
		'icon-comments' => __( 'Comments', IT_TEXTDOMAIN ),
		'icon-recent' => __( 'Clock', IT_TEXTDOMAIN ),							
		'icon-trending' => __( 'Trending Graph', IT_TEXTDOMAIN ),
		'icon-reviewed' => __( 'Line Graph', IT_TEXTDOMAIN ),
		'icon-chart-pie' => __( 'Pie Chart', IT_TEXTDOMAIN ),
		'icon-coffee' => __( 'Coffee', IT_TEXTDOMAIN ),
		'icon-food' => __( 'Food', IT_TEXTDOMAIN ),
		'icon-truck' => __( 'Truck', IT_TEXTDOMAIN ),
		'icon-water' => __( 'Water', IT_TEXTDOMAIN ),
		'icon-magnet' => __( 'Magnet', IT_TEXTDOMAIN ),
		'icon-brush' => __( 'Paint Brush', IT_TEXTDOMAIN ),
		'icon-leaf' => __( 'Leaf', IT_TEXTDOMAIN ),
		'icon-fire' => __( 'Fire', IT_TEXTDOMAIN ),
		'icon-moon' => __( 'Moon', IT_TEXTDOMAIN ),
		'icon-cloud' => __( 'Cloud', IT_TEXTDOMAIN ),
		'icon-cc' => __( 'CC License', IT_TEXTDOMAIN ),
		'icon-basket' => __( 'Shopping Cart', IT_TEXTDOMAIN ),
		'icon-credit-card' => __( 'Credit Card', IT_TEXTDOMAIN ),
		'icon-facebook' => __( 'Facebook', IT_TEXTDOMAIN ),
		'icon-pinterest' => __( 'Pinterest', IT_TEXTDOMAIN ),
		'icon-instagram' => __( 'Instagram', IT_TEXTDOMAIN ),
		'icon-flickr' => __( 'Flickr', IT_TEXTDOMAIN ),							
		'icon-stumbleupon' => __( 'StumbleUpon', IT_TEXTDOMAIN ),							
		'icon-twitter' => __( 'Twitter', IT_TEXTDOMAIN ),							
		'icon-googleplus' => __( 'Google+', IT_TEXTDOMAIN ),
		'icon-vimeo' => __( 'Vimeo', IT_TEXTDOMAIN ),
		'icon-tumblr' => __( 'Tumblr', IT_TEXTDOMAIN ),
		'icon-youtube' => __( 'Youtube', IT_TEXTDOMAIN ),
		'icon-linkedin' => __( 'LinkedIn', IT_TEXTDOMAIN ),
		'icon-skype' => __( 'Skype', IT_TEXTDOMAIN ),
		'icon-paypal' => __( 'Paypal', IT_TEXTDOMAIN ),
		'icon-picasa' => __( 'Picasa', IT_TEXTDOMAIN ),
		'icon-spotify' => __( 'Spotify', IT_TEXTDOMAIN ),
		'icon-lastfm' => __( 'Last.fm', IT_TEXTDOMAIN ),
		'icon-dropbox' => __( 'Dropbox', IT_TEXTDOMAIN ),
		'icon-appstore' => __( 'Apple', IT_TEXTDOMAIN ),
		'icon-windows' => __( 'Windows', IT_TEXTDOMAIN ),
		'icon-yahoo' => __( 'Yahoo!', IT_TEXTDOMAIN ),
		'icon-wikipedia' => __( 'Wikipedia', IT_TEXTDOMAIN ),
		'icon-html5' => __( 'HTML5', IT_TEXTDOMAIN ),
		'icon-wordpress' => __( 'WordPress', IT_TEXTDOMAIN ),
		'icon-gmail' => __( 'Gmail', IT_TEXTDOMAIN ),
		'icon-reddit' => __( 'Reddit', IT_TEXTDOMAIN ),
		'icon-acrobat' => __( 'Acrobat', IT_TEXTDOMAIN ),
		'icon-firefox' => __( 'Firefox', IT_TEXTDOMAIN ),
		'icon-chrome' => __( 'Chrome', IT_TEXTDOMAIN ),
		'icon-opera' => __( 'Opera', IT_TEXTDOMAIN ),
		'icon-ie' => __( 'Internet Explorer', IT_TEXTDOMAIN ),
		'icon-rss' => __( 'RSS', IT_TEXTDOMAIN ),		
		);
	return $icons;
}

/**
 * 
 */
function it_fonts() {
	$fonts = array(
		'Arial, Helvetica, sans-serif' => 'Arial',
		'Verdana, Geneva, Tahoma, sans-serif' => 'Verdana',
		'"Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif' => 'Lucida',
		'Georgia, Times, "Times New Roman", serif' => 'Georgia',
		'"Times New Roman", Times, Georgia, serif' => 'Times New Roman',
		'"Trebuchet MS", Tahoma, Arial, sans-serif' => 'Trebuchet',
		'"Courier New", Courier, monospace' => 'Courier New',
		'Impact, Haettenschweiler, "Arial Narrow Bold", sans-serif' => 'Impact',
		'Tahoma, Geneva, Verdana, sans-serif' => 'Tahoma',	
		'spacer' => '                ',
		'ABeeZee, sans-serif' => 'ABeeZee',
		'Abel, sans-serif' => 'Abel',
		'Abril Fatface, sans-serif' => 'Abril Fatface',
		'Aclonica, sans-serif' => 'Aclonica',
		'Acme, sans-serif' => 'Acme',
		'Actor, sans-serif' => 'Actor',
		'Adamina, sans-serif' => 'Adamina',
		'Advent Pro, sans-serif' => 'Advent Pro',
		'Aguafina Script, sans-serif' => 'Aguafina Script',
		'Akronim, sans-serif' => 'Akronim',
		'Aladin, sans-serif' => 'Aladin',
		'Aldrich, sans-serif' => 'Aldrich',
		'Alegreya, sans-serif' => 'Alegreya',
		'Alegreya SC, sans-serif' => 'Alegreya SC',
		'Alex Brush, sans-serif' => 'Alex Brush',
		'Alfa Slab One, sans-serif' => 'Alfa Slab One',
		'Alice, sans-serif' => 'Alice',
		'Alike, sans-serif' => 'Alike',
		'Alike Angular, sans-serif' => 'Alike Angular',
		'Allan, sans-serif' => 'Allan',
		'Allerta, sans-serif' => 'Allerta',
		'Allerta Stencil, sans-serif' => 'Allerta Stencil',
		'Allura, sans-serif' => 'Allura',
		'Almendra, sans-serif' => 'Almendra',
		'Almendra Display, sans-serif' => 'Almendra Display',
		'Almendra SC, sans-serif' => 'Almendra SC',
		'Amarante, sans-serif' => 'Amarante',
		'Amaranth, sans-serif' => 'Amaranth',
		'Amatic SC, sans-serif' => 'Amatic SC',
		'Amethysta, sans-serif' => 'Amethysta',
		'Anaheim, sans-serif' => 'Anaheim',
		'Andada, sans-serif' => 'Andada',
		'Andika, sans-serif' => 'Andika',
		'Angkor, sans-serif' => 'Angkor',
		'Annie Use Your Telescope, sans-serif' => 'Annie Use Your Telescope',
		'Anonymous Pro, sans-serif' => 'Anonymous Pro',
		'Antic, sans-serif' => 'Antic',
		'Antic Didone, sans-serif' => 'Antic Didone',
		'Antic Slab, sans-serif' => 'Antic Slab',
		'Anton, sans-serif' => 'Anton',
		'Arapey, sans-serif' => 'Arapey',
		'Arbutus, sans-serif' => 'Arbutus',
		'Arbutus Slab, sans-serif' => 'Arbutus Slab',
		'Architects Daughter, sans-serif' => 'Architects Daughter',
		'Archivo Black, sans-serif' => 'Archivo Black',
		'Archivo Narrow, sans-serif' => 'Archivo Narrow',
		'Arimo, sans-serif' => 'Arimo',
		'Arizonia, sans-serif' => 'Arizonia',
		'Armata, sans-serif' => 'Armata',
		'Artifika, sans-serif' => 'Artifika',
		'Arvo, sans-serif' => 'Arvo',
		'Asap, sans-serif' => 'Asap',
		'Asset, sans-serif' => 'Asset',
		'Astloch, sans-serif' => 'Astloch',
		'Asul, sans-serif' => 'Asul',
		'Atomic Age, sans-serif' => 'Atomic Age',
		'Aubrey, sans-serif' => 'Aubrey',
		'Audiowide, sans-serif' => 'Audiowide',
		'Autour One, sans-serif' => 'Autour One',
		'Average, sans-serif' => 'Average',
		'Average Sans, sans-serif' => 'Average Sans',
		'Averia Gruesa Libre, sans-serif' => 'Averia Gruesa Libre',
		'Averia Libre, sans-serif' => 'Averia Libre',
		'Averia Sans Libre, sans-serif' => 'Averia Sans Libre',
		'Averia Serif Libre, sans-serif' => 'Averia Serif Libre',
		'Bad Script, sans-serif' => 'Bad Script',
		'Balthazar, sans-serif' => 'Balthazar',
		'Bangers, sans-serif' => 'Bangers',
		'Basic, sans-serif' => 'Basic',
		'Battambang, sans-serif' => 'Battambang',
		'Baumans, sans-serif' => 'Baumans',
		'Bayon, sans-serif' => 'Bayon',
		'Belgrano, sans-serif' => 'Belgrano',
		'Belleza, sans-serif' => 'Belleza',
		'BenchNine, sans-serif' => 'BenchNine',
		'Bentham, sans-serif' => 'Bentham',
		'Berkshire Swash, sans-serif' => 'Berkshire Swash',
		'Bevan, sans-serif' => 'Bevan',
		'Bigelow Rules, sans-serif' => 'Bigelow Rules',
		'Bigshot One, sans-serif' => 'Bigshot One',
		'Bilbo, sans-serif' => 'Bilbo',
		'Bilbo Swash Caps, sans-serif' => 'Bilbo Swash Caps',
		'Bitter, sans-serif' => 'Bitter',
		'Black Ops One, sans-serif' => 'Black Ops One',
		'Bokor, sans-serif' => 'Bokor',
		'Bonbon, sans-serif' => 'Bonbon',
		'Boogaloo, sans-serif' => 'Boogaloo',
		'Bowlby One, sans-serif' => 'Bowlby One',
		'Bowlby One SC, sans-serif' => 'Bowlby One SC',
		'Brawler, sans-serif' => 'Brawler',
		'Bree Serif, sans-serif' => 'Bree Serif',
		'Bubblegum Sans, sans-serif' => 'Bubblegum Sans',
		'Bubbler One, sans-serif' => 'Bubbler One',
		'Buda, sans-serif' => 'Buda',
		'Buenard, sans-serif' => 'Buenard',
		'Butcherman, sans-serif' => 'Butcherman',
		'Butterfly Kids, sans-serif' => 'Butterfly Kids',
		'Cabin, sans-serif' => 'Cabin',
		'Cabin Condensed, sans-serif' => 'Cabin Condensed',
		'Cabin Sketch, sans-serif' => 'Cabin Sketch',
		'Caesar Dressing, sans-serif' => 'Caesar Dressing',
		'Cagliostro, sans-serif' => 'Cagliostro',
		'Calligraffitti, sans-serif' => 'Calligraffitti',
		'Cambo, sans-serif' => 'Cambo',
		'Candal, sans-serif' => 'Candal',
		'Cantarell, sans-serif' => 'Cantarell',
		'Cantata One, sans-serif' => 'Cantata One',
		'Cantora One, sans-serif' => 'Cantora One',
		'Capriola, sans-serif' => 'Capriola',
		'Cardo, sans-serif' => 'Cardo',
		'Carme, sans-serif' => 'Carme',
		'Carrois Gothic, sans-serif' => 'Carrois Gothic',
		'Carrois Gothic SC, sans-serif' => 'Carrois Gothic SC',
		'Carter One, sans-serif' => 'Carter One',
		'Caudex, sans-serif' => 'Caudex',
		'Cedarville Cursive, sans-serif' => 'Cedarville Cursive',
		'Ceviche One, sans-serif' => 'Ceviche One',
		'Changa One, sans-serif' => 'Changa One',
		'Chango, sans-serif' => 'Chango',
		'Chau Philomene One, sans-serif' => 'Chau Philomene One',
		'Chela One, sans-serif' => 'Chela One',
		'Chelsea Market, sans-serif' => 'Chelsea Market',
		'Chenla, sans-serif' => 'Chenla',
		'Cherry Cream Soda, sans-serif' => 'Cherry Cream Soda',
		'Cherry Swash, sans-serif' => 'Cherry Swash',
		'Chewy, sans-serif' => 'Chewy',
		'Chicle, sans-serif' => 'Chicle',
		'Chivo, sans-serif' => 'Chivo',
		'Cinzel, sans-serif' => 'Cinzel',
		'Cinzel Decorative, sans-serif' => 'Cinzel Decorative',
		'Clicker Script, sans-serif' => 'Clicker Script',
		'Coda, sans-serif' => 'Coda',
		'Coda Caption, sans-serif' => 'Coda Caption',
		'Codystar, sans-serif' => 'Codystar',
		'Combo, sans-serif' => 'Combo',
		'Comfortaa, sans-serif' => 'Comfortaa',
		'Coming Soon, sans-serif' => 'Coming Soon',
		'Concert One, sans-serif' => 'Concert One',
		'Condiment, sans-serif' => 'Condiment',
		'Content, sans-serif' => 'Content',
		'Contrail One, sans-serif' => 'Contrail One',
		'Convergence, sans-serif' => 'Convergence',
		'Cookie, sans-serif' => 'Cookie',
		'Copse, sans-serif' => 'Copse',
		'Corben, sans-serif' => 'Corben',
		'Courgette, sans-serif' => 'Courgette',
		'Cousine, sans-serif' => 'Cousine',
		'Coustard, sans-serif' => 'Coustard',
		'Covered By Your Grace, sans-serif' => 'Covered By Your Grace',
		'Crafty Girls, sans-serif' => 'Crafty Girls',
		'Creepster, sans-serif' => 'Creepster',
		'Crete Round, sans-serif' => 'Crete Round',
		'Crimson Text, sans-serif' => 'Crimson Text',
		'Croissant One, sans-serif' => 'Croissant One',
		'Crushed, sans-serif' => 'Crushed',
		'Cuprum, sans-serif' => 'Cuprum',
		'Cutive, sans-serif' => 'Cutive',
		'Cutive Mono, sans-serif' => 'Cutive Mono',
		'Damion, sans-serif' => 'Damion',
		'Dancing Script, sans-serif' => 'Dancing Script',
		'Dangrek, sans-serif' => 'Dangrek',
		'Dawning of a New Day, sans-serif' => 'Dawning of a New Day',
		'Days One, sans-serif' => 'Days One',
		'Delius, sans-serif' => 'Delius',
		'Delius Swash Caps, sans-serif' => 'Delius Swash Caps',
		'Delius Unicase, sans-serif' => 'Delius Unicase',
		'Della Respira, sans-serif' => 'Della Respira',
		'Denk One, sans-serif' => 'Denk One',
		'Devonshire, sans-serif' => 'Devonshire',
		'Didact Gothic, sans-serif' => 'Didact Gothic',
		'Diplomata, sans-serif' => 'Diplomata',
		'Diplomata SC, sans-serif' => 'Diplomata SC',
		'Domine, sans-serif' => 'Domine',
		'Donegal One, sans-serif' => 'Donegal One',
		'Doppio One, sans-serif' => 'Doppio One',
		'Dorsa, sans-serif' => 'Dorsa',
		'Dosis, sans-serif' => 'Dosis',
		'Dr Sugiyama, sans-serif' => 'Dr Sugiyama',
		'Droid Sans, sans-serif' => 'Droid Sans',
		'Droid Sans Mono, sans-serif' => 'Droid Sans Mono',
		'Droid Serif, sans-serif' => 'Droid Serif',
		'Duru Sans, sans-serif' => 'Duru Sans',
		'Dynalight, sans-serif' => 'Dynalight',
		'EB Garamond, sans-serif' => 'EB Garamond',
		'Eagle Lake, sans-serif' => 'Eagle Lake',
		'Eater, sans-serif' => 'Eater',
		'Economica, sans-serif' => 'Economica',
		'Electrolize, sans-serif' => 'Electrolize',
		'Elsie, sans-serif' => 'Elsie',
		'Elsie Swash Caps, sans-serif' => 'Elsie Swash Caps',
		'Emblema One, sans-serif' => 'Emblema One',
		'Emilys Candy, sans-serif' => 'Emilys Candy',
		'Engagement, sans-serif' => 'Engagement',
		'Englebert, sans-serif' => 'Englebert',
		'Enriqueta, sans-serif' => 'Enriqueta',
		'Erica One, sans-serif' => 'Erica One',
		'Esteban, sans-serif' => 'Esteban',
		'Euphoria Script, sans-serif' => 'Euphoria Script',
		'Ewert, sans-serif' => 'Ewert',
		'Exo, sans-serif' => 'Exo',
		'Expletus Sans, sans-serif' => 'Expletus Sans',
		'Fanwood Text, sans-serif' => 'Fanwood Text',
		'Fascinate, sans-serif' => 'Fascinate',
		'Fascinate Inline, sans-serif' => 'Fascinate Inline',
		'Faster One, sans-serif' => 'Faster One',
		'Fasthand, sans-serif' => 'Fasthand',
		'Federant, sans-serif' => 'Federant',
		'Federo, sans-serif' => 'Federo',
		'Felipa, sans-serif' => 'Felipa',
		'Fenix, sans-serif' => 'Fenix',
		'Finger Paint, sans-serif' => 'Finger Paint',
		'Fjalla One, sans-serif' => 'Fjalla One',
		'Fjord One, sans-serif' => 'Fjord One',
		'Flamenco, sans-serif' => 'Flamenco',
		'Flavors, sans-serif' => 'Flavors',
		'Fondamento, sans-serif' => 'Fondamento',
		'Fontdiner Swanky, sans-serif' => 'Fontdiner Swanky',
		'Forum, sans-serif' => 'Forum',
		'Francois One, sans-serif' => 'Francois One',
		'Freckle Face, sans-serif' => 'Freckle Face',
		'Fredericka the Great, sans-serif' => 'Fredericka the Great',
		'Fredoka One, sans-serif' => 'Fredoka One',
		'Freehand, sans-serif' => 'Freehand',
		'Fresca, sans-serif' => 'Fresca',
		'Frijole, sans-serif' => 'Frijole',
		'Fruktur, sans-serif' => 'Fruktur',
		'Fugaz One, sans-serif' => 'Fugaz One',
		'GFS Didot, sans-serif' => 'GFS Didot',
		'GFS Neohellenic, sans-serif' => 'GFS Neohellenic',
		'Gabriela, sans-serif' => 'Gabriela',
		'Gafata, sans-serif' => 'Gafata',
		'Galdeano, sans-serif' => 'Galdeano',
		'Galindo, sans-serif' => 'Galindo',
		'Gentium Basic, sans-serif' => 'Gentium Basic',
		'Gentium Book Basic, sans-serif' => 'Gentium Book Basic',
		'Geo, sans-serif' => 'Geo',
		'Geostar, sans-serif' => 'Geostar',
		'Geostar Fill, sans-serif' => 'Geostar Fill',
		'Germania One, sans-serif' => 'Germania One',
		'Gilda Display, sans-serif' => 'Gilda Display',
		'Give You Glory, sans-serif' => 'Give You Glory',
		'Glass Antiqua, sans-serif' => 'Glass Antiqua',
		'Glegoo, sans-serif' => 'Glegoo',
		'Gloria Hallelujah, sans-serif' => 'Gloria Hallelujah',
		'Goblin One, sans-serif' => 'Goblin One',
		'Gochi Hand, sans-serif' => 'Gochi Hand',
		'Gorditas, sans-serif' => 'Gorditas',
		'Goudy Bookletter 1911, sans-serif' => 'Goudy Bookletter 1911',
		'Graduate, sans-serif' => 'Graduate',
		'Grand Hotel, sans-serif' => 'Grand Hotel',
		'Gravitas One, sans-serif' => 'Gravitas One',
		'Great Vibes, sans-serif' => 'Great Vibes',
		'Griffy, sans-serif' => 'Griffy',
		'Gruppo, sans-serif' => 'Gruppo',
		'Gudea, sans-serif' => 'Gudea',
		'Habibi, sans-serif' => 'Habibi',
		'Hammersmith One, sans-serif' => 'Hammersmith One',
		'Hanalei, sans-serif' => 'Hanalei',
		'Hanalei Fill, sans-serif' => 'Hanalei Fill',
		'Handlee, sans-serif' => 'Handlee',
		'Hanuman, sans-serif' => 'Hanuman',
		'Happy Monkey, sans-serif' => 'Happy Monkey',
		'Headland One, sans-serif' => 'Headland One',
		'Henny Penny, sans-serif' => 'Henny Penny',
		'Herr Von Muellerhoff, sans-serif' => 'Herr Von Muellerhoff',
		'Holtwood One SC, sans-serif' => 'Holtwood One SC',
		'Homemade Apple, sans-serif' => 'Homemade Apple',
		'Homenaje, sans-serif' => 'Homenaje',
		'IM Fell DW Pica, sans-serif' => 'IM Fell DW Pica',
		'IM Fell DW Pica SC, sans-serif' => 'IM Fell DW Pica SC',
		'IM Fell Double Pica, sans-serif' => 'IM Fell Double Pica',
		'IM Fell Double Pica SC, sans-serif' => 'IM Fell Double Pica SC',
		'IM Fell English, sans-serif' => 'IM Fell English',
		'IM Fell English SC, sans-serif' => 'IM Fell English SC',
		'IM Fell French Canon, sans-serif' => 'IM Fell French Canon',
		'IM Fell French Canon SC, sans-serif' => 'IM Fell French Canon SC',
		'IM Fell Great Primer, sans-serif' => 'IM Fell Great Primer',
		'IM Fell Great Primer SC, sans-serif' => 'IM Fell Great Primer SC',
		'Iceberg, sans-serif' => 'Iceberg',
		'Iceland, sans-serif' => 'Iceland',
		'Imprima, sans-serif' => 'Imprima',
		'Inconsolata, sans-serif' => 'Inconsolata',
		'Inder, sans-serif' => 'Inder',
		'Indie Flower, sans-serif' => 'Indie Flower',
		'Inika, sans-serif' => 'Inika',
		'Irish Grover, sans-serif' => 'Irish Grover',
		'Istok Web, sans-serif' => 'Istok Web',
		'Italiana, sans-serif' => 'Italiana',
		'Italianno, sans-serif' => 'Italianno',
		'Jacques Francois, sans-serif' => 'Jacques Francois',
		'Jacques Francois Shadow, sans-serif' => 'Jacques Francois Shadow',
		'Jim Nightshade, sans-serif' => 'Jim Nightshade',
		'Jockey One, sans-serif' => 'Jockey One',
		'Jolly Lodger, sans-serif' => 'Jolly Lodger',
		'Josefin Sans, sans-serif' => 'Josefin Sans',
		'Josefin Slab, sans-serif' => 'Josefin Slab',
		'Joti One, sans-serif' => 'Joti One',
		'Judson, sans-serif' => 'Judson',
		'Julee, sans-serif' => 'Julee',
		'Julius Sans One, sans-serif' => 'Julius Sans One',
		'Junge, sans-serif' => 'Junge',
		'Jura, sans-serif' => 'Jura',
		'Just Another Hand, sans-serif' => 'Just Another Hand',
		'Just Me Again Down Here, sans-serif' => 'Just Me Again Down Here',
		'Kameron, sans-serif' => 'Kameron',
		'Karla, sans-serif' => 'Karla',
		'Kaushan Script, sans-serif' => 'Kaushan Script',
		'Kavoon, sans-serif' => 'Kavoon',
		'Keania One, sans-serif' => 'Keania One',
		'Kelly Slab, sans-serif' => 'Kelly Slab',
		'Kenia, sans-serif' => 'Kenia',
		'Khmer, sans-serif' => 'Khmer',
		'Kite One, sans-serif' => 'Kite One',
		'Knewave, sans-serif' => 'Knewave',
		'Kotta One, sans-serif' => 'Kotta One',
		'Koulen, sans-serif' => 'Koulen',
		'Kranky, sans-serif' => 'Kranky',
		'Kreon, sans-serif' => 'Kreon',
		'Kristi, sans-serif' => 'Kristi',
		'Krona One, sans-serif' => 'Krona One',
		'La Belle Aurore, sans-serif' => 'La Belle Aurore',
		'Lancelot, sans-serif' => 'Lancelot',
		'Lato, sans-serif' => 'Lato',
		'League Script, sans-serif' => 'League Script',
		'Leckerli One, sans-serif' => 'Leckerli One',
		'Ledger, sans-serif' => 'Ledger',
		'Lekton, sans-serif' => 'Lekton',
		'Lemon, sans-serif' => 'Lemon',
		'Libre Baskerville, sans-serif' => 'Libre Baskerville',
		'Life Savers, sans-serif' => 'Life Savers',
		'Lilita One, sans-serif' => 'Lilita One',
		'Limelight, sans-serif' => 'Limelight',
		'Linden Hill, sans-serif' => 'Linden Hill',
		'Lobster, sans-serif' => 'Lobster',
		'Lobster Two, sans-serif' => 'Lobster Two',
		'Londrina Outline, sans-serif' => 'Londrina Outline',
		'Londrina Shadow, sans-serif' => 'Londrina Shadow',
		'Londrina Sketch, sans-serif' => 'Londrina Sketch',
		'Londrina Solid, sans-serif' => 'Londrina Solid',
		'Lora, sans-serif' => 'Lora',
		'Love Ya Like A Sister, sans-serif' => 'Love Ya Like A Sister',
		'Loved by the King, sans-serif' => 'Loved by the King',
		'Lovers Quarrel, sans-serif' => 'Lovers Quarrel',
		'Luckiest Guy, sans-serif' => 'Luckiest Guy',
		'Lusitana, sans-serif' => 'Lusitana',
		'Lustria, sans-serif' => 'Lustria',
		'Macondo, sans-serif' => 'Macondo',
		'Macondo Swash Caps, sans-serif' => 'Macondo Swash Caps',
		'Magra, sans-serif' => 'Magra',
		'Maiden Orange, sans-serif' => 'Maiden Orange',
		'Mako, sans-serif' => 'Mako',
		'Marcellus, sans-serif' => 'Marcellus',
		'Marcellus SC, sans-serif' => 'Marcellus SC',
		'Marck Script, sans-serif' => 'Marck Script',
		'Margarine, sans-serif' => 'Margarine',
		'Marko One, sans-serif' => 'Marko One',
		'Marmelad, sans-serif' => 'Marmelad',
		'Marvel, sans-serif' => 'Marvel',
		'Mate, sans-serif' => 'Mate',
		'Mate SC, sans-serif' => 'Mate SC',
		'Maven Pro, sans-serif' => 'Maven Pro',
		'McLaren, sans-serif' => 'McLaren',
		'Meddon, sans-serif' => 'Meddon',
		'MedievalSharp, sans-serif' => 'MedievalSharp',
		'Medula One, sans-serif' => 'Medula One',
		'Megrim, sans-serif' => 'Megrim',
		'Meie Script, sans-serif' => 'Meie Script',
		'Merienda, sans-serif' => 'Merienda',
		'Merienda One, sans-serif' => 'Merienda One',
		'Merriweather, sans-serif' => 'Merriweather',
		'Merriweather Sans, sans-serif' => 'Merriweather Sans',
		'Metal, sans-serif' => 'Metal',
		'Metal Mania, sans-serif' => 'Metal Mania',
		'Metamorphous, sans-serif' => 'Metamorphous',
		'Metrophobic, sans-serif' => 'Metrophobic',
		'Michroma, sans-serif' => 'Michroma',
		'Milonga, sans-serif' => 'Milonga',
		'Miltonian, sans-serif' => 'Miltonian',
		'Miltonian Tattoo, sans-serif' => 'Miltonian Tattoo',
		'Miniver, sans-serif' => 'Miniver',
		'Miss Fajardose, sans-serif' => 'Miss Fajardose',
		'Modern Antiqua, sans-serif' => 'Modern Antiqua',
		'Molengo, sans-serif' => 'Molengo',
		'Molle, sans-serif' => 'Molle',
		'Monda, sans-serif' => 'Monda',
		'Monofett, sans-serif' => 'Monofett',
		'Monoton, sans-serif' => 'Monoton',
		'Monsieur La Doulaise, sans-serif' => 'Monsieur La Doulaise',
		'Montaga, sans-serif' => 'Montaga',
		'Montez, sans-serif' => 'Montez',
		'Montserrat, sans-serif' => 'Montserrat',
		'Montserrat Alternates, sans-serif' => 'Montserrat Alternates',
		'Montserrat Subrayada, sans-serif' => 'Montserrat Subrayada',
		'Moul, sans-serif' => 'Moul',
		'Moulpali, sans-serif' => 'Moulpali',
		'Mountains of Christmas, sans-serif' => 'Mountains of Christmas',
		'Mouse Memoirs, sans-serif' => 'Mouse Memoirs',
		'Mr Bedfort, sans-serif' => 'Mr Bedfort',
		'Mr Dafoe, sans-serif' => 'Mr Dafoe',
		'Mr De Haviland, sans-serif' => 'Mr De Haviland',
		'Mrs Saint Delafield, sans-serif' => 'Mrs Saint Delafield',
		'Mrs Sheppards, sans-serif' => 'Mrs Sheppards',
		'Muli, sans-serif' => 'Muli',
		'Mystery Quest, sans-serif' => 'Mystery Quest',
		'Neucha, sans-serif' => 'Neucha',
		'Neuton, sans-serif' => 'Neuton',
		'New Rocker, sans-serif' => 'New Rocker',
		'News Cycle, sans-serif' => 'News Cycle',
		'Niconne, sans-serif' => 'Niconne',
		'Nixie One, sans-serif' => 'Nixie One',
		'Nobile, sans-serif' => 'Nobile',
		'Nokora, sans-serif' => 'Nokora',
		'Norican, sans-serif' => 'Norican',
		'Nosifer, sans-serif' => 'Nosifer',
		'Nothing You Could Do, sans-serif' => 'Nothing You Could Do',
		'Noticia Text, sans-serif' => 'Noticia Text',
		'Nova Cut, sans-serif' => 'Nova Cut',
		'Nova Flat, sans-serif' => 'Nova Flat',
		'Nova Mono, sans-serif' => 'Nova Mono',
		'Nova Oval, sans-serif' => 'Nova Oval',
		'Nova Round, sans-serif' => 'Nova Round',
		'Nova Script, sans-serif' => 'Nova Script',
		'Nova Slim, sans-serif' => 'Nova Slim',
		'Nova Square, sans-serif' => 'Nova Square',
		'Numans, sans-serif' => 'Numans',
		'Nunito, sans-serif' => 'Nunito',
		'Odor Mean Chey, sans-serif' => 'Odor Mean Chey',
		'Offside, sans-serif' => 'Offside',
		'Old Standard TT, sans-serif' => 'Old Standard TT',
		'Oldenburg, sans-serif' => 'Oldenburg',
		'Oleo Script, sans-serif' => 'Oleo Script',
		'Oleo Script Swash Caps, sans-serif' => 'Oleo Script Swash Caps',
		'Open Sans, sans-serif' => 'Open Sans',
		'Open Sans Condensed, sans-serif' => 'Open Sans Condensed',
		'Oranienbaum, sans-serif' => 'Oranienbaum',
		'Orbitron, sans-serif' => 'Orbitron',
		'Oregano, sans-serif' => 'Oregano',
		'Orienta, sans-serif' => 'Orienta',
		'Original Surfer, sans-serif' => 'Original Surfer',
		'Oswald, sans-serif' => 'Oswald',
		'Over the Rainbow, sans-serif' => 'Over the Rainbow',
		'Overlock, sans-serif' => 'Overlock',
		'Overlock SC, sans-serif' => 'Overlock SC',
		'Ovo, sans-serif' => 'Ovo',
		'Oxygen, sans-serif' => 'Oxygen',
		'Oxygen Mono, sans-serif' => 'Oxygen Mono',
		'PT Mono, sans-serif' => 'PT Mono',
		'PT Sans, sans-serif' => 'PT Sans',
		'PT Sans Caption, sans-serif' => 'PT Sans Caption',
		'PT Sans Narrow, sans-serif' => 'PT Sans Narrow',
		'PT Serif, sans-serif' => 'PT Serif',
		'PT Serif Caption, sans-serif' => 'PT Serif Caption',
		'Pacifico, sans-serif' => 'Pacifico',
		'Paprika, sans-serif' => 'Paprika',
		'Parisienne, sans-serif' => 'Parisienne',
		'Passero One, sans-serif' => 'Passero One',
		'Passion One, sans-serif' => 'Passion One',
		'Patrick Hand, sans-serif' => 'Patrick Hand',
		'Patrick Hand SC, sans-serif' => 'Patrick Hand SC',
		'Patua One, sans-serif' => 'Patua One',
		'Paytone One, sans-serif' => 'Paytone One',
		'Peralta, sans-serif' => 'Peralta',
		'Permanent Marker, sans-serif' => 'Permanent Marker',
		'Petit Formal Script, sans-serif' => 'Petit Formal Script',
		'Petrona, sans-serif' => 'Petrona',
		'Philosopher, sans-serif' => 'Philosopher',
		'Piedra, sans-serif' => 'Piedra',
		'Pinyon Script, sans-serif' => 'Pinyon Script',
		'Pirata One, sans-serif' => 'Pirata One',
		'Plaster, sans-serif' => 'Plaster',
		'Play, sans-serif' => 'Play',
		'Playball, sans-serif' => 'Playball',
		'Playfair Display, sans-serif' => 'Playfair Display',
		'Playfair Display SC, sans-serif' => 'Playfair Display SC',
		'Podkova, sans-serif' => 'Podkova',
		'Poiret One, sans-serif' => 'Poiret One',
		'Poller One, sans-serif' => 'Poller One',
		'Poly, sans-serif' => 'Poly',
		'Pompiere, sans-serif' => 'Pompiere',
		'Pontano Sans, sans-serif' => 'Pontano Sans',
		'Port Lligat Sans, sans-serif' => 'Port Lligat Sans',
		'Port Lligat Slab, sans-serif' => 'Port Lligat Slab',
		'Prata, sans-serif' => 'Prata',
		'Preahvihear, sans-serif' => 'Preahvihear',
		'Press Start 2P, sans-serif' => 'Press Start 2P',
		'Princess Sofia, sans-serif' => 'Princess Sofia',
		'Prociono, sans-serif' => 'Prociono',
		'Prosto One, sans-serif' => 'Prosto One',
		'Puritan, sans-serif' => 'Puritan',
		'Purple Purse, sans-serif' => 'Purple Purse',
		'Quando, sans-serif' => 'Quando',
		'Quantico, sans-serif' => 'Quantico',
		'Quattrocento, sans-serif' => 'Quattrocento',
		'Quattrocento Sans, sans-serif' => 'Quattrocento Sans',
		'Questrial, sans-serif' => 'Questrial',
		'Quicksand, sans-serif' => 'Quicksand',
		'Quintessential, sans-serif' => 'Quintessential',
		'Qwigley, sans-serif' => 'Qwigley',
		'Racing Sans One, sans-serif' => 'Racing Sans One',
		'Radley, sans-serif' => 'Radley',
		'Raleway, sans-serif' => 'Raleway',
		'Raleway Dots, sans-serif' => 'Raleway Dots',
		'Rambla, sans-serif' => 'Rambla',
		'Rammetto One, sans-serif' => 'Rammetto One',
		'Ranchers, sans-serif' => 'Ranchers',
		'Rancho, sans-serif' => 'Rancho',
		'Rationale, sans-serif' => 'Rationale',
		'Redressed, sans-serif' => 'Redressed',
		'Reenie Beanie, sans-serif' => 'Reenie Beanie',
		'Revalia, sans-serif' => 'Revalia',
		'Ribeye, sans-serif' => 'Ribeye',
		'Ribeye Marrow, sans-serif' => 'Ribeye Marrow',
		'Righteous, sans-serif' => 'Righteous',
		'Risque, sans-serif' => 'Risque',
		'Roboto, sans-serif' => 'Roboto',
		'Roboto Condensed, sans-serif' => 'Roboto Condensed',
		'Rochester, sans-serif' => 'Rochester',
		'Rock Salt, sans-serif' => 'Rock Salt',
		'Rokkitt, sans-serif' => 'Rokkitt',
		'Romanesco, sans-serif' => 'Romanesco',
		'Ropa Sans, sans-serif' => 'Ropa Sans',
		'Rosario, sans-serif' => 'Rosario',
		'Rosarivo, sans-serif' => 'Rosarivo',
		'Rouge Script, sans-serif' => 'Rouge Script',
		'Ruda, sans-serif' => 'Ruda',
		'Rufina, sans-serif' => 'Rufina',
		'Ruge Boogie, sans-serif' => 'Ruge Boogie',
		'Ruluko, sans-serif' => 'Ruluko',
		'Rum Raisin, sans-serif' => 'Rum Raisin',
		'Ruslan Display, sans-serif' => 'Ruslan Display',
		'Russo One, sans-serif' => 'Russo One',
		'Ruthie, sans-serif' => 'Ruthie',
		'Rye, sans-serif' => 'Rye',
		'Sacramento, sans-serif' => 'Sacramento',
		'Sail, sans-serif' => 'Sail',
		'Salsa, sans-serif' => 'Salsa',
		'Sanchez, sans-serif' => 'Sanchez',
		'Sancreek, sans-serif' => 'Sancreek',
		'Sansita One, sans-serif' => 'Sansita One',
		'Sarina, sans-serif' => 'Sarina',
		'Satisfy, sans-serif' => 'Satisfy',
		'Scada, sans-serif' => 'Scada',
		'Schoolbell, sans-serif' => 'Schoolbell',
		'Seaweed Script, sans-serif' => 'Seaweed Script',
		'Sevillana, sans-serif' => 'Sevillana',
		'Seymour One, sans-serif' => 'Seymour One',
		'Shadows Into Light, sans-serif' => 'Shadows Into Light',
		'Shadows Into Light Two, sans-serif' => 'Shadows Into Light Two',
		'Shanti, sans-serif' => 'Shanti',
		'Share, sans-serif' => 'Share',
		'Share Tech, sans-serif' => 'Share Tech',
		'Share Tech Mono, sans-serif' => 'Share Tech Mono',
		'Shojumaru, sans-serif' => 'Shojumaru',
		'Short Stack, sans-serif' => 'Short Stack',
		'Siemreap, sans-serif' => 'Siemreap',
		'Sigmar One, sans-serif' => 'Sigmar One',
		'Signika, sans-serif' => 'Signika',
		'Signika Negative, sans-serif' => 'Signika Negative',
		'Simonetta, sans-serif' => 'Simonetta',
		'Sintony, sans-serif' => 'Sintony',
		'Sirin Stencil, sans-serif' => 'Sirin Stencil',
		'Six Caps, sans-serif' => 'Six Caps',
		'Skranji, sans-serif' => 'Skranji',
		'Slackey, sans-serif' => 'Slackey',
		'Smokum, sans-serif' => 'Smokum',
		'Smythe, sans-serif' => 'Smythe',
		'Sniglet, sans-serif' => 'Sniglet',
		'Snippet, sans-serif' => 'Snippet',
		'Snowburst One, sans-serif' => 'Snowburst One',
		'Sofadi One, sans-serif' => 'Sofadi One',
		'Sofia, sans-serif' => 'Sofia',
		'Sonsie One, sans-serif' => 'Sonsie One',
		'Sorts Mill Goudy, sans-serif' => 'Sorts Mill Goudy',
		'Source Code Pro, sans-serif' => 'Source Code Pro',
		'Source Sans Pro, sans-serif' => 'Source Sans Pro',
		'Special Elite, sans-serif' => 'Special Elite',
		'Spicy Rice, sans-serif' => 'Spicy Rice',
		'Spinnaker, sans-serif' => 'Spinnaker',
		'Spirax, sans-serif' => 'Spirax',
		'Squada One, sans-serif' => 'Squada One',
		'Stalemate, sans-serif' => 'Stalemate',
		'Stalinist One, sans-serif' => 'Stalinist One',
		'Stardos Stencil, sans-serif' => 'Stardos Stencil',
		'Stint Ultra Condensed, sans-serif' => 'Stint Ultra Condensed',
		'Stint Ultra Expanded, sans-serif' => 'Stint Ultra Expanded',
		'Stoke, sans-serif' => 'Stoke',
		'Strait, sans-serif' => 'Strait',
		'Sue Ellen Francisco, sans-serif' => 'Sue Ellen Francisco',
		'Sunshiney, sans-serif' => 'Sunshiney',
		'Supermercado One, sans-serif' => 'Supermercado One',
		'Suwannaphum, sans-serif' => 'Suwannaphum',
		'Swanky and Moo Moo, sans-serif' => 'Swanky and Moo Moo',
		'Syncopate, sans-serif' => 'Syncopate',
		'Tangerine, sans-serif' => 'Tangerine',
		'Taprom, sans-serif' => 'Taprom',
		'Tauri, sans-serif' => 'Tauri',
		'Telex, sans-serif' => 'Telex',
		'Tenor Sans, sans-serif' => 'Tenor Sans',
		'Text Me One, sans-serif' => 'Text Me One',
		'The Girl Next Door, sans-serif' => 'The Girl Next Door',
		'Tienne, sans-serif' => 'Tienne',
		'Tinos, sans-serif' => 'Tinos',
		'Titan One, sans-serif' => 'Titan One',
		'Titillium Web, sans-serif' => 'Titillium Web',
		'Trade Winds, sans-serif' => 'Trade Winds',
		'Trocchi, sans-serif' => 'Trocchi',
		'Trochut, sans-serif' => 'Trochut',
		'Trykker, sans-serif' => 'Trykker',
		'Tulpen One, sans-serif' => 'Tulpen One',
		'Ubuntu, sans-serif' => 'Ubuntu',
		'Ubuntu Condensed, sans-serif' => 'Ubuntu Condensed',
		'Ubuntu Mono, sans-serif' => 'Ubuntu Mono',
		'Ultra, sans-serif' => 'Ultra',
		'Uncial Antiqua, sans-serif' => 'Uncial Antiqua',
		'Underdog, sans-serif' => 'Underdog',
		'Unica One, sans-serif' => 'Unica One',
		'UnifrakturCook, sans-serif' => 'UnifrakturCook',
		'UnifrakturMaguntia, sans-serif' => 'UnifrakturMaguntia',
		'Unkempt, sans-serif' => 'Unkempt',
		'Unlock, sans-serif' => 'Unlock',
		'Unna, sans-serif' => 'Unna',
		'VT323, sans-serif' => 'VT323',
		'Vampiro One, sans-serif' => 'Vampiro One',
		'Varela, sans-serif' => 'Varela',
		'Varela Round, sans-serif' => 'Varela Round',
		'Vast Shadow, sans-serif' => 'Vast Shadow',
		'Vibur, sans-serif' => 'Vibur',
		'Vidaloka, sans-serif' => 'Vidaloka',
		'Viga, sans-serif' => 'Viga',
		'Voces, sans-serif' => 'Voces',
		'Volkhov, sans-serif' => 'Volkhov',
		'Vollkorn, sans-serif' => 'Vollkorn',
		'Voltaire, sans-serif' => 'Voltaire',
		'Waiting for the Sunrise, sans-serif' => 'Waiting for the Sunrise',
		'Wallpoet, sans-serif' => 'Wallpoet',
		'Walter Turncoat, sans-serif' => 'Walter Turncoat',
		'Warnes, sans-serif' => 'Warnes',
		'Wellfleet, sans-serif' => 'Wellfleet',
		'Wendy One, sans-serif' => 'Wendy One',
		'Wire One, sans-serif' => 'Wire One',
		'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz',
		'Yellowtail, sans-serif' => 'Yellowtail',
		'Yeseva One, sans-serif' => 'Yeseva One',
		'Yesteryear, sans-serif' => 'Yesteryear',
		'Zeyada, sans-serif' => 'Zeyada'
		);		
	return $fonts;
}


?>
