<?php

namespace Unakit;

get_header();
?>
<main class="site__main error-404" role="main">

	<section class="page__content content-wrapper container">

		<header id="content" class="page__header main__header">
			<h1>
				<?php 
$unakit_title_404 = __( 'Error: 404', 'unakit' );
echo  esc_html( $unakit_title_404 ) ;
?>
			</h1>
		</header>
		<?php 
?>
		<p>
			<?php 
echo  sprintf(
    // translators: %s: Link to homepage
    wp_kses_post( apply_filters( 'unakit_404_message', __( 'This page does not exist (anymore). Do you want to visit the <a href="%s">Homepage</a> instead?', 'unakit' ) ) ),
    esc_url( home_url( '/' ) )
) ;
?>
		</p>
		<?php 
get_search_form();
?>

	</section>

</main>

<?php 
get_footer();