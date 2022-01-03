<?php

namespace Unakit;

class Contact_Link
{
    protected  $id ;
    protected  $label ;
    protected  $icon_class ;
    protected  $control_type ;
    protected  $example ;
    protected  $sanitize_callback ;
    protected  $url_callback ;
    protected  $default ;
    protected static  $setting_prefix = 'contact_' ;
    /**
     * @param  string $id  Id for the contact platform. Also used to create prefixed settings id for database.
     * @param  string $label  Displayed label for the contact platform
     * @param  string $example  Example for valid input
     * @param  string $icon_class  CSS class value for Font Awesome icon
     * @param  string $control_type  WP Customizer control type (see https://developer.wordpress.org/reference/classes/wp_customize_control/__construct/#parameters)
     * @param  callable $sanitize_callback  WP Customizer sanitization callback (see https://developer.wordpress.org/themes/theme-security/data-sanitization-escaping/)
     * @param  callable $url_callback  URL creation callback. Uses sanitized input if empty or null
     * @param  mixed $default  Default input value
     */
    function __construct(
        $id,
        $label,
        $example,
        $icon_class = 'fas fa-link',
        $control_type = 'url',
        $sanitize_callback = 'esc_url_raw',
        $url_callback = null,
        $default = ''
    )
    {
        $this->id = $id;
        $this->label = $label;
        $this->icon_class = $icon_class;
        $this->control_type = $control_type;
        $this->example = $example;
        $this->sanitize_callback = $sanitize_callback;
        $this->url_callback = $url_callback;
        $this->default = $default;
    }
    
    /**
     * Echoes the HTML code for displaying the contact link
     */
    public function the_link()
    {
        echo  $this->get_the_link() ;
    }
    
    /**
     * Returns the escaped HTML code for displaying the contact link
     */
    public function get_the_link()
    {
        if ( Customizer::get_mod( $this->get_the_setting_id() ) == '' ) {
            return '';
        }
        ob_start();
        ?>
		<div class="social__link <?php 
        echo  esc_attr( $this->id ) ;
        ?>">
			<a href="<?php 
        echo  $this->get_the_url() ;
        ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php 
        echo  esc_attr( $this->label ) ;
        ?>">
				<i class="<?php 
        echo  esc_attr( $this->icon_class ) ;
        ?>"></i>
			</a>
		</div>
		<?php 
        return ob_get_clean();
    }
    
    /**
     * Returns the escaped contact URL
     */
    public function get_the_url()
    {
        
        if ( $this->url_callback == null ) {
            return esc_url_raw( Customizer::get_mod( $this->get_the_setting_id() ) );
        } else {
            return esc_url_raw( call_user_func( $this->url_callback, Customizer::get_mod( $this->get_the_setting_id() ) ) );
        }
    
    }
    
    /**
     * Returns a prefixed settings id
     */
    public function get_the_setting_id()
    {
        return self::$setting_prefix . $this->id;
    }
    
    /**
     * Registers the link in the customizer
     */
    public function register_customizer_control( $section, $priority )
    {
        global  $wp_customize ;
        $has_example = esc_html( $this->example ) != '';
        $wp_customize->add_setting( $this->get_the_setting_id(), array(
            'default'           => esc_attr( $this->default ),
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
            'sanitize_callback' => $this->sanitize_callback,
        ) );
        $wp_customize->add_control( $this->get_the_setting_id(), array(
            'label'       => esc_html( $this->label ),
            'description' => ( !$has_example ? '' : '<small>' . esc_html( $this->example ) . '</small>' ),
            'section'     => $section,
            'settings'    => $this->get_the_setting_id(),
            'type'        => esc_attr( $this->control_type ),
            'priority'    => $priority,
        ) );
    }

}
class Customizer
{
    protected static  $initialized = false ;
    /**
     * Is the Customizer initialized yet?
     */
    public static function is_initialized()
    {
        return self::$initialized;
    }
    
    /**
     * Default values for the customizer.
     */
    protected static  $defaults = array() ;
    /**
     * Get the default values for the customizer. Returns an array of strings.
     */
    public static function get_defaults()
    {
        return self::$defaults;
    }
    
    protected static  $body_fonts = array() ;
    /**
     * Body font options
     */
    public static function get_body_fonts()
    {
        return self::$body_fonts;
    }
    
    protected static  $heading_fonts = array() ;
    /**
     * Heading font options
     */
    public static function get_heading_fonts()
    {
        return self::$heading_fonts;
    }
    
    protected static  $contact_links = array() ;
    /**
     * Available contact links
     */
    public static function get_contact_links()
    {
        return self::$contact_links;
    }
    
    /**
     * Returns a theme mod value
     */
    public static function get_mod( $setting_name, $default = false )
    {
        self::init();
        if ( isset( self::$defaults[$setting_name] ) ) {
            $default = self::$defaults[$setting_name];
        }
        return get_theme_mod( $setting_name, $default );
    }
    
    /**
     * Initialize the Customizer and its values
     * @param bool $force Force initialization even if already initialized before
     * @return bool true, if successfully initialized, else false (e.g. skipped)
     */
    public static function init( $force = false )
    {
        if ( self::is_initialized() ) {
            return false;
        }
        self::$defaults = [
            'round_logo'                 => false,
            'header_height'              => 5,
            'fixed_header'               => true,
            'header_shadow'              => false,
            'nav_style'                  => 'responsive',
            'wide_footer'                => false,
            'body_background'            => '#eee',
            'header_background'          => '#fff',
            'header_background_a'        => 1,
            'footer_background'          => '#fff',
            'text_color'                 => '#333',
            'heading_color'              => '#333',
            'font_heading'               => 'Source Sans Pro',
            'font_regular'               => 'Source Sans Pro',
            'link_color'                 => '#25a',
            'header_text_color'          => '#333',
            'footer_text_color'          => '#333',
            'footer_heading_color'       => '#333',
            'footer_link_color'          => '#333',
            'enable_post_meta_preview'   => true,
            'enable_post_meta_full'      => true,
            'nav_menu_label_description' => false,
            'underline_links'            => true,
            'add_polyfills'              => false,
        ];
        self::$contact_links = [
            new Contact_Link(
            'facebook',
            __( 'Facebook', 'unakit' ),
            _x( 'https://www.facebook.com/NAME', 'Customizer Facebook URL example', 'unakit' ),
            'fab fa-facebook'
        ),
            new Contact_Link(
            'twitter',
            __( 'Twitter', 'unakit' ),
            _x( 'https://www.twitter.com/NAME', 'Customizer Twitter URL example', 'unakit' ),
            'fab fa-twitter'
        ),
            new Contact_Link(
            'instagram',
            __( 'Instagram', 'unakit' ),
            _x( 'https://www.instagram.com/NAME', 'Customizer Instagram URL example', 'unakit' ),
            'fab fa-instagram'
        ),
            new Contact_Link(
            'youtube',
            __( 'YouTube', 'unakit' ),
            _x( 'https://www.youtube.com/channel/ID', 'Customizer YouTube URL example', 'unakit' ),
            'fab fa-youtube'
        )
        ];
        $font_fallback = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"';
        self::$body_fonts = apply_filters( 'unakit_customizer_body_fonts', [
            'Source Sans Pro' => '"Source Sans Pro", ' . $font_fallback,
            'Montserrat'      => '"Montserrat", ' . $font_fallback,
            'Raleway'         => '"Raleway", ' . $font_fallback,
            'Nunito'          => '"Nunito", ' . $font_fallback,
            'Sans-Serif'      => 'sans-serif',
            'Serif'           => 'serif',
        ] );
        self::$heading_fonts = apply_filters( 'unakit_customizer_heading_fonts', [
            'Source Sans Pro' => '"Source Sans Pro", ' . $font_fallback,
            'Montserrat'      => '"Montserrat", ' . $font_fallback,
            'Raleway'         => '"Raleway", ' . $font_fallback,
            'Nunito'          => '"Nunito", ' . $font_fallback,
            'Josefin Sans'    => '"Josefin Sans", ' . $font_fallback,
            'Teko'            => '"Teko", ' . $font_fallback,
            'Indie Flower'    => '"Indie Flower", ' . $font_fallback,
            'Oswald'          => '"Oswald", ' . $font_fallback,
            'Sans-Serif'      => 'sans-serif',
            'Serif'           => 'serif',
        ] );
        $initialized = true;
        return true;
    }

}
add_action( 'init', 'Unakit\\Customizer::init' );
function sanitize_select( $input, $setting )
{
    $input = wp_filter_post_kses( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * see https://wordpress.stackexchange.com/a/225862
 */
function sanitize_float( $input )
{
    return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}

function sanitize_checkbox( $input )
{
    return $input == true;
}

function sanitize_phone( $phone )
{
    return preg_replace( '/[^\\d+-]/', '', $phone );
}

function create_mailto_link( $address )
{
    $address = preg_replace( '/^(?!mailto?:)/', 'mailto:', sanitize_email( $address ) );
    return esc_url_raw( $address );
}

function create_phone_link( $phone_number )
{
    $phone_number = preg_replace( '/^(?!tel?:)/', 'tel:', sanitize_phone( $phone_number ) );
    return esc_url_raw( $phone_number );
}

function customize_register( $wp_customize )
{
    $defaults = Customizer::get_defaults();
    $heading_fonts = Customizer::get_heading_fonts();
    $body_fonts = Customizer::get_body_fonts();
    $available_font_weights_heading = [
        '300' => esc_html_x( 'Light', 'font weight', 'unakit' ),
        '400' => esc_html_x( 'Normal', 'font weight', 'unakit' ),
        '600' => esc_html_x( 'Semi Bold', 'font weight', 'unakit' ),
        '700' => esc_html_x( 'Bold', 'font weight', 'unakit' ),
        '900' => esc_html_x( 'Black', 'font weight', 'unakit' ),
    ];
    $available_font_weights_regular = [
        '300' => esc_html_x( 'Light', 'font weight', 'unakit' ),
        '400' => esc_html_x( 'Normal', 'font weight', 'unakit' ),
        '600' => esc_html_x( 'Semi Bold', 'font weight', 'unakit' ),
    ];
    class Separator_Control extends \WP_Customize_Control
    {
        public  $has_line ;
        public function render_content()
        {
            if ( $this->has_line ) {
                ?>
				<hr class="customize-separator-line">
			<?php 
            }
            
            if ( $this->label != '' ) {
                ?>
				<span class="customize-control-title customize-separator-title" style="font-size:15px"><?php 
                echo  esc_html( $this->label ) ;
                ?></span>
			<?php 
            }
            
            
            if ( $this->description != '' ) {
                ?>
				<span class="description customize-control-description customize-separator-description">
					<p><?php 
                echo  wp_kses_data( $this->description ) ;
                ?></p>
				</span>
			<?php 
            }
        
        }
    
    }
    class Font_Control extends \WP_Customize_Control
    {
        public function render_content()
        {
            $input_id = 'customize-input-' . $this->id;
            $description_id = 'customize-description-' . $this->id;
            $describedby_attr = ( !empty($this->description) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '' );
            if ( empty($this->choices) ) {
                return;
            }
            ?>
			<?php 
            
            if ( !empty($this->label) ) {
                ?>
				<label for="<?php 
                echo  esc_attr( $input_id ) ;
                ?>" class="customize-control-title"><?php 
                echo  esc_html( $this->label ) ;
                ?></label>
			<?php 
            }
            
            ?>
			<?php 
            
            if ( !empty($this->description) ) {
                ?>
				<span id="<?php 
                echo  esc_attr( $description_id ) ;
                ?>" class="description customize-control-description"><?php 
                echo  esc_html( $this->description ) ;
                ?></span>
			<?php 
            }
            
            ?>

			<select id="<?php 
            echo  esc_attr( $input_id ) ;
            ?>" class="customize-font-control" <?php 
            echo  esc_attr( $describedby_attr ) ;
            ?> <?php 
            $this->link();
            ?>>
				<?php 
            foreach ( $this->choices as $value => $label ) {
                echo  '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . 'style="font-family:' . esc_html( $value ) . '">' . esc_html( $label ) . '</option>' ;
            }
            ?>
			</select>
	<?php 
        }
    
    }
    /**
     * Adds a readonly seperator control to the customizer for organizing long sections. Outputs will be escaped.
     */
    function insert_separator(
        $section = '',
        $priority = '',
        $label = '',
        $description = '',
        $show_line = true
    )
    {
        global  $wp_customize ;
        $id = 'separator' . $section . $priority;
        $wp_customize->add_setting( $id, array(
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( new Separator_Control( $wp_customize, $id, array(
            'section'     => $section,
            'priority'    => $priority,
            'label'       => $label,
            'description' => $description,
            'has_line'    => $show_line,
        ) ) );
    }
    
    /**
     * SECTIONS
     */
    $wp_customize->add_section( 'background', [
        'title'       => esc_html_x( 'Background', 'Customizer Section', 'unakit' ),
        'description' => esc_html_x( 'Personalize your theme by adjusting the background color individually for different parts of the website.', 'Customizer Section', 'unakit' ),
        'priority'    => 75,
        'capability'  => 'edit_theme_options',
    ] );
    $wp_customize->add_section( 'typography', [
        'title'       => esc_html_x( 'Typography', 'Customizer Section', 'unakit' ),
        'description' => esc_html_x( 'Tune the text on your website.', 'Customizer Section', 'unakit' ),
        'priority'    => 76,
        'capability'  => 'edit_theme_options',
    ] );
    $wp_customize->add_section( 'header', [
        'title'       => esc_html_x( 'Header', 'Customizer Section', 'unakit' ),
        'description' => esc_html_x( "Configure the site's header.", 'Customizer Section', 'unakit' ),
        'priority'    => 77,
        'capability'  => 'edit_theme_options',
    ] );
    $wp_customize->add_section( 'footer', [
        'title'       => esc_html_x( 'Footer', 'Customizer Section', 'unakit' ),
        'description' => esc_html_x( "Configure the site's footer section.", 'Customizer Section', 'unakit' ),
        'priority'    => 78,
        'capability'  => 'edit_theme_options',
    ] );
    $wp_customize->add_section( 'archives', [
        'title'       => esc_html_x( 'Blog & Archives', 'Customizer Section', 'unakit' ),
        'description' => esc_html_x( 'Appearance of the blog and archive pages.', 'Customizer Section', 'unakit' ),
        'priority'    => 170,
        'capability'  => 'edit_theme_options',
    ] );
    $wp_customize->add_section( 'accessibility', [
        'title'       => esc_html_x( 'Accessibility & Compatibility', 'Customizer Section', 'unakit' ),
        'description' => esc_html_x( 'Help visitors to navigate the website more easily.', 'Customizer Section', 'unakit' ),
        'priority'    => 190,
        'capability'  => 'edit_theme_options',
    ] );
    /**
     * BACKGROUND
     */
    // Body Background
    $wp_customize->add_setting( 'body_background', array(
        'default'           => $defaults['body_background'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'body_background', array(
        'label'    => esc_html__( 'Body Background', 'unakit' ),
        'section'  => 'background',
        'priority' => 110,
        'settings' => 'body_background',
    ) ) );
    // Header Background
    $wp_customize->add_setting( 'header_background', array(
        'default'           => $defaults['header_background'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'header_background', array(
        'label'    => esc_html__( 'Header Background', 'unakit' ),
        'section'  => 'background',
        'priority' => 120,
        'settings' => 'header_background',
    ) ) );
    // Footer Background
    $wp_customize->add_setting( 'footer_background', array(
        'default'           => $defaults['footer_background'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'footer_background', array(
        'label'    => esc_html__( 'Footer Background', 'unakit' ),
        'section'  => 'background',
        'priority' => 130,
        'settings' => 'footer_background',
    ) ) );
    /**
     * TYPOGRAPHY
     */
    // Heading font
    $wp_customize->add_setting( 'font_heading', array(
        'default'           => $defaults['font_heading'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_select',
    ) );
    $wp_customize->add_control( new Font_Control( $wp_customize, 'font_heading', [
        'label'    => esc_html__( 'Heading Font', 'unakit' ),
        'section'  => 'typography',
        'priority' => 70,
        'settings' => 'font_heading',
        'type'     => 'select',
        'choices'  => array_combine( array_keys( $heading_fonts ), array_keys( $heading_fonts ) ),
    ] ) );
    // Body font
    $wp_customize->add_setting( 'font_regular', array(
        'default'           => $defaults['font_regular'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_select',
    ) );
    $wp_customize->add_control( new Font_Control( $wp_customize, 'font_regular', [
        'label'    => esc_html__( 'Body Font', 'unakit' ),
        'section'  => 'typography',
        'priority' => 80,
        'settings' => 'font_regular',
        'type'     => 'select',
        'choices'  => array_combine( array_keys( $body_fonts ), array_keys( $body_fonts ) ),
    ] ) );
    insert_separator( 'typography', 95, _x( 'Page Content Area', 'Customizer Separator', 'unakit' ) );
    // Heading color
    $wp_customize->add_setting( 'heading_color', array(
        'default'           => $defaults['heading_color'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'heading_color', array(
        'label'    => esc_html__( 'Heading Color', 'unakit' ),
        'section'  => 'typography',
        'priority' => 110,
        'settings' => 'heading_color',
    ) ) );
    // Text color
    $wp_customize->add_setting( 'text_color', array(
        'default'           => $defaults['text_color'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'text_color', array(
        'label'    => esc_html__( 'Text Color', 'unakit' ),
        'section'  => 'typography',
        'priority' => 120,
        'settings' => 'text_color',
    ) ) );
    // Link color
    $wp_customize->add_setting( 'link_color', array(
        'default'           => $defaults['link_color'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'link_color', array(
        'label'    => esc_html__( 'Link Color', 'unakit' ),
        'section'  => 'typography',
        'priority' => 130,
        'settings' => 'link_color',
    ) ) );
    insert_separator( 'typography', 200, _x( 'Header', 'Customizer Typography Separator', 'unakit' ) );
    // Header Text color
    $wp_customize->add_setting( 'header_text_color', array(
        'default'           => $defaults['header_text_color'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'header_text_color', array(
        'label'    => esc_html__( 'Header Text Color', 'unakit' ),
        'section'  => 'typography',
        'priority' => 210,
        'settings' => 'header_text_color',
    ) ) );
    insert_separator( 'typography', 300, _x( 'Footer', 'Customizer Separator', 'unakit' ) );
    // Footer Heading color
    $wp_customize->add_setting( 'footer_heading_color', array(
        'default'           => $defaults['footer_heading_color'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'footer_heading_color', array(
        'label'    => esc_html__( 'Footer Heading Color', 'unakit' ),
        'section'  => 'typography',
        'priority' => 310,
        'settings' => 'footer_heading_color',
    ) ) );
    // Footer Text color
    $wp_customize->add_setting( 'footer_text_color', array(
        'default'           => $defaults['footer_text_color'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'footer_text_color', array(
        'label'    => esc_html__( 'Footer Text Color', 'unakit' ),
        'section'  => 'typography',
        'priority' => 320,
        'settings' => 'footer_text_color',
    ) ) );
    // Footer Link color
    $wp_customize->add_setting( 'footer_link_color', array(
        'default'           => $defaults['footer_link_color'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'footer_link_color', array(
        'label'    => esc_html__( 'Footer Link Color', 'unakit' ),
        'section'  => 'typography',
        'priority' => 330,
        'settings' => 'footer_link_color',
    ) ) );
    /*
     * BRANDING
     */
    // Round Logo
    $wp_customize->add_setting( 'round_logo', array(
        'default'           => $defaults['round_logo'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'round_logo', array(
        'label'       => esc_html__( 'Round Logo', 'unakit' ),
        'description' => esc_html__( 'Round the corners of the logo.', 'unakit' ),
        'section'     => 'title_tagline',
        'settings'    => 'round_logo',
        'type'        => 'checkbox',
        'priority'    => 8,
    ) );
    /**
     * HEADER
     */
    // Header Height
    $wp_customize->add_setting( 'header_height', array(
        'default'           => $defaults['header_height'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'header_height', array(
        'label'       => esc_html__( 'Header Height', 'unakit' ),
        'section'     => 'header',
        'settings'    => 'header_height',
        'type'        => 'range',
        'priority'    => 10,
        'input_attrs' => array(
        'min'  => 3,
        'max'  => 6,
        'step' => 1,
    ),
    ) );
    // Fixed Header
    $wp_customize->add_setting( 'fixed_header', array(
        'default'           => $defaults['fixed_header'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'fixed_header', array(
        'label'       => esc_html__( 'Fixed Header', 'unakit' ),
        'description' => esc_html__( 'Keep the header visible on top of the screen when scrolling.', 'unakit' ),
        'section'     => 'header',
        'settings'    => 'fixed_header',
        'type'        => 'checkbox',
        'priority'    => 15,
    ) );
    // Header Shadow
    $wp_customize->add_setting( 'header_shadow', array(
        'default'           => $defaults['header_shadow'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'header_shadow', array(
        'label'       => esc_html__( 'Header Shadow', 'unakit' ),
        'description' => esc_html__( 'Display a shadow below the header to separate it from the background', 'unakit' ),
        'section'     => 'header',
        'settings'    => 'header_shadow',
        'type'        => 'checkbox',
        'priority'    => 18,
    ) );
    // Navigation Style
    $wp_customize->add_setting( 'nav_style', array(
        'default'           => $defaults['nav_style'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_select',
    ) );
    $wp_customize->add_control( 'nav_style', array(
        'label'    => esc_html__( 'Navigation Style', 'unakit' ),
        'section'  => 'header',
        'priority' => 117,
        'settings' => 'nav_style',
        'type'     => 'select',
        'choices'  => array(
        'responsive' => esc_html_x( 'Responsive', 'Navigation Style', 'unakit' ),
        'off-canvas' => esc_html_x( 'Off-Canvas', 'Navigation Style', 'unakit' ),
    ),
    ) );
    /**
     * FOOTER
     */
    $wp_customize->add_setting( 'wide_footer', array(
        'default'           => $defaults['wide_footer'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'wide_footer', array(
        'label'       => esc_html__( 'Wide Footer', 'unakit' ),
        'description' => esc_html__( 'Use a wider footer than the default content width.', 'unakit' ),
        'section'     => 'footer',
        'settings'    => 'wide_footer',
        'type'        => 'checkbox',
        'priority'    => 18,
    ) );
    /**
     * SOCIAL
     */
    insert_separator( 'title_tagline', 100, _x( 'Contact Links', 'Customizer Separator', 'unakit' ) );
    $current_contact_priority = 110;
    foreach ( Customizer::get_contact_links() as $contact_link ) {
        $contact_link->register_customizer_control( 'title_tagline', $current_contact_priority );
        $current_contact_priority += 5;
    }
    /**
     * BLOG SETTINGS
     */
    $wp_customize->add_setting( 'preview_thumbnails', array(
        'default'           => false,
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'preview_thumbnails', array(
        'label'       => esc_html__( 'Show Preview Thumbnails', 'unakit' ),
        'description' => esc_html__( 'Displays the featured image as thumbnail in post previews.', 'unakit' ),
        'section'     => 'archives',
        'settings'    => 'preview_thumbnails',
        'type'        => 'checkbox',
        'priority'    => 110,
    ) );
    insert_separator( 'archives', 200, _x( 'Meta Information', 'Customizer Separator', 'unakit' ) );
    // Post Meta in Preview
    $wp_customize->add_setting( 'enable_post_meta_preview', array(
        'default'           => $defaults['enable_post_meta_preview'],
        'type'              => 'theme_mod',
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'enable_post_meta_preview', array(
        'label'       => esc_html__( 'Post meta in archive', 'unakit' ),
        'description' => esc_html__( 'Shows meta information of a post when previewed in archives.', 'unakit' ),
        'section'     => 'archives',
        'settings'    => 'enable_post_meta_preview',
        'type'        => 'checkbox',
        'priority'    => 210,
    ) );
    // Post Meta in Full
    $wp_customize->add_setting( 'enable_post_meta_full', array(
        'default'           => $defaults['enable_post_meta_full'],
        'type'              => 'theme_mod',
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'enable_post_meta_full', array(
        'label'       => esc_html__( 'Post meta in post', 'unakit' ),
        'description' => esc_html__( 'Shows meta information of a post when viewed in full.', 'unakit' ),
        'section'     => 'archives',
        'settings'    => 'enable_post_meta_full',
        'type'        => 'checkbox',
        'priority'    => 220,
    ) );
    /**
     * Accessibility
     */
    // Navigation Toggle Description (Mobile)
    $wp_customize->add_setting( 'nav_menu_label_description', array(
        'default'           => $defaults['nav_menu_label_description'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'nav_menu_label_description', array(
        'label'       => esc_html__( 'Menu Toggle Label', 'unakit' ),
        'description' => esc_html__( 'Display a label below the menu icon on mobile devices.', 'unakit' ),
        'section'     => 'accessibility',
        'settings'    => 'nav_menu_label_description',
        'type'        => 'checkbox',
        'priority'    => 10,
    ) );
    // Underline links
    $wp_customize->add_setting( 'underline_links', array(
        'default'           => $defaults['underline_links'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'underline_links', array(
        'label'       => esc_html__( 'Underline Links', 'unakit' ),
        'description' => esc_html__( 'Improve the visibility of links with underlines', 'unakit' ),
        'section'     => 'accessibility',
        'settings'    => 'underline_links',
        'type'        => 'checkbox',
        'priority'    => 20,
    ) );
    insert_separator(
        'accessibility',
        200,
        _x( 'Browser Compatibility', 'Customizer Separator', 'unakit' ),
        _x( 'The following options can improve compatibility with older browsers, like Internet Explorer 11, while trading performance.', 'Customizer Separator', 'unakit' )
    );
    // Add polyfills
    $wp_customize->add_setting( 'add_polyfills', array(
        'default'           => $defaults['add_polyfills'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'Unakit\\sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'add_polyfills', array(
        'label'       => esc_html__( 'Add Polyfills', 'unakit' ),
        'description' => esc_html__( 'Add additional scripts that allow older browsers to display modern features more consistently.', 'unakit' ),
        'section'     => 'accessibility',
        'settings'    => 'add_polyfills',
        'type'        => 'checkbox',
        'priority'    => 210,
    ) );
}

add_action( 'customize_register', 'Unakit\\customize_register' );
/**
 * https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php/#31934345
 */
function hexToRgb( $hex, $alpha = false )
{
    $hex = str_replace( '#', '', $hex );
    $length = strlen( $hex );
    $rgb['r'] = hexdec( ( $length == 6 ? substr( $hex, 0, 2 ) : (( $length == 3 ? str_repeat( substr( $hex, 0, 1 ), 2 ) : 0 )) ) );
    $rgb['g'] = hexdec( ( $length == 6 ? substr( $hex, 2, 2 ) : (( $length == 3 ? str_repeat( substr( $hex, 1, 1 ), 2 ) : 0 )) ) );
    $rgb['b'] = hexdec( ( $length == 6 ? substr( $hex, 4, 2 ) : (( $length == 3 ? str_repeat( substr( $hex, 2, 1 ), 2 ) : 0 )) ) );
    $rgb['a'] = $alpha;
    return $rgb;
}

function customizer_css_variables()
{
    $defaults = Customizer::get_defaults();
    $heading_fonts = Customizer::get_heading_fonts();
    $body_fonts = Customizer::get_body_fonts();
    $header_colors = hexToRgb( Customizer::get_mod( 'header_background' ), Customizer::get_mod( 'header_background_a' ) );
    $site_header_bg_color = sprintf(
        'rgba(%d, %d, %d, %.2f)',
        $header_colors['r'],
        $header_colors['g'],
        $header_colors['b'],
        $header_colors['a']
    );
    $css_vars = [
        'background'                => Customizer::get_mod( 'body_background' ),
        'text_color'                => Customizer::get_mod( 'text_color' ),
        'heading_color'             => Customizer::get_mod( 'heading_color' ),
        'header_background'         => $site_header_bg_color,
        'header_background_opaque'  => Customizer::get_mod( 'header_background' ),
        'header_text_color'         => Customizer::get_mod( 'header_text_color' ),
        'header_height'             => get_theme_mod( 'header_height', "{$defaults['header_height']}" ) . 'rem',
        'header_overlap_height'     => ( Customizer::get_mod( 'fixed_header' ) ? get_theme_mod( 'header_height', "{$defaults['header_height']}" ) . 'rem' : 0 ),
        'footer_background'         => Customizer::get_mod( 'footer_background' ),
        'footer_text_color'         => Customizer::get_mod( 'footer_text_color' ),
        'footer_heading_color'      => Customizer::get_mod( 'footer_heading_color' ),
        'footer_link_text_color'    => Customizer::get_mod( 'footer_link_color' ),
        'link_text_color'           => Customizer::get_mod( 'link_color' ),
        'link_decoration'           => ( Customizer::get_mod( 'underline_links' ) ? 'underline' : 'none' ),
        'link_decoration_highlight' => ( Customizer::get_mod( 'underline_links' ) ? 'none' : 'underline' ),
        'font_heading'              => $heading_fonts[Customizer::get_mod( 'font_heading' )],
        'font_heading_weight'       => Customizer::get_mod( 'font_heading_weight' ),
        'font_heading_style'        => ( Customizer::get_mod( 'font_heading_italic' ) ? 'italic' : 'normal' ),
        'font_regular'              => $body_fonts[Customizer::get_mod( 'font_regular' )],
        'font_regular_weight'       => Customizer::get_mod( 'font_regular_weight' ),
    ];
    ?>
	<style type="text/css" id="unakit-customizer-variables">
		/*Unakit Customizer Variables*/
		:root {
			<?php 
    foreach ( $css_vars as $name => $value ) {
        if ( $value !== false && $value !== '' ) {
            echo  "--" . esc_html( $name ) . ":" . wp_kses_post( $value ) . ";\n" ;
        }
    }
    ?>
		}
	</style>
<?php 
}

function customizer_css()
{
    $defaults = Customizer::get_defaults();
    customizer_css_variables();
    ?>
	<style type="text/css" id="unakit-customizer-css">
		/*Unakit Customizer Styles*/
		<?php 
    
    if ( Customizer::get_mod( 'header_background_a' ) !== '1' ) {
        ?>

		/* Header position */
		.site__header {
			position: absolute;
		}

		.page__content:first-child>*:first-child {
			padding-top: var(--header_height, <?php 
        echo  esc_attr( $defaults['header_height'] ) ;
        ?>);
		}

		.main__header {
			margin-top: var(--header_height, <?php 
        echo  esc_attr( $defaults['header_height'] ) ;
        ?>);
		}

		<?php 
    }
    
    
    if ( Customizer::get_mod( 'fixed_header' ) ) {
        ?>

		/* Header position */
		.site__header {
			position: fixed;
		}

		.page__content:first-child>*:first-child {
			padding-top: var(--header_overlap_height, <?php 
        echo  esc_attr( $defaults['header_height'] ) ;
        ?>);
		}

		<?php 
    }
    
    if ( Customizer::get_mod( 'header_shadow' ) ) {
        ?>

		/* Header shadow */
		.site__header {
			box-shadow: 0 0 3rem .5rem rgba(0, 0, 0, .05);
		}

		.submenu {
			box-shadow: 0 0 3rem .5rem rgba(0, 0, 0, .05);
		}

		<?php 
    }
    if ( Customizer::get_mod( 'round_logo' ) ) {
        ?>

		/* Logo */
		.website-logo {
			border-radius: 1.25rem;
		}

		<?php 
    }
    if ( is_admin_bar_showing() ) {
        ?>

		/* Fix admin bar layout on no-scroll*/
		html.no-scroll {
			margin-top: 0 !important;
			padding-top: 46px;
		}

		<?php 
    }
    if ( Customizer::get_mod( 'wide_footer' ) ) {
        ?>

		/* wide footer */
		footer.site__footer>.site__footer__content {
			max-width: var(--container_width_wide);
		}

		<?php 
    }
    ?>
	</style>
<?php 
}

add_action(
    'wp_head',
    'Unakit\\customizer_css',
    20,
    1
);
function customizer_editor_css()
{
    customizer_css_variables();
    ?>
	<style type="text/css" id="unakit-customizer-edior-styles">
		/* Background */
		.block-editor-writing-flow {
			background-color: <?php 
    echo  esc_html( Customizer::get_mod( 'body_background' ) ) ;
    ?>;
		}

		/* Text */
		.block-editor-writing-flow {
			color: <?php 
    echo  esc_html( Customizer::get_mod( 'text_color' ) ) ;
    ?>;
		}

		.block-editor-writing-flow hr {
			border-top-color: <?php 
    echo  esc_html( Customizer::get_mod( 'text_color' ) ) ;
    ?>;
		}

		.block-editor-writing-flow a,
		.block-editor-writing-flow a:link,
		.block-editor-writing-flow a:visited,
		.block-editor-writing-flow a:hover,
		.block-editor-writing-flow a:focus,
		.block-editor-writing-flow a:active {
			color: <?php 
    echo  esc_html( Customizer::get_mod( 'link_color' ) ) ;
    ?>;
		}
	</style>
<?php 
}

add_action(
    'admin_head',
    'Unakit\\customizer_editor_css',
    20,
    1
);