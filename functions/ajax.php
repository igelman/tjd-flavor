<?php //processing for ajax user ratings

//this assumes your theme is not within a subfolder of your themes folder and goes all the way to the root of your WP install
//this file (ajax-sort.php) should be in the following location on your server: wp-content/themes/flavor/functions/ajax-sort.php
//that is why there are four ../ in a row, so it goes back four folders into your main WP install
//if ajax-sort.php is not in this location, you may need to add an extra ../ or remove one, but 99.9% of the time this will work
$wordpress_location = "../../../../wp-load.php";

//grab WordPress functions
require_once($wordpress_location);

$sorter=!empty($_POST["sorter"]) ? $_POST["sorter"] : '';
$loop=!empty($_POST["loop"]) ? $_POST["loop"] : '';
$location=!empty($_POST["location"]) ? $_POST["location"] : '';
if(empty($location)) $location = $loop; #a specified location overrides the loop parameter
$action=!empty($_POST["action"]) ? $_POST["action"] : '';
$cols=!empty($_POST["columns"]) ? $_POST["columns"] : '';
$paginated=!empty($_POST["paginated"]) ? $_POST["paginated"] : '';
$thumbnail=!empty($_POST["thumbnail"]) ? $_POST["thumbnail"] : '';
$morelink=!empty($_POST["morelink"]) ? $_POST["morelink"] : '';
$numarticles=!empty($_POST["numarticles"]) ? $_POST["numarticles"] : '';
$rating=!empty($_POST["rating"]) ? $_POST["rating"] : '';
$view=!empty($_POST["view"]) ? $_POST["view"] : '';
$likeaction=!empty($_POST["likeaction"]) ? $_POST["likeaction"] : '';
$postid=!empty($_POST["id"]) ? $_POST["id"] : '';
$timeperiod=!empty($_POST["timeperiod"]) ? $_POST["timeperiod"] : '';
$query=!empty($_POST["currentquery"]) ? $_POST["currentquery"] : '';
$meta=!empty($_POST["meta"]) ? $_POST["meta"] : '';
$metric=!empty($_POST["metric"]) ? $_POST["metric"] : '';
$divID=!empty($_POST["divID"]) ? $_POST["divID"] : '';
$method=!empty($_POST["method"]) ? $_POST["method"] : '';
$targeted=!empty($_POST["targeted"]) ? $_POST["targeted"] : '';
$taxonomy=!empty($_POST["taxonomy"]) ? $_POST["taxonomy"] : '';
$icon=!empty($_POST["icon"]) ? $_POST["icon"] : '';
$container=!empty($_POST["container"]) ? $_POST["container"] : '';
$comments_per_page=!empty($_POST["commentsperpage"]) ? $_POST["commentsperpage"] : '';
$offset=!empty($_POST["offset"]) ? $_POST["offset"] : '';
$type=!empty($_POST["type"]) ? $_POST["type"] : '';

#get the user's ip address
$ip=it_get_ip();

switch ($action) {
	case 'sort':
		switch ($loop) {
			case 'trending':
				#setup loop format
				$format = array('loop' => $loop, 'location' => $location, 'thumbnail' => $thumbnail, 'rating' => $rating, 'icon' => $icon);
				switch($sorter) {
					case 'recent':	
						break;
					case 'liked':
						$args = array('orderby' => 'meta_value_num', 'meta_key' => IT_META_TOTAL_LIKES);	
						break;
					case 'viewed':
						$args = array('orderby' => 'meta_value_num', 'meta_key' => IT_META_TOTAL_VIEWS);	
						break;
					case 'rated':
						$args = array('orderby' => 'meta_value_num', 'meta_key' => IT_META_TOTAL_SCORE_NORMALIZED, 'meta_query' => array(array( 'key' => IT_META_DISABLE_REVIEW, 'value' => 'false', 'compare' => '=' )));	
						break;
					case 'commented':
						$args = array('orderby' => 'comment_count');	
						break;
					case 'awarded':
						$args = array('orderby' => 'date', 'order' => 'DESC', 'meta_query' => array( array( 'key' => IT_META_AWARDS, 'value' => array(''), 'compare' => 'NOT IN') ) );	
						break;
				}
				$args['posts_per_page'] = $numarticles;
				#add current query to new query args
				if(!empty($query) && is_array($query)) $args = array_merge($args, $query);
			break;
			case 'top ten':
				#setup loop format
				$format = array('loop' => $loop, 'location' => $location, 'metric' => $sorter, 'thumbnail' => false, 'rating' => false, 'icon' => false);
				switch($sorter) {
					case 'liked':
						$args = array('order' => 'DESC', 'meta_key' => IT_META_TOTAL_LIKES, 'orderby' => 'meta_value_num');
						break;
					case 'viewed':
						$args = array('order' => 'DESC', 'meta_key' => IT_META_TOTAL_VIEWS, 'orderby' => 'meta_value_num');	
						break;
					case 'users':
						$format['rating'] = true;
						$args = array('orderby' => 'meta_value_num', 'meta_key' => IT_META_TOTAL_USER_SCORE_NORMALIZED, 'meta_query' => array(array( 'key' => IT_META_DISABLE_REVIEW, 'value' => 'false', 'compare' => '=' )));	
						break;
					case 'reviewed':
						$format['rating'] = true;
						$args = array('orderby' => 'meta_value_num', 'meta_key' => IT_META_TOTAL_SCORE_NORMALIZED, 'meta_query' => array(array( 'key' => IT_META_DISABLE_REVIEW, 'value' => 'false', 'compare' => '=' )));	
						break;
					case 'commented':
						$args = array('orderby' => 'comment_count');	
						break;
				}
				$args['posts_per_page'] = 10;
				#add current query to new query args
				if(!empty($query) && is_array($query)) $args = array_merge($args, $query);
			break;
			case 'main loop':
				#setup loop format
				$format = array('loop' => $loop, 'location' => $location, 'view' => $view, 'columns' => $cols, 'sort' => $sorter, 'paged' => $paginated, 'thumbnail' => true, 'rating' => true, 'icon' => true);
				switch($sorter) {
					case 'recent':
						break;
					case 'liked':
						$args = array('orderby' => 'meta_value_num', 'meta_key' => IT_META_TOTAL_LIKES);	
						break;
					case 'viewed':
						$args = array('orderby' => 'meta_value_num', 'meta_key' => IT_META_TOTAL_VIEWS);	
						break;
					case 'rated':
						$args = array('orderby' => 'meta_value_num', 'meta_key' => IT_META_TOTAL_SCORE_NORMALIZED, 'meta_query' => array(array( 'key' => IT_META_DISABLE_REVIEW, 'value' => 'false', 'compare' => '=' )));	
						break;
					case 'commented':
						$args = array('orderby' => 'comment_count');	
						break;
					case 'awarded':
						$args = array('orderby' => 'date', 'order' => 'DESC', 'meta_query' => array( array( 'key' => IT_META_AWARDS, 'value' => array(''), 'compare' => 'NOT IN') ));	
						break;
				}
				$args['paged'] = $paginated;
				#add current query to new query args
				if(!empty($query) && is_array($query)) $args = array_merge($args, $query);
			break;
			case 'minisite tabs':
				#setup loop format
				$format = array('loop' => $loop, 'location' => $location, 'sort' => $sorter, 'thumbnail' => $thumbnail, 'rating' => $rating, 'icon' => false, 'morelink' => $morelink);
				$args = array('posts_per_page' => $numarticles, 'post_type' => $sorter);				
			break;
			case 'recommended':
				#taxonomy query
				$tax_query = array(array('taxonomy' => $taxonomy, 'field' => 'id', 'terms' => $sorter));
									
				#format
				$format = array('loop' => $loop, 'location' => $location, 'thumbnail' => $thumbnail, 'rating' => $rating, 'icon' => $icon, 'container' => $container, 'columns' => $cols);		
				switch($method) {
					case 'tags':
						$args=array('tag_id' => $sorter);	
						break;
					case 'categories':
						$args=array('cat' => $sorter);	
						break;	
					case 'taxonomies':
						$args=array('tax_query' => $tax_query);							
						break;
				}
				$args['posts_per_page'] = $numarticles;
				$args['post__not_in'] = array($postid);
				#recommended targeted
				if(!empty($targeted)) $args['post_type'] = $targeted;
			break;
		}
		#add the time period to the args
		$week = date('W');
		$month = date('n');
		$year = date('Y');
		switch($timeperiod) {
			case 'This Week':
				$args['year'] = $year;
				$args['w'] = $week;
				$timeperiod='';
			break;	
			case 'This Month':
				$args['monthnum'] = $month;
				$args['year'] = $year;
				$timeperiod='';
			break;
			case 'This Year':
				$args['year'] = $year;
				$timeperiod='';
			break;
			case 'all':
				$timeperiod='';
			break;			
		}
		#build the loop html and return to ajax call	
		$loop = it_loop($args, $format, $timeperiod);
		$loop_content = '';
		$loop_pages = 0;
		$loop_updatepagination = '';
		$buildquery = '';
		if(array_key_exists('content',$loop)) $loop_content = $loop['content'];
		if(array_key_exists('pages',$loop)) $loop_pages = $loop['pages'];
		if(array_key_exists('updatepagination',$loop)) $loop_updatepagination = $loop['updatepagination'];
		if(!empty($query)) $buildquery = http_build_query($query);
		echo json_encode(array('content' => $loop_content, 'pagination' => it_pagination($loop_pages, $format, it_get_setting('page_range')), 'paginationmobile' => it_pagination($loop_pages, $format, it_get_setting('page_range_mobile')), 'pages' => $loop_pages, 'updatepagination' => $loop_updatepagination, 'utility' => $buildquery));
	break;
	case 'like':
		#get meta info
		$ips = get_post_meta($postid, IT_META_LIKE_IP_LIST, $single = true);
		if(!metadata_exists('post', $postid, IT_META_LIKE_IP_LIST)) $addipmeta=true;
		
		$likes = get_post_meta($postid, IT_META_TOTAL_LIKES, $single = true);
		if(!metadata_exists('post', $postid, IT_META_TOTAL_LIKES)) $addlikesmeta=true;
		
		$ip.=';'; #add delimiter
		
		if($likeaction=='like') {
			$ips.=$ip; #add ip to string
			$likes+=1; #increase likes
		} else {
			$ips = str_replace($ip,'',$ips); #remove ip from string
			$likes-=1; #decrease likes
		}
		
		#figure out whether to add or update the ip address meta field
		if($addipmeta) {
			add_post_meta($postid, IT_META_LIKE_IP_LIST, $ips);
		} else {
			update_post_meta($postid, IT_META_LIKE_IP_LIST, $ips);
		}
		
		#figure out whether to add or update the total likes meta field
		if($addlikesmeta) {
			add_post_meta($postid, IT_META_TOTAL_LIKES, $likes);
		} else {
			update_post_meta($postid, IT_META_TOTAL_LIKES, $likes);
		}
		
		if($likes=='') $likes=0;
		#determine label
		if($location=='single-page') {
			if($likes==1) {
				$likes.=__(' person likes this',IT_TEXTDOMAIN);
			} else {
				$likes.=__(' people like this',IT_TEXTDOMAIN);
			}
		} else {
			if($likes==0) $likes=''; #don't display 0 count
		}
		
		echo json_encode(array('content' => $likes));
	
	break;
	case 'rate':
	
		#setup the args
		$ratingargs = array('postid' => $postid, 'meta' => $meta, 'metric' => $metric, 'rating' => $rating);
		
		#perform the actual meta updates
		$ratings = it_save_user_ratings($ratingargs);
		
		#return the values
		echo json_encode(array('newrating' => $ratings['new_rating'], 'totalrating' => $ratings['total_rating'], 'normalized' => $ratings['normalized'], 'divID' => $divID, 'unlimitedratings' => $ratings['unlimitedratings']));
			
	break;
	case 'paginate_comments':
	
		#setup query
		$args = array('number' => $comments_per_page, 'status' => 'approve', 'type' => $type, 'post_id' => $postid, 'offset' => $offset);
		
		#get the comments	
		$comments = it_comments($args); 
		
		#return the values
		echo json_encode(array('content' => $comments['content']));
		
	break;
} ?>