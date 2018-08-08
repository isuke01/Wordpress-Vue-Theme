<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$theme = wp_get_theme();
// Theme consts
define('THEME_DIR',             get_template_directory()); 
define('THEME_INC',             THEME_DIR    . '/wp'); 

define('THEME_VER',              $theme->get('Version') );
define('THEME_SLUG',             sanitize_title($theme->get('Name')) );

/**
 * ONLY DURING DEVELOPMENT !!!
 * It allow access cross origin for API 
 */
if(defined( 'WP_DEBUG' ) && ( WP_DEBUG === true || WP_DEBUG === 'TRUE') ) {

    function add_cors_http_header(){
        header("Access-Control-Allow-Origin: *");
    }
    add_action('init','add_cors_http_header');
}

// Enable the option show in rest
add_filter( 'acf/rest_api/field_settings/show_in_rest', '__return_true' );

// Enable the option edit in rest
add_filter( 'acf/rest_api/field_settings/edit_in_rest', '__return_true' );


/**
 * Enque script and styles for theme
 * 
 */
function vt_rest_theme_scripts() {

    /* Enque script based on build system */
    $files = glob(THEME_DIR.'/dist/static/js/*.js');
    $manifest = '';
    $vendor = '';
    $app = '';
    global $json_api;
    foreach( $files as $key => $file){
        $file_name = basename($file);
        if (strpos($file_name, 'app.') !== false) {
            $app = '/dist/static/js/'.$file_name;
        }
        if (strpos($file_name, 'vendor.') !== false) {
            $vendor = '/dist/static/js/'.$file_name;
        }
        if (strpos($file_name, 'manifest.') !== false) {
            $manifest = '/dist/static/js/'.$file_name;
        }

    }
    // must be loaded last (proper order loading is required)
    wp_enqueue_script( 'rest-theme-vue-manifest', get_template_directory_uri() . $manifest , array( 'jquery' ), THEME_VER, true );    
    wp_enqueue_script( 'rest-theme-vue-vendor', get_template_directory_uri() . $vendor , array( 'jquery' ), THEME_VER, true );        
    wp_enqueue_script( 'rest-theme-vue-app', get_template_directory_uri() . $app , array( 'jquery' ), THEME_VER, true );    

    // Script varibles
    $script_data = vt_get_localize_script_data();
	wp_localize_script( 'rest-theme-vue-app', 'WPVUE', $script_data );

    $app_css = vt_style_file();
    
	wp_enqueue_style( 'style', get_template_directory_uri() . $app_css , null, THEME_VER );
    
}
add_action( 'wp_enqueue_scripts', 'vt_rest_theme_scripts' );


function vt_style_file(){
    $files = glob(THEME_DIR.'/dist/static/css/*.css');
    $app_css = '';
    foreach( $files as $key => $file){
        $file_name = basename($file);
        if (strpos($file_name, 'app.') !== false) {
            $app_css = '/dist/static/css/'.$file_name;
        }
    }
    return $app_css;
}

function vt_get_localize_script_data(){
    global $option;
    $current_user = wp_get_current_user();
    $capsType = '';
    $admin_url = false;
    /* Used to determine current logged in user */
    if ($current_user->ID && user_can( $current_user, 'administrator' ) ) {
        $capsType = 'administrator';
        $admin_url = esc_url_raw( admin_url() );
    }

    $pt_args = [
        'public'   => true,
        'show_ui' =>  true,
    ];
    $post_types = get_post_types($pt_args, 'objects'); 
    $show_types = null;

    foreach ($post_types as $key => $type) {
        $rewrite = $type->name;
        if( is_array($type->rewrite) ){
            $rewrite = $type->rewrite['slug'];
        }
        
        $show_types[$type->name] = [
            'name'  =>  $type->name,
            'labels' => $type->labels,
            'hierarchical' => $type->hierarchical,
            'show_bar'  => $type->show_in_admin_bar,
            'rewrite' => $rewrite,
        ];
        if($capsType === 'administrator'){
            $show_types[$type->name]['edit_link'] = $type->_edit_link; // if admin 
        }
    } 
    
       
    
    $base_url  = esc_url_raw( home_url() );
    $base_path = rtrim( parse_url( $base_url, PHP_URL_PATH ), '/' );
    $post_page_ID = get_option( 'page_for_posts' );
	$post_page_slug = $post_page_ID ? get_post_field( 'post_name', $post_page_ID ) : 'aktuelt';
    $page_contact_slug = get_post_field('post_name', $option['general-contact-page']);

    // Example for getting a post type slug to varible if needed
    // $activities = get_post_type_object('activities');
    
    // get all WP options from ACF PRO OPTIONS
    $options = get_fields('option'); 
    return [
        'rest'      => esc_url_raw( rest_url() ),
        'api'      => esc_url_raw( home_url('api/') ),
        'user'      => [
            'loggedIn'  => $current_user->ID, // 1
            'caps'      => $capsType, //administrator
            'admin'     => $admin_url, //develop url (eg. http://vuetheme.wp/wp-admin )
        ],
		'assets'    => get_template_directory_uri(),        
		'home_url'  => $base_url,
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'base_path' => $base_path ? $base_path . '/' : '/',
		'nonce'     => wp_create_nonce( 'wp_rest' ),
		'site_name' => get_bloginfo( 'name' ),
		'front_page'=> get_option( 'page_on_front' ),
		'post_page' => $post_page_ID,
		'post_page_slug' => $post_page_slug,
		'page_contact' => $page_contact_slug,
        'translationStrings' => [],
        'options'   => $options,
        'logo' => '',
        'post_types' => $show_types,
    ];
}

/**
 * Base theme setup
 */
function vt_setup_tbn_theme() {
    load_theme_textdomain( 'wpvue', THEME_DIR.'/languages' );
    
    /* 
    wp_enqueue_style( 'wp-mediaelement' );
    wp_enqueue_script( 'wp-playlist' ); 
    */

    /**
     * Auto load files from folders
     */
	$directory = THEME_DIR;
    if (! is_dir($directory)) {
        exit('Invalid diretory path');
    }
    // directories from where to load
    $Dirs = [
        $directory .'/wp',
        $directory .'/wp/postTypes',
        $directory .'/wp/taxonomies',
    ];
    foreach( $Dirs as $dir ){
        $files = glob($dir.'/*.php');
        foreach( $files as $file){
            require $file;
        }
    }

    register_nav_menus(
		array(
		'primary-menu' => __( 'Primary' ),
		'footer-menu' => __( 'Footer left' ),
		)
	);

	if ( function_exists( 'add_image_size' ) ) {
        add_image_size( 'min', 32 ); // used as preloader image 
        add_image_size( 'extra_large', 1920 ); // used as large images e.g full screen images
	}
	add_filter('show_admin_bar', '__return_false');


    /*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', [
		/*'search-form',
		'comment-form',
		'comment-list',*/
		'gallery',
		'caption',
     ]);

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
    add_theme_support( 'post-thumbnails' );    
	add_theme_support( 'post-formats', [
		//'aside',
		//'image',
		//'video',
		//'quote',
		//'link',
     ]);

    add_filter( 'use_default_gallery_style', '__return_false' );
    //add_theme_support( 'woocommerce' );
    
    // It has some issues, conflicts with ACF
    //style https://codex.wordpress.org/Editor_Style
    //Editor Style
    // $app_css = vt_style_file();
    // add_editor_style( get_template_directory_uri() . $app_css.'?v='.THEME_VER);
}
add_action( 'after_setup_theme', 'vt_setup_tbn_theme' );

/**
 * Admin panel styles css
 * 
 */
function vt_admin_styles_wp_theme() {
    wp_enqueue_style('admin-style', get_template_directory_uri() . '/css/admin.css', null, THEME_VER );
}
add_action('admin_enqueue_scripts', 'vt_admin_styles_wp_theme');

/**
 * Include font loader to delay font load, but incresce overall load speed
 */
function vt_admin_head_font() {
    ?>
	<script type="text/javascript">
	WebFontConfig = {
		google: { families: [ 'Open+Sans:300,400,700,800' ] }
	};
	(function() {
		var wf = document.createElement('script');
		wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
		'://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);
	})(); </script>
    <?php
}
add_action( 'admin_head', 'vt_admin_head_font' );

/**
 * Add mime supporttypes,
 * SVG
 */
function vt_add_mime_types_support($file_types){
	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes );
	return $file_types;
	}
add_action('upload_mimes', 'vt_add_mime_types_support');


// Polyfill for wp_title()
function vt_vue_title( $title, $sep, $seplocation ) {

	if ( false !== strpos( $title, __( 'Page not found' ) ) ) {
		$replacement = ucwords( str_replace( '/', ' ', $_SERVER['REQUEST_URI'] ) );
		$title       = str_replace( __( 'Page not found' ), $replacement, $title );
	}
	return $title;
}
add_filter( 'wp_title','vt_vue_title', 10, 3 );

/**
 * Hack that allow to display SVG images in wordpress media viewer
 */

function vt_svg_meta_data($data, $id){

    $attachment = get_post($id); // Filter makes sure that the post is an attachment
    $mime_type = $attachment->post_mime_type; // The attachment mime_type

    //If the attachment is an svg

    if($mime_type && $mime_type == 'image/svg+xml'){
        //If the svg metadata are empty or the width is empty or the height is empty
        //then get the attributes from xml.

        if(empty($data) || empty($data['width']) || empty($data['height'])){

            $xml = simplexml_load_file(wp_get_attachment_url($id));
            $attr = $xml->attributes();
            $viewbox = explode(' ', $attr->viewBox);
            $data['width'] = isset($attr->width) && preg_match('/\d+/', $attr->width, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[2] : null);
            $data['height'] = isset($attr->height) && preg_match('/\d+/', $attr->height, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[3] : null);
        }

    }
    return $data;

}
add_filter('wp_update_attachment_metadata', 'vt_svg_meta_data', 10, 2);


/**
 * When ACF image fields is set as ID for return, in this case return JS ready data.
 * It is for wp-json calls in responds
 */
function vt_format_image_value( $value, $post_id, $field ) {
    if( $value && is_numeric($value) ){
        $value = wp_prepare_attachment_for_js( $value );
    }
    return $value;
}
add_filter('acf/format_value/type=image', 'vt_format_image_value', 99, 3);

/**
 * Enable responsive embeds for WP video embeds
 */
if ( ! function_exists( 'vt_responsive_video_oembed_html' ) ) :
    function vt_responsive_video_oembed_html( $html, $url, $attr, $post_id ) {
    
        // Whitelist of oEmbed compatible sites that **ONLY** support video.
        // Cannot determine if embed is a video or not from sites that
        // support multiple embed types such as Facebook.
        // Official list can be found here https://codex.wordpress.org/Embeds
        $video_sites = array(
            'youtube', // first for performance
            'collegehumor',
            'dailymotion',
            'funnyordie',
            'ted',
            'videopress',
            'vimeo',
        );
    
        $is_video = false;
    
        // Determine if embed is a video
        foreach ( $video_sites as $site ) {
            // Match on `$html` instead of `$url` because of
            // shortened URLs like `youtu.be` will be missed
            if ( strpos( $html, $site ) ) {
                $is_video = true;
                break;
            }
        }
    
        // Process video embed
        if ( true == $is_video ) {
    
            // Find the `<iframe>`
            $doc = new DOMDocument();
            $doc->loadHTML( $html );
            $tags = $doc->getElementsByTagName( 'iframe' );
    
            // Get width and height attributes
            foreach ( $tags as $tag ) {
                $width  = $tag->getAttribute('width');
                $height = $tag->getAttribute('height');
                break; // should only be one
            }
    
            $class = 'responsive-embed'; // Foundation class
    
            // Determine if aspect ratio is 16:9 or wider
            if ( is_numeric( $width ) && is_numeric( $height ) && ( $width / $height >= 1.7 ) ) {
                $class .= ' widescreen'; // space needed
            }
    
            // Wrap oEmbed markup in Foundation responsive embed
            return '<div class="' . $class . '">' . $html . '</div>';
    
        } else { // not a supported embed
            return $html;
        }
    
    }
    add_filter( 'embed_oembed_html', 'vt_responsive_video_oembed_html', 10, 4 );
endif;