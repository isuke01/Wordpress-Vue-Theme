<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Options',
		'menu_title'	=> 'Theme Options',
		'menu_slug' 	=> 'settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	
}