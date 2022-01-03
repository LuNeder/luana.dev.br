<?php

namespace Unakit;

/*
 * INIT Freemius SDK
 */

if ( !function_exists( 'unakit_freemius' ) ) {
    // Create a helper function for easy SDK access.
    function unakit_freemius()
    {
        global  $unakit_freemius ;
        
        if ( !isset( $unakit_freemius ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $unakit_freemius = fs_dynamic_init( array(
                'id'             => '8619',
                'slug'           => 'unakit',
                'type'           => 'theme',
                'public_key'     => 'pk_77bd85ff115463236630ab73fdaa0',
                'is_premium'     => false,
                'premium_suffix' => 'Professional',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 14,
                'is_require_payment' => false,
            ),
                'menu'           => array(
                'slug'           => 'unakit',
                'override_exact' => true,
                'parent'         => array(
                'slug' => 'themes.php',
            ),
            ),
                'navigation'     => 'tabs',
                'is_live'        => true,
            ) );
        }
        
        return $unakit_freemius;
    }
    
    // Init Freemius.
    unakit_freemius();
    // Signal that SDK was initiated.
    do_action( 'unakit_freemius_loaded' );
    unakit_freemius()->add_filter( 'redirect_on_activation', '__return_false' );
}

define( 'UNAKIT_CONTENT_WIDTH', 972 );
if ( !isset( $content_width ) ) {
    $content_width = UNAKIT_CONTENT_WIDTH;
}
/*
 * LOAD LIB
 */
include_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
include_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
include_once 'includes/class-accessible-walker-nav-menu.php';
include_once 'includes/customizer.php';
include_once 'includes/fonts.php';
include_once 'includes/meta.php';
include_once 'includes/class-widgets.php';
/*
 * LOAD RESOURCES
 */
function load_css()
{
    wp_register_style(
        'unakit_bootstrap_css',
        get_template_directory_uri() . '/resources/css/vendor/dist/bootstrap.min.css',
        array(),
        false,
        'all'
    );
    wp_enqueue_style( 'unakit_bootstrap_css' );
    wp_register_style(
        'unakit_main_css',
        get_template_directory_uri() . '/resources/css/dist/main.min.css',
        array( 'unakit_bootstrap_css' ),
        false,
        'all'
    );
    wp_enqueue_style( 'unakit_main_css' );
    wp_register_style(
        'fontawesome',
        get_template_directory_uri() . '/resources/vendor/fontawesome/css/all.min.css',
        array(),
        false,
        'all'
    );
    wp_enqueue_style( 'fontawesome' );
}

add_action( 'wp_enqueue_scripts', 'Unakit\\load_css' );
function load_js()
{
    wp_enqueue_script( 'jquery' );
    wp_register_script(
        'unakit_main_js',
        get_template_directory_uri() . '/resources/js/main.js',
        'jquery',
        false,
        true
    );
    wp_enqueue_script( 'unakit_main_js' );
    
    if ( Customizer::get_mod( 'add_polyfills' ) ) {
        wp_register_script(
            'css_vars_ponyfill_js',
            get_template_directory_uri() . '/resources/js/vendor/css-vars-ponyfill.js',
            false,
            false,
            true
        );
        wp_enqueue_script( 'css_vars_ponyfill_js' );
        wp_register_script(
            'unakit_legacy_vars',
            get_template_directory_uri() . '/resources/js/legacy-vars.js',
            [ 'css_vars_ponyfill_js', 'unakit_main_js' ],
            false,
            true
        );
        wp_enqueue_script( 'unakit_legacy_vars' );
    }

}

add_action( 'wp_enqueue_scripts', 'Unakit\\load_js' );
/*
 * LOAD EDITOR RESOURCES
 */
function add_editor_assets()
{
    // Load the theme styles within Gutenberg.
    wp_register_style(
        'unakit_editor_css',
        get_template_directory_uri() . '/resources/css/dist/style-editor.min.css',
        array(),
        false,
        'all'
    );
    wp_enqueue_style( 'unakit_editor_css' );
}

add_action( 'enqueue_block_editor_assets', 'Unakit\\add_editor_assets' );
/*
 * THEME OPTIONS
 */
function theme_support()
{
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', [
        'width'       => '40',
        'height'      => '40',
        'flex-width'  => true,
        'flex-height' => false,
    ] );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', [
        'comment-list',
        'comment-form',
        'search-form',
        'gallery',
        'caption',
        'style',
        'script'
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( "wp-block-styles" );
    add_theme_support( 'responsive-embeds' );
    if ( apply_filters( 'unakit_disable_custom_editor_colors', false ) ) {
        add_theme_support( 'disable-custom-colors' );
    }
    // Use classic widget editor for now
    remove_theme_support( 'widgets-block-editor' );
}

add_action( 'after_setup_theme', 'Unakit\\theme_support' );
function custom_customize_enqueue()
{
    wp_enqueue_style( 'unakit-custom-customize', get_template_directory_uri() . '/resources/css/dist/customizer.min.css' );
}

add_action( 'customize_controls_enqueue_scripts', 'Unakit\\custom_customize_enqueue' );
/*
 * COLOR PALETTE
 */
function color_palette()
{
    $neutral_colors = [
        'neutral_white' => [
        'name'         => __( 'Neutral White', 'unakit' ),
        'color'        => '#ffffff',
        'customizable' => false,
    ],
        'neutral_light' => [
        'name'         => __( 'Neutral Light', 'unakit' ),
        'color'        => '#e8e8e8',
        'customizable' => false,
    ],
        'neutral'       => [
        'name'         => __( 'Neutral', 'unakit' ),
        'color'        => '#9b9b9b',
        'customizable' => false,
    ],
        'neutral_dark'  => [
        'name'         => __( 'Neutral Dark', 'unakit' ),
        'color'        => '#333333',
        'customizable' => false,
    ],
        'neutral_black' => [
        'name'         => __( 'Neutral Black', 'unakit' ),
        'color'        => '#000000',
        'customizable' => false,
    ],
    ];
    $brand_colors = [
        'brand_primary'   => [
        'name'         => __( 'Brand Primary', 'unakit' ),
        'color'        => '#215eac',
        'customizable' => true,
    ],
        'brand_secondary' => [
        'name'         => __( 'Brand Secondary', 'unakit' ),
        'color'        => '#0ab58a',
        'customizable' => true,
    ],
        'brand_light'     => [
        'name'         => __( 'Brand Light', 'unakit' ),
        'color'        => '#cddbf4',
        'customizable' => true,
    ],
        'brand_dark'      => [
        'name'         => __( 'Brand Dark', 'unakit' ),
        'color'        => '#093268',
        'customizable' => true,
    ],
    ];
    $palette_colors = array_merge( $neutral_colors, $brand_colors );
    function apply_custom_palette( $palette_colors )
    {
        // Build editor_color_palette parameter
        $editor_color_palette = [];
        foreach ( $palette_colors as $slug => $args ) {
            if ( !empty($args) && isset( $args['name'] ) && isset( $args['color'] ) ) {
                $editor_color_palette[] = [
                    'name'  => $args['name'],
                    'slug'  => $slug,
                    'color' => $args['color'],
                ];
            }
        }
        add_theme_support( 'editor-color-palette', $editor_color_palette );
        return $palette_colors;
    }
    
    $palette_colors = apply_custom_palette( $palette_colors );
    // Print color CSS
    add_action( 'wp_head', function () use( $palette_colors ) {
        insert_color_palette_styles( $palette_colors );
    } );
    add_action( 'admin_head', function () use( $palette_colors ) {
        insert_color_palette_styles( $palette_colors );
    } );
}

add_action( 'after_setup_theme', 'Unakit\\color_palette' );
/**
 * Prints the css variable definitions.
 */
function insert_color_palette_styles( $colors )
{
    $colors = apply_custom_palette( $colors );
    ?>
	<style type="text/css" id="unakit-color-palette">
		:root {
			<?php 
    foreach ( $colors as $slug => $args ) {
        if ( !empty($args) && isset( $args['name'] ) && isset( $args['color'] ) ) {
            echo  '--col_' . esc_attr( $slug ) . ': ' . esc_attr( $args['color'] ) . ';' ;
        }
    }
    ?>
		}

		<?php 
    foreach ( $colors as $slug => $args ) {
        if ( !empty($args) && isset( $args['name'] ) && isset( $args['color'] ) ) {
            echo  sprintf( '.has-%2$s-color{color:var(--col_%1$s);}' . "\n" . '.has-%2$s-background-color{background-color:var(--col_%1$s);}' . "\n" . '.wp-block-cover.has-%2$s-background-color:before {background-color:var(--col_%1$s);}' . "\n" . '.wp-block-group.has-%2$s-background-color:before {background-color:var(--col_%1$s);}' . "\n", esc_attr( $slug ), esc_attr( strtr( $slug, '_', '-' ) ) ) ;
        }
    }
    ?>
	</style>
	<?php 
}

/*
 * MENUS
 */
function register_menus()
{
    register_nav_menus( array(
        'primary' => _x( 'Primary Menu', 'Menu Label', 'unakit' ),
    ) );
}

add_action( 'after_setup_theme', 'Unakit\\register_menus' );
/**
 * Reassign menus to renamed menu slugs
 */
function update_deprecated_menu_theme_locations()
{
    $menu_slug_renames = [
        'header-menu' => 'primary',
    ];
    $nav_menu_locations = get_theme_mod( 'nav_menu_locations', [] );
    foreach ( $menu_slug_renames as $old_slug => $new_slug ) {
        if ( isset( $nav_menu_locations[$old_slug] ) ) {
            
            if ( !isset( $nav_menu_locations[$new_slug] ) || $nav_menu_locations[$new_slug] === 0 ) {
                $nav_menu_locations[$new_slug] = $nav_menu_locations[$old_slug];
                unset( $nav_menu_locations[$old_slug] );
                set_theme_mod( 'nav_menu_locations', $nav_menu_locations );
            }
        
        }
    }
}

add_action( 'after_setup_theme', 'Unakit\\update_deprecated_menu_theme_locations' );
/*
 * WIDGET AREAS
 */
add_action( 'widgets_init', 'Unakit\\Widgets::init' );
/*
 * ADMIN BAR HEIGHT
 */
function admin_bar_height()
{
    $admin_bar_height = ( is_admin_bar_showing() ? 32 : 0 );
    $admin_bar_height_mobile = ( is_admin_bar_showing() ? 46 : 0 );
    ?>
	<style type="text/css">
		:root {
			--admin_bar_height: <?php 
    echo  esc_attr( $admin_bar_height ) ;
    ?>px;
			--admin_bar_height_mobile: <?php 
    echo  esc_attr( $admin_bar_height_mobile ) ;
    ?>px;
		}
	</style>
<?php 
}

add_action(
    'wp_head',
    'Unakit\\admin_bar_height',
    18,
    1
);
/*
 * ARCHIVES
 */
function read_more_text()
{
    $continue_reading = sprintf(
        /* translators: %s: Name of current post. */
        esc_html__( 'Continue Reading %s', 'unakit' ),
        the_title( '<span class="screen-reader-text">', '</span>', false )
    );
    return $continue_reading;
}

function excerpt_more( $more )
{
    
    if ( is_admin() ) {
        return $more;
        // Prevent modifying admin area.
    }
    
    return ' ... <span class="post__readmore post__readmore--excerpt"><a href="' . esc_url( get_the_permalink() ) . '">' . read_more_text() . '</a></span>';
}

add_filter( 'excerpt_more', 'Unakit\\excerpt_more' );
function content_more( $more )
{
    
    if ( is_admin() ) {
        return $more;
        // Prevent modifying admin area.
    }
    
    return '<div class="post__readmore post__readmore--content"><a class="post__readmore" href="' . esc_url( get_the_permalink() ) . '">' . read_more_text() . '</a></div>';
}

add_filter( 'the_content_more_link', 'Unakit\\content_more' );
add_action( 'after_setup_theme', 'Unakit\\Meta::init' );
/*
 * COMMENTS
 */
function enqueue_comments_reply()
{
    if ( get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

add_action( 'comment_form_before', 'Unakit\\enqueue_comments_reply' );
/*
 * BLOCK STYLES
 */
/**
 * Enqueue Block Styles Javascript
 */
function custom_gutenberg_scripts()
{
    wp_enqueue_script(
        'unakit_block_styles_script',
        get_template_directory_uri() . '/resources/js/block.js',
        array(
        'wp-blocks',
        'wp-i18n',
        'wp-dom-ready',
        'wp-edit-post'
    ),
        false
    );
}

add_action( 'enqueue_block_editor_assets', 'Unakit\\custom_gutenberg_scripts' );
/**
 * Enqueue Block Styles Stylesheet
 */
function custom_gutenberg_styles()
{
    wp_enqueue_style( 'unakit_block_styles_css', get_template_directory_uri() . '/resources/css/dist/block-styles.css' );
}

add_action( 'enqueue_block_assets', 'Unakit\\custom_gutenberg_styles' );
add_action( 'enqueue_block_editor_assets', 'Unakit\\custom_gutenberg_styles' );
/**
 * ADMIN PAGE
 */
/**
 * Add a submenu page in the theme menu
 */
function register_theme_page()
{
    add_theme_page(
        _x( 'Unakit Theme', 'Admin Page Title', 'unakit' ),
        _x( 'Unakit Theme', 'Admin Menu Slug', 'unakit' ),
        'edit_theme_options',
        'unakit',
        'Unakit\\theme_page_content',
        4
    );
}

add_action( 'admin_menu', 'Unakit\\register_theme_page' );
/**
 * Display the content of the admin page
 */
function theme_page_content()
{
    
    if ( isset( $_GET['tab'] ) ) {
        $active_tab = esc_attr( wp_unslash( $_GET['tab'] ) );
    } else {
        $active_tab = 'getting-started';
    }
    
    ?>
	<div class="wrap">
		<h1><?php 
    echo  esc_html( _x( 'Unakit Theme', 'Admin Page Title', 'unakit' ) ) ;
    ?></h1>
		<?php 
    settings_errors();
    ?>
		<h2 class="nav-tab-wrapper">
			<a href="?page=unakit&tab=getting-started" class="nav-tab <?php 
    echo  ( $active_tab == 'getting-started' ? 'nav-tab-active' : '' ) ;
    ?>">
				<?php 
    echo  esc_html( __( 'Getting Started', 'unakit' ) ) ;
    ?>
			</a>
		</h2>
		<?php 
    
    if ( $active_tab == 'getting-started' ) {
        ?>
			<div class="welcome-panel">
				<div class="welcome-panel-content">
					<h2>
						<?php 
        echo  esc_html( _x( 'Resources', 'Settings Page Box Title', 'unakit' ) ) ;
        ?>
					</h2>
					<p class="about-description">
						<?php 
        echo  esc_html( __( 'Find what you need quickly:', 'unakit' ) ) ;
        ?>
					</p>
					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column">
							<h3>
								<?php 
        echo  esc_html( __( 'Get Started', 'unakit' ) ) ;
        ?>
							</h3>
							<ul>
								<li>
									<a href="<?php 
        echo  esc_url( admin_url( 'customize.php' ) ) ;
        ?>">
										<?php 
        echo  esc_html( __( 'Customize Your Theme', 'unakit' ) ) ;
        ?>
									</a>
								</li>
								<li>
									<a href="<?php 
        echo  esc_url( admin_url( 'nav-menus.php' ) ) ;
        ?>">
										<?php 
        echo  esc_html( __( 'Edit the Menu', 'unakit' ) ) ;
        ?>
									</a>
								</li>
								<li>
									<a href="<?php 
        echo  esc_url( admin_url( 'widgets.php' ) ) ;
        ?>">
										<?php 
        echo  esc_html( __( 'Manage Widgets', 'unakit' ) ) ;
        ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="welcome-panel-column">
							<h3>
								<?php 
        echo  esc_html( __( 'Documentation', 'unakit' ) ) ;
        ?>
							</h3>
							<ul>
								<li>
									<a href="https://cebbinger.gitbook.io/unakit" target="_blank" rel="noopener noreferrer">
										<?php 
        echo  esc_html( __( 'User Documentation', 'unakit' ) ) ;
        ?>
									</a>
								</li>
								<li>
									<a href="https://cebbinger.gitbook.io/unakit/development" target="_blank" rel="noopener noreferrer">
										<?php 
        echo  esc_html( __( 'Developer Documentation', 'unakit' ) ) ;
        ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="welcome-panel-column welcome-panel-last">
							<h3>
								<?php 
        echo  esc_html( __( 'Support', 'unakit' ) ) ;
        ?>
							</h3>
							<ul>
								<li>
									<a href="<?php 
        echo  esc_url( admin_url( 'themes.php?page=unakit-contact' ) ) ;
        ?>">
										<?php 
        echo  esc_html( __( 'Contact Us', 'unakit' ) ) ;
        ?>
									</a>
								</li>
								<li>
									<a href="<?php 
        echo  esc_url( admin_url( 'themes.php?page=unakit-wp-support-forum' ) ) ;
        ?>" target="_blank" rel="noopener noreferrer">
										<?php 
        echo  esc_html( __( 'Support Forum (WordPress.org)', 'unakit' ) ) ;
        ?>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<?php 
    }
    
    ?>
	</div>
<?php 
}

/*
 * INTERNATIONALIZATION
 */
/**
 * Load Textdomain
 */
function theme_setup()
{
    load_theme_textdomain( 'unakit', get_template_directory() . '/languages' );
}

add_action( 'after_setup_theme', 'Unakit\\theme_setup' );