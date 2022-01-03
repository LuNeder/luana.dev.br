<?php

namespace Unakit;

$unakit_branding_title = apply_filters('unakit_branding_title', get_bloginfo('name'));
$unakit_branding_tagline = apply_filters('unakit_branding_tagline', get_bloginfo('description'));
$unakit_nav_style_class = Customizer::get_mod('nav_style') ? ' nav-style-' . Customizer::get_mod('nav_style') : '';
?>
<div class="site__header__content<?php echo esc_attr($unakit_nav_style_class); ?>">
	<a href="<?php echo esc_url(apply_filters('unakit_header_title_link', home_url('/'))); ?>" class="branding">
		<?php // Website Logo
		if (has_custom_logo()) :
			$unakit_custom_logo_id = Customizer::get_mod('custom_logo');
			$unakit_custom_logo_data = wp_get_attachment_image_src($unakit_custom_logo_id, 'small');
			$unakit_custom_logo_url = $unakit_custom_logo_data[0];
		?>
			<img src="<?php echo esc_url($unakit_custom_logo_url); ?>" alt="" class="branding__logo<?php if (Customizer::get_mod('round_logo')) echo ' branding__logo--round'; ?>" />
		<?php
		endif;
		?>
		<div class="branding__text" rel="home">
			<span class="branding__text__title<?php if (unakit_freemius()->can_use_premium_code__premium_only() && Customizer::get_mod('hide_title')) {
													echo " screen-reader-text";
												}; ?>"><?php echo esc_html($unakit_branding_title); ?></span>
			<?php if ($unakit_branding_tagline != '') : ?>
				<span class="branding__text__tagline<?php if (unakit_freemius()->can_use_premium_code__premium_only() && Customizer::get_mod('hide_tagline')) {
														echo " screen-reader-text";
													}; ?>"><?php echo esc_html($unakit_branding_tagline); ?></span>
			<?php endif; ?>
		</div>
	</a>
	<input aria-hidden="true" id="nav-toggle-input" class="nav-toggle-input" type="checkbox" name="nav-toggle" value="show-navigation" autocomplete="off">
	<label aria-hidden="true" class="nav-toggle" for="nav-toggle-input">
		<span class="nav-toggle-icon"></span>
		<span class="nav-toggle-description<?php echo (Customizer::get_mod('nav_menu_label_description') ? '' : ' hidden'); ?>"><?php echo esc_html(_x('Menu', 'Mobile Navigation Menu Label', 'unakit')); ?></span>
	</label>
	<div class="nav-bg-overlay nav-close-area"></div>
	<nav class="menu header-menu primary" role="navigation" aria-label="<?php echo esc_attr_x('Primary Menu', 'Navigation Aria Label', 'unakit'); ?>">

		<?php
		// Header Menu
		if (has_nav_menu('primary')) {
			wp_nav_menu(
				[
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'clearfix',
					'walker'         => new Accessible_Walker_Nav_Menu(),
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				]
			);
		}

		get_template_part('template-parts/header', 'links');
		?>

	</nav>
</div>