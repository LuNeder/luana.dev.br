<?php

namespace Unakit;

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php 

if ( have_comments() ) {
    ?>
        <h2 class="comments-title">
            <?php 
    $unakit_comments_number = get_comments_number();
    
    if ( '1' === $unakit_comments_number ) {
        // translators: %s: post title
        printf( esc_html( _x( 'One Reply to "%s"', 'comments title', 'unakit' ) ), '<span>' . esc_html( get_the_title() ) . '</span>' );
    } else {
        printf(
            // translators: %1$s: number of comments, %2$s: post title
            esc_html( _nx(
                '%1$s Reply to "%2$s"',
                '%1$s Replies to "%2$s"',
                $unakit_comments_number,
                'comments title',
                'unakit'
            ) ),
            esc_html( number_format_i18n( $unakit_comments_number ) ),
            '<span>' . esc_html( get_the_title() ) . '</span>'
        );
    }
    
    ?>
        </h2>

        <ol class="comment-list">
            <?php 
    wp_list_comments( array(
        'style'       => 'ol',
        'avatar_size' => 48,
    ) );
    ?>
        </ol>

        <?php 
    
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
        ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h1 class="screen-reader-text section-heading"><?php 
        esc_html_e( 'Comment navigation', 'unakit' );
        ?></h1>
                <div class="nav-previous"><?php 
        previous_comments_link( esc_html__( '&larr; Older Comments', 'unakit' ) );
        ?></div>
                <div class="nav-next"><?php 
        next_comments_link( esc_html__( 'Newer Comments &rarr;', 'unakit' ) );
        ?></div>
            </nav>
        <?php 
    }
    
    ?>

        <?php 
    
    if ( !comments_open() && get_comments_number() ) {
        ?>
            <p class="no-comments"><?php 
        echo  esc_html( apply_filters( 'unakit_comments_closed_message', __( 'Comments are closed.', 'unakit' ) ) ) ;
        ?></p>
        <?php 
    }
    
    ?>

    <?php 
}

?>

    <?php 
comment_form();
?>

</div>
<?php 