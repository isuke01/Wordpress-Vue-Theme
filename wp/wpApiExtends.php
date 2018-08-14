<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * fix for localhost debug
 */
add_action( 'rest_api_init', function () {
    //Path to REST route and the callback function
    register_rest_route( 'wpvue/v2', '/getparams/', array(
        'methods' => 'GET',
        'callback' => 'vt_rest_get_wp_params',
    ));
});


function vt_rest_get_wp_params( WP_REST_Request $request  ){
    $params = vt_get_localize_script_data();
    return $params;
}


function vt_extend_request_add_featured_image() {
	// Add featured image
	$postTypes = post_types_arr();
	register_rest_field( $postTypes, // array of post types where this should be done
		'featured_image_src', //NAME OF THE NEW FIELD TO BE ADDED - you can call this anything
		[
			'get_callback'    => 'vt_get_image_src_for_rest',
			'update_callback' => null,
			'schema'          => null,
		]
	);

	if( class_exists('acf') ) { // Only if ACF plugin exist
		register_rest_field( $postTypes,
			'acf', //NAME OF THE NEW FIELD TO BE ADDED - you can call this anything
			[
				'get_callback'    => 'vt_get_acf_fields',
				'update_callback' => null,
				'schema'          => null,
			]
		);
	}

	register_rest_field( $postTypes,
		'protection', //NAME OF THE NEW FIELD TO BE ADDED - you can call this anything
		[
			'get_callback'    => 'vt_check_post_protection',
			'update_callback' => null,
			'schema'          => null,
		]
	);
}
add_action( 'rest_api_init', 'vt_extend_request_add_featured_image' );

function vt_get_image_src_for_rest( $object, $field_name, $request ) {

	if($object['featured_media']){
		$feat_img_array = wp_prepare_attachment_for_js($object['featured_media']);
	}else{
		$feat_img_array = false;
	}
	return $feat_img_array;
}


function vt_get_acf_fields( $object, $field_name, $request ) {
	$acf = false;
	//ADD fields to request, and check if password is needed to see content.
	if( $object['id'] && $object['password'] === '' || ($object['password'] === $request['password']) ){
		$acf = get_fields($object['id']);
	}
	return $acf;
}

/**
 * Password protection extend for rest api
 */
function vt_check_post_protection( $object, $field_name, $request ) {
	$protection = [
		'password' => false,
		'locked' => false,
	];
	if( $object['password'] !== '' ){
		$protection['password'] = true; 
		$protection['locked'] = true; 
	}
	if($object['password'] === $request['password']){
		$protection['locked'] = false; 
	}

	return $protection;
}

function vt_get_cat_name( $object, $field_name, $request ) {
	$cats = $object['categories'];
	$res = [];
	$ob = [];
	foreach ( $cats as $x ) {
		$cat_id = (int) $x;
		$cat = get_category( $cat_id );
		if ( is_wp_error( $cat ) ) {
			$res[] = '';
		} else {
			$ob['name'] = isset( $cat->name ) ? $cat->name : '';
			$ob['id']   = isset( $cat->term_id ) ? $cat->term_id : '';
			$ob['slug'] = isset( $cat->slug ) ? $cat->slug : '';
			$res[] = $ob;
		}
	}
	return $res;
}



/**
 * Plugin Name: JSON REST API Yoast routes 
 * Description: Adds Yoast fields to page and post metadata 
 * Author: jmfurlott<jmfurlott@gmail.com>
 * Author URI: https://jmfurlott.com
 * Author: nabilfreeman<nabil+oss@freemans.website>
 * Author URI: http://freemans.website
 * Version: 1.0.0
 * Plugin URI: https://github.com/jmfurlott/wp-api-yoast
 */
function wp_api_encode_yoast($data, $post, $context) {
    $yoastMeta = array(
        'yoast_wpseo_focuskw' => get_post_meta($post->ID, '_yoast_wpseo_focuskw', true),
        'yoast_wpseo_title' => get_post_meta($post->ID, '_yoast_wpseo_title', true),
        'yoast_wpseo_metadesc' => get_post_meta($post->ID, '_yoast_wpseo_metadesc', true),
        'yoast_wpseo_linkdex' => get_post_meta($post->ID, '_yoast_wpseo_linkdex', true),
        'yoast_wpseo_metakeywords' => get_post_meta($post->ID, '_yoast_wpseo_metakeywords', true),
        'yoast_wpseo_meta-robots-noindex' => get_post_meta($post->ID, '_yoast_wpseo_meta-robots-noindex', true),
        'yoast_wpseo_meta-robots-nofollow' => get_post_meta($post->ID, '_yoast_wpseo_meta-robots-nofollow', true),
        'yoast_wpseo_meta-robots-adv' => get_post_meta($post->ID, '_yoast_wpseo_meta-robots-adv', true),
        'yoast_wpseo_canonical' => get_post_meta($post->ID, '_yoast_wpseo_canonical', true),
        'yoast_wpseo_redirect' => get_post_meta($post->ID, '_yoast_wpseo_redirect', true),
        'yoast_wpseo_opengraph-title' => get_post_meta($post->ID, '_yoast_wpseo_opengraph-title', true),
        'yoast_wpseo_opengraph-description' => get_post_meta($post->ID, '_yoast_wpseo_opengraph-description', true),
        'yoast_wpseo_opengraph-image' => get_post_meta($post->ID, '_yoast_wpseo_opengraph-image', true),
        'yoast_wpseo_twitter-title' => get_post_meta($post->ID, '_yoast_wpseo_twitter-title', true),
        'yoast_wpseo_twitter-description' => get_post_meta($post->ID, '_yoast_wpseo_twitter-description', true),
        'yoast_wpseo_twitter-image' => get_post_meta($post->ID, '_yoast_wpseo_twitter-image', true)
    );
    $data = (array) $yoastMeta;
    return $data;
}

/**
 * fix for localhost debug
 */
add_action( 'rest_api_init', function () {
    //Path to REST route and the callback function
    register_rest_route( 'wpvue/v2', '/dailymenu/', array(
        'methods' => 'GET',
        'callback' => 'vt_daily_menus',
    ));
});
