<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Just in case
 */
remove_action('template_redirect', 'redirect_canonical');
// Redirect all requests to index.php so the Vue app is loaded and 404s aren't thrown
function remove_redirects() {
    add_rewrite_rule('^/(.+)/?', 'index.php', 'top');
}
add_action('init', 'remove_redirects');
/**
 * Force permalink structure after init
 */
function vt_custom_rewrite_rule() {
    $post_page_ID = get_option( 'page_for_posts' );	
	$post_page_slug = $post_page_ID ? get_post_field( 'post_name', $post_page_ID ) : 'aktuelt';

	global $wp_rewrite;

	$wp_rewrite->front = $wp_rewrite->root;

	//$wp_rewrite->set_permalink_structure( $post_page_slug.'/%postname%/' );

	$wp_rewrite->page_structure      = $wp_rewrite->root . '/%pagename%/';

	//$wp_rewrite->author_base         = 'author';
	//$wp_rewrite->author_structure    = '/' . $wp_rewrite->author_base . '/%author%';

	$wp_rewrite->set_category_base( $post_page_slug );
	$wp_rewrite->set_tag_base( 'tag' );

	//$wp_rewrite->add_rule( '^'.$post_page_slug.'$', 'index.php', 'top' );

}
add_action( 'init', 'vt_custom_rewrite_rule' );

/**
 * Force permalink srtucture even if some one try change it
 */
function vt_forcee_perma_struct( $old, $new ) {
    update_option( 'permalink_structure', $post_page_slug.'/%postname%' ); 
}
add_action( 'permalink_structure_changed', 'vt_forcee_perma_struct', 10, 2 );


/**
 * Fix permalink structure for standard WP posts;
 * and rewrite for it
 */
function vt_add_rewrite_rules( $wp_rewrite )
{
    $post_page_ID = get_option( 'page_for_posts' );	
	$post_page_slug = $post_page_ID ? get_post_field( 'post_name', $post_page_ID ) : 'aktuelt';
    $new_rules = array(
        $post_page_slug.'/(.+?)/?$' => 'index.php?post_type=post&name='. $wp_rewrite->preg_index(1),
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'vt_add_rewrite_rules'); 

/**
 * Update permalinks in backend to fix borken links when viewing posts from wordpress admin panel.
 */
function vt_change_blog_links($post_link, $id=0){
    $post_page_ID = get_option( 'page_for_posts' );	
	$post_page_slug = $post_page_ID ? get_post_field( 'post_name', $post_page_ID ) : 'aktuelt';
    $post = get_post($id);

    if( is_object($post) && $post->post_type == 'post'){
        return home_url('/'.$post_page_slug.'/'. $post->post_name.'/');
    }

    return $post_link;
}
add_filter('post_link', 'vt_change_blog_links', 1, 3);


