<?php
/**
 *
 */
class itOptionGenerator {
	
	private $saved_options;
	private $saved_internal;
	private $saved_sidebars;	
	
	function __construct( $options ) {
		
		$this->saved_options();
		
		$out = '<div id="it_admin_panel">';
		$out .= '<form name="it_admin_form" method="post" action="options.php" id="it_admin_form">';
		
		$out .= $this->settings_fields();
		$out .= '<input type="hidden" name="it_full_submit" value="0" id="it_full_submit" />';
		$out .= '<input type="hidden" name="it_admin_wpnonce" value="' . wp_create_nonce( IT_SETTINGS . '_wpnonce' ) . '" />';
		
		$out .= '<div id="it_header">';
		
		$out .= '<div id="it_logo"><img src="' . ( !empty( $this->saved_options['admin_logo_url'] ) ? esc_url( $this->saved_options['admin_logo_url'] ) :
		esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/logo.png' ) . '" alt="" /></div>';
		
		$out .= '<div id="header_links">';
		$out .= '<span>' . THEME_NAME . ' ' . THEME_VERSION . '</span>';
		$out .= '<a href="' . DOCUMENTATION_URL . '" target="_blank">' . __( 'Documentation', IT_TEXTDOMAIN ) . '</a>';
		$out .= '<a href="' . SUPPORT_URL . '" target="_blank">' . __( 'Support Portal', IT_TEXTDOMAIN ) . '</a>';
		$out .= '<a href="' . CREDITS_URL . '" target="_blank">' . __( 'Credits', IT_TEXTDOMAIN ) . '</a>';
		$out .= '</div><!-- #header_links -->';
		$out .= '</div><!-- #it_header -->';
		
		$out .= '<div id="it_body">';
		
		foreach( $options as $option )
			$out .= $this->$option['type']( $option );

		$out .= '</div><!-- #it_tab_content -->';
		$out .= '<div class="clear"></div>';
		$out .= '</div><!-- #it_body -->';
		
		$out .= '<div id="it_footer">';
		
		$out .= '<input type="submit" name="' . IT_SETTINGS . '[reset]" value="' . esc_attr__( 'Reset All Options' , IT_TEXTDOMAIN ) . '" class="button it_reset_button" />';
		$out .= '<input type="submit" name="' . IT_SETTINGS . '[load_demo]" value="' . esc_attr__( 'Load Demo Settings' , IT_TEXTDOMAIN ) . '" class="button-primary it_demo_button" />';
		$out .= '<input type="submit" name="submit" value="' . esc_attr__( 'Save All Changes' , IT_TEXTDOMAIN ) . '" class="button-primary it_footer_submit" />';
		
		$out .= '</div><!-- #it_footer -->';
		
		$out .= '</form><!-- #it_admin_form -->';
		
		$out .= '</div><!-- #it_admin_panel -->';
		
		echo $out;
	}
	
	/**
	 *
	 */
	function saved_options() {
		$this->saved_options = get_option( IT_SETTINGS );
		$this->saved_internal = get_option( IT_INTERNAL_SETTINGS );
		$this->saved_sidebars = get_option( IT_SIDEBARS );
	}
	
	/**
	 *
	 */
	function messages() {
		$message = '';
		
		if( isset( $_GET['reset'] ) )
			$message = __( 'All options and widgets successfully restored to default.', IT_TEXTDOMAIN );
			
		if( isset( $_GET['demo'] ) )
			$message = __( 'All demo options, widgets, and menus successfully imported.', IT_TEXTDOMAIN );
			
		if( isset( $_GET['confirm_minisites'] ) )
			$message = __( 'Options saved and minisites confirmed.', IT_TEXTDOMAIN );		
			
		if( isset( $_GET['settings-updated'] ) )
			$message = __( 'Settings Saved.', IT_TEXTDOMAIN );
			
		if( isset( $_GET['import'] ) && $_GET['import'] == 'true' )
			$message = __( 'Custom Options Import Successful.', IT_TEXTDOMAIN );
			
		if( isset( $_GET['import'] ) && $_GET['import'] == 'false' )
			$message = __( 'There was an error importing your options, please try again.', IT_TEXTDOMAIN );
			
		$style = ( !$message ) ? ' style="display:none;"' : '';
		
		$out = '<div id="message" class="error fade below-h2"' . $style . '>' . $message . '</div>';
		$out .= '<div id="ajax-feedback"><img src="' . esc_url( admin_url( 'images/loading.gif' ) ) . '" alt="" /></div>';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function settings_fields() {
		ob_start(); settings_fields( IT_SETTINGS ); $out = ob_get_clean();
		return $out;
	}
	
	/**
	 * 
	 */
	function navigation( $value ) {
		$out = '<div id="it_admin_tabs">';
		$out .= '<ul>';
		
		foreach( $value['name'] as $key => $name ) {
			$out .= '<li><a title="' . $name . '" href="#' . $key . '">' . $name . '</a></li>';
		}
		$out .= '</ul>';
		$out .= '</div><!-- #it_admin_tabs -->';
		$out .= '<div id="it_tab_content">';
		
		$out .= $this->messages();
		
		$out .= '<div class="it_admin_save"><input type="submit" name="submit" value="' . esc_attr__( 'Save All Changes' , IT_TEXTDOMAIN ) . '" class="button-primary" /></div>';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function tab_start( $value ) {
		foreach( $value['name'] as $key => $name ) {
			$out = '<div id="' . $key . '" class="it_tab">';
			$out .= '<div>';
			$out .= '<h2>' . $name[$key] . '</h2>';
			$out .= '</div>';
		}
		
		return $out;
	}
	
	/**
	 * 
	 */
	function tab_end( $value ) {
		$out = '</div>';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function option_start( $value ) {
		$out = '';
		
		if( $value['name'] ) {
			$out .= '<div class="it_option_header">' . $value['name'] . '</div>';
		}
		
		$out .= '<div class="it_option">';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function option_end( $value ) {
		$out = '</div><!-- it_option -->';
		
		if( !empty( $value['desc'] ) ) {
			$out .= '<div class="it_option_help">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . $value['desc'] . '</div>';
			$out .= '</div>';
		}

		$out .= '<div class="clear"></div>';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function toggle_start( $value ) {
		$toggle_class = ( !empty( $value['toggle_class'] ) ) ? $value['toggle_class'] . ' ' : '';
		
		$out = '<div class="it_option_set toggle_option_set">';
		$out .= '<h3 class="option_toggle ' . $toggle_class . 'trigger"><a href="#">' . str_replace( ' ~', '', $value['name'] ) . ' <span>[+]</span></a></h3>';
		$out .= '<div class="toggle_container" style="display:none;">';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function toggle_end ($value ) {
		$out = '</div></div>';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function heading( $value ) {
		$out = '<h3>'.$value['name'].'</h3>';		
		
		if( !empty( $value['desc'] ) ) {			
			$out .= '<div class="desc">' . $value['desc'] . '</div>';			
		}
		
		return $out;
	}
	
	/**
	 *
	 */
	function text( $value ) {
		$size = isset( $value['size'] ) ? $value['size'] : '10';
		$toggle_class = ( !empty( $value['toggle_class'] ) ) ? $value['toggle_class'] . ' ' : '';
		
		$out = '<div class="' . $toggle_class . 'it_option_set text_option_set">';
		
		$out .= $this->option_start( $value );
		
		$out .= '<input type="text" name="' . IT_SETTINGS . '[' . $value['id'] . ']" id="' . $value['id'] . '" class="it_textfield" value="' .
		( isset( $this->saved_options[$value['id']] ) && isset( $value['htmlentities'] )
		? stripslashes(htmlentities( $this->saved_options[$value['id']], ENT_QUOTES, 'UTF-8' ) ) : ( isset( $this->saved_options[$value['id']] ) && isset( $value['htmlspecialchars'] )
		? stripslashes(htmlspecialchars( $this->saved_options[$value['id']] ) )
		: ( isset( $this->saved_options[$value['id']] ) ? stripslashes( $this->saved_options[$value['id']] ) : ( isset( $value['default'] ) ? $value['default'] : '' ) ) ) ) . '" />';
		
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .text_option_set -->';
		
		return $out;
	}
	
	/**
	 *
	 */
	function textarea( $value ) {
		$toggle_class = ( !empty( $value['toggle_class'] ) ) ? $value['toggle_class'] . ' ' : '';
		
		$out = '<div class="' . $toggle_class . 'it_option_set textarea_option_set">';
		
		$out .= $this->option_start( $value );
		
		$out .= '<textarea rows="8" cols="8" name="' . IT_SETTINGS . '[' . $value['id'] . ']" id="' . $value['id'] . '" class="it_textarea">' .
		( isset( $this->saved_options[$value['id']] )
		? stripslashes( $this->saved_options[$value['id']] )
		: ( isset( $value['default'] ) ? $value['default'] : '' ) ) . '</textarea><br />';
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .textarea_option_set -->';
		
		return $out;
	}
	
	/**
	 *
	 */
	function select( $value ) {
		$toggle_class = ( !empty( $value['toggle_class'] ) ) ? $value['toggle_class'] . ' ' : '';
		$toggle = ( !empty( $value['toggle'] ) ) ? $value['toggle'] . ' ' : '';
		
		$out = '<div class="' . $toggle_class . 'it_option_set select_option_set">';
		
		$out .= $this->option_start( $value );
		
		$nodisable = '';
		if(array_key_exists('nodisable',$value)) $nodisable = $value['nodisable'];
		
		if( isset( $value['target'] ) ) {
			if( isset( $value['options'] ) ) {
				$value['options'] = $value['options'] + $this->select_target_options( $value['target'], $value['nodisable'] );
			} else {
				$value['options'] = $this->select_target_options( $value['target'], $nodisable );
			}
		}
		
		$out .= '<select name="' . IT_SETTINGS . '[' . $value['id'] . ']" id="' . $value['id'] . '" class="' . $toggle . 'it_select">';
		
		$out .= '<option value="">' . __( 'Choose one...', IT_TEXTDOMAIN ) . '</option>';
		
		if(is_array($value)) {
			if(array_key_exists('options',$value)) {
				foreach( (array)$value['options'] as $key => $option ) {
					if($value['target']=='fonts') {
						$out .= '<option value="' . esc_attr( $key ) . '"';
					} else {
						$out .= '<option value="' . $key . '"';
					}
					if( isset( $this->saved_options[$value['id']] ) ) {
						if($value['target']=='fonts') {
							if( stripslashes($this->saved_options[$value['id']]) == $key ) {
								$out .= ' selected="selected"';
							}
						} else {
							if( $this->saved_options[$value['id']] == $key ) {
								$out .= ' selected="selected"';
							}
						}
						
					} elseif( isset( $value['default'] ) ) {
						if( $value['default'] == $key ) {
							$out .= ' selected="selected"';
						}
					}
					
					$out .= '>' . esc_attr( $option ) . '</option>';
				}
			}
		}
		
		$out .= '</select>';
		
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .select_option_set -->';
		
		return $out;
	}
	
	/**
	 *
	 */
	function multidropdown( $value ) {
		$toggle_class = ( !empty( $value['toggle_class'] ) ) ? $value['toggle_class'] . ' ' : '';
		
		$out = '<div class="' . $toggle_class . 'it_option_set multidropdown_option_set">';
		
		$out .= $this->option_start( $value );
		
		if( isset( $value['target'] ) ) {
			if( isset( $value['options'] ) ) {
				$value['options'] = $value['options'] + $this->select_target_options( $value['target'] );
			} else {
				$value['options'] = $this->select_target_options( $value['target'] );
			}
		}

		$selected_keys = ( isset( $this->saved_options[$value['id']] ) ) ? $this->saved_options[$value['id']] : array();
		
		$out .= '<div id="' . IT_SETTINGS . '[' . $value['id'] . ']" class="multidropdown">';
		
		$i = 0;
		foreach( $selected_keys as $selected ) {
			$out .= '<select name="' . $value['id'] . '_' . $i . '" id="' . $value['id'] . '_' . $i . '" class="it_select">';
			$out .= '<option value=""> ' . __( 'Choose one...', IT_TEXTDOMAIN ) . '</option>';
			foreach( $value['options'] as $key => $option ) {
				$out .= '<option value="' . $key . '"';
				if( $selected == $key ) {
					$out .= ' selected="selected"';
				}
				$out .= '>' . esc_attr( $option ) . '</option>';
			}
			$i++;
			$out .= '</select>';
		}
		
		$out .= '<select name="' . $value['id'] . '_' . $i . '" id="' . $value['id'] . '_' . $i . '" class="it_select">';
		$out .= '<option value="">' . __( 'Choose one...', IT_TEXTDOMAIN ) . '</option>';
		foreach( $value['options'] as $key => $option ) {
			$out .= '<option value="' . $key . '">' . $option . '</option>';
		}
		$out .= '</select></div>';
		
		$out .= $this->option_end( $value );
	
		$out .= '</div><!-- .multidropdown_option_set -->';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function checkbox( $value ) {
		$toggle_class = ( !empty( $value['toggle_class'] ) ) ? $value['toggle_class'] . ' ' : '';
		$toggle = ( !empty( $value['toggle'] ) ) ? ' class="' . $value['toggle'] . '"' : '';
		
		$out = '<div class="' . $toggle_class . 'it_option_set checkbox_option_set">';
		
		$out .= $this->option_start( $value );
		
		if( isset( $value['target'] ) ) {
			if( isset( $value['options'] ) ) {
				$value['options'] = $value['options'] + $this->select_target_options( $value['target'] );
			} else {
				$value['options'] = $this->select_target_options( $value['target'] );
			}
		}
		
		$i = 0;
		foreach( $value['options'] as $key => $option ) {
			$i++;
			$checked = '';
			if( isset( $this->saved_options[$value['id']] ) ) {
				if( is_array( $this->saved_options[$value['id']] ) ) {
					if( in_array( $key, $this->saved_options[$value['id']] ) ) {
						$checked = ' checked="checked"';
					}
				}
				
			} elseif ( isset( $value['default'] ) ){
				if( is_array( $value['default'] ) ) {
					if( in_array( $key, $value['default'] ) ) {
						$checked = ' checked="checked"';
					}
				}
			}
			
			$out .= '<input type="checkbox" name="' . IT_SETTINGS . '[' . $value['id'] . '][]" value="' . $key . '" id="' . $value['id'] . '-' . $i . '"' . $checked . $toggle . ' />';
			$out .= '<label for="' . $value['id'] . '-' . $i . '">' . esc_html( $option ) . '</label><br />';
		}
		
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .checkbox_option_set -->';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function radio( $value ) {
		$toggle_class = ( !empty( $value['toggle_class'] ) ) ? $value['toggle_class'] . ' ' : '';
		$toggle = ( !empty( $value['toggle'] ) ) ? ' class="' . $value['toggle'] . '"' : '';
		
		$out = '<div class="' . $toggle_class . 'it_option_set radio_option_set">';
		
		$out .= $this->option_start( $value );
		
		$checked_key = ( isset( $this->saved_options[$value['id']] ) ? $this->saved_options[$value['id']] : ( isset( $value['default'] ) ? $value['default'] : '' ) );
			
		$i = 0;
		foreach( $value['options'] as $key => $option ) {
			$i++;
			$checked = ( $key == $checked_key ) ? ' checked="checked"' : '';
			
			$out .= '<input type="radio" name="' . IT_SETTINGS . '[' . $value['id'] . ']" value="' . $key . '" ' . $checked . ' id="' . $value['id'] . '_' . $i . '"' . $toggle .' />';
			$out .= '<label for="' . $value['id'] . '_' . $i . '">' . $option . '</label><br />';
		}
		
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .radio_option_set -->';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function upload( $value ) {
		$out = '<div class="it_option_set upload_option_set">';
		
		$out .= $this->option_start( $value );
		
		$out .= '<input type="text" name="' . IT_SETTINGS . '[' . $value['id'] . ']" value="' . ( isset( $this->saved_options[$value['id']] )
		? esc_url(stripslashes( $this->saved_options[$value['id']] ) )
		: ( isset( $value['default'] ) ? $value['default'] : '' ) ) . '" id="' . $value['id'] . '" class="it_upload" />';
		
		$out .= '<input type="button" value="' . esc_attr__( 'Choose' , IT_TEXTDOMAIN ) . '" class="upload_button button" id="' . $value['id'] . '_button" name="' . $value['id'] . '_button" /><br />';
		
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .upload_option_set -->';
		
		return $out;
	}
	
	/**
	 * 
	 */
	function editor( $value ) {
		global $wp_version, $post, $post_type;
		
		$out = '';
		
		if( !isset( $value['no_header'] ) && isset( $value['name'] ) ) {
			$out .= '<h3 class="editor_option_header">' . $value['name'] . '</h3>';
			$value['name'] = '';
		}
		
		$out .= '<div class="it_option_set editor_option_set">';
		
		$out .= $this->option_start( $value );

		$content = ( isset( $this->saved_options[$value['id']] ) ? stripslashes( $this->saved_options[$value['id']] )
		: ( isset( $value['default'] ) ? $value['default'] : '' ) );
		
		$content_id = IT_SETTINGS . '[' . $value['id'] .']';
		
		if( version_compare( $wp_version, '3.3', '>=' ) ) {
			
			ob_start();
			$args = array("textarea_name" => $content_id, "textarea_rows" => 10);
			wp_editor( $content, $content_id, $args );
			$editor = ob_get_contents();
			ob_end_clean();

			$out .= $editor;
		}
		else
		{
			$out .= '<div id="poststuff"><div id="post-body"><div id="post-body-content"><div class="postarea" id="postdivrich">';
			
			ob_start();
			wp_editor( $content, $content_id );
			$editor = ob_get_contents();
			ob_end_clean();

			$content_replace = IT_SETTINGS . '_' . $value['id'];

			$editor = str_replace( $content_id, $content_replace, $editor );
			$out .= str_replace( 'name=\'' . $content_replace . '\'', 'name=\'' . $content_id . '\'', $editor );
			
			$out .= '</div></div></div></div>';
		}
		
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .editor_option_set -->';

		return $out;
	}
	
	/**
	 * 
	 */
	function layout( $value ) {
		$out = '<div class="it_option_set layout_option_set">';
		
		$out .= $this->option_start( $value );
		
		foreach( $value['options'] as $rel => $image ) {
			$out .= '<a href="#" rel="' . $rel . '"><img src="' . esc_url( $image ) . '" alt="" /></a>';
		}
		
		$out .= '<input type="hidden" name="' . IT_SETTINGS . '[' . $value['id'] . ']" id="' . $value['id'] . '" value="' . ( isset( $this->saved_options[$value['id']] )
		? stripslashes( $this->saved_options[$value['id']] )
		: ( isset( $value['default'] ) ? $value['default'] : '' ) ) . '" />';
		
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .layout_option_set -->';
		
		return $out;
	}
	
	/**
	 *
	 */
	function export_options( $value ) {
		$toggle_class = ( !empty( $value['toggle_class'] ) ) ? $value['toggle_class'] . ' ' : '';
		
		$out = '<div class="' . $toggle_class . 'it_option_set textarea_option_set">';
		
		$out .= $this->option_start( $value );
		
		$options = $this->saved_options;
		
		$export_options = array();
		if( !empty( $options ) ) {
			foreach( $options as $key => $option ) {
				if( is_string( $option ) )
					$export_options[$key] = preg_replace( "/(\r\n|\r|\n)\s*/i", '<br /><br />', stripslashes( $option ) );
				else
					$export_options[$key] = $option;
			}
		}
		
		if( !empty( $export_options ) ) {
			$export_options = array_merge( $export_options, array( 'it_options_export' => true ) );
			$export_options = it_encode( $export_options, $serialize = true );
		}
					
		$out .= '<textarea rows="8" cols="8" name="' . IT_SETTINGS . '[' . $value['id'] . ']" id="' . $value['id'] . '" class="it_textarea">' . $export_options . '</textarea><br />';
		
		$out .= $this->option_end( $value );
		
		$out .= '</div><!-- .textarea_option_set -->';
		
		return $out;
	}
	
	/**
	 *
	 */
	function sidebar( $value ) {
		$out = '<div class="it_option_set sidebar_option_set">';
		
		$out .= $this->option_start( $value );
		
		$out .= '<input type="text" name="' . $value['id'] . '" id="' . $value['id'] . '" class="it_textfield" onkeyup="itAdmin.fixField(this);" value="" />';
		
		$out .= '<div class="add_sidebar">';
		$out .= '<span class="button it_add_sidebar">' . __( 'Add Sidebar', IT_TEXTDOMAIN ) . '</span>';
		$out .= '</div><!-- .add_sidebar -->';
		
		$out .= $this->option_end( $value );
		
		$init = ( !empty( $this->saved_sidebars ) ) ? false : true;
		
		$out .= '<div class="clear menu_clear"' . ( $init ? ' style="display:none;"' : '' ) . '></div>';
		
		$out .= '<ul id="sidebar-to-edit" class="menu"' . ( $init ? ' style="display:none;"' : '' ) . '>';
		
		if( !$init ){
			foreach( $this->saved_sidebars as $key => $sidebar ){
				$out .= '<li class="menu-item" id="sidebar-item-' . $key . '">';
				$out .= '<dl class="menu-item-bar">';
				$out .= '<dt class="menu-item-handle">';
				$out .= '<span class="sidebar-title">' . $sidebar . '</span>';
				$out .= '<span class="item-controls"><a href="#" class="item-type delete_sidebar" rel="sidebar-item-' . $key . '">' . __( 'Delete', IT_TEXTDOMAIN ) . '</a></span>';
				$out .= '</dt>';
				$out .= '</dl>';
				$out .= '</li>';
			}
			
		} elseif( $init ) {
			$out .= '<li></li>';
		}
		$out .= '</ul><!-- #sidebar-to-edit -->';
		
		$out .= '<ul id="sample-sidebar-item" class="menu" style="display:none;"> ';
		$out .= '<li class="menu-item" id="sidebar-item-:">';
		$out .= '<dl class="menu-item-bar">';
		$out .= '<dt class="menu-item-handle">';
		$out .= '<span class="sidebar-title">:</span>';
		$out .= '<span class="item-controls"><a href="#" class="item-type delete_sidebar" rel="sidebar-item-:">' . __( 'Delete', IT_TEXTDOMAIN ) . '</a></span>';
		$out .= '</dt>';
		$out .= '</dl>';
		$out .= '</li>';
		$out .= '</ul><!-- #sample-sidebar-item -->';
		
		$out .= '</div><!-- .sidebar_option_set -->';
		
		return $out;
	}
		
	/**
	 *
	 */
	function sociable( $value ) {
		$options = ( isset( $this->saved_options[$value['id']] ) ) ? $this->saved_options[$value['id']] : array( 'keys' => '#' );
		
		$init = false;
		
		if( $options['keys'] == '#' )
			$init = true;
		
		$sociable_keys = explode(',', $options['keys'] );
		
		$key_count = count( $sociable_keys );
					
		$out = '<div class="it_option_set sociable_option_set">';
		$out .= '<div class="add_menu"><span class="button it_add_menu">' . __( 'Add New Social Link', IT_TEXTDOMAIN ) . '</span></div>';
		
		$out .= '<div class="clear menu_clear"' . ( $init == true ? ' style="display:none;"' : '' ) . '></div>';
		
		if( $init == true )
			$out .= '<ul class="menu-to-edit menu" style="display:none;"><li></li></ul><!-- .menu-to-edit -->';
		
		$i=1;
		foreach( $sociable_keys as $key ) {
			if( ( $i == 1 ) && ( $init == false ) )
				$out .= '<ul class="menu-to-edit menu">';

			if ( $i == $key_count )
				$out .= '<ul class="sample-to-edit menu" style="display:none;">';
			
			$id = $key;
			$val = ( ( $id != '#' ) && ( isset( $options[$key] ) ) ) ? $options[$key] : '';
			
			$name = IT_SETTINGS . '[sociable][' . $id . ']';
			$custom = ( !empty( $val['custom'] ) ) ? esc_url(stripslashes( $val['custom'] ) ) : '';
			$link = ( !empty( $val['link'] ) ) ? esc_url(stripslashes( $val['link'] ) ) : '';
			$hover = ( !empty( $val['hover'] ) ) ? $val['hover'] : '';
			$icon = ( !empty( $val['icon'] ) ) ? $val['icon'] : '';
			
			if( !empty( $icon ) ) {				
				$icon_title = ucwords( $icon );
			}
						
			$out .= '<li id="sociable-menu-item-' . $id . '" class="menu-item menu-item-edit-inactive">';
			
			# menu handle
			$out .= '<dl class="menu-item-bar">';
			$out .= '<dt class="menu-item-handle">';
			$out .= '<span class="item-title">' . ( $custom || $id == '#' || empty( $icon ) ? sprintf( __( 'Social Link %1$s', IT_TEXTDOMAIN ), $i ) : $icon_title ) . '</span>';
			$out .= '<span class="item-controls">';
			$out .= '<a href="sociable-menu-item-settings-' . $id .'" title="Edit Menu Item" id="sociable-menu-edit-' . $id . '" class="item-edit">' . __( 'Edit Menu Item', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</span>';
			$out .= '</dt>';
			$out .= '</dl>';
			
			# menu settings
			$out .= '<div id="sociable-menu-item-settings-' . $id . '" class="menu-item-settings" style="display:none;">';
			
			# sociable icon
			$out .= '<p class="field-link-target description description-thin"><label for="edit-menu-sociable-icon-' . $id . '">' . __( 'Preset Icon', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<select id="edit-menu-sociable-icon-' . $id . '" class="widefat" name="' . $name . '[icon]">';
			
			$sociables_icons = it_sociable_option();
			foreach ( $sociables_icons['sociables'] as $key => $val ) {
				
				$selected = ( $icon == $key ) ? ' selected="selected"' : '' ;
				$out .= '<option' . $selected. ' value="' . $key . '">' . $val . '</option>';
			}
			$out .= '</select>';
			$out .= '</label>';
			$out .= '</p>';			
			
			# sociable url
			$out .= '<p class="description description-thin"><label for="edit-sociable-menu-url-' . $id . '">' . __( 'Custom Icon', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $custom . '" name="' . $name . '[custom]" id="edit-sociable-menu-url-' . $id . '" class="widefat sociable_custom" />';
			$out .= '&nbsp;<input type="button" value="' . esc_attr__( 'Upload' , IT_TEXTDOMAIN ) . '" class="upload_button button" /><br />';
			$out .= '</label>';
			$out .= '</p>';
			
			# sociable link
			$out .= '<p class="description description-thin"><label for="edit-sociable-menu-link-' .$id. '">' . __( 'Social Link URL', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $link . '" name="' . $name . '[link]" id="edit-sociable-menu-link-' . $id . '" class="widefat" />';
			$out .= '</label>';
			$out .= '</p>';
			
			# custom hover text
			$out .= '<p class="description description-thin"><label for="edit-sociable-menu-hover-' .$id. '">' . __( 'Custom Hover Text', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $hover . '" name="' . $name . '[hover]" id="edit-sociable-menu-hover-' . $id . '" class="widefat" />';
			$out .= '</label>';
			$out .= '</p>';
			
			# menu item actions
			$out .= '<div class="menu-item-actions description-wide submitbox">';
			$out .= '<a href="" id="delete-sociable-menu-item-' . $id . '" class="submitdelete slider_deletion">' . __( 'Remove', IT_TEXTDOMAIN ) . '</a> ';
			$out .= '<span class="meta-sep"> | </span> <a href="sociable-menu-item-settings-' . $id .'" class="slider_cancel submitcancel">' . __( 'Cancel', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</div>';
			
			
			$out .= '</div><!-- #sociable-menu-item-settings-## -->';
			$out .= '</li>';
			
			if( $i == $key_count-1 )
				$out .= '</ul><!-- .menu-to-edit -->';
			
			if( $i == $key_count )
				$out .= '</ul><!-- .sample-to-edit -->';
			
			$i++;
		}
		
		$out .= '<input type="hidden" name="' . IT_SETTINGS . '[sociable][keys]" value="' . $options['keys'] . '" class="menu-keys" />';
		$out .= '</div><!-- .sociable_option_set -->';
		
		return $out;
	}
	
	
	function signoff( $value ) {
		error_reporting(E_ALL);
		$options = ( isset( $this->saved_options[$value['id']] ) ) ? $this->saved_options[$value['id']] : array( 'keys' => '#' );
		
		$init = false;
		
		if( $options['keys'] == '#' )
			$init = true;
		
		$signoff_keys = explode(',', $options['keys'] );
		
		$key_count = count( $signoff_keys );
		
		$out = '<div class="it_option_set signoff_option_set">';
		$out .= '<div class="it_option_heading">' . $value['name'] . '</div>';
		$out .= '<div class="add_menu"><span class="button it_add_menu">' . __( 'Add New Signoff', IT_TEXTDOMAIN ) . '</span></div>';		
		
		$out .= '<div class="clear menu_clear"' . ( $init == true ? ' style="display:none;"' : '' ) . '></div>';
		
		if( $init == true )
			$out .= '<ul class="menu-to-edit menu" style="display:none;"><li></li></ul><!-- .menu-to-edit -->';
		
		$i=1;
		foreach( $signoff_keys as $key ) {
			if( ( $i == 1 ) && ( $init == false ) )
				$out .= '<ul class="menu-to-edit menu">';

			if ( $i == $key_count )
				$out .= '<ul class="sample-to-edit menu" style="display:none;">';
			
			$id = $key;
			$val = ( ( $id != '#' ) && ( isset( $options[$key] ) ) ) ? $options[$key] : '';
			
			$name = IT_SETTINGS . '[signoff][' . $id . ']';
			$signoff_name = ( !empty( $val['name'] ) ) ? stripslashes($val['name'])  : '';
			$signoff_content = ( !empty( $val['content'] ) ) ? stripslashes($val['content'])  : '';
			$custom = '';
			
			$out .= '<li id="signoff-menu-item-' . $id . '" class="menu-item menu-item-edit-inactive">';
			
			# menu handle
			$out .= '<dl class="menu-item-bar">';
			$out .= '<dt class="menu-item-handle">';
			$out .= '<span class="item-title">' .sprintf( __( 'Signoff %1$s', IT_TEXTDOMAIN ), $i ). '</span>';
			$out .= '<span class="item-controls">';
			$out .= '<a href="signoff-menu-item-settings-' . $id .'" title="Edit Signoff" id="signoff-menu-edit-' . $id . '" class="item-edit">' . __( 'Edit Signoff', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</span>';
			$out .= '</dt>';
			$out .= '</dl>';
			
			# menu settings
			$out .= '<div id="signoff-menu-item-settings-' . $id . '" class="menu-item-settings" style="display:none;">';
			
			# signoff name
			$out .= '<p class="description description-thin"><label for="edit-signoff-menu-name-' .$id. '">' . __( 'Name', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $signoff_name . '" name="' . $name . '[name]" id="edit-signoff-menu-name-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';	
			
			# signoff content
			$out .= '<p class="description description-wide"><label for="edit-signoff-menu-content-' .$id. '">' . __( 'Content', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<textarea cols="20" rows="7" name="' . $name . '[content]" id="edit-signoff-menu-content-' . $id . '" class="widefat">' . $signoff_content . '</textarea>';
			$out .= '</label>';			
			$out .= '</p>';	
			
			# menu item actions
			$out .= '<div class="menu-item-actions description-wide submitbox">';
			$out .= '<a href="" id="delete-signoff-menu-item-' . $id . '" class="submitdelete slider_deletion">' . __( 'Remove', IT_TEXTDOMAIN ) . '</a> ';
			$out .= '<span class="meta-sep"> | </span> <a href="signoff-menu-item-settings-' . $id .'" class="slider_cancel submitcancel">' . __( 'Cancel', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</div>';
			
			
			$out .= '</div><!-- #signoff-menu-item-settings-## -->';
			$out .= '</li>';
			
			if( $i == $key_count-1 )
				$out .= '</ul><!-- .menu-to-edit -->';
			
			if( $i == $key_count )
				$out .= '</ul><!-- .sample-to-edit -->';
			
			$i++;
		}			
		$out .= '<input type="hidden" name="' . IT_SETTINGS . '[signoff][keys]" value="' . $options['keys'] . '" class="menu-keys" />';
		$out .= '</div><!-- .signoff_option_set -->';
		
		return $out;
	}
	
	
	function minisite( $value ) {
		error_reporting(E_ALL);
		$options = ( isset( $this->saved_options[$value['id']] ) ) ? $this->saved_options[$value['id']] : array( 'keys' => '#' );
		
		$init = false;
		
		if( $options['keys'] == '#' )
			$init = true;
		
		$minisite_keys = explode(',', $options['keys'] );
		
		$key_count = count( $minisite_keys );
		
		$out = '<div class="it_option_set minisite_option_set">';
		$out .= '<div class="it_option_heading">' . $value['name'] . '</div>';
		$out .= '<div class="add_menu"><span class="button it_add_menu">' . __( 'Add New minisite', IT_TEXTDOMAIN ) . '</span></div>';		
		
		$out .= '<div class="clear menu_clear"' . ( $init == true ? ' style="display:none;"' : '' ) . '></div>';
		
		if( $init == true )
			$out .= '<ul class="menu-to-edit menu" style="display:none;"><li></li></ul><!-- .menu-to-edit -->';
		
		$i=1;
		foreach( $minisite_keys as $key ) {
			if( ( $i == 1 ) && ( $init == false ) )
				$out .= '<ul class="menu-to-edit menu">';

			if ( $i == $key_count )
				$out .= '<ul class="sample-to-edit menu" style="display:none;">';
			
			$id = $key;
			$val = ( ( $id != '#' ) && ( isset( $options[$key] ) ) ) ? $options[$key] : '';
			
			$name = IT_SETTINGS . '[minisite][' . $id . ']';
			$minisite_name = ( !empty( $val['name'] ) ) ? stripslashes($val['name'])  : '';
			$slug = '';
			if(is_array($val)) $slug = $val['slug'];
			$minisite_slug = it_get_slug($slug, $minisite_name);			
			$minisite_enabled = ( !empty( $val['enabled'] ) ) ? stripslashes($val['enabled'])  : '';
			$minisite_excluded = ( !empty( $val['excluded'] ) ) ? stripslashes($val['excluded'])  : '';
			$checked_enabled = ( $minisite_enabled ) ? ' checked="checked"' : '';
			$checked_disabled = ( !$minisite_enabled ) ? ' checked="checked"' : '';
			$checked_excluded = ( $minisite_excluded ) ? ' checked="checked"' : '';
			$checked_included = ( !$minisite_excluded ) ? ' checked="checked"' : '';
			$custom = '';
			
			$out .= '<li id="minisite-menu-item-' . $id . '" class="menu-item menu-item-edit-inactive">';
			
			# menu handle
			$out .= '<dl class="menu-item-bar">';
			$out .= '<dt class="menu-item-handle">';
			$out .= '<span class="item-title">' .sprintf( __( 'Minisite %1$s', IT_TEXTDOMAIN ), $i ). '</span>';
			$out .= '<span class="item-controls">';
			$out .= '<a href="minisite-menu-item-settings-' . $id .'" title="Edit Minisite" id="minisite-menu-edit-' . $id . '" class="item-edit">' . __( 'Edit Minisite', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</span>';
			$out .= '</dt>';
			$out .= '</dl>';
			
			# menu settings
			$out .= '<div id="minisite-menu-item-settings-' . $id . '" class="menu-item-settings" style="display:none;">';
			
			# minisite name
			$out .= '<p class="description description-thin"><label for="edit-minisite-menu-name-' .$id. '">' . __( 'Name', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $minisite_name . '" name="' . $name . '[name]" id="edit-minisite-menu-name-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';			
			
			# minisite slug
			$out .= '<div style="position:relative;">';
			$out .= '<p class="description description-thin"><label for="edit-minisite-menu-slug-' .$id. '">' . __( 'Slug', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $minisite_slug . '" name="' . $name . '[slug]" id="edit-minisite-menu-slug-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';
			$out .= '<div class="it_option_help">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'The slug of each minisite must be unique and cannot match the slugs of any other minisites, or anything else in WordPress for that matter (such as categories, tags, names of pages, taxonomies, etc.) Slugs must only contain lower-case letters and underscores, and cannot contain any other types of characters or spaces, and must be less than 17 characters in length. WARNING: if you change the slug after you create the minisite you will lose all of your settings and content within the minisite! Please use extreme caution if you change the minisite slug, especially on production sites.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div>';
			$out .= '</div>';
			
			# minisite enabled			
			$out .= '<div class="it_option">';
			$out .= '<input type="radio" name="' . $name . '[enabled]" value="1" ' . $checked_enabled .' id="enabled_' . $minisite_slug . '_1">';
			$out .= '<label for="enabled_' . $minisite_slug . '_1">' . __( 'Enabled', IT_TEXTDOMAIN ) . '</label>';
			$out .= '<input type="radio" name="' . $name . '[enabled]" value="0" ' . $checked_disabled .' id="enabled_' . $minisite_slug . '_2">';
			$out .= '<label for="enabled_' . $minisite_slug . '_2">' . __( 'Disabled', IT_TEXTDOMAIN ) . '</label>';
			$out .= '</div>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:95px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Disabling the minisite will hide the minisite from your front-end and admin menus but retain all settings and content posted within the minisite.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div>';
			
			# minisite excluded		
			$out .= '<div class="it_option">';
			$out .= '<input type="radio" name="' . $name . '[excluded]" value="1" ' . $checked_excluded .' id="excluded_' . $minisite_slug . '_1">';
			$out .= '<label for="excluded_' . $minisite_slug . '_1">' . __( 'Excluded', IT_TEXTDOMAIN ) . '</label>';
			$out .= '<input type="radio" name="' . $name . '[excluded]" value="0" ' . $checked_included .' id="excluded_' . $minisite_slug . '_2">';
			$out .= '<label for="excluded_' . $minisite_slug . '_2">' . __( 'Included', IT_TEXTDOMAIN ) . '</label>';
			$out .= '</div>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:160px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Excluding the minisite will hide articles from the minisite from all parts of the site except where it is explicitly set, such as the minisite itself, minisite directory pages, and certain widgets. For instance, if you don not want articles from this minisite to display in your homepage post loop, then you should mark this minisite as excluded.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div>';
			
			
			# menu item actions
			$out .= '<div class="menu-item-actions description-wide submitbox">';
			$out .= '<a href="" id="delete-minisite-menu-item-' . $id . '" class="submitdelete slider_deletion">' . __( 'Remove', IT_TEXTDOMAIN ) . '</a> ';
			$out .= '<span class="meta-sep"> | </span> <a href="minisite-menu-item-settings-' . $id .'" class="slider_cancel submitcancel">' . __( 'Cancel', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</div>';
			
			
			$out .= '</div><!-- #minisite-menu-item-settings-## -->';
			$out .= '</li>';
			
			if( $i == $key_count-1 )
				$out .= '</ul><!-- .menu-to-edit -->';
			
			if( $i == $key_count )
				$out .= '</ul><!-- .sample-to-edit -->';
			
			$i++;
		}
		$out .= '<input type="submit" name="' . IT_SETTINGS . '[confirm_minisites]" value="' . esc_attr__( 'Confirm Minisites' , IT_TEXTDOMAIN ) . '" class="button confirm it_confirm_minisites" />';		
		$out .= '<input type="hidden" name="' . IT_SETTINGS . '[minisite][keys]" value="' . $options['keys'] . '" class="menu-keys" />';
		$out .= '</div><!-- .minisite_option_set -->';
		
		return $out;
	}

	function taxonomies( $value ) {
		error_reporting(E_ALL);
		$options = ( isset( $this->saved_options[$value['id']] ) ) ? $this->saved_options[$value['id']] : array( 'keys' => '#' );
		
		$init = false;
		
		if( $options['keys'] == '#' )
			$init = true;
			
		$minisite = $value['minisite'];
		
		$taxonomies_keys = explode(',', $options['keys'] );
			
		$key_count = count( $taxonomies_keys );
		
		$out = '<div class="it_option_set taxonomies_option_set distinct_option_set">';
		$out .= '<div class="it_option_heading">' . $value['name'] . '</div>';
		$out .= '<div class="add_menu"><span class="button it_add_menu">' . __( 'Add New Taxonomy', IT_TEXTDOMAIN ) . '</span></div>';
		
		if( $init == true )
			$out .= '<ul class="menu-to-edit menu" style="display:none;"><li></li></ul><!-- .menu-to-edit -->';
		
		$i=1;
		foreach( $taxonomies_keys as $key ) {
			if( ( $i == 1 ) && ( $init == false ) )
				$out .= '<ul class="menu-to-edit menu">';

			if ( $i == $key_count )
				$out .= '<ul class="sample-to-edit menu" style="display:none;">';
			
			$id = $key . '_' . $minisite;
			$val = ( ( $id != '#_' . $minisite ) && ( isset( $options[$id] ) ) ) ? $options[$id] : '';
			
			$name = IT_SETTINGS . '[taxonomies_'.$minisite.'][' . $id . ']';
			$taxonomy_name = ( !empty( $val['name'] ) ) ? stripslashes($val['name'])  : '';
			$slug = '';
			if(is_array($val)) $slug = $val['slug'];
			$taxonomy_slug = it_get_slug($slug, $taxonomy_name);			
			$taxonomy_primary = ( !empty( $val['primary'] ) ) ? stripslashes($val['primary'])  : '';
			$checked = ( $taxonomy_primary ) ? ' checked="checked"' : '';
			$custom = '';
			
			$out .= '<li id="taxonomies-menu-item-' . $id . '" class="menu-item menu-item-edit-inactive">';
			
			# menu handle
			$out .= '<dl class="menu-item-bar">';
			$out .= '<dt class="menu-item-handle">';
			$out .= '<span class="item-title">' .sprintf( __( 'Taxonomy %1$s', IT_TEXTDOMAIN ), $i ). '</span>';
			$out .= '<span class="item-controls">';
			$out .= '<a href="taxonomies-menu-item-settings-' . $id .'" title="Edit Menu Item" id="taxonomies-menu-edit-' . $id . '" class="item-edit">' . __( 'Edit Menu Item', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</span>';
			$out .= '</dt>';
			$out .= '</dl>';
			
			# menu settings
			$out .= '<div id="taxonomies-menu-item-settings-' . $id . '" class="menu-item-settings" style="display:none;">';
			
			# taxonomies name
			$out .= '<p class="description description-thin"><label for="edit-taxonomies-menu-name-' .$id. '">' . __( 'Name', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $taxonomy_name . '" name="' . $name . '[name]" id="edit-taxonomies-menu-name-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';
			
			# taxonomies slug
			$out .= '<div style="position:relative;">';
			$out .= '<p class="description description-thin"><label for="edit-taxonomies-menu-slug-' .$id. '">' . __( 'Slug', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $taxonomy_slug . '" name="' . $name . '[slug]" id="edit-taxonomies-menu-slug-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';
			$out .= '<div class="it_option_help">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'The slug of each taxonomy must be unique and cannot match the slugs of any other taxonomies, or anything else in WordPress for that matter (such as categories, tags, names of pages, taxonomies, etc.) Slugs must only contain lower-case letters and underscores, and cannot contain any other types of characters or spaces. WARNING: if you change the slug after you create the taxonomy you will lose all of your settings and content within the taxonomy! Please use extreme caution if you change the taxonomy slug, especially on production sites.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div>';
			$out .= '</div>';
			
			# taxonomies primary			
			$out .= '<div class="it_option">';
			$out .= '<input type="checkbox" name="' . $name . '[primary]" value="1" ' . $checked .' id="primary_' . $taxonomy_slug . '">';
			$out .= '<label for="primary_' . $taxonomy_slug . '">' . __( 'Primary', IT_TEXTDOMAIN ) . '</label>';			
			$out .= '</div>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:95px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Use this taxonomy to populate the minisite submenu.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div>';
			
			
			# menu item actions
			$out .= '<div class="menu-item-actions description-wide submitbox">';
			$out .= '<a href="" id="delete-taxonomies-menu-item-' . $id . '" class="submitdelete slider_deletion">' . __( 'Remove', IT_TEXTDOMAIN ) . '</a> ';
			$out .= '<span class="meta-sep"> | </span> <a href="taxonomies-menu-item-settings-' . $id .'" class="slider_cancel submitcancel">' . __( 'Cancel', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</div>';
			
			
			$out .= '</div><!-- #taxonomies-menu-item-settings-## -->';
			$out .= '</li>';
			
			if( $i == $key_count-1 )
				$out .= '</ul><!-- .menu-to-edit -->';
			
			if( $i == $key_count )
				$out .= '</ul><!-- .sample-to-edit -->';
			
			$i++;
		}
		$out .= '<input type="submit" name="' . IT_SETTINGS . '[confirm_taxonomies]" value="' . esc_attr__( 'Confirm Taxonomies' , IT_TEXTDOMAIN ) . '" class="button confirm it_confirm_taxonomies" />';		
		$out .= '<input type="hidden" name="' . IT_SETTINGS . '[taxonomies_'.$minisite.'][keys]" value="' . $options['keys'] . '" class="menu-keys" />';
		$out .= '</div><!-- .taxonomies_option_set -->';
		
		return $out;
	}
	
	function details( $value ) {
		error_reporting(E_ALL);
		$options = ( isset( $this->saved_options[$value['id']] ) ) ? $this->saved_options[$value['id']] : array( 'keys' => '#' );
		
		$init = false;
		
		if( $options['keys'] == '#' )
			$init = true;
			
		$minisite = $value['minisite'];
		
		$details_keys = explode(',', $options['keys'] );
				
		$key_count = count( $details_keys );
		
		$out = '<div class="it_option_set details_option_set distinct_option_set">';
		$out .= '<div class="it_option_heading">' . $value['name'] . '</div>';
		$out .= '<div class="add_menu"><span class="button it_add_menu">' . __( 'Add New Detail', IT_TEXTDOMAIN ) . '</span></div>';	
		
		if( $init == true )
			$out .= '<ul class="menu-to-edit menu" style="display:none;"><li></li></ul><!-- .menu-to-edit -->';
		
		$i=1;
		foreach( $details_keys as $key ) {
			if( ( $i == 1 ) && ( $init == false ) )
				$out .= '<ul class="menu-to-edit menu">';

			if ( $i == $key_count )
				$out .= '<ul class="sample-to-edit menu" style="display:none;">';
			
			$id = $key . '_' . $minisite;
			$val = ( ( $id != '#_' . $minisite ) && ( isset( $options[$id] ) ) ) ? $options[$id] : '';
			
			$name = IT_SETTINGS . '[details_'.$minisite.'][' . $id . ']';
			$details_name = ( !empty( $val['name'] ) ) ? stripslashes($val['name'])  : '';
			$slug = '';
			if(is_array($val)) 
				if(array_key_exists('slug',$val)) $slug = $val['slug'];
			$details_slug = it_get_slug($slug, $details_name);
			$custom = '';
			
			$out .= '<li id="details-menu-item-' . $id . '" class="menu-item menu-item-edit-inactive">';
			
			# menu handle
			$out .= '<dl class="menu-item-bar">';
			$out .= '<dt class="menu-item-handle">';
			$out .= '<span class="item-title">' .sprintf( __( 'Detail %1$s', IT_TEXTDOMAIN ), $i ). '</span>';
			$out .= '<span class="item-controls">';
			$out .= '<a href="details-menu-item-settings-' . $id .'" title="Edit Menu Item" id="details-menu-edit-' . $id . '" class="item-edit">' . __( 'Edit Menu Item', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</span>';
			$out .= '</dt>';
			$out .= '</dl>';
			
			# menu settings
			$out .= '<div id="details-menu-item-settings-' . $id . '" class="menu-item-settings" style="display:none;">';
			
			# details name
			$out .= '<p class="description description-thin"><label for="edit-details-menu-name-' .$id. '">' . __( 'Name', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $details_name . '" name="' . $name . '[name]" id="edit-details-menu-name-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';
			$out .= '<div class="it_option_help">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'The name of the detail field. This is a way of describing the posts within your minisite.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div>';				
			
			# menu item actions
			$out .= '<div class="menu-item-actions description-wide submitbox">';
			$out .= '<a href="" id="delete-details-menu-item-' . $id . '" class="submitdelete slider_deletion">' . __( 'Remove', IT_TEXTDOMAIN ) . '</a> ';
			$out .= '<span class="meta-sep"> | </span> <a href="details-menu-item-settings-' . $id .'" class="slider_cancel submitcancel">' . __( 'Cancel', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</div>';
			
			
			$out .= '</div><!-- #details-menu-item-settings-## -->';
			$out .= '</li>';
			
			if( $i == $key_count-1 )
				$out .= '</ul><!-- .menu-to-edit -->';
			
			if( $i == $key_count )
				$out .= '</ul><!-- .sample-to-edit -->';
			
			$i++;
		}		
		$out .= '<input type="hidden" name="' . IT_SETTINGS . '[details_'.$minisite.'][keys]" value="' . $options['keys'] . '" class="menu-keys" />';
		$out .= '</div><!-- .details_option_set -->';
		
		return $out;
	}
	
	function criteria( $value ) {
		error_reporting(E_ALL);
		$options = ( isset( $this->saved_options[$value['id']] ) ) ? $this->saved_options[$value['id']] : array( 'keys' => '#' );
		
		$init = false;
		
		if( $options['keys'] == '#' )
			$init = true;
			
		$minisite = $value['minisite'];
		
		$criteria_keys = explode(',', $options['keys'] );
			
		$key_count = count( $criteria_keys );
		
		$out = '<div class="it_option_set criteria_option_set distinct_option_set">';
		$out .= '<div class="it_option_heading">' . $value['name'] . '</div>';
		$out .= '<div class="add_menu"><span class="button it_add_menu">' . __( 'Add New Criteria', IT_TEXTDOMAIN ) . '</span></div>';
		
		if( $init == true )
			$out .= '<ul class="menu-to-edit menu" style="display:none;"><li></li></ul><!-- .menu-to-edit -->';
		
		$i=1;
		foreach( $criteria_keys as $key ) {
			if( ( $i == 1 ) && ( $init == false ) )
				$out .= '<ul class="menu-to-edit menu">';

			if ( $i == $key_count )
				$out .= '<ul class="sample-to-edit menu" style="display:none;">';
			
			$id = $key . '_' . $minisite;
			$val = ( ( $id != '#_' . $minisite ) && ( isset( $options[$id] ) ) ) ? $options[$id] : '';
			
			$name = IT_SETTINGS . '[criteria_'.$minisite.'][' . $id . ']';
			$criteria_name = ( !empty( $val['name'] ) ) ? stripslashes($val['name'])  : '';		
			$criteria_weight = ( !empty( $val['weight'] ) ) ? stripslashes($val['weight'])  : '';
			$custom = '';
			
			$out .= '<li id="criteria-menu-item-' . $id . '" class="menu-item menu-item-edit-inactive">';
			
			# menu handle
			$out .= '<dl class="menu-item-bar">';
			$out .= '<dt class="menu-item-handle">';
			$out .= '<span class="item-title">' .sprintf( __( 'Criteria %1$s', IT_TEXTDOMAIN ), $i ). '</span>';
			$out .= '<span class="item-controls">';
			$out .= '<a href="criteria-menu-item-settings-' . $id .'" title="Edit Menu Item" id="criteria-menu-edit-' . $id . '" class="item-edit">' . __( 'Edit Menu Item', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</span>';
			$out .= '</dt>';
			$out .= '</dl>';
			
			# menu settings
			$out .= '<div id="criteria-menu-item-settings-' . $id . '" class="menu-item-settings" style="display:none;">';
			
			# criteria name
			$out .= '<p class="description description-thin"><label for="edit-criteria-menu-name-' .$id. '">' . __( 'Name', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $criteria_name . '" name="' . $name . '[name]" id="edit-criteria-menu-name-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';
			
			# criteria weight
			$out .= '<div style="position:relative;">';
			$out .= '<p class="description description-thin"><label for="edit-criteria-menu-slug-' .$id. '">' . __( 'Weight', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $criteria_weight . '" name="' . $name . '[weight]" id="edit-criteria-menu-slug-' . $id . '" class="criteria_weight" />';
			$out .= '</label>';			
			$out .= '</p>';
			$out .= '<div class="it_option_help">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'You can assign weight to your rating criteria to make them more important during the averaging of the total score. You can use any scale you want, such as assigning 1 to regular criteria and 2 to more important criteria, or 100 to regular criteria and 80 to less important criteria. The values can be anything you want because they are relative only to each other. You can leave this field blank to keep all the weights the same.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div>';
			$out .= '</div>';
			
			# menu item actions
			$out .= '<div class="menu-item-actions description-wide submitbox">';
			$out .= '<a href="" id="delete-criteria-menu-item-' . $id . '" class="submitdelete slider_deletion">' . __( 'Remove', IT_TEXTDOMAIN ) . '</a> ';
			$out .= '<span class="meta-sep"> | </span> <a href="criteria-menu-item-settings-' . $id .'" class="slider_cancel submitcancel">' . __( 'Cancel', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</div>';
			
			
			$out .= '</div><!-- #criteria-menu-item-settings-## -->';
			$out .= '</li>';
			
			if( $i == $key_count-1 )
				$out .= '</ul><!-- .menu-to-edit -->';
			
			if( $i == $key_count )
				$out .= '</ul><!-- .sample-to-edit -->';
			
			$i++;
		}				
		$out .= '<input type="hidden" name="' . IT_SETTINGS . '[criteria_'.$minisite.'][keys]" value="' . $options['keys'] . '" class="menu-keys" />';
		$out .= '</div><!-- .criteria_option_set -->';
		
		return $out;
	}
	
	function awards( $value ) {
		error_reporting(E_ALL);
		$options = ( isset( $this->saved_options[$value['id']] ) ) ? $this->saved_options[$value['id']] : array( 'keys' => '#' );
		
		$init = false;
		
		if( $options['keys'] == '#' )
			$init = true;
			
		$minisite = $value['minisite'];
		
		$awards_keys = explode(',', $options['keys'] );
			
		$key_count = count( $awards_keys );
		
		$out = '<div class="it_option_set awards_option_set distinct_option_set">';
		$out .= '<div class="it_option_heading">' . $value['name'] . '</div>';
		$out .= '<div class="add_menu"><span class="button it_add_menu">' . __( 'Add New Award', IT_TEXTDOMAIN ) . '</span></div>';
		
		if( $init == true )
			$out .= '<ul class="menu-to-edit menu" style="display:none;"><li></li></ul><!-- .menu-to-edit -->';
		
		$i=1;
		foreach( $awards_keys as $key ) {
			if( ( $i == 1 ) && ( $init == false ) )
				$out .= '<ul class="menu-to-edit menu">';

			if ( $i == $key_count )
				$out .= '<ul class="sample-to-edit menu" style="display:none;">';
			
			$id = $key . '_' . $minisite;
			$val = ( ( $id != '#_' . $minisite ) && ( isset( $options[$id] ) ) ) ? $options[$id] : '';
			
			$name = IT_SETTINGS . '[awards_'.$minisite.'][' . $id . ']';
			$awards_name = ( !empty( $val['name'] ) ) ? stripslashes($val['name'])  : '';
			$slug = '';
			if(!empty($val['slug'])) $slug = $val['slug'];
			$awards_slug = it_get_slug($slug, $awards_name);
			$awards_badge = ( !empty( $val['badge'] ) ) ? stripslashes($val['badge'])  : '';
			$awards_icon = ( !empty( $val['icon'] ) ) ? stripslashes($val['icon'])  : '';
			$awards_iconhd = ( !empty( $val['iconhd'] ) ) ? stripslashes($val['iconhd'])  : '';
			$awards_iconwhite = ( !empty( $val['iconwhite'] ) ) ? stripslashes($val['iconwhite'])  : '';
			$awards_iconhdwhite = ( !empty( $val['iconhdwhite'] ) ) ? stripslashes($val['iconhdwhite'])  : '';
			$badge_checked = ( $awards_badge ) ? ' checked="checked"' : '';
			$custom = '';
			
			$out .= '<li id="awards-menu-item-' . $id . '" class="menu-item menu-item-edit-inactive">';
			
			# menu handle
			$out .= '<dl class="menu-item-bar">';
			$out .= '<dt class="menu-item-handle">';
			$out .= '<span class="item-title">' .sprintf( __( 'Award %1$s', IT_TEXTDOMAIN ), $i ). '</span>';
			$out .= '<span class="item-controls">';
			$out .= '<a href="awards-menu-item-settings-' . $id .'" title="Edit Menu Item" id="awards-menu-edit-' . $id . '" class="item-edit">' . __( 'Edit Menu Item', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</span>';
			$out .= '</dt>';
			$out .= '</dl>';
			
			# menu settings
			$out .= '<div id="awards-menu-item-settings-' . $id . '" class="menu-item-settings" style="display:none;">';
			
			# awards name
			$out .= '<p class="description description-thin"><label for="edit-awards-menu-name-' .$id. '">' . __( 'Name', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $awards_name . '" name="' . $name . '[name]" id="edit-awards-menu-name-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';
			
			# awards slug
			$out .= '<p class="description description-thin"><label for="edit-awards-menu-slug-' .$id. '">' . __( 'Slug', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" value="' . $awards_slug . '" name="' . $name . '[slug]" id="edit-awards-menu-slug-' . $id . '" class="widefat" />';
			$out .= '</label>';			
			$out .= '</p>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:55px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Leave this blank unless you need to specifically manually enter a slug, such as if you want to use non-UTF-8 characters in the name of the award. For detailed information please reference the theme documentation. The slug will be created automatically based on the name of the award if you leave this field blank (recommended). Slugs must only contain lower-case letters and underscores, and cannot contain any other types of characters or spaces. WARNING: if you change the slug after you create the award you will have to re-assign the award to your posts.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div><br style="clear:both;" />';
			
			# awards image
			$out .= '<p class="description description-thin"><label for="edit-awards-menu-icon-' .$id. '">' . __( 'Icon (16px)', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" class="upload-text" name="' . $name . '[icon]" value="' . $awards_icon . '" id="edit-awards-menu-icon-' . $id . '" class="it_upload" />';			
			$out .= '<input type="button" value="' . esc_attr__( 'Choose' , IT_TEXTDOMAIN ) . '" class="upload_button button" id="edit-awards-menu-icon-' . $id . '_button" name="edit-awards-menu-icon-' . $id . '_button" />';
			$out .= '</label>';	
			$out .= '</p>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:55px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Image size should be 16px by 16px square.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div><br style="clear:both;" />';
			
			# awards image HD
			$out .= '<p class="description description-thin"><label for="edit-awards-menu-iconhd-' .$id. '">' . __( 'HD Icon (32px)', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" class="upload-text" name="' . $name . '[iconhd]" value="' . $awards_iconhd . '" id="edit-awards-menu-iconhd-' . $id . '" class="it_upload" />';			
			$out .= '<input type="button" value="' . esc_attr__( 'Choose' , IT_TEXTDOMAIN ) . '" class="upload_button button" id="edit-awards-menu-iconhd-' . $id . '_button" name="edit-awards-menu-iconhd-' . $id . '_button" />';
			$out .= '</label>';	
			$out .= '</p>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:103px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Choose a separate image for use in HD (hiDPI/retina) displays. Image should be 32px by 32px square.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div><br style="clear:both;" />';
			
			# awards image white
			$out .= '<p class="description description-thin"><label for="edit-awards-menu-iconwhite-' .$id. '">' . __( 'Optional White Icon (16px)', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" class="upload-text" name="' . $name . '[iconwhite]" value="' . $awards_iconwhite . '" id="edit-awards-menu-iconwhite-' . $id . '" class="it_upload" />';			
			$out .= '<input type="button" value="' . esc_attr__( 'Choose' , IT_TEXTDOMAIN ) . '" class="upload_button button" id="edit-awards-menu-iconwhite-' . $id . '_button" name="edit-awards-menu-iconwhite-' . $id . '_button" />';
			$out .= '</label>';	
			$out .= '</p>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:151px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Optional white version of the 16px icon for use in places with a dark background such as the featured slider. If you leave this blank the icon above will be used', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div><br style="clear:both;" />';
			
			# awards image HD white
			$out .= '<p class="description description-thin"><label for="edit-awards-menu-iconhdwhite-' .$id. '">' . __( 'Optional White HD Icon (32px)', IT_TEXTDOMAIN ) . '<br />';
			$out .= '<input type="text" class="upload-text" name="' . $name . '[iconhdwhite]" value="' . $awards_iconhdwhite . '" id="edit-awards-menu-iconhdwhite-' . $id . '" class="it_upload" />';			
			$out .= '<input type="button" value="' . esc_attr__( 'Choose' , IT_TEXTDOMAIN ) . '" class="upload_button button" id="edit-awards-menu-iconhdwhite-' . $id . '_button" name="edit-awards-menu-iconhdwhite-' . $id . '_button" />';
			$out .= '</label>';	
			$out .= '</p>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:199px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Optional white version of the 32px icon for use in places with a dark background cush as the featured slider. If you leave this blank the icon above will be used.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div><br style="clear:both;" />';
			
			# awards badge			
			$out .= '<div style="padding:5px 5px 5px 2px;">';
			$out .= '<input type="checkbox" name="' . $name . '[badge]" value="1" ' . $badge_checked .' id="badge_' . $id . '">';
			$out .= '<label for="badge_' . $id . '"> ' . __( 'This is a Badge', IT_TEXTDOMAIN ) . '</label>';			
			$out .= '</div>';
			$out .= '<div class="it_option_help"><div style="position:relative;top:226px;">';
			$out .= '<a href="#"><img src="' . esc_url( THEME_ADMIN_ASSETS_URI ) . '/images/help.png" alt="" /></a>';
			$out .= '<div class="it_help_tooltip">' . __( 'Treat this item as a badge instead of an award. Badges appear as a simple icon in the badges section with the badge name in the tooltip (hover text). By contrast, Awards display in the awards section with the name of the award visible.', IT_TEXTDOMAIN ) . '</div>';
			$out .= '</div></div>';
			
			# menu item actions
			$out .= '<div class="menu-item-actions description-wide submitbox">';
			$out .= '<a href="" id="delete-awards-menu-item-' . $id . '" class="submitdelete slider_deletion">' . __( 'Remove', IT_TEXTDOMAIN ) . '</a> ';
			$out .= '<span class="meta-sep"> | </span> <a href="awards-menu-item-settings-' . $id .'" class="slider_cancel submitcancel">' . __( 'Cancel', IT_TEXTDOMAIN ) . '</a>';
			$out .= '</div>';
			
			
			$out .= '</div><!-- #awards-menu-item-settings-## -->';
			$out .= '</li>';
			
			if( $i == $key_count-1 )
				$out .= '</ul><!-- .menu-to-edit -->';
			
			if( $i == $key_count )
				$out .= '</ul><!-- .sample-to-edit -->';
			
			$i++;
		}				
		$out .= '<input type="hidden" name="' . IT_SETTINGS . '[awards_'.$minisite.'][keys]" value="' . $options['keys'] . '" class="menu-keys" />';
		$out .= '</div><!-- .awards_option_set -->';
		
		return $out;
	}


	/**
	 *
	 */
	function color($value) {
		$out = '<div class="it_option_set color_option_set">';
		
		$out .= $this->option_start($value);
		
		$val = ( isset( $this->saved_options[$value['id']] )
		? stripslashes( $this->saved_options[$value['id']] )
		: ( isset( $value['default'] )
		? $value['default'][0]
		: '' ) );
		
		$out .= '<input type="text" id="' .$value['id']. '" name="' . IT_SETTINGS . '['.$value['id'].']" value="' .$val. '" class="wp-color-picker" data-default-color="#effeff" />';
		$out .= $this->option_end($value);		
		
		$out .= '</div><!-- color_option_set -->';
		
		return $out;
	}
		
	/**
	 *
	 */
	function select_target_options( $type, $nodisable = false ) {
		$options = array();
		switch( $type ) {
			
			case 'seconds':				
				if(!$nodisable) $options[0] = 'Disable Auto-Scrolling';
				for($i=1;$i<=25;$i++) {					
					if($i==1) {
						$options[$i] = $i . " second";
					} else {
						$options[$i] = $i . " seconds";
					}
				}
				break;
			case 'seconds_decimal':
				if(!$nodisable) $options[0] = 'Disable';
				for($i=1;$i<=50;$i+=1) {
					if($i==10) {
						$options[$i] = $i/10 . " second";
					} else {
						$options[$i] = $i/10 . " seconds";
					}
				}				
				break;	
			case 'minisite_num':
				for($i=1;$i<=999;$i++) {
					$options[$i] = $i;				
				}				
				break;	
			case 'icon_size':
				for($i=10;$i<=500;$i++) {
					if($i<=100 || ($i>100 && $i % 10==0))
						$options[$i] = $i . "px";				
				}				
				break;	
			case 'font_size':
				for($i=6;$i<=50;$i++) {
					$options[$i] = $i . "px";				
				}				
				break;	
			case 'percentage':
				for($i=1;$i<=100;$i++) {
					$options[$i] = $i . "%";				
				}				
				break;	
			case 'seconds_twitter':
				for($i=10;$i<=600;$i+=10) {
					$options[$i] = $i . " seconds";				
				}				
				break;			
			case 'trending_number':
				$options[-1] = 'Show All';
				for($i=4;$i<=80;$i+=4) {						
					$options[$i] = $i;					
				}
				break;
			case 'recommended_number':
				for($i=3;$i<=60;$i+=3) {						
					$options[$i] = $i;					
				}
				break;
			case 'recommended_filters_number':
				for($i=1;$i<=20;$i++) {				
					$options[$i] = $i;					
				}
				break;
			case 'sizzlin_number':				
				$options[-1] = 'Show All';
				for($i=1;$i<=20;$i++) {				
					$options[$i] = $i;					
				}
				break;
			case 'twitter_number':
				for($i=1;$i<=10;$i++) {				
					$options[$i] = $i;					
				}
				break;
			case 'ad_number':
				if(!$nodisable) $options[0] = 'Disable';
				for($i=1;$i<=10;$i++) {				
					$options[$i] = $i;					
				}
				break;
			case 'flickr_number':
				for($i=1;$i<=30;$i++) {				
					$options[$i] = $i;					
				}
				break;
			case 'range_number':
				for($i=1;$i<=100;$i++) {				
					$options[$i] = $i;					
				}
				break;
			case 'page':
				$entries = get_pages( 'title_li=&orderby=name' );
				foreach( $entries as $key => $entry ) {
					$options[$entry->ID] = $entry->post_title;
				}
				break;
			case 'cat':
				$entries = get_categories( 'orderby=name&hide_empty=0' );
				foreach( $entries as $key => $entry ) {
					$options[$entry->term_id] = $entry->name;
				}
				break;
			case 'tag':
				$entries = get_tags( 'orderby=name&hide_empty=0' );
				foreach( $entries as $key => $entry ) {
					$options[$entry->term_id] = $entry->name;
				}
				break;
			case 'minisites':
				global $itMinisites;		
				foreach($itMinisites->minisites as $minisite){
					$options[$minisite->id] = $minisite->name;
				}				
				break;			
			case 'custom_sidebars':
				$custom_sidebars = ( get_option( IT_SIDEBARS ) ) ? get_option( IT_SIDEBARS ) : array();
				foreach( $custom_sidebars as $key => $value ) {
					$options[$value] = $value;
				}
				break;
			case 'fonts':
				$options = it_fonts();
				break;
			case 'signoff':
				$options = it_signoffs();
				break;
			case 'icons':
				$options = it_icons();
				break;
			case 'featured_transition':
				$transitions = array( 'random' => __('Random',IT_TEXTDOMAIN), 'boxslide' => __('Box Slide',IT_TEXTDOMAIN), 'boxfade' => __('Box Fade',IT_TEXTDOMAIN), 'slotzoom-horizontal' => __('Slot Zoom Horizontal',IT_TEXTDOMAIN), 'slotslide-horizontal' => __('Slot Slide Horizontal',IT_TEXTDOMAIN), 'slotfade-horizontal' => __('Slot Fade Horizontal',IT_TEXTDOMAIN), 'slotzoom-vertical' => __('Slot Zoom Vertical',IT_TEXTDOMAIN), 'slotslide-vertical' => __('Slot Slide Vertical',IT_TEXTDOMAIN), 'slotfade-vertical' => __('Slot Fade Vertical',IT_TEXTDOMAIN), 'curtain-1' => __('Curtain 1',IT_TEXTDOMAIN), 'curtain-2' => __('Curtain 2',IT_TEXTDOMAIN), 'curtain-3' => __('Curtain 3',IT_TEXTDOMAIN), 'slideleft' => __('Slide Left',IT_TEXTDOMAIN), 'slideright' => __('Slide Right',IT_TEXTDOMAIN), 'slideup' => __('Slide Up',IT_TEXTDOMAIN), 'slidedown' => __('Slide Down',IT_TEXTDOMAIN), 'fade' => __('Fade',IT_TEXTDOMAIN), 'slidehorizontal' => __('Slide Horizontal',IT_TEXTDOMAIN), 'slidevertical' => __('Slide Vertical',IT_TEXTDOMAIN), 'papercut' => __('Papercut',IT_TEXTDOMAIN), 'flyin' => __('Flyin',IT_TEXTDOMAIN), 'turnoff' => __('Turn Off',IT_TEXTDOMAIN), 'cube' => __('Cube',IT_TEXTDOMAIN), '3dcurtain-vertical' => __('3D Curtain Vertical',IT_TEXTDOMAIN), '3dcurtain-horizontal' => __('3dcurtain-horizontal',IT_TEXTDOMAIN) );
				foreach( $transitions as $key => $value ) {
					$options[$key] = $value;
				}
				break;
			case 'featured_caption_effect':
				$effects = array( 'sft' => __('Short From Top',IT_TEXTDOMAIN), 'sfb' => __('Short From Bottom',IT_TEXTDOMAIN), 'sfr' => __('Short From Right',IT_TEXTDOMAIN), 'sfl' => __('Short From Left',IT_TEXTDOMAIN), 'lft' => __('Long From Top',IT_TEXTDOMAIN), 'lfb' => __('Long From Bottom',IT_TEXTDOMAIN), 'lfr' => __('Long From Right',IT_TEXTDOMAIN), 'lfl' => __('Long From Left',IT_TEXTDOMAIN), 'fade' => __('Fade In',IT_TEXTDOMAIN), 'randomrotate' => __('Fade in and rotate from a random position',IT_TEXTDOMAIN) );
				foreach( $effects as $key => $value ) {
					$options[$key] = $value;
				}
				break;
			case 'timeperiod':
				$effects = array( 'This Week' => __('This Week',IT_TEXTDOMAIN), 'This Month' => __('This Month',IT_TEXTDOMAIN), 'This Year' => __('This Year',IT_TEXTDOMAIN), '-7 days' => __('Within Past Week',IT_TEXTDOMAIN), '-30 days' => __('Within Past Month',IT_TEXTDOMAIN), '-60 days' => __('Within Past 2 Months',IT_TEXTDOMAIN), '-90 days' => __('Within Past 3 Months',IT_TEXTDOMAIN), '-180 days' => __('Within Past 6 Months',IT_TEXTDOMAIN), '-365 days' => __('Within Past Year',IT_TEXTDOMAIN), 'all' => __('All Time',IT_TEXTDOMAIN) );
				foreach( $effects as $key => $value ) {
					$options[$key] = $value;
				}
				break;
			case 'author_role':
				$effects = array( 'all' => __('All Roles',IT_TEXTDOMAIN), 'subscriber' => __('Subscribers',IT_TEXTDOMAIN), 'contributor' => __('Contributors',IT_TEXTDOMAIN), 'editor' => __('Author',IT_TEXTDOMAIN) );
				foreach( $effects as $key => $value ) {
					$options[$key] = $value;
				}
				break;
			case 'author_order':
				$effects = array( 'nicename' => __('Nice Name',IT_TEXTDOMAIN), 'email' => __('Email',IT_TEXTDOMAIN), 'url' => __('URL',IT_TEXTDOMAIN), 'registered' => __('Registered',IT_TEXTDOMAIN), 'display_name' => __('Display Name',IT_TEXTDOMAIN), 'post_count' => __('Post Count',IT_TEXTDOMAIN) );
				foreach( $effects as $key => $value ) {
					$options[$key] = $value;
				}
				break;
			
		}
		
		return $options;
	}
	
}

?>
