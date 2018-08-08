<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
* Small improvments for photo swipe galleries to dont load them when unnecessery
 */

// function proper_enquer_photoSwipeScripts(){
//     if( is_singular() &&  !is_home() && !is_front_page() ){
//         add_action('wp_enqueue_scripts', 'photoswipe_enqueue', 99);
//     }
// }
// if ( function_exists('photoswipe_enqueue') ) {
//     remove_action( 'wp_enqueue_scripts', 'photoswipe_enqueue' );
//     add_action('wp_enqueue_scripts', 'proper_enquer_photoSwipeScripts');
// }


/**
* Extend Gallery to additional fields for extra dispaly settings
*/
add_action('print_media_templates', function(){
    /*
    <h3>Custom Settings</h3>

    <label class="setting">
        <span><?php _e('Text'); ?></span>
        <input type="text" value="" data-setting="ds_text" style="float:left;">
    </label>

    <label class="setting">
        <span><?php _e('Textarea'); ?></span>
        <textarea value="" data-setting="ds_textarea" style="float:left;"></textarea>
    </label>

    <label class="setting">
        <span><?php _e('Number'); ?></span>
        <input type="number" value="" data-setting="ds_number" style="float:left;" min="1" max="9">
    </label>
        <label class="setting">
      <span><?php _e('Use lightbox', 'wpvue'); ?></span>
      <input type="checkbox" name="lightbox" data-setting="lightbox" checked>
    </label>
   
    <label class="setting">
      <span><?php _e('Wide image', 'wpvue'); ?></span>
      <input type="checkbox" name="extend" data-setting="wide" >
    </label>
        <label class="setting">
      <span><?php _e('Style', 'wpvue'); ?></span>
      <select data-setting="type">
        <option value="grid"><?php _e('Grid', 'wpvue'); ?></option>
        <option value="carousel"><?php _e('Carousel', 'wpvue'); ?></option>
      </select>
    </label>
    */
?>
<script type="text/html" id="tmpl-custom-gallery-setting">
    <label class="setting">
      <span><?php _e('Image proportion', 'wpvue'); ?></span>
      <select data-setting="proportion">
        <option value="default"><?php _e('Default', 'wpvue'); ?></option>
        <option value="wide"><?php _e('Wide (16:9)', 'wpvue'); ?></option>
        <option value="square"><?php _e('Square (1:1)', 'wpvue'); ?></option>
        <option value="protret"><?php _e('Protret (4:3)', 'wpvue'); ?></option>
      </select>
    </label>


</script>

<script>

    jQuery(document).ready(function()
    {
        _.extend(wp.media.gallery.defaults, {
            wide: false,
            lightbox: true,
            proportion: 'default',
            type: 'grid',
        });

        wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('custom-gallery-setting')(view);
        },
        // this is function copies from WP core /wp-includes/js/media-views.js?ver=4.6.1
        update: function( key ) {
          var value = this.model.get( key ),
            $setting = this.$('[data-setting="' + key + '"]'),
            $buttons, $value;

          // Bail if we didn't find a matching setting.
          if ( ! $setting.length ) {
            return;
          }

          // Attempt to determine how the setting is rendered and update
          // the selected value.

          // Handle dropdowns.
          if ( $setting.is('select') ) {
            $value = $setting.find('[value="' + value + '"]');

            if ( $value.length ) {
              $setting.find('option').prop( 'selected', false );
              $value.prop( 'selected', true );
            } else {
              // If we can't find the desired value, record what *is* selected.
              this.model.set( key, $setting.find(':selected').val() );
            }

          // Handle button groups.
          } else if ( $setting.hasClass('button-group') ) {
            $buttons = $setting.find('button').removeClass('active');
            $buttons.filter( '[value="' + value + '"]' ).addClass('active');

          // Handle text inputs and textareas.
          } else if ( $setting.is('input[type="text"], textarea') ) {
            if ( ! $setting.is(':focus') ) {
              $setting.val( value );
            }
          // Handle checkboxes.
          } else if ( $setting.is('input[type="checkbox"]') ) {
            $setting.prop( 'checked', !! value && 'false' !== value );
          }
          // HERE the only modification I made
          else {
            $setting.val( value ); // treat any other input type same as text inputs
          }
          // end of that modification
        },
        });
    });

</script>
<?php
});

/**
* Override standard gallery shortcode to do what I want
*/
add_filter( 'post_gallery', 'extend_post_gallery', 10, 2 );
function extend_post_gallery( $output, $attr) {
    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $attr['orderby'] ) ) {
            $attr['orderby'] = 'post__in';
        }
        $attr['include'] = $attr['ids'];
    }

    $html5 = current_theme_supports( 'html5', 'gallery' );
    $atts = shortcode_atts( array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => $html5 ? 'figure'     : 'dl',
        'icontag'    => $html5 ? 'div'        : 'dt',
        'captiontag' => $html5 ? 'figcaption' : 'dd',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'link'       => '',
        //extra
        'wide'       => 'false',
        'type'       => 'grid',
        'proportion' => 'default'
    ), $attr, 'gallery' );

    $id = intval( $atts['id'] );

    //Extra style added depending of gallery settings dropdown


    if ( ! empty( $atts['include'] ) ) {
        $_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( ! empty( $atts['exclude'] ) ) {
        $attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    } else {
        $attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    }

    if ( empty( $attachments ) ) {
        return '';
    }

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment ) {
            $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
        }
        return $output;
    }

    $itemtag = tag_escape( $atts['itemtag'] );
    $captiontag = tag_escape( $atts['captiontag'] );
    $icontag = tag_escape( $atts['icontag'] );
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) ) {
        $itemtag = 'dl';
    }
    if ( ! isset( $valid_tags[ $captiontag ] ) ) {
        $captiontag = 'dd';
    }
    if ( ! isset( $valid_tags[ $icontag ] ) ) {
        $icontag = 'dt';
    }

    $columns = intval( $atts['columns'] );
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = '';

    /**
     * Filters whether to print default gallery styles.
     *
     * @since 3.1.0
     *
     * @param bool $print Whether to print default gallery styles.
     *                    Defaults to false if the theme supports HTML5 galleries.
     *                    Otherwise, defaults to true.
     */
    if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
        $gallery_style = "
        <style type='text/css'>
            #{$selector} {
                margin: auto;
            }
            #{$selector} .gallery-item {
                float: {$float};
                margin-top: 10px;
                text-align: center;
                width: {$itemwidth}%;
            }
            #{$selector} img {
                border: 2px solid #cfcfcf;
            }
            #{$selector} .gallery-caption {
                margin-left: 0;
            }
            /* see gallery_shortcode() in wp-includes/media.php */
        </style>\n\t\t";
    }

    $gallery_type_selected = (string) $atts['type'];
    $data_cols = $columns;

    if ($atts['type'] == 'carousel') {
        $columns = '1';
        $gallery_type_selected .= ' owl-carousel';
    }

    $wide_class = sanitize_html_class( $atts['wide'] );

    $size_class = sanitize_html_class( $atts['size'] );

    $type_class = sanitize_html_class( $atts['link'] );

    $proportion_class = sanitize_html_class( $atts['proportion'] );

    $gallery_div = "<div itemscope itemtype='http://schema.org/ImageGallery' data-cols='{$data_cols}' id='{$selector}' class='gallery proportion-{$proportion_class} gallery-wide-{$wide_class} gallery-type-{$gallery_type_selected} galleryid-{$id} gallery-columns-{$columns} type-{$type_class} gallery-size-{$size_class}'>";

    /**
     * Filters the default gallery shortcode CSS styles.
     *
     * @since 2.5.0
     *
     * @param string $gallery_style Default CSS styles and opening HTML div container
     *                              for the gallery shortcode output.
     */
    $output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        $type = '';
        $attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
        if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
            $type = 'link';
            $image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
        } elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
            $image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
        } else {
            $type = 'link';
            
            $image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
        }
        $image_meta  = wp_get_attachment_metadata( $id );

        $orientation = '';
        if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
            $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
        }
        $output .= "<{$itemtag} data-width='{$image_meta['width']}' data-height='{$image_meta['height']}' itemprop='associatedMedia' itemscope itemtype='http://schema.org/ImageObject' class='gallery-item'>";
        $output .= "
            <{$icontag} class='gallery-icon {$orientation}'>
                $image_output
            </{$icontag}>";
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag} itemprop='caption description' class='wp-caption-text gallery-caption' id='$selector-$id'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }
        $output .= "</{$itemtag}>";
        if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
            $output .= '<br style="clear: both" />';
        }
    }

    if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
        $output .= "
            <br style='clear: both' />";
    }

    $output .= "
        </div>\n";

    return $output;
}

