<?php #setup minisites
global $itMinisites;
#determine which columns to display
$layout = it_footer_layout(it_get_setting('footer_layout'));

?>

	<?php if(it_get_setting('ad_footer')!='') { #the ad above the footer ?>
        
        <div class="row ad" id="ad-footer">
            
            <div class="span12">
            
                <?php echo it_get_setting('ad_footer'); ?>  
                
            </div>                    
              
        </div>
    
    <?php } ?>

</div> <?php # close main container that houses everything but the top menu ?>

<?php if(!it_get_setting('footer_disable')) { ?>

    <div id="footer-wrapper">
    
        <div class="container">
    
            <div id="footer">
            
                <div class="row">
                
                	<?php if(!empty($layout['col1'])) { ?>
                
                        <div class="span<?php echo $layout['col1']; ?>">
                        
                            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Column 1') ) : else : ?>
                            
                                <div class="widget">
                                
                                    <h3><?php _e( 'Footer Column 1', IT_TEXTDOMAIN ); ?></h3>                           
                                    <p><?php _e( 'This is a widget panel. To remove this text, login to your WordPress admin panel and go to Appearance >> Widgets, and drag &amp; drop a widget into this widget panel. If you want to leave this area blank, simply put a blank Text widget into this widget panel which will overwrite this text.', IT_TEXTDOMAIN ); ?></p>
                                    
                                </div>
                            
                            <?php endif; ?>
                        
                        </div>
                        
                    <?php } ?>
                    
                    <?php if(!empty($layout['col2'])) { ?>
                    
                        <div class="span<?php echo $layout['col2']; ?>">
                        
                            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Column 2') ) : else : ?>
                            
                                <div class="widget">
                                
                                    <h3><?php _e( 'Footer Column 2', IT_TEXTDOMAIN ); ?></h3>                           
                                    <p><?php _e( 'This is a widget panel. To remove this text, login to your WordPress admin panel and go to Appearance >> Widgets, and drag &amp; drop a widget into this widget panel. If you want to leave this area blank, simply put a blank Text widget into this widget panel which will overwrite this text.', IT_TEXTDOMAIN ); ?></p>
                                    
                                </div>
                            
                            <?php endif; ?>
                        
                        </div>
                        
                    <?php } ?>
                    
                    <?php if(!empty($layout['col3'])) { ?>
                        
                        <div class="span<?php echo $layout['col3']; ?>">
                        
                            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Column 3') ) : else : ?>
                            
                                <div class="widget">
                                
                                    <h3><?php _e( 'Footer Column 3', IT_TEXTDOMAIN ); ?></h3>                           
                                    <p><?php _e( 'This is a widget panel. To remove this text, login to your WordPress admin panel and go to Appearance >> Widgets, and drag &amp; drop a widget into this widget panel. If you want to leave this area blank, simply put a blank Text widget into this widget panel which will overwrite this text.', IT_TEXTDOMAIN ); ?></p>
                                    
                                </div>
                            
                            <?php endif; ?>
                        
                        </div>
                        
                    <?php } ?>
                    
                    <?php if(!empty($layout['col4'])) { ?>
                        
                        <div class="span<?php echo $layout['col4']; ?>">
                        
                            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Column 4') ) : else : ?>
                            
                                <div class="widget">
                                
                                    <h3><?php _e( 'Footer Column 4', IT_TEXTDOMAIN ); ?></h3>                           
                                    <p><?php _e( 'This is a widget panel. To remove this text, login to your WordPress admin panel and go to Appearance >> Widgets, and drag &amp; drop a widget into this widget panel. If you want to leave this area blank, simply put a blank Text widget into this widget panel which will overwrite this text.', IT_TEXTDOMAIN ); ?></p>
                                    
                                </div>
                            
                            <?php endif; ?>                        
                        
                        </div>
                        
                    <?php } ?>
                    
                    <?php if(!empty($layout['col5'])) { ?>
                        
                        <div class="span<?php echo $layout['col5']; ?>">
                        
                            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Column 5') ) : else : ?>
                            
                                <div class="widget">
                                
                                    <h3><?php _e( 'Footer Column 5', IT_TEXTDOMAIN ); ?></h3>                           
                                    <p><?php _e( 'This is a widget panel. To remove this text, login to your WordPress admin panel and go to Appearance >> Widgets, and drag &amp; drop a widget into this widget panel. If you want to leave this area blank, simply put a blank Text widget into this widget panel which will overwrite this text.', IT_TEXTDOMAIN ); ?></p>
                                    
                                </div>
                            
                            <?php endif; ?>
                        
                        </div>
                        
                    <?php } ?>
                    
                    <?php if(!empty($layout['col6'])) { ?>
                        
                        <div class="span<?php echo $layout['col6']; ?>">
                        
                            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Column 6') ) : else : ?>
                            
                                <div class="widget">
                                
                                    <h3><?php _e( 'Footer Column 6', IT_TEXTDOMAIN ); ?></h3>                           
                                    <p><?php _e( 'This is a widget panel. To remove this text, login to your WordPress admin panel and go to Appearance >> Widgets, and drag &amp; drop a widget into this widget panel. If you want to leave this area blank, simply put a blank Text widget into this widget panel which will overwrite this text.', IT_TEXTDOMAIN ); ?></p>
                                    
                                </div>
                            
                            <?php endif; ?>
                        
                        </div>
                        
                    <?php } ?>
                    
                </div>
                
                <?php if(!it_get_setting('backtotop_disable')) { ?>
        
                    <a class="info back-to-top" title="<?php _e('Back to top',IT_TEXTDOMAIN); ?>" href="#top"><span class="icon-up-bold"></span></a>
                
                <?php } ?>
                
            </div>
            
        </div>
        
    </div>
    
<?php } ?>

<?php if(!it_get_setting('subfooter_disable')) { ?>

    <div id="subfooter-wrapper"<?php if(it_get_setting('footer_disable')) { ?> class="solo"<?php } ?>>
    
        <div class="container">
    
            <div id="subfooter">
    
                <div class="row">
                    
                    <div class="span6 copyright">
                    
                        <?php if(it_get_setting('copyright_text')!='') { ?>
                        
                            <?php echo it_get_setting('copyright_text'); ?>
                            
                        <?php } else { ?>
                        
                            <?php _e( 'Copyright', IT_TEXTDOMAIN ); ?> &copy; <?php echo date("Y").' '.get_bloginfo('name'); ?>,&nbsp;<?php _e( 'All Rights Reserved.', IT_TEXTDOMAIN ); ?>
                        
                        <?php } ?>  
                        
                    </div>
                    
                    <div class="span6 credits">
                    
                        <?php if(it_get_setting('credits_text')!='') { ?>
                        
                            <?php echo it_get_setting('credits_text'); ?>
                            
                        <?php } else { ?>
                        
                            <?php _e( 'Fonts by', IT_TEXTDOMAIN); ?> <a href="http://www.google.com/fonts/"><?php _e( 'Google Fonts', IT_TEXTDOMAIN); ?></a>. <?php _e( 'Icons by', IT_TEXTDOMAIN); ?> <a href="http://fontello.com/"><?php _e( 'Fontello', IT_TEXTDOMAIN); ?></a>. <?php _e( 'Full Credits', IT_TEXTDOMAIN); ?> <a href="<?php echo CREDITS_URL; ?>"><?php _e( 'here &raquo;', IT_TEXTDOMAIN); ?></a>
                        
                        <?php } ?>                         
                    
                    </div>
                
                </div>
                
            </div>
            
        </div>
        
    </div>
    
<?php } ?>

<?php do_action('it_body_end'); ?>
<?php wp_footer(); ?>

</body>

</html>
