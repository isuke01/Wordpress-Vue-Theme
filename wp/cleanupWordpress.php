<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Function for remove unnecessery metaboxes from posts
 * 
 * @remove_meta_box( BOX_ID, POST_TYPE, POSITION )
 * @remove_submenu_page() - https://codex.wordpress.org/Function_Reference/remove_submenu_page
 * @remove_menu_page() https://codex.wordpress.org/Function_Reference/remove_menu_page
 */
function remove_unnecessery_metaboxes() {
    //META BOXES
    //remove_meta_box( 'tagsdiv-calories', 'products', 'side' );
    
    //admin menu
	// remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
	// remove_menu_page( 'edit.php' );
}

add_action( 'admin_menu', 'remove_unnecessery_metaboxes' );

// wp remove unnecessery elements
function deactivate_post_defaults() {
/* 	remove_post_type_support( 'post', 'excerpt' );
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' ); */
}
add_action( 'admin_init', 'deactivate_post_defaults' );

function vt_manage_columns( $columns ) {
/* 	unset($columns['categories']);
	unset($columns['tags']); */
	return $columns;
}
  
function vt_column_init() {
    add_filter( 'manage_posts_columns' , 'vt_manage_columns' );
}
add_action( 'admin_init' , 'vt_column_init' );


/**
 * Cleanup wordpress crapp
 */

  
if ( ! function_exists( 'start_themer_Cleanup' ) ) :
function start_themer_Cleanup() {

    // Launching operation cleanup.
    add_action( 'init', 'foundationpress_cleanup_head' );

    // Remove WP version from RSS.
    add_filter( 'the_generator', 'foundationpress_remove_rss_version' );

    // Remove pesky injected css for recent comments widget.
    add_filter( 'wp_head', 'foundationpress_remove_wp_widget_recent_comments_style', 1 );

    // Clean up comment styles in the head.
    add_action( 'wp_head', 'foundationpress_remove_recent_comments_style', 1 );

    // Remove inline width attribute from figure tag
    add_filter( 'img_caption_shortcode', 'foundationpress_remove_figure_inline_style', 10, 3 );

}
add_action( 'after_setup_theme','start_themer_Cleanup' );
endif;
/**
 * Clean up head.+
 * ----------------------------------------------------------------------------
 */

if ( ! function_exists( 'foundationpress_cleanup_head' ) ) :
function foundationpress_cleanup_head() {

    // EditURI link.
    remove_action( 'wp_head', 'rsd_link' );

    // Category feed links.
    remove_action( 'wp_head', 'feed_links_extra', 3 );

    // Post and comment feed links.
    remove_action( 'wp_head', 'feed_links', 2 );

    // Windows Live Writer.
    remove_action( 'wp_head', 'wlwmanifest_link' );

    // Index link.
    remove_action( 'wp_head', 'index_rel_link' );

    // Previous link.
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );

    // Start link.
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );

    // Canonical.
    remove_action( 'wp_head', 'rel_canonical', 10, 0 );

    // Shortlink.
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

    // Links for adjacent posts.
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

    // WP version.
    remove_action( 'wp_head', 'wp_generator' );

    // Emoji detection script.
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

    // Emoji styles.
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
endif;

// Remove WP version from RSS.
if ( ! function_exists( 'foundationpress_remove_rss_version' ) ) :
function foundationpress_remove_rss_version() { return ''; }
endif;

// Remove injected CSS for recent comments widget.
if ( ! function_exists( 'foundationpress_remove_wp_widget_recent_comments_style' ) ) :
function foundationpress_remove_wp_widget_recent_comments_style() {
    if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
        remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
    }
}
endif;

// Remove injected CSS from recent comments widget.
if ( ! function_exists( 'foundationpress_remove_recent_comments_style' ) ) :
function foundationpress_remove_recent_comments_style() {
    global $wp_widget_factory;
    if ( isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments']) ) {
    remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
    }
}
endif;

// Remove inline width attribute from figure tag causing images wider than 100% of its conainer
if ( ! function_exists( 'foundationpress_remove_figure_inline_style' ) ) :
function foundationpress_remove_figure_inline_style( $output, $attr, $content ) {
    $atts = shortcode_atts( array(
        'id'      => '',
        'align'   => 'alignnone',
        'width'   => '',
        'caption' => '',
        'class'   => '',
    ), $attr, 'caption' );

    $atts['width'] = (int) $atts['width'];
    if ( $atts['width'] < 1 || empty( $atts['caption'] ) ) {
        return $content;
    }

    if ( ! empty( $atts['id'] ) ) {
        $atts['id'] = 'id="' . esc_attr( $atts['id'] ) . '" ';
    }

    $class = trim( 'wp-caption ' . $atts['align'] . ' ' . $atts['class'] );

    if ( current_theme_supports( 'html5', 'caption' ) ) {
        return '<figure ' . $atts['id'] . ' class="' . esc_attr( $class ) . '">'
        . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption></figure>';
    }

}
endif;

// Add WooCommerce support for wrappers per http://docs.woothemes.com/document/third-party-custom-theme-compatibility/
/* remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
add_action('woocommerce_before_main_content', 'foundationpress_before_content', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_after_main_content', 'foundationpress_after_content', 10); */


