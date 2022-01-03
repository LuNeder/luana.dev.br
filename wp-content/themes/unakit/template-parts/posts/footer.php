<?php

namespace Unakit;

?>
<footer class="post__footer content-wrapper">
    <?php if (Customizer::get_mod('enable_post_meta_full') == true) {
        get_template_part('template-parts/posts/meta');
    } ?>
    <?php
    if (comments_open() || get_comments_number()) :
        comments_template();
    endif;
    ?>
</footer>