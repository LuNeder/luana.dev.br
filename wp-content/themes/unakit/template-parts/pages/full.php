<?php

namespace Unakit;

?>
<article id="page-<?php the_ID(); ?>" class="page<?php if (has_post_thumbnail()) echo ' has_thumbnail'; ?>">
    <header id="content" class="page__header main__header content-wrapper">
        <?php if (has_post_thumbnail()) : ?>
            <div class="featured_image"><?php the_post_thumbnail(); ?></div>
        <?php endif; ?>
        <?php edit_post_link(apply_filters('unakit_edit_post_link_text', null), '<div class="post-edit-link-container">', '</div>'); ?>
        <h1>
            <?php the_title(); ?>
        </h1>
    </header>
    <section class="page__content content-wrapper">
        <?php the_content(); ?>
        <?php wp_link_pages(); ?>
        <?php
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
    </section>
</article>