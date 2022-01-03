<?php

namespace Unakit;

class Font
{
    public  $family ;
    public  $weight ;
    public  $style ;
    /**
     * @param Font_Family $family
     * @param int $weight
     * @param string $style
     */
    function __construct( Font_Family $family, $weight = 400, $style = "normal" )
    {
        // Check if font family is available in the theme
        $family_is_available = false;
        foreach ( Font_Manager::$font_families as $f ) {
            
            if ( $f->slug === $family->slug ) {
                $family_is_available = true;
                break;
            }
        
        }
        if ( !$family_is_available ) {
            // Fall back to first font if any exist
            try {
                $family = Font_Manager::$font_families[0];
            } catch ( \OutOfBoundsException $e ) {
                throw new \Exception( "Cannot create font with family \"{$family->slug}\"." );
            }
        }
        $this->family = $family;
        // Validate and check availablility of weight
        if ( $weight < 1 || $weight > 1000 || !in_array( $weight, $family->weights, true ) ) {
            $weight = 400;
        }
        $this->weight = $weight;
        // Validate and check availablility of style
        if ( !($style === "italic" || $style === "normal") || $weight === 400 || !in_array( $style, $family->styles, true ) ) {
            $style === "normal";
        }
        $this->style = $style;
    }
    
    /**
     * Get the file name without extension
     */
    public function get_file_name()
    {
        return $this->family->slug . '-latin-' . (( $this->weight !== 400 ? $this->weight . (( $this->style === "italic" ? "italic" : "" )) : (( $this->style === "italic" ? "italic" : "regular" )) ));
    }
    
    /**
     * Get a font path with the given extension or all paths for all valid extensions
     * 
     * @param string $extension
     */
    public function get_paths( $extension )
    {
        $file_name = $this->get_file_name();
        // All extenstions
        if ( $extension === null || $extension === "" || !array_key_exists( $extension, Font_Manager::$font_types ) ) {
            return array_filter( Font_Manager::$font_paths, function ( $font_path ) use( $file_name ) {
                return str_contains( $font_path, $file_name );
            } );
        }
        // Given extension
        $full_file_name = $file_name . "." . $extension;
        foreach ( Font_Manager::$font_paths as $font_path ) {
            if ( str_contains( $font_path, $full_file_name ) ) {
                return $font_path;
            }
        }
        return [];
    }
    
    /**
     * Generates the escaped HTML preload tag for each preloading file type to insert in the document
     */
    public function create_preload_tag()
    {
        $tag = "";
        $file_name = $this->get_file_name();
        foreach ( Font_Manager::$preload_file_extensions as $extension ) {
            $tag .= sprintf( '<link rel="preload" as="font" crossorigin="anonymous" type="%1$s" href="%2$s">', esc_attr( Font_Manager::$font_types[$extension] ), esc_attr( get_template_directory_uri() . '/resources/fonts/' . $file_name . '.' . $extension ) );
        }
        return $tag;
    }

}
/**
 * Font Family available in the Unakit Theme
 */
class Font_Family
{
    public  $slug ;
    public  $label ;
    public  $weights ;
    public  $styles ;
    /**
     * @param string $slug
     * @param string $label
     * @param Array $weights
     * @param Array $styles
     */
    public function __construct(
        $slug,
        $label,
        $weights = array(),
        $styles = array()
    )
    {
        $this->slug = $slug;
        $this->label = $label;
        $this->weights = $weights;
        $this->styles = $styles;
    }

}
/**
 * Responsible for collecting information about fonts and optimizing fonts
 */
class Font_Manager
{
    /**
     * Slugs of available font families in the theme
     */
    public static  $font_family_slugs = array(
        'Source Sans Pro' => 'source-sans-pro-v13',
        'Montserrat'      => 'montserrat-v15',
        'Raleway'         => 'raleway-v18',
        'Nunito'          => 'nunito-v14',
        'Josefin Sans'    => 'josefin-sans-v16',
        'Teko'            => 'teko-v10',
        'Indie Flower'    => 'indie-flower-v12',
        'Oswald'          => 'oswald-v35',
    ) ;
    /**
     * Available font families in the theme
     */
    public static  $font_families = array() ;
    /**
     * IANA MIME Media Types for use in the preload tag
     * (compiled at https://stackoverflow.com/a/20723357)
     */
    public static  $font_types = array(
        "otf"   => "font/otf",
        "sfnt"  => "font/sfnt",
        "ttf"   => "font/ttf",
        "woff"  => "font/woff",
        "woff2" => "font/woff2",
        "eot"   => "application/vnd.ms-fontobject",
        "svg"   => "image/svg+xml",
    ) ;
    /**
     * List of all available font paths
     */
    public static  $font_paths = array() ;
    /**
     * Active fonts in the theme
     */
    public static  $active_fonts = array() ;
    /**
     * Which file types to preload
     */
    public static  $preload_file_extensions = array( 'woff2' ) ;
    public static function init()
    {
        self::fetch_font_paths();
        self::create_font_families();
        // Hook Font Preloading
        add_action( 'wp_head', 'Unakit\\Font_Manager::preload_fonts', 5 );
    }
    
    /**
     * Fetch currently used fonts (depending on theme mods)
     */
    public static function fetch_active_fonts()
    {
        self::$active_fonts = [];
        $font_family_heading = self::get_font_family_by_name( Customizer::get_mod( 'font_heading' ) );
        $font_weight_heading = Customizer::get_mod( 'font_heading_weight' );
        $font_style_heading = ( Customizer::get_mod( 'font_heading_italic' ) ? 'italic' : 'normal' );
        
        if ( $font_family_heading !== null && in_array( $font_weight_heading, $font_family_heading->weights ) ) {
            $heading_font = new Font( $font_family_heading, $font_weight_heading, $font_style_heading );
            self::$active_fonts[] = $heading_font;
        }
        
        $font_family_regular = self::get_font_family_by_name( Customizer::get_mod( 'font_regular' ) );
        $font_weight_regular = Customizer::get_mod( 'font_regular_weight' );
        
        if ( $font_family_regular !== null ) {
            $regular_font = new Font( $font_family_regular, $font_weight_regular );
            self::$active_fonts[] = $regular_font;
        }
    
    }
    
    /**
     * Create font family objects from available paths
     */
    private static function create_font_families()
    {
        foreach ( self::$font_paths as $font_path ) {
            $pathinfo = pathinfo( $font_path );
            $filename = $pathinfo['filename'];
            $split = array_map( function ( $s ) {
                return strrev( $s );
            }, explode( '-', strrev( $filename ), 3 ) );
            $slug = $split[2];
            $label = array_search( $slug, self::$font_family_slugs, true );
            if ( !$label ) {
                continue;
            }
            // Select or create family object
            $family = self::get_font_family_by_slug( $split[2] );
            
            if ( $family === null ) {
                $family = new Font_Family( $slug, $label );
                self::$font_families[] = $family;
            }
            
            // Add weight and style to family
            $weight_and_style = $split[0];
            $weight_matches = [];
            
            if ( preg_match( '/^[0-9]{3}/', $weight_and_style, $weight_matches ) ) {
                $weight = intval( $weight_matches[0] );
            } else {
                $weight = 400;
            }
            
            if ( !in_array( $weight, $family->weights ) ) {
                $family->weights[] = $weight;
            }
            $style_matches = [];
            
            if ( preg_match( '/[A-Za-z]+$/', $weight_and_style, $style_matches ) ) {
                $style = ( $style_matches[0] === "italic" ? "italic" : "normal" );
            } else {
                $style = "normal";
            }
            
            if ( !in_array( $style, $family->styles ) ) {
                $family->styles[] = $style;
            }
        }
    }
    
    /**
     * Get an available Unakit\Font_Family object with the given slug
     * 
     * @param string $slug
     */
    public static function get_font_family_by_slug( $slug )
    {
        foreach ( self::$font_families as $font_family ) {
            if ( $font_family instanceof Font_Family && $font_family->slug === $slug ) {
                return $font_family;
            }
        }
        return null;
    }
    
    /**
     * Get an available Unakit\Font_Family object with the given name
     * 
     * @param string $name
     */
    public static function get_font_family_by_name( $name )
    {
        foreach ( self::$font_families as $font_family ) {
            if ( $font_family instanceof Font_Family && $font_family->label === $name ) {
                return $font_family;
            }
        }
        return null;
    }
    
    /**
     * Insert Preload Tags
     */
    public static function preload_fonts()
    {
        self::fetch_active_fonts();
        self::print_all_preload_tags();
    }
    
    /**
     * Generate all preloading HTML tags
     */
    public static function print_all_preload_tags()
    {
        foreach ( self::$active_fonts as $active_font ) {
            echo  $active_font->create_preload_tag() ;
        }
    }
    
    /**
     * Gathers all files' paths the theme's font directories
     */
    private static function fetch_font_paths()
    {
        $directories = apply_filters( 'unakit_font_directories', [ get_template_directory() . '/resources/fonts/' ] );
        $font_paths = [];
        foreach ( $directories as $font_dir ) {
            $wp_filesystem = new \WP_Filesystem_Direct( null );
            $dirlist = $wp_filesystem->dirlist( $font_dir );
            if ( $dirlist ) {
                $font_paths = array_merge( $font_paths, array_map( function ( $file ) use( $font_dir ) {
                    return $font_dir . $file['name'];
                }, $dirlist ) );
            }
        }
        self::$font_paths = $font_paths;
    }

}
Font_Manager::init();