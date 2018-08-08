<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'init', 'create_taxonomy_calories', 0 );
function create_taxonomy_calories(){
    $labels = array(
        'name'              => _x( 'Calories', 'taxonomy general name', 'tankenbak' ),
        'singular_name'     => _x( 'Calories', 'taxonomy singular name', 'tankenbak' ),
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => false,
        'query_var'         => true,
        'public'            => false,
        'has_archive'       => false,
        'show_in_rest'      => true,
        //'rewrite'           => array( 'slug' => 'calories' ),
    );

    $post_types = array('diet');
    register_taxonomy( 'calories', $post_types , $args );
}
