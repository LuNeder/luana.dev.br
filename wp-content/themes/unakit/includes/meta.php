<?php

namespace Unakit;

class Meta
{
    public static  $visibility ;
    public static function get_visibility()
    {
        return self::$visibility;
    }
    
    public static function is_visible( $meta_type )
    {
        return ( isset( self::$visibility[$meta_type] ) ? self::$visibility[$meta_type] : false );
    }
    
    public static  $titles ;
    public static function get_titles()
    {
        return self::$titles;
    }
    
    public static function title( $meta_type )
    {
        return ( isset( self::$titles[$meta_type] ) ? self::$titles[$meta_type] : '' );
    }
    
    public static function init()
    {
        self::$visibility = [
            'datetime'   => true,
            'author'     => true,
            'tags'       => true,
            'categories' => true,
            'comments'   => true,
        ];
        self::$titles = [
            'datetime'   => _x( 'Published: ', 'Post Meta Label for date, time or date & time published', 'unakit' ),
            'author'     => _x( 'Author: ', 'Post Meta Label for author', 'unakit' ),
            'tags'       => _x( 'Tags: ', 'Post Meta Label for tags', 'unakit' ),
            'categories' => _x( 'Categories: ', 'Post Meta Label for categories', 'unakit' ),
            'comments'   => _x( 'Comments: ', 'Post Meta Label for comments number', 'unakit' ),
        ];
    }

}