<?php

namespace Unakit;

?>
<footer class="site__footer">
	<div class="site__footer__content">
		<?php 
$unakit_footer_widgets_active = false;
for ( $i = 0 ;  $i < Widgets::$footer_widget_columns ;  $i++ ) {
    
    if ( is_active_sidebar( 'footer_' . ($i + 1) ) ) {
        $unakit_footer_widgets_active = true;
        break;
    }

}

if ( $unakit_footer_widgets_active ) {
    ?>
			<div class="row unakit_widgets">
				<?php 
    for ( $i = 0 ;  $i < Widgets::$footer_widget_columns ;  $i++ ) {
        $number = $i + 1;
        
        if ( is_active_sidebar( 'footer_' . $number ) ) {
            ?>
						<div class="unakit_widgets__container col-sm">
							<?php 
            dynamic_sidebar( 'footer_' . $number );
            ?>
						</div>
				<?php 
        }
    
    }
    ?>
			</div>
		<?php 
}

?>
	</div>
	<?php 
?>
		<div class="unakit_theme_contentinfo text-center">
			<p><small><a href="https://ebbinger.com/unakit" target="_blank" rel="noopener noreferrer"><?php 
echo  esc_html__( 'Designed with Unakit for WordPress', 'unakit' ) ;
?></a></small></p>
		</div>
	<?php 
?>
</footer>

<?php 
wp_footer();
?>
</body>

</html><?php 