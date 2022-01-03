<?php

namespace Unakit;

?>
<section class="archive__list content-wrapper">
    <?php 

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'template-parts/posts/preview' );
    }
} else {
    ?>
        <p>
            <?php 
    $unakit_empty_message = __( 'Sorry, no posts matched your criteria.', 'unakit' );
    echo  esc_html( $unakit_empty_message ) ;
    ?>
        </p>
    <?php 
}

the_posts_pagination( array(
    'type' => 'list',
) );
?>
</section>
<?php 