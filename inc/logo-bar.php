<?php global $itMinisites;

#theme options
$sizzlin_category=it_get_setting('sizzlin_category');
$sizzlin_tag=it_get_setting('sizzlin_tag');
$logo_url=it_get_setting('logo_url');
$logo_width=it_get_setting('logo_width');
$logo_height=it_get_setting('logo_height');
$logo_always_home=it_get_setting('logo_always_home');
$link_url=home_url();
$dimensions = '';
#setup wp_query args
$sizzlinargs = array('posts_per_page' => it_get_setting("sizzlin_number"));
#setup loop format
$format = array('loop' => 'sizzlin', 'thumbnail' => false, 'rating' => false, 'icon' => false);

if(!empty($sizzlin_category)) {
	#add category to query args
	$sizzlinargs['cat'] = $sizzlin_category;	
} else {	
	#add tag to query args
	$sizzlinargs['tag_id'] = $sizzlin_tag;	
}
#determine if we are on a minisite page
$minisite = it_get_minisite($post->ID);
if($minisite) {		
	#add post type to query args
	if($minisite->sizzlin_targeted) $sizzlinargs['post_type'] = $minisite->id;	
	if($minisite->logo_url) $logo_url = $minisite->logo_url;	
	if($minisite->logo_width) $logo_width = $minisite->logo_width;
	if($minisite->logo_height) $logo_height = $minisite->logo_height;
	$link_url = $logo_always_home ? home_url() : $minisite->more_link;
}
if(!empty($logo_width)) $dimensions .= ' width="'.$logo_width.'"';
if(!empty($logo_height)) $dimensions .= ' height="'.$logo_height.'"';
?>

<div id="logo-bar">

	<div class="row">

        <div id="logo" class="<?php if(it_get_setting('logobar_layout')=="large") { ?>span12<?php } else { ?>span4<?php } ?>">
    
            <?php if(it_get_setting('display_logo') && it_get_setting('logo_url')!='') { ?>
                <a href="<?php echo $link_url; ?>">
                    <img id="site-logo" class="hires" alt="<?php bloginfo('name'); ?>" src="<?php echo $logo_url; ?>"<?php echo $dimensions; ?> />                             
                </a>
                <br class="clearer" />
            <?php } else { ?>     
                <h1><a href="<?php echo $link_url; ?>/"><?php bloginfo('name'); ?></a></h1>
            <?php } ?>
            
            <?php if(!it_get_setting('description_disable') && get_bloginfo('description')!=='') { ?>
            
                <div class="subtitle<?php echo $subtitleclass; ?>"><?php bloginfo('description'); ?></div>
                
            <?php } ?>
            
        </div>
            
        <?php if(it_get_setting('logobar_layout')=="large") { ?></div><div class="row"><?php } ?>
        
        <div id="sizzlin-wrapper" class="<?php if(it_get_setting('logobar_layout')=="large") { ?>span12<?php } else { ?>span8<?php } ?>">
            
            <?php if(it_get_setting('ad_header')!='') { ?>
            
                <div class="row ad" id="ad-header">
                
                	<div class="<?php if(it_get_setting('logobar_layout')=="large") { ?>span12<?php } else { ?>span8<?php } ?>">
                    
                    	<?php echo it_get_setting('ad_header'); ?>  
                        
                    </div>                    
                      
                </div>
            
            <?php } ?> 
              
            
            <div class="row">
            
                <div class="<?php if(it_get_setting('logobar_layout')=="large") { ?>span12<?php } else { ?>span8<?php } ?>">
                
                	<?php if(!it_component_disabled('sizzlin', $post->ID)) { ?> 
                    
                        <div id="sizzlin-header" class="panel<?php if(it_get_setting("social_top_disable")) { ?> full<?php } ?>">
                        
                        	<div class="inner<?php if(it_get_setting('sizzlin_icon_disable')) { ?> hide-icon<?php } ?>">
                        
                        		<?php echo ( it_get_setting("sizzlin_title")!="" ) ? it_get_setting("sizzlin_title") : __("SIZZLIN'", IT_TEXTDOMAIN); ?>
                                
                                <br class="clearer" />
                                
                            </div>
                            
                        </div>                    
                    
                        <div id="sizzlin" class="panel<?php if(it_get_setting("social_top_disable")) { ?> full<?php } ?>">
                        
                        	<div class="inner">
                        
                            	<ul class="slide">
                                    <?php $loop = it_loop($sizzlinargs, $format); echo $loop['content']; ?>
                                </ul>
                                
                                <br class="clearer" />
                                
                            </div>
                            
                        </div>
                        
                    <?php } ?>
                    
                    <?php if(!it_get_setting("social_top_disable")) { ?> 
                        
                        <div id="social-top" class="panel<?php if(it_component_disabled('sizzlin', $post->ID)) { ?> full<?php } ?>">
                        
                        	<div class="inner">
                            
                            	<?php #top social links
                                $social = it_get_setting( 'sociable' );
                            
                                if( $social['keys'] != '#' ) {
                                    $social_keys = explode( ',', $social['keys'] );
                            
                                    foreach ( $social_keys as $key ) {
                                        if( $key != '#' ) {
                            
											$social_link = ( !empty( $social[$key]['link'] ) ) ? $social[$key]['link'] : home_url();
											$social_icon = ( !empty( $social[$key]['icon'] ) ) ? $social[$key]['icon'] : '#';
											$social_custom = ( !empty( $social[$key]['custom'] ) ) ? $social[$key]['custom'] : '#';
											$social_hover = ( !empty( $social[$key]['hover'] ) ) ? $social[$key]['hover'] : ucwords($social_icon);
											
											#$social_link = 'http://'.str_replace('http://','',$social_link);
											
                                            if( !empty( $social[$key]['custom'] ) )
                                                echo '<a href="'.esc_url( $social_link ).'" class="info" title="'.$social_hover.'"><img src="' . esc_url( $social_custom ) . '" alt="link" /></a>';
                            
                                            elseif( empty( $social[$key]['custom'] ) )
												echo '<a href="'.esc_url( $social_link ).'" class="icon-'.$social_icon.' info" title="'.$social_hover.'"></a>';
                                              
                                        }
                                    }
                                }
                                                            
								?>
                                
                                <br class="clearer" />
                                
                            </div>
                            
                        </div> 
                        
                    <?php } ?> 
                
                </div>
            
            </div>
            
        </div> 
    
    </div>
    
</div>

<?php wp_reset_query(); ?>