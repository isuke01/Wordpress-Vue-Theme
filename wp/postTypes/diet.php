<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'init', 'create_post_type_diet', 0 );
function create_post_type_diet() {

    $labels = array(
        'name'                => _x( 'DIET', 'Post Type General Name', 'wpvue' ),
        'singular_name'       => _x( 'DIET', 'Post Type Singular Name', 'wpvue' ),
        'menu_name'           => __( 'DIET', 'wpvue' ),
        'name_admin_bar'      => __( 'DIET', 'wpvue' ),
    );
    $args = array(
        'label'               => __( 'DIET', 'wpvue' ),
        'description'         => __( 'DIET', 'wpvue' ),
        'labels'              => $labels,
        'supports'            => array( 'title'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-carrot',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'show_in_rest'        => true,
        'rewrite'             => array( 'slug' => 'diet' ),
        'capability_type'     => 'post',
    );

    register_post_type( 'diet', $args );

}



