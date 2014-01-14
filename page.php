<?php get_header(); # show header

#determine if this page is connected to a minisite
$minisite = it_get_minisite_by_meta($post->ID);

if($minisite) {
	it_get_template_part('post-loop'); # post loop page
} else {
	it_get_template_part('page-content'); # content page	
}

get_footer(); # show footer ?>