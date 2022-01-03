<?php

namespace Unakit;

$unakit_post_has_tags = boolval(get_the_tags());
$unakit_post_comments_number = get_comments_number();

?>
<div class="meta">
    <?php if (Meta::is_visible('datetime')) : ?>
        <span class="meta__datetime"><span class="meta__title"><i class="fas fa-calendar-day"></i><?php echo esc_html(Meta::title('datetime')); ?></span><a href="<?php echo the_permalink(); ?>"><?php get_template_part('template-parts/datetime'); ?></a></span>
    <?php endif;
    if (Meta::is_visible('author')) : ?>
        <span class="meta__author"><span class="meta__title"><i class="fas fa-user"></i><?php echo esc_html(Meta::title('author')); ?></span><?php the_author_posts_link(); ?></span>
    <?php endif;
    if ($unakit_post_has_tags && Meta::is_visible('tags')) : ?>
        <span class="meta__tags"><span class="meta__title"><i class="fas fa-tags"></i><?php echo esc_html(Meta::title('tags')); ?></span><?php the_tags('<span class="meta__list meta__list--tags">', '', '</span>'); ?></span>
    <?php endif;
    if ($unakit_post_comments_number > 0 && Meta::is_visible('comments')) : ?>
        <span class="meta__comments"><span class="meta__title"><i class="fas fa-comments"></i><?php echo esc_html(Meta::title('comments')); ?></span><a href="<?php echo the_permalink() . '/#comments'; ?>"><?php echo esc_html($unakit_post_comments_number); ?></a></span>
    <?php endif;
    if (current_user_can('edit_post', $post->ID)) : ?>
        <span class="meta__edit"><span class="meta__title"><i class="fas fa-pen"></i></span><?php edit_post_link(apply_filters('unakit_edit_post_link_text', null)); ?></span>
    <?php endif; ?>
</div>