<?php

namespace Unakit;

class Widgets
{
    public static  $footer_widget_columns ;
    public static function get_footer_widget_columns()
    {
        return self::$footer_widget_columns;
    }
    
    public static function init()
    {
        self::$footer_widget_columns = 3;
        for ( $i = 0 ;  $i < self::$footer_widget_columns ;  $i++ ) {
            $number = $i + 1;
            register_sidebar( array(
                // translators: 1: Number of footer column
                'name'          => sprintf( esc_html__( 'Footer %1d', 'unakit' ), $number ),
                'id'            => 'footer_' . $number,
                // translators: 1: Number of footer column
                'description'   => sprintf( esc_html__( '%1d. Widget Column in the Footer', 'unakit' ), $number ),
                'before_widget' => '<div class="widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget__title">',
                'after_title'   => '</h2>',
            ) );
        }
        if ( apply_filters( 'unakit_experimental_header_widget', false ) === true ) {
            register_sidebar( array(
                'name'          => esc_html__( 'Header-Menu', 'unakit' ),
                'id'            => 'header_menu',
                'description'   => esc_html__( 'Widget Area in the Header Menu', 'unakit' ),
                'before_widget' => '<div class="widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget__title">',
                'after_title'   => '</h2>',
            ) );
        }
    }

}