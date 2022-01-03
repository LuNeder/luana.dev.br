<?php

namespace Unakit;

$unakit_contact_links = Customizer::get_contact_links();
$unakit_enable_social = array_filter( $unakit_contact_links, function ( $contact_link ) {
    $id = $contact_link->get_the_setting_id();
    return Customizer::get_mod( $id ) != '';
} );
$unakit_enable_cta = !Customizer::get_mod( 'hide_cta' );
$unakit_cta_label = Customizer::get_mod( 'cta_label' );
$unakit_cta_url = Customizer::get_mod( 'cta_url' );
$unakit_new_tab = Customizer::get_mod( 'cta_new_tab' );
if ( $unakit_enable_social || $unakit_enable_cta ) {
    ?> <div class="header_links">
    <?php 
}
?>

    <div class="widget-container<?php 
if ( !is_active_sidebar( 'header_menu' ) ) {
    echo  ' widget-container--empty' ;
}
?>">
        <?php 
if ( is_active_sidebar( 'header_menu' ) ) {
    dynamic_sidebar( 'header_menu' );
}
?>
    </div>

    <?php 
// Social Icons

if ( $unakit_enable_social ) {
    // if enabled and a link or email is given
    ?>
        <div class="social">
            <?php 
    foreach ( $unakit_contact_links as $unakit_contact_link ) {
        $unakit_contact_link->the_link();
    }
    ?>
        </div>
        <?php 
}
