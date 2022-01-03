<?php

namespace Unakit;

/* Template Name: Title In Content */

get_header();
?>
<main class="site__main">
    <?php
    if (is_page()) :
        the_post();
        get_template_part('template-parts/pages/notitle');
    else :
    ?>
        <section class="archive">
            <header id="content" class="archive__header main__header content-wrapper">
                <h1><?php single_post_title(); ?></h1>
            </header>
            <?php get_template_part('template-parts/archive/list'); ?>
        </section>
    <?php
    endif;
    ?>
</main>
<?php get_footer(); ?>