<?php

namespace Unakit;

?>
<footer class="post__footer">
    <?php if (Customizer::get_mod('enable_post_meta_preview')) {
        get_template_part('template-parts/posts/meta');
    } ?>
</footer>