<?php

namespace Unakit;

$unakit_additional_post_classes = (has_post_thumbnail()) ? ' has_thumbnail' : '';
?>
<article <?php post_class($unakit_additional_post_classes); ?>>
    <?php get_template_part('template-parts/posts/header'); ?>
    <section class="post__content content-wrapper">
        <?php the_content(); ?>
        <?php wp_link_pages(); ?>
    </section>
    <?php get_template_part('template-parts/posts/footer'); ?>
</article>