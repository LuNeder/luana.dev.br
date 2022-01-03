<?php

namespace Unakit;

get_header(); ?>
<main class="site__main">
    <section class="archive">
        <header id="content" class="archive__header main__header content-wrapper">
            <h1><?php bloginfo('name') ?></h1>
        </header>
        <?php get_template_part('template-parts/archive/list'); ?>
    </section>
</main>
<?php get_footer(); ?>