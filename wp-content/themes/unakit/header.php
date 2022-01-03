<?php

namespace Unakit;

?>
<!doctype html>
<html <?php 
language_attributes();
?>>

<head>
	<meta charset="<?php 
bloginfo( 'charset' );
?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php 
wp_head();
?>
</head>

<body <?php 
body_class();
?>>
	<?php 
( function_exists( 'wp_body_open' ) ? wp_body_open() : do_action( 'wp_body_open' ) );
?>
	<header class="site__header">
		<a class="skip-link" href="#content"><?php 
echo  esc_html( 'Skip to content', 'unakit' ) ;
?></a>
		<?php 
get_template_part( 'template-parts/header', 'navbar' );
?>
	</header><?php 