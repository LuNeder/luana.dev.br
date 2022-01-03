<?php

namespace Unakit;

$unakit_show_preview_image = Customizer::get_mod( 'preview_thumbnails' ) && has_post_thumbnail();
$unakit_additional_post_classes = [ 'post--preview', ( $unakit_show_preview_image ? ' has_thumbnail' : '' ) ];
?>
<article id="post-<?php 
the_ID();
?>" <?php 
post_class( $unakit_additional_post_classes );
?>>
    <div class="post__excerpt">
        <?php 
get_template_part( 'template-parts/posts/header-preview' );
?>
        <section class="post__content">
            <?php 
?>
            <div class="post__content__text">
                <?php 
the_excerpt();
?>
            </div>
            <?php 

if ( $unakit_show_preview_image ) {
    ?>
                <div class="featured_image">
                    <?php 
    echo  ( has_post_thumbnail() ? the_post_thumbnail( 'medium' ) : '<img loading="lazy" src="' . esc_url( Customizer::get_mod( 'preview_thumbnail_placeholder' ) ) . '" alt="" role="presentation"/>' ) ;
    ?>
                </div>
            <?php 
}

?>
        </section>
        <?php 
get_template_part( 'template-parts/posts/footer-preview' );
?>
    </div>
</article><?php 