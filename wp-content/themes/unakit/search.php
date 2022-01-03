<?php

namespace Unakit;

get_header();
?>
<main class="site__main">
    <section class="archive search">
        <header id="content" class="archive__header main__header content-wrapper">
            <h1>
                <?php 
// translators: %s: search term
$unakit_title_search = __( 'Search Results for: %s', 'unakit' );
printf( esc_html( $unakit_title_search ), '<span class="searchterm">' . get_search_query() . '</span>' );
?>
            </h1>
            <?php 
get_search_form();
?>
        </header>
        <?php 
get_template_part( 'template-parts/archive/list' );
?>
    </section>
</main>
<?php 
get_footer();