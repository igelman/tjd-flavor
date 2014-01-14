<?php

	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<?php _e( 'This post is password protected. Enter the password to view comments.', IT_TEXTDOMAIN ); ?>
	<?php
		return;
	}
?> 

<?php 
$type = 'comment'; //all
if(have_comments()) {
	$label = $comment_num . __(' comments', IT_TEXTDOMAIN);
} else {
	$label = __('Be the first to comment!', IT_TEXTDOMAIN);	
}
?>
        
<div id="comments">
    <div class="filterbar">
        <div class="sort-buttons" data-number="'.$comments_per_page.'" data-type="'.$type.'">
            <div class="comments-label"><span class="icon-comments"></span><?php echo $label; ?></div>
           
            <a class="reply-link" href="#reply-form"><?php _e('Leave a reply &raquo;',IT_TEXTDOMAIN); ?></a>
            
            <?php $args = array(
				'prev_text'    => __('&laquo;',IT_TEXTDOMAIN),
				'next_text'    => __('&raquo;',IT_TEXTDOMAIN),
				'mid_size'     => '8',
				'prev_next'	=> false );
			?> 
            <?php paginate_comments_links($args); ?>
           
        </div>
        <br class="clearer" />
    </div>
     
    <div class="loading"><div>&nbsp;</div></div>
    
    <div class="comment-list-wrapper">
         <ul class="comment-list">            
        	<?php wp_list_comments('type='.$type.'&callback=it_comment&max_depth='.get_option('thread_comments_depth')); ?> 
         </ul>
    </div>
</div>

<?php if ( comments_open() ) : ?>

	<div id="reply-form">

		<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
        
            <p><?php _e('You must',IT_TEXTDOMAIN); ?>&nbsp;<a href="<?php echo wp_login_url(); ?>" title="<?php _e('log in',IT_TEXTDOMAIN); ?>"><?php _e('log in',IT_TEXTDOMAIN); ?></a>&nbsp;<?php _e('to post a comment',IT_TEXTDOMAIN); ?> </p>
            
        <?php else : ?>
        
            <?php //dislay comment form		
			global $post;
			$minisite = it_get_minisite($post->ID, true);
			if($minisite && !$minisite->user_rating_disable && $minisite->allow_blank_comments) {
				$comment_placeholder = __('Additional Comments (optional)',IT_TEXTDOMAIN);
			} else {
				$comment_placeholder = __('Comment',IT_TEXTDOMAIN);
			}
			$fields = array();
			$fields['author'] = '<input id="author" class="input-block-level" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="'.__('Name',IT_TEXTDOMAIN).'" />';
			$fields['email'] = '<input id="email" class="input-block-level" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="'.__('E-mail',IT_TEXTDOMAIN).'" />';
			$fields['website'] = '<input id="url" class="input-block-level" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="'.__('Website (optional)',IT_TEXTDOMAIN).'" />';
			$comment_field = '<textarea id="comment" class="input-block-level" name="comment" aria-required="true" rows="8" placeholder="'.$comment_placeholder.'"></textarea>';	
			$title_reply = '<span class="icon-pencil"></span>'.__( 'Leave a Response',IT_TEXTDOMAIN );	
			$title_reply_to = '<span class="icon-pencil"></span>'.__( 'Leave a Reply to %s',IT_TEXTDOMAIN );
            $commentargs = array(
                'comment_notes_before' => '',
                'comment_notes_after'  => '',
				'fields'               => $fields,
                'comment_field' 	   => $comment_field,
                'title_reply'          => $title_reply,
                'title_reply_to'       => $title_reply_to,
				'label_submit'         => __('Post',IT_TEXTDOMAIN),
            );
            ?>
        
            <?php comment_form($commentargs); ?> 
    
        <?php endif; // If registration required and not logged in ?>
    
    </div>

<?php endif; ?>
