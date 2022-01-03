<?php

namespace Unakit;

?>

<header id="content" class="post__header<?php 
if ( is_single() ) {
    echo  ' main__header' ;
}
?> content-wrapper">
    <?php 

if ( has_post_thumbnail() ) {
    ?>
        <div class="featured_image"><?php 
    the_post_thumbnail( add_filter( 'unakit_post_thumbnail_size', 'post-thumbnail' ) );
    ?></div>
    <?php 
}


if ( Meta::is_visible( 'categories' ) ) {
    ?>
        <p class="meta"><?php 
    echo  get_the_category_list( ' ' ) ;
    ?></p>
    <?php 
}

?>
    <h1>
        <?php 
the_title();
?>
    </h1>
    <?php 
edit_post_link( apply_filters( 'unakit_edit_post_link_text', null ), '<div class="post-edit-link-container">', '</div>' );
?>
</header><?php 