<?php

namespace Unakit;

?>

<header class="post__header">
    <p class="meta"><span class="screen-reader-text"><?php 
echo  esc_html_x( 'Categorized as: ', 'Screen Reader Text before list of categories', 'unakit' ) ;
?></span><?php 
echo  get_the_category_list( ' ' ) ;
?></p>
    <a href="<?php 
the_permalink();
?>">
        <h2>
            <?php 
if ( is_sticky() ) {
    echo  '<i class="fa fa-thumbtack sticky"><span class="screen-reader-text">' . esc_html_x( 'Sticky Post', 'Screen Reader Text before Sticky Title', 'unakit' ) . '</span></i>' ;
}
the_title();
?>
        </h2>
    </a>
</header><?php 