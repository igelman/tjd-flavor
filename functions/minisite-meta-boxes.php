<?php
# it is still necessary to pull in minisite meta boxes in this manner because of the need for the global
# post object to be set before creating the meta boxes because of the unknown number of rating criteria
# and details for each minisite type

add_action('add_meta_boxes', 'it_add_minisite_box');

function it_add_minisite_box() {		
	#minisite options for each minisite type
	global $itMinisites;
	foreach($itMinisites->minisites as $minisite){			
		add_meta_box( 'it_meta_rating', __( 'Review Information', IT_TEXTDOMAIN ), 'it_minisite_meta', $minisite->id, 'normal', 'high' );		
		add_meta_box( 'it_awards', __( 'Awards', IT_TEXTDOMAIN ), 'it_awards', $minisite->id, 'side', 'default' );	
		add_meta_box( 'it_badges', __( 'Badges', IT_TEXTDOMAIN ), 'it_badges', $minisite->id, 'side', 'default' );	
		add_meta_box( 'it_resets', __( 'Resets', IT_TEXTDOMAIN ), 'it_resets', $minisite->id, 'normal', 'low' );
	}	
	add_meta_box( 'it_resets', __( 'Resets', IT_TEXTDOMAIN ), 'it_resets', 'post', 'normal', 'low' );		
}
function it_minisite_meta() {
	global $itMinisites, $post;		
	$minisite = $itMinisites->get_type_by_id(get_post_type($post->ID));
	#nonce to verify intention later
	wp_nonce_field( 'it_meta_box_nonce', 'meta_box_nonce' );
	#get values for filling in the inputs if we have them.
	$positives = get_post_meta( $post->ID, IT_META_POSITIVES, true ); 
	$negatives = get_post_meta( $post->ID, IT_META_NEGATIVES, true ); 
	$bottomline = get_post_meta( $post->ID, IT_META_BOTTOM_LINE, true ); 
	?>     
    
    <?php $disable_review = get_post_meta( $post->ID, IT_META_DISABLE_REVIEW, true ); ?>
    <?php if(!metadata_exists('post', $post->ID, IT_META_DISABLE_REVIEW)) $disable_review='false'; ?>
    <p><b><?php _e( 'Type of Post',IT_TEXTDOMAIN); ?></b></p>
    <input type="radio" name="TypeOfPost" id="ReviewEnable" value="false" <?php checked( $disable_review, 'false' ); ?> />
    <label for="ReviewEnable"><?php _e( 'Review',IT_TEXTDOMAIN); ?></label>
    <input type="radio" name="TypeOfPost" id="ReviewDisable" value="true" <?php checked( $disable_review, 'true' ); ?> />
    <label for="ReviewDisable"><?php _e( 'Article',IT_TEXTDOMAIN); ?></label>
    <p class="description"><?php _e( 'Choose "Article" to completely disable review functionality from this specific post.',IT_TEXTDOMAIN); ?></p>
    
    <div style="clear:both;">&nbsp;</div>
    
    <div style="float:left;width:50%;">
    	<div style="padding-right:7px;">
    	<label for="positives"><?php _e( 'Positives',IT_TEXTDOMAIN); ?></label> 
		<textarea class="widefat" id="Positives" name="Positives" rows="10"><?php echo $positives; ?></textarea>  
        </div>      
	</div>
    <div style="float:left;width:50%;">
    	<div style="padding-left:7px;">
    	<label for="negatives"><?php _e( 'Negatives',IT_TEXTDOMAIN); ?></label> 
		<textarea class="widefat" id="Negatives" name="Negatives" rows="10"><?php echo $negatives; ?></textarea> 
        </div>     
	</div>  
     
    <div style="clear:both;">&nbsp;</div>
   
   	<div style="float:left;width:50%;">
        <div style="border:1px solid #CCC;background:#E6E6E6;padding:10px;margin-right:7px;">    
            <table cellpadding="3" cellspacing="0" border="0" width="100%">
                <?php 
                // get review-specific rating type
                $rating_metric = $minisite->rating_metric;	
                $criteria = $minisite->criteria;	
                switch ($rating_metric) {
                    case 'stars':
                        $desc = __( 'Select number of stars', IT_TEXTDOMAIN );                
                        break;
                    case 'percentage': 
                        $desc = __( 'Select percentages', IT_TEXTDOMAIN ); 
                        break;
                    case 'number': 
                        $desc = __( 'Select number ratings', IT_TEXTDOMAIN ); 
                        break;
                    case 'letter': 
                        $desc = __( 'Select letter grades', IT_TEXTDOMAIN ); 
                        break;			
                } ?>
                
                <tr>
                    <td colspan="2">
                        <b><?php _e( 'Rating', IT_TEXTDOMAIN); ?></b>                           
                        <p class="description"><?php echo $desc; ?></p>
                    </td>
                </tr>
                
                <?php foreach($criteria as $criterion) {	
                    $name = $criterion[0]->name;                     
                    $safe_name = $criterion[0]->safe_name;  
                    $meta_name = $criterion[0]->meta_name;  
                    $meta = get_post_meta($post->ID, $meta_name, $single = true);
                    $letters = explode(',', IT_LETTER_ARRAY);
                    if(!empty($name)) {
                        ?>                
                        <tr>
                        <td><label for="<?php echo $safe_name; ?>"><?php echo $name; ?></label></td>
                        <td>
                            <select name="<?php echo $safe_name; ?>" id="<?php echo $safe_name; ?>"> 
                                <?php 
                                switch ($rating_metric) {
                                    case 'stars':
                                        for($i=5;$i>=0;$i-=.5)
                                            echo '<option value="'.$i.'" "'.selected( $meta, $i ).'>'.$i.'</option>';                                        
                                        break;
                                    case 'percentage': 
                                        for($i=100;$i>=0;$i--)
                                            echo '<option value="'.$i.'" "'.selected( $meta, $i ).'>'.$i.'</option>';    
                                        break;
                                    case 'number': 
                                        for($i=100;$i>=0;$i--)
                                            echo '<option value="'. $i/10 .'" "'.selected( $meta, $i/10 ).'>'. $i/10 .'</option>';  
                                        break;
                                    case 'letter': 
                                        foreach($letters as $letter)
                                            echo '<option value="'.$letter.'" "'.selected( $meta, $letter ).'>'.$letter.'</option>';  
                                        break;			
                                } ?>                                                       
                            </select> 
                        </td>
                        </tr>
                    <?php }			 
                } ?> 
                
                <tr>
                    <td colspan="2">
                        <br />
                        <b><?php _e( 'Manual Override', IT_TEXTDOMAIN); ?></b>                           
                        <p class="description"><?php _e( 'Replaces the auto-generated total', IT_TEXTDOMAIN); ?></p>
                    </td>
                </tr> 
                
                <tr>
                    <td><label for="TotalScore"><?php _e( 'Total Score (Optional)', IT_TEXTDOMAIN); ?></label></td>
                    <td>
                        <select name="TotalScore" id="TotalScore"> 
                            <?php 
                            $meta = get_post_meta($post->ID, IT_META_TOTAL_SCORE_OVERRIDE, $single = true);
                            echo '<option value="auto" "'.selected( $meta, 'auto' ).'>auto</option>';
                            switch ($rating_metric) {
                                case 'stars':
                                    for($i=5;$i>=0;$i-=.5)
                                        echo '<option value="'.$i.'" "'.selected( $meta, $i ).'>'.$i.'</option>';                                        
                                    break;
                                case 'percentage': 
                                    for($i=100;$i>=0;$i--)
                                        echo '<option value="'.$i.'" "'.selected( $meta, $i ).'>'.$i.'</option>';    
                                    break;
                                case 'number': 
                                    for($i=100;$i>=0;$i--)
                                        echo '<option value="'. $i/10 .'" "'.selected( $meta, $i/10 ).'>'. $i/10 .'</option>';  
                                    break;
                                case 'letter': 
                                    foreach($letters as $letter)
                                        echo '<option value="'.$letter.'" "'.selected( $meta, $letter ).'>'.$letter.'</option>';  
                                    break;			
                            } ?>                                                       
                        </select> 
                    </td>
                </tr>                          
                    
            </table>
        </div>
    </div>
    <div style="float:left;width:50%;">
    	<div style="margin-left:7px;">
            <label for="bottomline"><?php _e( 'Bottom Line',IT_TEXTDOMAIN); ?></label> 
            <textarea class="widefat" id="bottomline" name="bottomline" rows="10"><?php echo $bottomline; ?></textarea> 
        </div>
    </div>
    
    <div style="clear:both;">&nbsp;</div>
    
    <?php #custom meta fields	
	$details = $minisite->details;
	foreach($details as $detail){
		$name = $detail[0]->name;	
		if(!empty($name)) {	
			$safe_name = $detail[0]->safe_name;  
            $meta_name = $detail[0]->meta_name; 
			$meta = get_post_meta($post->ID, $meta_name, $single = true);	?>		
			<p>
			<label for="<?php echo $safe_name; ?>"><?php echo $name; ?></label>
			<textarea class="widefat" id="<?php echo $safe_name; ?>" name="<?php echo $safe_name; ?>"><?php echo $meta; ?></textarea>
			</p>
        
	<?php }
	} ?>    
    
<?php
}

function it_resets() { ?>
	<?php wp_nonce_field( 'it_meta_box_nonce', 'meta_box_nonce' ); ?>
	<div style="border:1px solid;border-color:#C99090;background:#F7EBEB;padding:10px;margin-right:7px;">  
    	<span style="color:#C00;font-weight:bold;"><?php _e( 'ADVANCED',IT_TEXTDOMAIN); ?></span> &nbsp;&nbsp;<?php _e( 'Caution: these settings are irreversible! Check the box to reset the value, and then update the post for the changes to take effect.',IT_TEXTDOMAIN); ?>
        <p>
        <input type="checkbox" name="ResetLikes" id="ResetLikes" value="true" />
    	<label for="ResetLikes"><?php _e( 'Reset Likes',IT_TEXTDOMAIN); ?></label>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="ResetViews" id="ResetViews" value="true" />
    	<label for="ResetViews"><?php _e( 'Reset Views',IT_TEXTDOMAIN); ?></label>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="ResetUserRating" id="ResetUserRating" value="true" />
    	<label for="ResetUserRating"><?php _e( 'Reset User Ratings (Minisites Only)',IT_TEXTDOMAIN); ?></label>       
        </p>
    </div> 	
<?php
}

function it_awards() {
	#get review-specific awards
	$out='';
	global $itMinisites, $post; 	
	$minisite_id = get_post_type( $post->ID );	
	$minisite = $itMinisites->get_type_by_id($minisite_id);
	$awards = $minisite->awards;
	$meta = get_post_meta($post->ID, IT_META_AWARDS, $single = true);	
	$current_awards = array();
	if(!empty($meta)) $current_awards = explode('; ',$meta);
	foreach($awards as $award){		
		$name = stripslashes($award[0]->name);
		$icon = $award[0]->icon;
		$isBadge = $award[0]->isBadge;
		if(!empty($name) && empty($isBadge)) {
			$id = preg_replace('/[^a-z0-9]/i', '', strtolower($name));
			$checked = '';
			if(in_array($name,$current_awards)) $checked = ' checked="checked"';				
			$out .= '<p style="margin:3px 0px;">';			
			$out .= '<input type="checkbox" name="' . $name . '" value="1" ' . $checked .' id="' . $id . '">';
			$out .= '<label for="' . $id . '"><span style="width:16px;height:16px;display:inline-block;padding:0px 10px 0px 14px;position:relative;top:4px;"><img src="' . $icon . '" /></span>' . $name;
			$out .= '</label>';	
			$out .= '</p>';			       
		}
	} 
	$awards = array_filter($awards);
	if(empty($awards)) {
		$out .= '<p><em>No awards exist for this minisite</em></p>';	
	}
	echo $out; 
}

function it_badges() {
	#get review-specific awards
	$out='';
	global $itMinisites, $post; 
	$minisite_id = get_post_type( $post->ID );	
	$minisite = $itMinisites->get_type_by_id($minisite_id);
	$badges = $minisite->awards;
	$meta = get_post_meta($post->ID, IT_META_BADGES, $single = true);	
	$current_badges = array();
	if(!empty($meta)) $current_badges = explode('; ',$meta);
	foreach($badges as $badge){
		$name = stripslashes($badge[0]->name);
		$icon = $badge[0]->icon;	
		$isBadge = $badge[0]->isBadge;
		if(!empty($name) && !empty($isBadge)) {	
			$id = preg_replace('/[^a-z0-9]/i', '', strtolower($name));
			$checked = '';
			if(in_array($name,$current_badges)) $checked = ' checked="checked"';	
			$out .= '<p style="margin:3px 0px;">';			
			$out .= '<input type="checkbox" name="' . $name . '" value="1" ' . $checked .' id="' . $id . '">';
			$out .= '<label for="' . $id . '"><span style="width:16px;height:16px;display:inline-block;padding:0px 10px 0px 14px;position:relative;top:4px;"><img src="' . $icon . '" /></span> ' . $name;
			$out .= '</label>';	
			$out .= '</p>';	
		}
	} 
	$badges = array_filter($badges);
	if(empty($badges)) {
		$out .= '<p><em>No badges exist for this minisite</em></p>';	
	}
	echo $out; 
}


//save all the meta boxes
add_action( 'save_post', 'it_meta_save' );
function it_meta_save( $id )
{
	global $post;
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'it_meta_box_nonce' ) ) return;

	if( !current_user_can( 'edit_post' ) ) return;

	$allowed = array(
		'p'	=> array()
	);
	
	#update standard fields
	update_post_meta( $id, IT_META_DISABLE_REVIEW, $_POST['TypeOfPost'] );
	update_post_meta( $id, IT_META_POSITIVES, $_POST['Positives'] );
	update_post_meta( $id, IT_META_NEGATIVES, $_POST['Negatives'] );
	update_post_meta( $id, IT_META_BOTTOM_LINE, $_POST['bottomline'] );	
		
	# get review-specific details
	global $itMinisites; 
	$minisite_id = get_post_type( $post->ID );	
	$minisite = $itMinisites->get_type_by_id($minisite_id);
	$details = $minisite->details;
	if(!empty($details)) { #loop thru and save details fields
		foreach($details as $detail) {
			$name = $detail[0]->name;			
			$safe_name = $detail[0]->safe_name;  
            $meta_name = $detail[0]->meta_name; 
			update_post_meta( $id, $meta_name, $_POST[$safe_name] );
		}
	}	
	
	# get review-specific awards	
	$awards = $minisite->awards;
	if(!empty($awards)) { #loop thru and save awards
		$awards_list = '';
		$badges_list = '';
		foreach($awards as $award) {
			$name = stripslashes($award[0]->name);		
			$safe_name = str_replace(" ","_",$name);
			#die(var_export($_POST));
			if($award[0]->isBadge) {
				if($_POST[$safe_name]==1) $badges_list .= $name . '; ';
			} else {
				if($_POST[$safe_name]==1) $awards_list .= $name . '; ';
			}
			
		}
		update_post_meta( $id, IT_META_AWARDS, $awards_list );
		update_post_meta( $id, IT_META_BADGES, $badges_list );
	}
	
	# letter/number equivalents
	$letters=array('A+'=>14,'A'=>13,'A-'=>12,'B+'=>11,'B'=>10,'B-'=>9,'C+'=>8,'C'=>7,'C-'=>6,'D+'=>5,'D'=>4,'D-'=>3,'F+'=>2,'F'=>1);
	$numbers=array(14=>'A+',13=>'A',12=>'A-',11=>'B+',10=>'B',9=>'B-',8=>'C+',7=>'C',6=>'C-',5=>'D+',4=>'D',3=>'D-',2=>'F+',1=>'F');
	
	# get review-specific rating metric
	$rating_metric = $minisite->rating_metric;	
	$criteria = $minisite->criteria;	
	$total=0;
	$ratings=0;
	if(!empty($criteria)) {	 #loop thru and save criteria fields
		foreach($criteria as $criterion) {				
			$name = $criterion[0]->name;
			$weight = $criterion[0]->weight;			
			$safe_name = $criterion[0]->safe_name;  
            $meta_name = $criterion[0]->meta_name; 
			if(!empty($meta_name)) {
				# save individual rating
				update_post_meta( $id, $meta_name, $_POST[$safe_name] );
				# set rating value into variable
				$rating=$_POST[$safe_name];
				# different averaging method for letter grades
				if ($rating_metric=="letter") $rating=$letters[$rating];
				# set default weight if non-existent or invalid
				if(!is_numeric($weight)) $weight=1;	
				# increase total score	
				$ratings+=$rating*$weight;
				# increase total weight
				$weights+=$weight;				
			}
		} 
		if($weights == 0) $weights = 1;		
		# get averaged total
		$rating=$ratings/$weights;
		# different rounding based on metric
		switch ($rating_metric) {
			case 'stars':
				$rating = round($rating * 2) / 2; # need to get an even .5 rating for stars
				break;
			case 'percentage':
				$rating = round($rating); # round to the closest whole number
				break;
			case 'number':
				$rating = round($rating, 1); # round to the closest decimal point (tenth place)
				break;
			case 'letter':
				$rating = round($rating); # round to the closest whole number										
				break;
		}
		#need to use override value for normalized value if present
		$rating_override = $_POST['TotalScore'];		
		if($rating_override!='auto') {
			if($rating_metric=='letter') $rating_override=$letters[$rating_override];
			$rating_normalized = it_normalize_value($rating_override, $minisite);
		} else {
			$rating_normalized = it_normalize_value($rating, $minisite);
		}
		if($rating_metric=='letter') $rating=$numbers[$rating]; # turn the rating number back into a letter	
	} 
	# update override score
	update_post_meta( $id, IT_META_TOTAL_SCORE_OVERRIDE, $_POST['TotalScore'] );
	# update auto score
	update_post_meta( $id, IT_META_TOTAL_SCORE, $rating );
	# update normalized score (for use in cross-type sorting)
	update_post_meta( $id, IT_META_TOTAL_SCORE_NORMALIZED, $rating_normalized );
	
	#perform any necessary resets
	if($_POST['ResetLikes']=='true') {
		delete_post_meta( $id, IT_META_TOTAL_LIKES);
		delete_post_meta( $id, IT_META_LIKE_IP_LIST);
	}
	if($_POST['ResetViews']=='true') {
		delete_post_meta( $id, IT_META_TOTAL_VIEWS);
		delete_post_meta( $id, IT_META_VIEW_IP_LIST);
	}
	if($_POST['ResetUserRating']=='true') {
		delete_post_meta( $id, IT_META_TOTAL_USER_SCORE);
		delete_post_meta( $id, IT_META_TOTAL_USER_SCORE_NORMALIZED);
		#loop through and delete rating criteria specific meta fields
		if(!empty($criteria)) {	
			foreach($criteria as $criterion) {		 
				$meta_name = $criterion[0]->meta_name.'_user'; 
				if(!empty($meta_name)) {	
					delete_post_meta( $id, $meta_name);	
					delete_post_meta( $id, $meta_name.'_ips');	
					delete_post_meta( $id, $meta_name.'_ratings');					
				}
			} 
		}
	}	
}

?>