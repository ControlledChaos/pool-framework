<?php


/***************************************************
:: Theme options
 ***************************************************/

add_filter( 'kleo_theme_settings', 'kleo_extras_settings' );

function kleo_extras_settings( $kleo )
{
    //
    // Settings Sections
    //

    $kleo['sec']['kleo_section_misc'] = array(
        'title' => esc_html__('Miscellaneous', 'pool'),
        'priority' => 50
    );


    //
    // Misc
    //

    $kleo['set'][] = array(
        'id' => 'maintenance_mode',
        'type' => 'switch',
        'title' => esc_html__('Maintenance Enabled', 'pool'),
        'default' => 0,
        'section' => 'kleo_section_misc',
        'description' => esc_html__('WARNING: It will make the site available to admins only', 'pool'),
        'customizer' => false,
    );

    $kleo['set'][] = array(
        'id' => 'maintenance_msg',
        'type' => 'textarea',
        'title' =>  esc_html__('Maintenance Message', 'pool'),
        'default' => 'We are not available for the moment!!!',
        'section' => 'kleo_section_misc',
        'description' => esc_html__('The message that is visible for guests if you enabled maintenance', 'pool'),
        'customizer' => false,
        'condition' => array( 'maintenance_mode', 1 )
    );

    $kleo['set'][] = array(
        'id' => 'admin_bar',
        'type' => 'switch',
        'title' => esc_html__('Admin bar visible', 'pool'),
        'default' => '1',
        'section' => 'kleo_section_misc',
        'description' => esc_html__('If by default the Admin menu bar is enabled', 'pool'),
        'customizer' => false,
    );

    $kleo['set'][] = array(
        'id' => 'page_comments_disable',
        'type' => 'switch',
        'title' => esc_html__('Disable page comments', 'pool'),
        'default' => '0',
        'section' => 'kleo_section_misc',
        'description' => esc_html__('Force disable comments on all pages', 'pool'),
        'customizer' => false,
    );


    $kleo['set'][] = array(
        'id' => 'quick_css',
        'type' => 'textarea',
        'title' =>  esc_html__('Quick CSS', 'pool'),
        'default' => '',
        'section' => 'kleo_section_misc',
        'description' => esc_html__('Quickly add small css codes in this section to apply to the whole site.', 'pool'),
        'customizer' => false,
    );

    $kleo['set'][] = array(
        'id' => 'quick_js',
        'type' => 'textarea',
        'title' =>  esc_html__('Quick Javascript', 'pool'),
        'default' => '',
        'section' => 'kleo_section_misc',
        'description' => esc_html__('Small JS code to load in the footer of your site. You need to include the script tags.', 'pool'),
        'customizer' => false,
    );

    $kleo['set'][] = array(
        'id' => 'dev_mode',
        'type' => 'switch',
        'title' => esc_html__( 'Development mode', 'pool' ),
        'default' => 0,
        'section' => 'kleo_section_misc',
        'description' => esc_html__('This will load css/js files not minified.', 'pool'),
        'customizer' => false,
    );

    return $kleo;


}


/***************************************************
:: Render functions
 ***************************************************/

//
// ADMIN BAR
// Enable or disable the bar, depending of the theme option setting
//
if (sq_option('admin_bar', 1) == '0') {
    remove_action('wp_footer', 'wp_admin_bar_render', 1000);
    add_filter('show_admin_bar', '__return_false');
}



//
// MAINTENANCE MODE
//
if ( ! function_exists('kleo_maintenance_mode') ) {
    function kleo_maintenance_mode() {

        $logo_path = apply_filters( 'kleo_logo', sq_option('logo') );
        $logo_img = '<img src="'. $logo_path .'" alt="Maintenance" style="margin: 0 auto; display: block;" />';

        if ( sq_option('maintenance_mode', false) ) {

            if ( !current_user_can( 'edit_themes' ) || !is_user_logged_in() ) {
                wp_die(
                    $logo_img
                    . '<div style="text-align:center">'
                    . sq_option('maintenance_msg', '')
                    . '</div>',
                    get_bloginfo( 'name' )
                );
            }
        }
    }
    add_action('get_header', 'kleo_maintenance_mode');
}


if ( sq_option('page_comments_disable') ) {
    add_action( 'wp', 'sq_disable_page_comments' );
}

function sq_disable_page_comments() {
    if ( is_page() ) {
        add_filter( 'comments_open', '__return_false');
    }
}