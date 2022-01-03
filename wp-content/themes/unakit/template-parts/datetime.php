<?php

namespace Unakit;

$unakit_display_date = apply_filters( 'unakit_datetime_display_date', true );
$unakit_display_time = apply_filters( 'unakit_datetime_display_time', true );

if ( $unakit_display_date ) {
    ?>
    <!-- Prevent Whitespace
    --><span class="post__footer__date"><?php 
    echo  wp_kses_data( get_the_date( get_option( 'date_format' ) ) ) ;
    ?></span>
    <!--
--><?php 
}


if ( $unakit_display_date && $unakit_display_time ) {
    ?>
    <!--
    --><span class="post__footer__divider"><?php 
    echo  wp_kses_data( apply_filters( 'unakit_datetime_divider', ' - ' ) ) ;
    ?></span>
    <!--
--><?php 
}


if ( $unakit_display_time ) {
    ?>
    <!--
    --><span class="post__footer__time"><?php 
    echo  wp_kses_data( get_the_time( get_option( 'time_format' ) ) ) ;
    ?></span>
    <!--
--><?php 
}
