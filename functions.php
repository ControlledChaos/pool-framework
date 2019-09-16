<?php
/**
 * @package WordPress
 * @subpackage Next
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Next 1.0
 */

/**
 * Load Kleo framework
 */
define( 'KLEO_THEME_VERSION', '1.5.8' );

if ( ! isset( $content_width ) ) {
    $content_width = 1200;
}

require_once get_parent_theme_file_path( '/kleo-framework/kleo.php' );

// Set some theme configuration options.
Kleo::init_config( [
    'styling_variables' => array(
        'background-color' => esc_html__( 'Background color', 'pool' ),
        'border-color'     => esc_html__( 'Border color', 'pool' ),
        'heading-color'    => esc_html__( 'Heading color', 'pool' ),
        'text-color'       => esc_html__( 'Text color', 'pool' ),
        'link-color'       => esc_html__( 'Link color', 'pool' ),
        'hover-link-color' => esc_html__( 'Hover Link color', 'pool' ),
        'accent-color'     => esc_html__( 'Accent color', 'pool' )
    ),
    // Post image sizes for carousels and galleries.
    'post_gallery_img_width'  => 640,
    'post_gallery_img_height' => 480,

    // Page templates.
    'tpl_map' => [
        'page-templates/full-width.php'    => 'full',
        'page-templates/left-sidebar.php'  => 'left',
        'page-templates/right_sidebar.php' => 'right',
	],
    'container_class'       => 'container-fluid',
    'menu_icon_default'     => 'buddyapp-default',
    'footer_text'           => '',
    'default_font_headings' => 'Montserrat',
    'default_font_text'     => 'Open Sans',
	'blog_meta_defaults'    => [
		'date',
		'author_link',
		'categories',
		'tags',
		'comments'
	],
	'blog_meta_elements' => [
		'avatar'      => 'Author Avatar',
		'author_link' => 'Author name',
		'date'        => 'Date',
		'message'     => 'Message Link',
		'categories'  => 'Categories',
		'tags'        => 'Tags',
		'comments'    => 'Comments'
	]

] );

/**
 * Theme setup
 *
 * Sets up theme defaults and registers the various WordPress features.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Kleo Framework 1.0
 */
function kleo_setup() {

	/*
	 * Makes theme available for translation.
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'pool', get_parent_theme_file_path( '/languages' ) );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support(
		'post-formats',
		[
			'image',
			'gallery',
			'status',
			'quote',
			'video'
		]
	);

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menu( 'primary', esc_html__( 'Main Menu (Side)', 'pool' ) );
    register_nav_menu( 'top-left', esc_html__( 'Top Left Header Menu', 'pool' ) );
    register_nav_menu( 'top-right', esc_html__( 'Top Right Header Menu', 'pool' ) );

    // This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 9999 ); // Unlimited height, soft crop

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
	) );

	// Specific framework functionality.
	add_theme_support( 'kleo-sidebar-generator' );
    add_theme_support( 'kleo-menu-custom' );
    add_theme_support( 'kleo-menu-items' );

	// Third-party plugins.
	add_theme_support( 'bbpress' );

	// Woocomemrce suppoert.
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    add_theme_support( 'title-tag' );


}
add_action( 'after_setup_theme', 'kleo_setup' );

/**
 * Load Theme files
 */
function kleo_theme_functions() {

    // Resize on the fly.
    require_once( KLEO_LIB_DIR . '/inc/aq_resizer.php' );

    // Menu structure.
    require_once( KLEO_LIB_DIR . '/menu.php' );

    // Custom menu.
    require_if_theme_supports('kleo-menu-custom', KLEO_LIB_DIR . '/menu-custom.php');

    // Custom menu items.
    require_if_theme_supports('kleo-menu-items', KLEO_LIB_DIR . '/menu-items.php');

    // Include admin customizations.
    if ( is_customize_preview() ) {
        require_once( KLEO_LIB_DIR . '/customizer/setup.php' );
    }

    // Meta boxes.
    if ( is_admin() ) {
        require_once(KLEO_LIB_DIR . '/metaboxes.php');
    }

    // Dynamic CSS generation.
    require_once( KLEO_LIB_DIR . '/dynamic-css/dynamic-css.php' );

    // BuddyPress compatibility.
    if ( function_exists( 'bp_is_active' ) ) {
        require_once( KLEO_LIB_DIR . '/plugins/buddypress/buddypress.php' );
    }

    // bbPress compatibility.
    if ( class_exists( 'bbPress' ) ) {
        require_once( KLEO_LIB_DIR . '/plugins/bbpress/bbpress.php' );
    }

    // Woocommerce compatibility.
    if (  class_exists( 'WooCommerce' ) ) {
        require_once( KLEO_LIB_DIR . '/plugins/woocommerce.php' );
    }


    // WPML compatibility.
    if ( function_exists( 'icl_get_languages' ) ) {
        require_once( KLEO_LIB_DIR . '/plugins/wpml.php' );
    }

    // Visual composer compatibility.
    if ( function_exists( 'vc_set_as_theme' ) ) {
        require_once( KLEO_LIB_DIR . '/plugins/visual-composer.php' );
    }

    // Cleverness TodoList compatibility.
    if ( class_exists('CTDL_Widget')) {
        require_once( KLEO_LIB_DIR . '/plugins/ctdl.php' );
    }

    // Easy Knowledge Base compatibility.
    if ( function_exists( 'sq_kb_setup_post_type' ) ) {
        require_once( KLEO_LIB_DIR . '/plugins/easy-kb/easy-kb.php' );
    }

    // Menu items visibility control plugin compatibility.
    if ( class_exists( 'Boom_Walker_Nav_Menu_Edit' ) ) {
        require_once( KLEO_LIB_DIR . '/plugins/menu-items-visibility-control.php' );
    }

}
add_action( 'after_setup_theme', 'kleo_theme_functions', 12 );

// Load theme-specific functions.
require_once get_parent_theme_file_path( 'theme-functions.php' );

// Load theme panel.
if ( is_admin() ) {
    require_once get_parent_theme_file_path( 'theme-panel/init.php' );
}

// File importer.
if ( is_admin() ) {
    require_once get_parent_theme_file_path( '/importer/import.php' );
}

// Load components.
$kleo_components = [
    'base.php',
    'page-title.php',
    'extras.php',
];

$kleo_components = apply_filters( 'kleo_components', $kleo_components );

foreach ( $kleo_components as $component ) {
    $file_path = trailingslashit( KLEO_LIB_DIR ) . 'components/' . $component;
    include_once $file_path;
}

// Load modules.
$kleo_modules =[
    'facebook-login.php'
];

$kleo_modules = apply_filters( 'kleo_modules', $kleo_modules );

foreach ( $kleo_modules as $module ) {
    $file_path = trailingslashit( KLEO_LIB_DIR ) . 'modules/' . $module;
    include_once $file_path;
}

// Include widgets.
$kleo_widgets = [
    'recent_posts.php',
	'social-share.php'
];

$kleo_widgets = apply_filters( 'kleo_widgets', $kleo_widgets );

foreach ( $kleo_widgets as $widget ) {
    $file_path = trailingslashit( KLEO_LIB_DIR ) . 'widgets/' . $widget;

    if ( file_exists( $file_path ) ) {
        require_once( $file_path );
    }
}

/**
 * Widget areas
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Kleo 1.0
 */
function pool_widgets_init() {

	register_sidebar( [
		'name'          => esc_html__('Main Sidebar', 'pool'),
		'id'            => 'sidebar-1',
		'description'   => esc_html__('Main Sidebar', 'pool'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	] );

	register_sidebar( [
		'name'          => esc_html__('Side menu Area', 'pool'),
		'id'            => 'side',
		'description'   => esc_html__('Side Menu Area', 'pool'),
		'before_widget' => '<div id="%1$s" class="menu-section widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="menu-section-header"><span>',
		'after_title'   => '</span></p>',
	] );

	register_sidebar( [
		'name'          => 'Footer column 1',
		'id'            => 'footer-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	] );

	register_sidebar( [
		'name'          => 'Footer column 2',
		'id'            => 'footer-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	] );

	register_sidebar( [
		'name'          => 'Footer column 3',
		'id'            => 'footer-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	] );

	register_sidebar( [
		'name'          => 'Footer column 4',
		'id'            => 'footer-4',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	] );

}
add_action( 'widgets_init', 'pool_widgets_init' );

/**
 * Scripts/Styles load
 */
function pool_frontend_files() {

	$min = '';
	if ( sq_option( 'dev_mode', false ) == false ) {
		$min = '.min';
	}

	// Modernizr.
	// wp_register_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr.custom.92164.js', [], '', false );

	/* Footer scripts */
	wp_register_script( 'pool-plugins', get_template_directory_uri() . '/assets/js/plugins.js', [ 'jquery' ], '', true );
	wp_register_script( 'pool-app', get_template_directory_uri() . '/assets/js/functions' . $min . '.js', [ 'jquery', 'kleo-plugins' ], '', true );
	// wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'pool-plugins' );
	wp_enqueue_script( 'pool-app' );

	$obj_array = [
		'ajaxurl'        =>  admin_url( 'admin-ajax.php' ),
		'loadingMessage' => '<i class="icon-refresh animate-spin"></i> ' . esc_html__( 'Sending info, please wait...', 'pool' ),
		'errorMessage'   => esc_html__('Sorry, an error occurred. Try again later.', 'pool')
	];
	$obj_array = apply_filters( 'kleo_localize_app', $obj_array );

	wp_localize_script( 'kleo-app', 'KLEO', $obj_array );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Register the styles.
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', [], '', 'all' );
	wp_register_style( 'pool', get_template_directory_uri() . '/assets/less/theme' . $min . '.css', [], '', 'all' );
	wp_register_style( 'pool-font-icons', sq_get_fonts_path() , [], '', 'all' );
	wp_register_style( 'pool-animate', get_template_directory_uri() . '/assets/css/animate.css', [], '', 'all' );
	wp_register_style( 'magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', [], '', 'all' );
	wp_register_style( 'outdatedbrowser', get_template_directory_uri() . '/assets/js/3rd-plugins/outdatedbrowser/outdatedbrowser.min.css', [], '', 'all' );
	wp_register_style( 'pool-style', CHILD_THEME_URI . '/style.css', [], '', 'all' );

	// Enqueue registered styles.
	wp_enqueue_style( 'bootstrap' );
	wp_enqueue_style( 'pool' );
	wp_enqueue_style( 'pool-font-icons' );
	wp_enqueue_style( 'pool-animate' );
	wp_enqueue_style( 'magnific-popup' );
	wp_enqueue_style( 'outdatedbrowser' );

}
add_action( 'wp_enqueue_scripts', 'pool_frontend_files' );

function sq_get_fonts_path() {

	$fonts_path = get_template_directory_uri() . '/assets/fonts/style.css';

	if ( is_child_theme() && file_exists( CHILD_THEME_DIR . '/assets/fonts/style.css' )) {
		$fonts_path = get_stylesheet_directory_uri() . '/assets/fonts/style.css';
	}

	return $fonts_path;
}

function pool_load_files_plugin_compat() {

	// Enqueue child theme style only if activated.
	if ( is_child_theme() ) {
		wp_enqueue_style( 'pool-style' );
	}
}
add_action( 'wp_enqueue_scripts', 'pool_load_files_plugin_compat', 1000 );

/***************************************************
:: ADMIN CSS
***************************************************/
function kleo_admin_styles() {
    wp_register_style( "kleo-admin", KLEO_LIB_URI . "/assets/admin-custom.css", false, "1.0", "all" );
    wp_enqueue_style( 'kleo-admin' );
}
add_action( 'admin_enqueue_scripts', 'kleo_admin_styles' );

/**
 * Customize wp-login.php
 */
function custom_login_css() {

	echo "\n<style>";
	echo '.login h1 a { background-image: url("'.sq_option('logo','none').'");background-size: contain;min-height: 88px;width:auto;}';
	echo '#login {padding: 20px 0 0;}';
	echo '.login #nav a, .login #backtoblog a {color:'.sq_option('header_primary_color').'!important;text-shadow:none;}';
	echo "</style>\n";

}
add_action( 'login_enqueue_scripts', 'custom_login_css' );

function kleo_new_wp_login_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'kleo_new_wp_login_url' );

function kleo_new_wp_login_title() {
	return get_option('blogname');
}
add_filter( 'login_headertitle', 'kleo_new_wp_login_title' );

if ( ! function_exists( '_wp_render_title_tag' ) ) {
    function kleo_slug_render_title() {
        ?>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
    <?php
    }
    add_action( 'wp_head', 'kleo_slug_render_title' );
}

if ( ! function_exists( 'kleo_wp_title' ) ) :
    /**
     * Creates a nicely formatted and more specific title element text
     * for output in head of document, based on current view.
     *
     * @since Kleo Framework 1.0
     *
     * @param string $title Default title text for current view.
     * @param string $sep Optional separator.
     * @return string Filtered title.
     */
    function kleo_wp_title( $title, $sep ) {

        global $paged, $page;

        if ( is_feed() ) {
            return $title;
		}

        // Add the site name.
        $title .= get_bloginfo( 'name' );

        // Add the site description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $title = "$title $sep $site_description";
		}

        // Add a page number if necessary.
        if ( $paged >= 2 || $page >= 2 ) {
            $title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'pool' ), max( $paged, $page ) );
        }

        return $title;
	}

    if ( ! function_exists( '_wp_render_title_tag' ) ) {
        add_filter( 'wp_title', 'kleo_wp_title', 10, 2 );
    }
endif;

if ( ! function_exists( 'kleo_the_attached_image' ) ) :
    /**
     * Print the attached image with a link to the next attached image.
     *
     * @since Kleo 1.0
     *
     * @return void
     */
    function kleo_the_attached_image() {
        $post = get_post();
        /**
         * Filter the default attachment size.
         *
         * @since Kleo 1.0
         *
         * @param array $dimensions {
         *     An array of height and width dimensions.
         *
         *     @type int $height Height of the image in pixels. Default 810.
         *     @type int $width  Width of the image in pixels. Default 810.
         * }
         */
        $attachment_size     = apply_filters( 'kleo_attachment_size', [ 810, 810 ] );
        $next_attachment_url = wp_get_attachment_url();

        /*
         * Grab the IDs of all the image attachments in a gallery so we can get the URL
         * of the next adjacent image in a gallery, or the first image (if we're
         * looking at the last image in a gallery), or, in a gallery of one, just the
         * link to that image file.
         */
        $attachment_ids = get_posts( [
            'post_parent'    => $post->post_parent,
            'fields'         => 'ids',
            'numberposts'    => -1,
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => 'ASC',
            'orderby'        => 'menu_order ID',
        ] );

        // If there is more than 1 attachment in a gallery.
        if ( count( $attachment_ids ) > 1 ) {

            foreach ( $attachment_ids as $attachment_id ) {
                if ( $attachment_id == $post->ID ) {
                    $next_id = current( $attachment_ids );
                    break;
                }
            }

            // Get the URL of the next image attachment.
            if ( $next_id ) {
                $next_attachment_url = get_attachment_link( $next_id );
            }

            // Or get the URL of the first image attachment.
            else {
                $next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
            }
        }

        printf( '<a href="%1$s" rel="attachment">%2$s</a>',
            esc_url( $next_attachment_url ),
            wp_get_attachment_image( $post->ID, $attachment_size )
        );
    }
endif;

/**
 * Modal AJAX login && modal lost password
 */
function kleo_login_settings( $kleo ) {

	// Settings sections.
    $kleo['sec']['kleo_section_login'] = [
        'title'    => esc_html__( 'Login redirect', 'pool' ),
        'priority' => 16
	];

    $kleo['set'][] = [
        'id'          => 'login_redirect',
        'title'       => esc_html__( 'Login page redirect', 'pool' ),
        'type'        => 'select',
        'default'     => 'default',
        'choices'     => [
			'default' => 'Default',
			'reload'  => 'Reload',
			'custom'  => 'Custom link'
		],
        'section'     => 'kleo_section_login',
        'description' => esc_html__( 'Default: WordPress default behaviour. Reload: will reload current page.', 'pool' ),
	];

    $kleo['set'][] = [
        'id'          => 'login_redirect_custom',
        'title'       => esc_html__( 'Custom link redirect', 'pool' ),
        'type'        => 'text',
        'default'     => '',
        'section'     => 'kleo_section_login',
        'description' => wp_kses(
			__( 'Set a link like http://yoursite.com/homepage for users to get redirected on login.<br> ' .
			'For more complex redirect logic please set Login redirect to Default WordPress and use Peter\'s redirect plugin or similar.', 'pool' ), [ 'br' => [] ]
		),
        'condition' => [
			'login_redirect',
			'custom'
		]
	];

    return $kleo;

}
add_filter( 'kleo_theme_settings', 'kleo_login_settings' );

function kleo_load_popups() {
    get_template_part( 'page-parts/general-popups' );
}
add_action( 'wp_footer', 'kleo_load_popups', 12 );

if ( ! function_exists( 'kleo_ajax_login' ) ) {
    function kleo_ajax_login() {

        // If not our action, bail out.
        if ( ! isset( $_POST['action'] ) || ( isset( $_POST['action'] ) && $_POST['action'] != 'kleoajaxlogin' ) ) {
            return false;
        }

        // If user is already logged in print a specific message.
        if ( is_user_logged_in() ) {
            $link = "javascript:window.location.reload();return false;";
            echo json_encode(
                [
                    'loggedin' => false,
                    'message' => '<i class="icon-info-outline"></i> ' .
                        wp_kses(
                            sprintf( __( 'You are already logged in. Please <a href="#" onclick="%s">refresh</a> page', 'pool' ), $link ),
                            [
								'a' => [
									'href'    => true,
									'onclick' => true
								]
							]
                        )
				]
            );
            die();
        }

        // Check the nonce, if it fails the function will break.
        check_ajax_referer( 'kleo-ajax-login-nonce', 'security-login' );

        // Nonce is checked, continue.
        $secure_cookie = '';

        // If the user wants ssl but the session is not ssl, force a secure cookie.
        if ( ! empty( $_POST['log'] ) && ! force_ssl_admin() ) {

			$user_name = sanitize_user( $_POST['log'] );

            if ( $user = get_user_by( 'login', $user_name ) ) {

                if ( get_user_option( 'use_ssl', $user->ID ) ) {

                    $secure_cookie = true;
                    force_ssl_admin( true );
                }
            }
        }

        if ( isset( $_REQUEST['redirect_to'] ) ) {

			$redirect_to = $_REQUEST['redirect_to'];

            // Redirect to https if user wants SSL.
            if ( $secure_cookie && false !== strpos( $redirect_to, 'wp-admin' ) )
				$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );

        } else {
            $redirect_to = '';
        }

		$user_signon = wp_signon( '', $secure_cookie );

        if ( is_wp_error( $user_signon ) ) {

            $error_msg = $user_signon->get_error_message();
            echo json_encode(
				[
					'loggedin' => false,
					'message'  => '<span class="wrong-response"><i class="icon-warning"></i> ' . wp_kses_data( $error_msg ) . '</span>'
				]
			);
			/**
			 * echo json_encode(
			 * 	[
			 * 		'loggedin' => false,
			 * 		'message'  => '<span class="wrong-response"><i class="icon-warning"></i> ' . esc_html__( 'Wrong username or password. Please try again.', 'pool' ) . '</span>'
			 * 	]
			 * );
			 */

        } else {

            if ( sq_option( 'login_redirect' , 'default' ) == 'reload' ) {

				$redirecturl = NULL;

            } else {

				$requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

                /**
                 * Filter the login redirect URL.
                 *
                 * @since 3.0.0
                 *
                 * @param string           $redirect_to           The redirect destination URL.
                 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
                 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
                 */
                $redirecturl = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $user_signon );

            }

            echo json_encode(
                [
                    'loggedin'    => true,
                    'redirecturl' => $redirecturl,
                    'message'     => '<span class="good-response"><i class="icon-check"></i> ' .
                        esc_html__( 'Login successful, redirecting...', 'pool' ) . '</span>'
                ]
            );
        }

        die();
	}
	add_action( 'init', 'kleo_ajax_login' );
}

if ( ! function_exists( 'kleo_lost_password_ajax' )) {
    function kleo_lost_password_ajax() {

        $errors = new WP_Error();

        if ( isset( $_POST ) ) {

            // Check the nonce, if it fails the function will break.
            check_ajax_referer( 'kleo-ajax-lost-pass-nonce', 'security_lost_pass' );

            if ( empty( $_POST['user_login'] ) ) {

                $errors->add(
                    'empty_username',
                    wp_kses_data( __( '<strong>ERROR</strong>: Enter a username or e-mail address.', 'default' ) )
				);

            } elseif ( strpos( $_POST['user_login'], '@' ) ) {

	            $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
                if ( empty( $user_data ) )
                    $errors->add(
                        'invalid_email',
                        wp_kses_data( __( '<strong>ERROR</strong>: There is no user registered with that email address.', 'default' ) )
					);

            } else {

                $login = trim( $_POST['user_login'] );
                $user_data = get_user_by( 'login', $login );
            }

	        /**
	         * Fires before errors are returned from a password reset request.
	         *
	         * @since 2.1.0
	         * @since 4.4.0 Added the `$errors` parameter.
	         *
	         * @param WP_Error $errors A WP_Error object containing any errors generated
	         *                         by using invalid credentials.
	         */
	        do_action( 'lostpassword_post', $errors );

            if ( $errors->get_error_code() ) {
                echo '<span class="wrong-response">' . $errors->get_error_message() . '</span>';
                die();
            }

            if ( ! $user_data ) {
                $errors->add(
                    'invalidcombo', wp_kses_data( __( '<strong>ERROR</strong>: Invalid username or e-mail.', 'default' ) )
                );
                echo '<span class="wrong-response">' . $errors->get_error_message() . '</span>';
                die();
            }

            // Redefining user_login ensures we return the right case in the email.
            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;
	        $key        = get_password_reset_key( $user_data );

	        if ( is_wp_error( $key ) ) {
		        echo '<span class="wrong-response">' . $key->get_error_message() . '</span>';
		        die();
	        }

            $message = esc_html__( 'Someone requested that the password be reset for the following account:', 'default' ) . "\r\n\r\n";
            $message .= network_home_url( '/' ) . "\r\n\r\n";
            $message .= sprintf( esc_html__( 'Username: %s', 'default' ), $user_login ) . "\r\n\r\n";
            $message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'default' ) . "\r\n\r\n";
            $message .= esc_html__( 'To reset your password, visit the following address:', 'default') . "\r\n\r\n";
            $message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

            if ( is_multisite() ) {
				$blogname = get_network()->site_name;
			} else {
                /*
                 * The blogname option is escaped with esc_html on the way into the database
                 * in sanitize_option we want to reverse this for the plain text arena of emails.
                 */
				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			}

            $title = sprintf( esc_html__( '[%s] Password Reset', 'default' ), $blogname );

	        /**
	         * Filters the subject of the password reset email.
	         *
	         * @since 2.8.0
	         * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
	         *
	         * @param string  $title      Default email title.
	         * @param string  $user_login The username for the user.
	         * @param WP_User $user_data  WP_User object.
	         */
	        $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

	        /**
	         * Filters the message body of the password reset mail.
	         *
	         * @since 2.8.0
	         * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
	         *
	         * @param string  $message    Default mail message.
	         * @param string  $key        The activation key.
	         * @param string  $user_login The username for the user.
	         * @param WP_User $user_data  WP_User object.
	         */
	        $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

            if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
                echo '<span class="wrong-response">' . esc_html__( 'Failure!', 'pool' );
                echo esc_html__( 'The e-mail could not be sent.', 'default' );
                echo '</span>';
                die();
            } else {
                echo '<span class="good-response">' . esc_html__( 'Email successfully sent!', 'pool' ) . '</span>';
                die();
            }
        }
        die();
    }
}
add_action( 'wp_ajax_kleo_lost_password', 'kleo_lost_password_ajax' );
add_action( 'wp_ajax_nopriv_kleo_lost_password', 'kleo_lost_password_ajax' );

// Custom redirect from Theme options - Login redirect.
if ( sq_option( 'login_redirect', 'default' ) == 'custom' && sq_option( 'login_redirect_custom', '' ) != '' ) {
    add_filter( 'login_redirect', 'kleo_custom_redirect', 12, 3 );
}

/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $requested_redirect_to URL for redirect
 * @param object $user Logged user's data.
 * @return string
 */
function kleo_custom_redirect( $redirect_to, $requested_redirect_to, $user  ) {

    if ( ( $requested_redirect_to == ''|| $requested_redirect_to != home_url() ) && ! is_wp_error( $user ) ) {
        $redirect_to = sq_option( 'login_redirect_custom', '' );
        $redirect_to = str_replace( '##member_name##', $user->user_login, $redirect_to );
    }
    return $redirect_to;
}

/**
 * Render the footer columns
 */
function add_footer_widgets_columns() {

	if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) ) {
		return;
	} ?>
    <div id="footer" class="footer-color border-top">
        <div class="container-full">
            <div class="template-page tpl-no">
                <div class="wrap-content">
                    <div class="row">
                        <div class="col-sm-3">
                            <div id="footer-sidebar-1" class="footer-sidebar widget-area" role="complementary">
                                <?php if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'footer-1' ) ) : endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div id="footer-sidebar-2" class="footer-sidebar widget-area" role="complementary">
								<?php if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'footer-2' ) ) : endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div id="footer-sidebar-3" class="footer-sidebar widget-area" role="complementary">
								<?php if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'footer-3' ) ) : endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div id="footer-sidebar-4" class="footer-sidebar widget-area" role="complementary">
								<?php if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'footer-4' ) ) : endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- #footer -->
    <?php
}
add_action( 'kleo_after_content', 'add_footer_widgets_columns', 20 );