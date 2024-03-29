<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 */

add_filter( 'cmb2_meta_boxes', 'kleo_define_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function kleo_define_metaboxes( array $meta_boxes ) {

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_kleo_';


    $meta_boxes['general_metabox'] = array(
        'id'            => 'general_metabox',
        'title'         => esc_html__( 'Theme General options', 'pool' ),
        'object_types'  => array( 'post', 'page' , 'product' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
        'fields'        => array(

            array(
                'name' => esc_html__( 'Show page title section', 'pool' ),
                'desc' => esc_html__( 'If you want to show the page title section on your page','pool'),
                'id'   => $prefix . 'page_title_enable',
                'type' => 'select',
                'options' => array(
                    '' => 'Default',
                    'yes' => 'Yes',
                    'no' => 'No'
                ),
                'value' => ''
            ),
            array(
                'name' => esc_html__( 'Custom page background color','pool'),
                'desc' => esc_html__('A custom background color just for this page','pool'),
                'id'   => $prefix . 'page_bg',
                'type' => 'colorpicker',
                'default' => ''
            ),
            array(
                'name' => esc_html__( 'Custom page background','pool'),
                'desc' => esc_html__('A custom background image just for this page','pool'),
                'id'   => $prefix . 'page_bg_image',
                'type' => 'file',
                'allow' => 'url',
                'default' => '',
            ),
        ),
    );


    $meta_boxes['post_metabox'] = array(
        'id'            => 'post_metabox',
        'title'         => esc_html__( 'Theme Post options', 'pool' ),
        'object_types'  => array( 'post' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
        'fields'        => array(

            array(
                'name' => 'Show media on post page',
                'desc' => 'If you want to show post featured media on single page',
                'id'   => $prefix . 'post_media_status',
                'type' => 'select',
                'options' => array(
                    '' => 'Default',
                    'yes' => 'Yes',
                    'no' => 'No'
                ),
                'value' => ''
            ),
            array(
                'name' => 'Gallery images',
                'desc' => 'Used when you select the Gallery format.',
                'id'   => $prefix . 'slider',
                'type' => 'file_list',
                'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
                'allow' => 'url'
            ),
            array(
                'name' => 'Video oEmbed URL',
                'desc' => wp_kses_data( __( 'Enter a youtube, twitter, or instagram URL. Supports services listed at <a target="_blank" href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'pool' ) ),
                'id'   => $prefix . 'embed',
                'type' => 'oembed',
            ),
        ),
    );

    $meta_boxes['post_layout'] = array(
        'id'         => 'post_layout',
        'title'      => esc_html__('Post Layout Settings', 'pool'),
        'object_types'      => array( 'post', 'product' ), // Post type
        'context'    => 'side',
        'priority'   => 'default',
        'show_names' => false, // Show field names on the left
        'fields'     => array(
            array(
                'name' => esc_html__('Post Layout', 'pool'),
                'desc' => '',
                'id'   => $prefix . 'post_layout',
                'type' => 'select',
                'options' => array(
                    'default' => esc_html__('Default', 'pool'),
                    'right' => esc_html__('Right Sidebar', 'pool'),
                    'left' => esc_html__('Left sidebar', 'pool'),
                    'full' => esc_html__('Full width, no sidebar', 'pool'),
                ),
                'value' => 'default'
            ),

        ),
    );

    $meta_boxes['header_settings'] = array(
        'id'         => 'header_settings',
        'title'      => esc_html__('Header Settings', 'pool'),
        'object_types'      => array( 'post', 'product', 'page' ), // Post type
        'context'    => 'side',
        'priority'   => 'default',
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => esc_html__('Sidemenu status', 'pool'),
                'id'   => $prefix . 'header_sidemenu',
                'type' => 'select',
                'options' => array(
                    'default' => esc_html__('Default', 'pool'),
                    'enabled' => esc_html__('Enabled', 'pool'),
                    'disabled' => esc_html__('Disabled', 'pool'),
                ),
                'value' => 'default',
                'desc' => esc_html__('Enabled/Disable the sidemenu', 'pool')
            ),

        ),
    );

    return $meta_boxes;
}


add_action( 'init', 'kleo_initialize_meta_boxes' );
/**
 * Initialize the metabox class.
 */
function kleo_initialize_meta_boxes() {
	/* Add meta boxes only on posts and pages to prevent conflicts like cmb2 */
	$allowed_post_types = apply_filters( 'kleo_meta_boxes_on_post_type', [ 'post', 'page', 'product' ] );
	$post_type = kleo_get_current_post_type();
	if( $post_type && in_array( $post_type, $allowed_post_types ) ) {

		if ( file_exists( KLEO_DIR . '/metaboxes/init.php' ) ) {
			require_once KLEO_DIR . '/metaboxes/init.php';
		}
	}
}

function kleo_get_current_post_type() {
	if (isset( $_REQUEST['post_type'] ) ) {
		return $_REQUEST['post_type'];
	} elseif ( isset( $_REQUEST['post'] ) && get_post_type( $_REQUEST['post'] ) ) {
		return get_post_type( $_REQUEST['post'] );
	}
	return false;
}