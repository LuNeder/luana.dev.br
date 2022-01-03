<?php

namespace Unakit;

get_header(); ?>
<main class="site__main">
    <?php
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/posts/full');
        endwhile;
    else :
        esc_html_e('Sorry, could not find that post.', 'unakit');
    endif;
    ?>
</main>
<?php get_footer();
