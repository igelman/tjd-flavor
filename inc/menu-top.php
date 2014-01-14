<?php if (!it_component_disabled('topmenu', $post->ID)) { ?>

    <div class="container-fluid" id="top-menu-wrapper">
    
        <div class="row-fluid"> 
        
            <div class="floatleft" id="top-menu">           
                
                <div id="top-menu-full">
                
                    <?php //title attribute gets in the way - remove it
                    $menu = wp_nav_menu( array( 'theme_location' => 'top-menu', 'container' => 'div', 'fallback_cb' => false, 'container_class' => 'menu', 'echo' => '0' ) );
                    $menu = preg_replace('/title=\"(.*?)\"/','',$menu);
                    echo $menu;
                    ?>
                    
                </div>
                
                <div id="top-menu-compact">
                
                    <?php //get a separate drop down menu for responsive
                    $menu_name = 'top-menu';
                    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
                        $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
                    
                        $menu_items = wp_get_nav_menu_items($menu->term_id);
                    
                        $menu_list = '<select id="select-menu-' . $menu_name . '" class="mobile_menu"><option>'.__( 'Page Navigation',IT_TEXTDOMAIN).'</option>';
                    
                        foreach ( (array) $menu_items as $key => $menu_item ) {						
                            $title = $menu_item->title;
                            $url = $menu_item->url;
                            $parentid = $menu_item->menu_item_parent;
                            $indent = '';
                            if($parentid!=0) { //see if this item needs to be indented
                                $indent .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';							
                            }
                            $objectid = $menu_item->object_id;
                            $selected = '';
                            $selectedoption = '';
                            if(is_tax() || is_category() || is_tag()) { //see if this is the currently displayed taxonomy/category/tag listing
                                $termid = get_queried_object()->term_id;
                                if($termid == $objectid) {
                                    $selected = "selected";
                                    $selectedoption = 'selected="selected"';
                                }
                            } elseif($objectid == $post->ID) { //see if this is the currently displayed page
                                $selected = "selected";
                                $selectedoption = 'selected="selected"';
                            }
                            
                            $menu_list .= '<option ';
                            if($selectedoption!='') {
                                $menu_list .= $selectedoption;
                            }
                            $menu_list .= ' ';
                            if($selected!='') {
                                $menu_list .= 'class="' . $selected . '"';
                            }
                            $menu_list .= ' value="' . $url . '">' . $indent . $title . '</option>';
                        }
                        $menu_list .= '</select>';
                    } else {
                        $menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';
                    }
                    
                    echo $menu_list;
                    ?>
                    
                </div>
                
            </div>
            
            <div class="floatright" id="top-search">
                
                <div class="wrapper">
                 
                    <form method="get" id="searchformtop" action="<?php echo home_url(); ?>/">                             
                        <input type="text" value="<?php _e( 'search', IT_TEXTDOMAIN ); ?>" onfocus="if (this.value == '<?php _e( 'search', IT_TEXTDOMAIN ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'search', IT_TEXTDOMAIN ); ?>';}" name="s" id="s" />          
                    </form>
                    
                </div>
            
            </div> 
        
        </div>
        
    </div>

<?php } ?>