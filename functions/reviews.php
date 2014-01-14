<?php 
/*
this file contains functions related to theme presentation
specific to minisites and reviews/ratings
*/

#html display of awards and badges
function it_get_awards($postid, $minisite, $location, $badge = false) {
	$current_awards = array();
	#get awards for this minisite
	$awards = $minisite->awards;
	$meta_name = IT_META_AWARDS;
	if($badge) $meta_name = IT_META_BADGES;
	#get awards for this post
	$meta = get_post_meta($postid, $meta_name, $single = true);		
	if(!empty($meta)) $current_awards = explode('; ',$meta);
	$i=0;
	$out='';
	$style='';
	#loop through all awards in minisite
	foreach($awards as $award){	
		if(is_array($award)) {
			if(array_key_exists(0, $award)) {
				$i++;			
				$name = stripslashes($award[0]->name);
				#this minisite award is assigned to this post
				if(!empty($name) && in_array($name,$current_awards)) {
					$id = preg_replace('/[^a-z0-9]/i', '', strtolower($name));
					#award or badge?
					if($badge) { #badge
					
						$out .= $style;
						$out .= '<span class="badge-wrapper info" title="' . $name . '"><span class="badge-icon award-icon-' . $id . '"></span></span>';
													   
					} else { #award			
						
						$out .= $style;
						$out .= '<span class="award-wrapper"><span class="award-icon award-icon-' . $id . '"></span>' . $name . '</span>';	
						
						#exit function if we only need the first item
						if($location=='featured') return $out;	
						
					}
				}
			}
		}
	}
	return $out;	
}

#html display of pros and cons
function it_get_pros_cons($postid) {
	$minisite = it_get_minisite($postid);
	$disable_review = get_post_meta( $postid, IT_META_DISABLE_REVIEW, $single = true );
	if(empty($minisite) || $disable_review=='true') return false;
	$positives = wpautop(get_post_meta($postid, IT_META_POSITIVES, $single = true));	
	$negatives = wpautop(get_post_meta($postid, IT_META_NEGATIVES, $single = true));	
	$positives_label = ( !empty($minisite->positives_label) ) ? $minisite->positives_label : __('Positives',IT_TEXTDOMAIN);
	$negatives_label = ( !empty($minisite->negatives_label) ) ? $minisite->negatives_label : __('Negatives',IT_TEXTDOMAIN);	
	$out='';
	if(!empty($positives) || !empty($negatives)) {
		$out.='<div class="procon-wrapper">';
			if(!empty($positives)) {
				$out.='<div class="col-wrapper';
				if(empty($negatives)) $out.=' solo';
				$out.='">';
					$out.='<div class="procon pro">';
						$out.='<div class="header">';
							$out.='<span class="icon-plus"></span>';
							$out.=$positives_label;
						$out.='</div>';
						$out.=$positives;
					$out.='</div>';
				$out.='</div>';
			}
			if(!empty($negatives)) {
				$out.='<div class="col-wrapper';
				if(empty($positives)) $out.=' solo';
				$out.='">';
					$out.='<div class="procon con">';
						$out.='<div class="header">';
							$out.='<span class="icon-minus"></span>';
							$out.=$negatives_label;
						$out.='</div>';
						$out.=$negatives;
					$out.='</div>';
				$out.='</div>';
			}
		$out.='</div>';
	}
	$out.='<br class="clearer" />';
	return $out;
}

#html display of bottom line
function it_get_bottom_line($postid) {
	$minisite = it_get_minisite($postid);
	$disable_review = get_post_meta( $postid, IT_META_DISABLE_REVIEW, $single = true );
	if(empty($minisite) || $disable_review=='true') return false;
	$bottomline = wpautop(get_post_meta($postid, IT_META_BOTTOM_LINE, $single = true));		
	$bottomline_label = ( !empty($minisite->bottomline_label) ) ? $minisite->bottomline_label : __('Bottom Line',IT_TEXTDOMAIN);
	$out='';
	if(!empty($bottomline)) {
		$out.='<div class="bottomline-wrapper">';
			$out.='<div class="bottomline">';
				$out.='<div class="header">';
					$out.='<span class="icon-quote-circled"></span>';
					$out.=$bottomline_label;
				$out.='</div>';
				
				$out.=$bottomline;
				
			$out.='</div>';
		$out.='</div>';
	}
	return $out;
}

#html display of ratings
function it_show_rating($postid, $minisite, $single = false, $editor_rating_hide = false, $user_rating_hide = false) {
	$solo_user = '';
	$solo_editor = '';
	$disable_review = get_post_meta( $postid, IT_META_DISABLE_REVIEW, $single = true );
	if($disable_review=='true') return false;
	if($single)	$singlecss=" single"; #css class added if this is a single review page
	#ratings
	$editor_rating = get_post_meta($postid, IT_META_TOTAL_SCORE, $single = true);
	$editor_rating_override = get_post_meta($postid, IT_META_TOTAL_SCORE_OVERRIDE, $single = true);
	if(!empty($editor_rating_override) && $editor_rating_override!='auto') $editor_rating = $editor_rating_override;
	$user_rating = get_post_meta($postid, IT_META_TOTAL_USER_SCORE, $single = true);
	#show/hide ratings
	$editor_rating_hide_override = get_post_meta($postid, IT_META_HIDE_EDITOR_RATING, $single = true);
	$user_rating_hide_override = get_post_meta($postid, IT_META_HIDE_USER_RATING, $single = true);
	if($minisite->editor_rating_disable || $minisite->editor_rating_hide || $editor_rating_hide_override) {
		$editor_rating_hide=true;
		$solo_user=' solo';
	}
	if($minisite->user_rating_disable || $minisite->user_rating_hide || $user_rating_hide_override) {
		$user_rating_hide=true;
		$solo_editor=' solo';
	}
	#get html for rating
	$out = '<div class="rating small '.$minisite->rating_metric.'_wrapper">';
	
	if(!$editor_rating_hide) $out .= '<div class="editor_rating' . $solo_editor .'">' . it_get_rating($editor_rating, $minisite, 'editor') . '</div>';
	if(!$user_rating_hide) $out .= '<div class="user_rating' . $solo_user .'">' . it_get_rating($user_rating, $minisite, 'user') . '</div>';
	
	$out .= '</div>';
	#display the rating
	return $out;
}

#get normalized value for a rating
function it_normalize_value($value, $minisite) {
	$value = (float) $value;
	switch ($minisite->rating_metric) {
		case 'stars':
			$out = $value * 20;
			break;
		case 'percentage':					
			$out = $value;
			break;
		case 'number':				
			$out = $value * 10;
			break;
		case 'letter':			
			$out = round($value * 7.14285);	
			break;
	}	
	return $out;
}

#get individual rating
function it_get_rating($rating, $minisite, $type) {
	$out = '';
	switch ($minisite->rating_metric) {
		case 'stars':
			if($type=='editor')	$out.=it_get_star_rating($rating, $minisite->editor_star_color, $type);
			if($type=='user') $out.=it_get_star_rating($rating, $minisite->user_star_color, $type);
			break;
		case 'percentage':					
			$out.=it_get_percentage_rating($rating, $minisite->color_ranges_disable, $type);
			break;
		case 'number':				
			$out.=it_get_number_rating($rating, $minisite->color_ranges_disable, $type);
			break;
		case 'letter':			
			$out.=it_get_letter_rating($rating, $minisite->color_ranges_disable, $type);
			break;
	}
	return $out;
}

#get background color for rating based on color ranges
function it_get_rating_color($rating, $metric, $disable) {
	switch($metric) {
		case "percentage":
			if($rating<20) {
				$color = 'color1';
			} elseif($rating<40) {
				$color = 'color2';
			} elseif($rating<60) {
				$color = 'color3';
			} elseif($rating<80) {
				$color = 'color4';
			} else {
				$color = 'color5';
			}
		break;			
		case "number":
			if($rating<2) {
				$color = 'color1';
			} elseif($rating<4) {
				$color = 'color2';
			} elseif($rating<6) {
				$color = 'color3';
			} elseif($rating<8) {
				$color = 'color4';
			} else {
				$color = 'color5';
			}
		break;
		case "letter":
			if($rating=='F+' || $rating=='F' || $rating=='F-') {
				$color = 'color1';
			} elseif($rating=='D+' || $rating=='D' || $rating=='D-') {
				$color = 'color2';
			} elseif($rating=='C+' || $rating=='C' || $rating=='C-') {
				$color = 'color3';
			} elseif($rating=='B+' || $rating=='B' || $rating=='B-') {
				$color = 'color4';
			} else {
				$color = 'color5';
			}
		break;
		
	}
	if($disable) $color = 'nocolor';
	return $color;	
}

#html for displaying stars
function it_get_star_rating($rating, $color, $type = NULL) {
	if(empty($color)) $color="yellow";
	#check for acceptable rating value
	if(!is_numeric($rating)) $rating = 0; #if rating is not a number set it to 5
	$valid=false; #default flag
	foreach (range(0, 5, .5) as $num) {
		if($rating == $num) $valid=true; #flag valid value
	}
	if(!$valid) $rating = 0; #valid flag was never set
	$output = '<div class="stars '.$color.'">';
	$output .= '<div class="star';
	if($rating>=1) {
		$output .= ' full';
	} elseif($rating>0) {
		$output .= ' half';
	}
	$output .= '">&nbsp;</div>';
	$output .= '<div class="star';
	if($rating>=2) {
		$output .= ' full';
	} elseif($rating>1) {
		$output .= ' half';
	}
	$output .= '">&nbsp;</div>';
	$output .= '<div class="star';
	if($rating>=3) {
		$output .= ' full';
	} elseif($rating>2) {
		$output .= ' half';
	}
	$output .= '">&nbsp;</div>';
	$output .= '<div class="star';
	if($rating>=4) {
		$output .= ' full';
	} elseif($rating>3) {
		$output .= ' half';
	}
	$output .= '">&nbsp;</div>';
	$output .= '<div class="star';
	if($rating>=5) {
		$output .= ' full';
	} elseif($rating>4) {
		$output .= ' half';
	}
	$output .= '">&nbsp;</div>';
	$output .= '</div><br class="clearer" />';
	return $output;
}

#html for displaying percentages
function it_get_percentage_rating($rating, $color_ranges_disable, $type = NULL) {	
	$na = '';
	#check for acceptable rating value
	if(!is_numeric($rating)) {
		$rating = 100; #if rating is not a number set it to 100
		if($type=='user') $na = 'noratings '; #don't display default value for user ratings
	}
	$valid=false; #default flag
	foreach (range(0, 100, 1) as $num) {
		if($rating == $num) $valid=true; #flag valid value
	}
	if(!$valid) $rating = 0; #valid flag was never set			
	$color = it_get_rating_color($rating, 'percentage', $color_ranges_disable);
	$rating .= '<span class="percentage">&#37;</span>';	
	if($na=='noratings ') $rating = '&mdash;';
	#begin html output
	$output = '<div class="number '.$color.'">';
	$output .= $rating;	
	$output .= '</div>';
	return $output;
}

#html for displaying numbers
function it_get_number_rating($rating, $color_ranges_disable, $type = NULL) {	
	#check for acceptable rating value
	$na = '';
	$single = '';
	if(!is_numeric($rating)) {
		$rating = 10; #if rating is not a number set it to 10
		if($type=='user') $na = 'noratings '; #don't display default value for user ratings
	}
	$color = it_get_rating_color($rating, 'number', $color_ranges_disable);
	if((!strpos($rating,".") && $rating!=10 && $rating>=.9) || $rating==0) $rating .= ".0"; //add .0 if rating is an even number
	if($na=='noratings ') $rating = '&mdash;';
	//begin html output
	$output = '<div class="number '.$na.$color.$single.'">';
	$output .= $rating;
	$output .= '</div>';
	return $output;
}

#html for displaying letter grades
function it_get_letter_rating($rating, $color_ranges_disable, $type = NULL) {
	$na = '';
	#get letter rating in correct format
	$rating = trim(str_replace(" ","",strtoupper($rating)));
	#create array of acceptable values
	$letters = explode(',', IT_LETTER_ARRAY);
	#check for acceptable rating value
	if(!in_array($rating, $letters)) {
		$rating = "A+"; #if rating is not a number set it to A+
		if($type=='user') $na = 'noratings '; #don't display default value for user ratings
	}
	$color = it_get_rating_color($rating, 'letter', $color_ranges_disable);
	if($na=='noratings ') $rating = '&mdash;';
	//begin html output
	$output = '<div class="letter '.$na.$color.'">';
	$output .= $rating;
	$output .= '</div>';
	return $output;
}

#html display of the rating criteria for use on a single review page
function it_get_criteria($postid){
	$minisite = it_get_minisite($postid);
	$criteria = $minisite->criteria;
	$metric = $minisite->rating_metric;
	$user_star_color = $minisite->user_star_color;
	$color_ranges_disable = $minisite->color_ranges_disable;
	$editor_rating_disable = $minisite->editor_rating_disable;
	$user_rating_disable = $minisite->user_rating_disable;	
	$user_ratings_top_disable = $minisite->user_ratings_top_disable;	
	$animation_disable = $minisite->rating_animations_disable;
	$ratingcss = '';
	$criteriacss = '';
	if($metric=='stars') {
		$ratingcss = ' stars-wrapper';
		$criteriacss = ' rating small';
	}
	
	$letters=array('A+'=>14,'A'=>13,'A-'=>12,'B+'=>11,'B'=>10,'B-'=>9,'C+'=>8,'C'=>7,'C-'=>6,'D+'=>5,'D'=>4,'D-'=>3,'F+'=>2,'F'=>1);
	$numbers=array(14=>'A+',13=>'A',12=>'A-',11=>'B+',10=>'B',9=>'B-',8=>'C+',7=>'C',6=>'C-',5=>'D+',4=>'D',3=>'D-',2=>'F+',1=>'F');
	
	$total = count($criteria);
	$out='';
	$sleep=1000;
	$offset=500;
	
	$disable_review = get_post_meta( $postid, IT_META_DISABLE_REVIEW, $single = true );
	if(empty($minisite) || ($editor_rating_disable && $user_rating_disable) || $disable_review=='true') return false;
	
	if(!($editor_rating_disable && $user_rating_disable)) 
	{
		$out.='<div class="ratings">';
		
			#EDITOR RATINGS PANEL
			if(!$editor_rating_disable) {
				$count=0;
				$delay=0;
				$out.='<div class="ratings-panel-wrapper';
				if($user_rating_disable) $out.=' solo';
				$out.='">';
					$out.='<div class="ratings-panel editor-rating">';
						$out.='<div class="header">';
							$out.='<span class="icon-reviewed"></span>';
							$out.=__('Editor Rating',IT_TEXTDOMAIN);
						$out.='</div>';
						
						#individual criteria
						if($total > 1) {
							foreach($criteria as $criterion) {
								if(is_array($criterion)) {
									if(array_key_exists(0, $criterion)) {
										$delay = $count * $offset + $sleep;
										$id = 'editor_rating_'.$count;
										$name = $criterion[0]->name;
										$meta_name = $criterion[0]->meta_name;
										$value = get_post_meta($postid, $meta_name, $single = true);								
										#get number value of letter grade
										if ($metric=='letter') $value=$letters[$value];
										$value_normalized = it_normalize_value($value, $minisite);
										#turn number back into letter for display
										if ($metric=='letter') $value=$numbers[$value];
										$left = -(100 - $value_normalized);
										if(!empty($name)) {
											$out.='<div class="rating-wrapper" id="'.$id.'">';
												$out.='<div class="rating-bar'.$ratingcss.$criteriacss.'">';
													if(!$animation_disable) $out.='<div class="rating-meter">&nbsp;</div>';
													$out.='<div class="rating-label">'.$name.'</div>';
													$out.='<div class="rating-value">'.it_get_rating($value, $minisite, 'editor').'</div>';
													$out.='<br class="clearer" />';
												$out.='</div>';
											$out.='</div>';
											$out.='<script type="text/javascript">';
												$out.='jQuery(window).load(function() {';
													$out.='animateRating('.$left.','.$delay.',"'.$id.'")';	
												$out.='});';					
											$out.='</script>';
										}
										$count++;
									}
								}
							}
						}
						#total score
						$label = ( !empty($minisite->total_score_label) ) ? $minisite->total_score_label : __('Total Score',IT_TEXTDOMAIN);
						$value = get_post_meta($postid, IT_META_TOTAL_SCORE, $single = true);
						$value_normalized = get_post_meta( $postid, IT_META_TOTAL_SCORE_NORMALIZED, $single = true );
						$editor_rating_override = get_post_meta($postid, IT_META_TOTAL_SCORE_OVERRIDE, $single = true);
						if(!empty($editor_rating_override) && $editor_rating_override!='auto') $value = $editor_rating_override;
						$color = it_get_rating_color($value, $metric, $color_ranges_disable);
						$out.='<div class="rating-wrapper total" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
							$out.='<div class="rating-bar '.$color.$ratingcss.'">';
								$out.='<div class="rating-meter">&nbsp;</div>';
								$out.='<div class="rating-label">'.$label.'</div>';
								$out.='<meta itemprop="worstRating" content="0">';
								if($metric=='stars') $out.='<meta itemprop="ratingValue" content="'.$value.'" />';
								if($metric=='letter') $out.='<meta itemprop="ratingValue" content="'.$value_normalized.'" />';
								$out.='<div class="rating-value"';
								#different display for letters since they use normalized values
								#i.e. display one value but show google rich snippets another
								if($metric!='letter' && $metric!='stars') $out.=' itemprop="ratingValue"';
								$out.='>'.it_get_rating($value, $minisite, 'editor').'</div>';
								$out.='<meta itemprop="bestRating" content="'.it_get_best_rating($postid).'">';	
								$out.='<br class="clearer" />';
							$out.='</div>';
						$out.='</div>';
						
					$out.='</div>';
				$out.='</div>';
			}
			
			#USER RATINGS PANEL
			if(!$user_rating_disable) {
				$count=0;
				$totalnum=0;
				$delay=0;
				$flagcss = '';
				#get the user's ip address
				$ip=it_get_ip();
				$out.='<div class="ratings-panel-wrapper right';
				if($editor_rating_disable) $out.=' solo';
				$out.='">';
					$out.='<div class="ratings-panel user-rating">';
						$out.='<div class="header">';
							if(!$user_ratings_top_disable) $out.='<div class="hovertorate"><span class="icon-down-bold"></span><span class="hover-text">'.__('Hover To Rate',IT_TEXTDOMAIN).'</span></div>';
							$out.='<span class="icon-users"></span>';
							$out.=__('User Rating',IT_TEXTDOMAIN);							
						$out.='</div>';
						
						#individual criteria
						if($total > 1) {
							foreach($criteria as $criterion) {
								if(is_array($criterion)) {
									if(array_key_exists(0, $criterion)) {
										$delay = $count * $offset + $sleep;
										$id = 'user_rating_'.$count;
										$name = $criterion[0]->name;
										$meta_name = $criterion[0]->meta_name.'_user';
										$value = get_post_meta($postid, $meta_name, $single = true);
										$ips = get_post_meta($postid, $meta_name.'_ips', $single = true);
										$ips = substr_replace($ips,"",-1);
										#don't create the array if the string is empty because it will create an array of one element and throw off the count
										if(!empty($ips)) {
											$numarr = explode(';',$ips);																	
											$num = count($numarr);	
											$totalnum += $num;
										}
										#check and see if user has already rated this criteria
										$iconcss = '';
										$wrappercss = ' single';
										$pos = strpos($ips,$ip);
										$readonly = 'false';
										if($pos !== false && !it_get_setting('rating_limit_disable')) {
											$flagcss = ' active';
											$iconcss = ' active';
											$wrappercss = '';
											$readonly = 'true';
										}
										#top user ratings are disabled but don't display "you have rated this" indicators
										if($user_ratings_top_disable) {
											$wrappercss = '';
											$readonly = 'true';
										}
										#don't display hover effect for stars rating type
										if($metric=='stars') {
											$wrappercss = '';	
										}
										#get number value of letter grade
										if ($metric=='letter') $value=$letters[$value];
										$value_normalized = it_normalize_value($value, $minisite);
										#turn number back into letter for display
										if ($metric=='letter') $value=$numbers[$value];
										$left = -(100 - $value_normalized);
										#get type of rating to display
										if (it_get_setting('rating_limit_disable')) {
											$unlimitedratings = 1;
										}
										if ($metric=='stars') {
											$rating_value = '<div class="rateit '.$user_star_color.'" data-rateit-starwidth="14" data-rateit-starheight="14" data-rateit-resetable="false" data-rateit-value="'.$value.'" data-rateit-ispreset="true" data-postid="'.$postid.'" data-rateit-readonly="'.$readonly.'" data-meta="'.$meta_name.'" data-unlimitedratings="'.$unlimitedratings.'"></div>';
										} else {
											$rating_value = it_get_rating($value, $minisite, 'user');
										}
										if(!empty($name)) {
											$out.='<div id="'.$id.'_wrapper" class="rating-wrapper'.$wrappercss.'" onclick = "void(0)">';
												$out.='<div id="'.$id.'" class="rating-bar'.$ratingcss.$criteriacss.'" data-meta="'.$meta_name.'">';
													if(!$animation_disable) $out.='<div class="rating-meter">&nbsp;</div>';
													$out.='<div class="rating-label">'.$name.'</div>';
													$out.='<div class="icon-check'.$iconcss.'"></div>';
													$out.='<div class="rating-value">'.$rating_value.'</div>';											
													$out.='<br class="clearer" />';
													$out.='<div class="form-selector"></div>';
												$out.='</div>';
											$out.='</div>';
											$out.='<script type="text/javascript">';
												$out.='jQuery(window).load(function() {';
													$out.='animateRating('.$left.','.$delay.',"'.$id.'")';	
												$out.='});';					
											$out.='</script>';
											
											$count++;
										}								
									}
								}
							} 						
						}
						#avoid divide by 0
						if($count==0) $count = 1;
						#determine number of user ratings						
						$numratings = round($totalnum / $count, 0);						
						$numlabel = __(' ratings',IT_TEXTDOMAIN);
						if($numratings==1) $numlabel = __(' rating',IT_TEXTDOMAIN);
						#total score
						$label = ( !empty($minisite->total_user_score_label) ) ? $minisite->total_user_score_label : __('User Score',IT_TEXTDOMAIN);
						$value = get_post_meta($postid, IT_META_TOTAL_USER_SCORE, $single = true);
						$out.='<div class="rating-wrapper total" data-numratings="'.$numratings.'">';
							$out.='<div class="rating-bar '.$ratingcss.'">';
								$out.='<div class="rating-meter">&nbsp;</div>';
								$out.='<div class="rating-label">'.$label.'</div>';
								if(!$minisite->user_ratings_number_disable && $numratings > 0) $out.='<div class="rating-number"><span class="value">' . $numratings . '</span>' . $numlabel . '</div>';
								$out.='<div class="rating-value">'.it_get_rating($value, $minisite, 'user').'</div>';
								$out.='<br class="clearer" />';
							$out.='</div>';
						$out.='</div>';
						$out.='<div class="rated-legend'.$flagcss.'"><span class="icon-check"></span>'.__('You have rated this',IT_TEXTDOMAIN).'</div>';
						
					$out.='</div>';
				$out.='</div>';
			}
			
		$out.='</div>';
		$out.='<br class="clearer" />';
		
	}

	return $out;
}

#html display of the rating criteria for use on in the comment area
function it_get_comment_criteria($postid){
	$minisite = it_get_minisite($postid);
	$criteria = $minisite->criteria;
	$metric = $minisite->rating_metric;
	$user_star_color = $minisite->user_star_color;
	$color_ranges_disable = $minisite->color_ranges_disable;
	if($metric=='stars') {
		$ratingcss = ' stars-wrapper';
		$criteriacss = ' rating small';
	}
	
	$letters=array('A+'=>14,'A'=>13,'A-'=>12,'B+'=>11,'B'=>10,'B-'=>9,'C+'=>8,'C'=>7,'C-'=>6,'D+'=>5,'D'=>4,'D-'=>3,'F+'=>2,'F'=>1);
	$numbers=array(14=>'A+',13=>'A',12=>'A-',11=>'B+',10=>'B',9=>'B-',8=>'C+',7=>'C',6=>'C-',5=>'D+',4=>'D',3=>'D-',2=>'F+',1=>'F');
	
	$out='';	
		
	$count=0;
	$totalnum=0;
	$flag=false;
	#get the user's ip address
	$ip=it_get_ip();
	
	foreach($criteria as $criterion) {
		if(is_array($criterion)) {
			if(array_key_exists(0, $criterion)) {
				$id = 'user_comment_rating_'.$count;
				$name = $criterion[0]->name;
				$meta_name = $criterion[0]->meta_name.'_user';
				$value = get_post_meta($postid, $meta_name, $single = true);
				$ips = get_post_meta($postid, $meta_name.'_ips', $single = true);
				$ips = substr_replace($ips,"",-1);
				#don't create the array if the string is empty because it will create an array of one element and throw off the count
				if(!empty($ips)) {
					$numarr = explode(';',$ips);																	
					$num = count($numarr);
				}
				#check and see if user has already rated this criteria
				$iconcss = '';
				$wrappercss = '';
				$pos = strpos($ips,$ip);
				$readonly = 'false';
				if($pos !== false && !it_get_setting('rating_limit_disable')) {
					$flagcss = ' active';
					$iconcss = ' active';
					$wrappercss = ' rated';
					$readonly = 'true';
					$flag = true;
				}
				#don't display hover effect for stars rating type
				if($metric=='stars') {
					$wrappercss = '';	
				}
				#get number value of letter grade
				if ($metric=='letter') $value=$letters[$value];
				$value_normalized = it_normalize_value($value, $minisite);
				#turn number back into letter for display
				if ($metric=='letter') $value=$numbers[$value];
				#get type of rating to display
				if (it_get_setting('rating_limit_disable')) {
					$unlimitedratings = 1;
				}
				if ($metric=='stars') {
					$rating_value = '<div class="rateit '.$user_star_color.'" data-rateit-starwidth="14" data-rateit-starheight="14" data-rateit-resetable="false" data-rateit-value="'.$value.'" data-rateit-ispreset="true" data-postid="'.$postid.'" data-rateit-readonly="'.$readonly.'" data-meta="'.$meta_name.'" data-unlimitedratings="'.$unlimitedratings.'" data-noupdate="1"></div>';
				} else {
					if($readonly == 'true') {
						$rating_value = it_get_rating($value, $minisite, 'user');
					} else {
						$rating_value = '&mdash;';
					}
				}
				if(!empty($name)) {
					$out.='<div id="'.$id.'_wrapper" class="rating-wrapper'.$wrappercss.'">';
						$out.='<div class="rating-label">'.$name.'</div>';
						if($metric!='stars') $out.='<div class="icon-check'.$iconcss.'"></div>';
						$out.='<div class="rating-value">'.$rating_value.'</div>';
						$out.='<input type="hidden" class="hidden-rating-value" name="'.$meta_name.'" />';											
						$out.='<br class="clearer" />';
						if($metric!='stars') $out.='<div class="form-selector"></div>';
					$out.='</div>';						
					$count++;
				}
			}
		}
	}		
	$out.='<input type="hidden" id="comment-metric" value="'. $metric .'" />';
	
	#if pros/cons are enabled and user's ip address doesn't appear in the post meta, display the comment fields
	if(!$minisite->user_comment_procon_disable) {
		$positives_ips = get_post_meta($postid, IT_META_USER_PROS_IP_LIST, $single = true);
		$negatives_ips = get_post_meta($postid, IT_META_USER_CONS_IP_LIST, $single = true);
		$positives_pos = strpos($positives_ips,$ip);
		$negatives_pos = strpos($negatives_ips,$ip);
		$positives_label = ( !empty($minisite->positives_label) ) ? $minisite->positives_label : __('Positives',IT_TEXTDOMAIN);
		$negatives_label = ( !empty($minisite->negatives_label) ) ? $minisite->negatives_label : __('Negatives',IT_TEXTDOMAIN);	
		if($positives_pos === false || it_get_setting('rating_limit_disable')) {
			$out .= '<textarea name="comment-pros" class="input-block-level comment-pros" aria-required="true" rows="4" placeholder="'.$positives_label.'"></textarea>';
		} else {
			$out.='<div class="rating-wrapper">';
				$out.='<div class="rating-label">'.$positives_label.'</div>';
				$out.='<div class="icon-check active"></div>';
				$out.='<br class="clearer" />';
			$out.='</div>';	
			$flag = true;
		}
		if($negatives_pos === false || it_get_setting('rating_limit_disable')) {
			$out .= '<textarea name="comment-cons" class="input-block-level comment-cons" aria-required="true" rows="4" placeholder="'.$negatives_label.'"></textarea>';
		} else {
			$out.='<div class="rating-wrapper">';
				$out.='<div class="rating-label">'.$negatives_label.'</div>';
				$out.='<div class="icon-check active"></div>';
				$out.='<br class="clearer" />';
			$out.='</div>';	
			$flag = true;	
		}
	}	
	if($flag) $out.='<div class="rated-legend active"><span class="icon-check"></span>'.__('saved',IT_TEXTDOMAIN).'</div>';
	
	return $out;
}

#html display of user's ratings within comment list
function it_get_comment_rating($postid, $commentid) {
	$out = '';
	$minisite = it_get_minisite($postid, true);
	$disable_review = get_post_meta( $postid, IT_META_DISABLE_REVIEW, $single = true );
	if($disable_review=='true') return false;
	$metric = $minisite->rating_metric;
	$user_star_color = $minisite->user_star_color;
	$numbers=array(14=>'A+',13=>'A',12=>'A-',11=>'B+',10=>'B',9=>'B-',8=>'C+',7=>'C',6=>'C-',5=>'D+',4=>'D',3=>'D-',2=>'F+',1=>'F');
	if($minisite && !$minisite->user_rating_disable) {
		#get pros/cons
		if(!$minisite->user_comment_procon_disable) {
			$positives_label = ( !empty($minisite->positives_label) ) ? $minisite->positives_label : __('Positives',IT_TEXTDOMAIN);
			$negatives_label = ( !empty($minisite->negatives_label) ) ? $minisite->negatives_label : __('Negatives',IT_TEXTDOMAIN);	
			#$pros = apply_filters('the_content',get_comment_meta($commentid, 'user_pros', true));
			#$cons = apply_filters('the_content',get_comment_meta($commentid, 'user_cons', true));
			$pros = get_comment_meta($commentid, 'user_pros', true);
			$cons = get_comment_meta($commentid, 'user_cons', true);
		}
		#get ratings
		if(!$minisite->user_comment_rating_disable) {
			$criteria = $minisite->criteria;
			$ratings = array();
			foreach($criteria as $criterion) {
				$meta_name = $criterion[0]->meta_name.'_user';
				$name = $criterion[0]->name;
				$rating = get_comment_meta($commentid, $meta_name, true);
				#get letter value for letter ratings
				if($metric=='letter') $rating = $numbers[$rating];
				if(!empty($rating)) $ratings[$name] = $rating;
			}
		}
		if(!empty($pros) || !empty($cons) || !empty($ratings)) {
			$out .= '<div class="comment-rating">';
				if(!empty($pros)) {	
					$out.='<div class="comment-procon-wrapper">';				
						$out.='<div class="header">';
							$out.='<span class="icon-plus"></span>';
							$out.=$positives_label;
						$out.='</div>';
						$out.=$pros;
					$out.='</div>';					
				}
				if(!empty($cons)) {
					$out.='<div class="comment-procon-wrapper">';				
						$out.='<div class="header">';
							$out.='<span class="icon-minus"></span>';
							$out.=$negatives_label;
						$out.='</div>';
						$out.=$cons;
					$out.='</div>';		
				}
				if(!empty($ratings)) {
					$out.='<div class="comment-procon-wrapper">';				
						$out.='<div class="header">';
							$out.='<span class="icon-reviewed"></span>';
							$out.=__('Rating',IT_TEXTDOMAIN);
						$out.='</div>';
						foreach($ratings as $key => $value) {
							if($metric=='stars') {
								$value = '<div class="rateit '.$user_star_color.'" data-rateit-starwidth="14" data-rateit-starheight="14" data-rateit-resetable="false" data-rateit-value="'.$value.'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
							}
							$out.='<div class="rating-wrapper">';
								$out.='<span class="rating">'.$key.'</span>';
								$out.='<span class="value">'.$value.'</span>';
							$out.='</div>';
						}
					$out.='</div>';						
				}
			$out .= '</div><br class="clearer" />';
		}
	}
	return $out;
}

function it_save_comment_meta($comment_id) {
	global $post;
	#setup variables
	$pros=wp_filter_nohtml_kses($_POST['comment-pros']);
	$cons=wp_filter_nohtml_kses($_POST['comment-cons']);	
	$minisite = it_get_minisite($post->ID, true);
	$criteria = $minisite->criteria;
	$metric = $minisite->rating_metric;	
	$flag = false;
	$letters=array('A+'=>14,'A'=>13,'A-'=>12,'B+'=>11,'B'=>10,'B-'=>9,'C+'=>8,'C'=>7,'C-'=>6,'D+'=>5,'D'=>4,'D-'=>3,'F+'=>2,'F'=>1);
	#get the user's ip address
	$ip=it_get_ip();
	
	if($minisite && !$minisite->user_rating_disable) {
		#save pros/cons
		if(!$minisite->user_comment_procon_disable) {
			if(!empty($pros)) {
				$flag = true;
				add_comment_meta( $comment_id, 'user_pros', $pros );	
				#also update post meta for use in display of pro/con comment meta fields
				$ips = get_post_meta($post->ID, IT_META_USER_PROS_IP_LIST, $single = true);
				if(!metadata_exists('post', $post->ID, IT_META_USER_PROS_IP_LIST)) $addprometa=true;	
				#add ip to string
				$ips.=$ip.';';
				if($addprometa) {
					add_post_meta($post->ID, IT_META_USER_PROS_IP_LIST, $ips);
				} else {
					update_post_meta($post->ID, IT_META_USER_PROS_IP_LIST, $ips);
				}		
			}
			if(!empty($cons)) {
				$flag = true;
				add_comment_meta( $comment_id, 'user_cons', $cons );	
				#also update post meta for use in display of pro/con comment meta fields
				$ips = get_post_meta($post->ID, IT_META_USER_CONS_IP_LIST, $single = true);
				if(!metadata_exists('post', $post->ID, IT_META_USER_CONSS_IP_LIST)) $addconmeta=true;	
				#add ip to string
				$ips.=$ip.';';
				if($addconmeta) {
					add_post_meta($post->ID, IT_META_USER_CONS_IP_LIST, $ips);
				} else {
					update_post_meta($post->ID, IT_META_USER_CONS_IP_LIST, $ips);
				}				
			}
		}
		#save ratings
		if(!$minisite->user_comment_rating_disable) {
			foreach($criteria as $criterion) {
				$meta_name = $criterion[0]->meta_name.'_user';
				$rating = wp_filter_nohtml_kses($_POST[$meta_name]);
				#get number value for letter ratings
				if($metric=='letter') $rating = $letters[$rating];
				
				if(!empty($rating)) {
					
					#trip flag
					$flag = true;
				
					#setup the args
					$ratingargs = array('postid' => $post->ID, 'meta' => $meta_name, 'metric' => $metric, 'rating' => $rating);
					
					#perform the actual meta updates
					$ratings = it_save_user_ratings($ratingargs);
					
					#add to comment meta
					add_comment_meta( $comment_id, $meta_name, $rating );
					
				}
				
			}
		}
		#if there is not at least one meta value, delete the comment
		if(!$flag) {
			$val = rand(0, 384534);
			if(strpos($_POST['comment'],'it_hide_this_comment')>0) wp_delete_comment( $comment_id, true );
		}
	}
}

#loops through rating criteria and updates custom fields
function it_save_user_ratings($args) {
	
	#get the user's ip address
	$ip=it_get_ip();
	
	#get passed array into variables
	extract($args);
	
	#get minisite
	$minisite = it_get_minisite($postid, true);		
	
	#ip list meta field
	$ips = get_post_meta($postid, $meta.'_ips', $single = true);
	if(!metadata_exists('post', $postid, $meta.'_ips')) $addipmeta=true;

	#add ip to string
	$ips.=$ip.';';
	
	#figure out whether to add or update the ip address meta field
	if($addipmeta) {
		add_post_meta($postid, $meta.'_ips', $ips);
	} else {
		update_post_meta($postid, $meta.'_ips', $ips);
	}
	
	#rating list meta field
	$ratings = get_post_meta($postid, $meta.'_ratings', $single = true);
	if(!metadata_exists('post', $postid, $meta.'_ratings')) $addratingsmeta=true;

	#add rating to string
	$ratings.=$rating.';';
	
	#figure out whether to add or update the rating list meta field
	if($addratingsmeta) {
		add_post_meta($postid, $meta.'_ratings', $ratings);
	} else {
		update_post_meta($postid, $meta.'_ratings', $ratings);
	}
	
	$letters=array('A+'=>14,'A'=>13,'A-'=>12,'B+'=>11,'B'=>10,'B-'=>9,'C+'=>8,'C'=>7,'C-'=>6,'D+'=>5,'D'=>4,'D-'=>3,'F+'=>2,'F'=>1);
	$numbers=array(14=>'A+',13=>'A',12=>'A-',11=>'B+',10=>'B',9=>'B-',8=>'C+',7=>'C',6=>'C-',5=>'D+',4=>'D',3=>'D-',2=>'F+',1=>'F');
	
	#get new user rating
	$ratings = substr_replace($ratings,"",-1);
	$arr = explode(";",$ratings);
	$sum = array_sum($arr);
	$count = count($arr);
	$precision = 1;
	if($metric=='percentage') $precision = 0;
	if(empty($count)) $count = 1;
	$new_rating = round($sum / $count, $precision);
	
	if($metric=='letter') $new_rating = $numbers[$new_rating];		
	
	#user rating meta field
	$rating_meta = get_post_meta($postid, $meta, $single = true);
	if(!metadata_exists('post', $postid, $meta)) $addratingmeta=true;		
			
	#figure out whether to add or update the user rating meta field
	if($addratingmeta) {
		add_post_meta($postid, $meta, $new_rating);
	} else {
		update_post_meta($postid, $meta, $new_rating);
	}
	#get individual normalized value to pass back to ajax
	$normalized = it_normalize_value($new_rating, $minisite);
	
	#get updated total user score
	$criteria = $minisite->criteria;	
	$sum_ratings=0;
	if(!empty($criteria)) {	#loop thru criteria
		foreach($criteria as $criterion) { 
			$meta_name = $criterion[0]->meta_name.'_user'; 
			$w = $criterion[0]->weight;				
			#get criteria rating
			$r = get_post_meta($postid, $meta_name, $single = true);
			if(!empty($r) || $r=='0') {
				# different averaging method for letter grades
				if ($metric=="letter") $r=$letters[$r];
				# set default weight if non-existent or invalid
				if(!is_numeric($w)) $w=1;	
				# increase total score	
				$sum_ratings += (float) $r * $w;
				# increase total weight
				$sum_weights += $w;
			}
		} 		
		# get averaged total
		$total_rating = $sum_ratings / $sum_weights;
		# different rounding based on metric
		switch ($metric) {
			case 'stars':
				$total_rating = round($total_rating * 2) / 2; # need to get an even .5 rating for stars
				break;
			case 'percentage':
				$total_rating = round($total_rating, $precision); # round to the closest whole number
				break;
			case 'number':
				$total_rating = round($total_rating, $precision); # round to the closest decimal point (tenth place)
				break;
			case 'letter':
				$total_rating = round($total_rating); # round to the closest whole number		
				$total_rating = $numbers[$total_rating]; # turn the rating number back into a letter							
				break;
		}
		$rating_normalized = it_normalize_value($total_rating, $minisite);
	} 
		
	#see if total user rating meta fields exist
	if(!metadata_exists('post', $postid, IT_META_TOTAL_USER_SCORE)) $addtotalmeta=true;
	if(!metadata_exists('post', $postid, IT_META_TOTAL_USER_SCORE_NORMALIZED)) $addnormalizedmeta=true;	
	
	#update auto score
	if($addtotalmeta) {
		add_post_meta( $postid, IT_META_TOTAL_USER_SCORE, $total_rating );
	} else {
		update_post_meta( $postid, IT_META_TOTAL_USER_SCORE, $total_rating );
	}		
	
	#update normalized score (for use in cross-type sorting)
	if($addnormalizedmeta) {
		add_post_meta( $postid, IT_META_TOTAL_USER_SCORE_NORMALIZED, $rating_normalized );
	} else {
		update_post_meta( $postid, IT_META_TOTAL_USER_SCORE_NORMALIZED, $rating_normalized );
	}
	
	#get updated total user score
	if($metric=='stars') $total_rating = it_get_rating($total_rating, $minisite, 'user');
	
	#add percentages if needed
	if($metric=='percentage') {
		$new_rating .= '<span class="percentage">&#37;</span>';
		$total_rating .= '<span class="percentage">&#37;</span>';			
	}
	
	#should ratings become disabled
	$unlimitedratings = 0;
	if(it_get_setting('rating_limit_disable')) $unlimitedratings = 1;
	
	return array('new_rating' => $new_rating, 'total_rating' => $total_rating, 'normalized' => $normalized, 'unlimitedratings' => $unlimitedratings);
}

#html display of the meta fields and taxonomies
function it_get_details($postid){
	$out = '';
	$minisite = it_get_minisite($postid);
	$disable_review = get_post_meta( $postid, IT_META_DISABLE_REVIEW, $single = true );
	if(empty($minisite) || $disable_review=='true') return false;
	$details = $minisite->details;
	$taxonomies = $minisite->taxonomies;
	$badges_hide = $minisite->badges_hide;
	$details_hide = $minisite->details_hide;
	$taxonomies_hide = $minisite->taxonomies_hide;
	if(empty($details)) $details_hide = true;
	if(empty($taxonomies)) $taxonomies_hide = true;
	$title = ( !empty($minisite->details_label) ) ? $minisite->details_label : __('Details about this review',IT_TEXTDOMAIN);
	$badges = it_get_awards($postid, $minisite, 'main-loop', true);
	if(empty($badges)) $badges_hide = true;
	if($details_hide && $taxonomies_hide && $badges_hide) return false;
	$out .= '<div class="details-box-wrapper">';
		$out .= '<div class="details-box">';
			$out .= '<div class="details-title">';
				$out .= it_get_category($minisite);
				$out .= '<div class="detail-label">'.$title.'</div>';
			$out .= '</div>';
			if(!$badges_hide) {
				$out .= '<div class="badges-wrapper">';
					$out .= '<div class="detail-item">';
						$out .= '<span class="detail-label">' . __('Badges',IT_TEXTDOMAIN) . '</span>';
						$out .= '<span class="detail-content">'.$badges.'</span>';
					$out .= '</div>';
				$out .= '</div>';	
			}
			if(!$taxonomies_hide) {
				$out .= '<div class="taxonomies-wrapper">';
				#loop through taxonomies and display
				foreach($taxonomies as $tax){
					if(!empty($tax[0]->slug)) {
						$detail_item = get_the_term_list( $postid, $tax[0]->slug, '<span class="detail-label">' . ucwords($tax[0]->name) . '</span><span class="detail-content">', ', ', '</span>' );
						if(!empty($detail_item)) {
							$out .= '<div class="detail-item">';
								$out .= $detail_item;
							$out .= '</div>';
						}
					}
				}				
				$out .= '</div>';	
			}
			if(!$details_hide) {
				$out .= '<div class="details-wrapper">';
				#loop through details and display
				foreach($details as $detail) {	
					if(is_array($detail)) {
						if(array_key_exists(0, $detail)) {
							$name = $detail[0]->name;	
							if(!empty($name)) { 
								$meta_name = $detail[0]->meta_name; 
								$meta = wpautop(get_post_meta($postid, $meta_name, $single = true));
								if(!empty($meta)) {
									$out .= '<div class="detail-item">';
										$out .= '<span class="detail-label">'.$name.'</span>';								
										$out .= '<span class="detail-content">'.$meta.'</span>';
									$out .= '</div>';
								}
							}	
						}
					}
				}
				$out .= '</div>';	
			}
		$out .= '</div>';
	$out .= '</div>';
	
	return $out;	
}

?>