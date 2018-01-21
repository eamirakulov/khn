<!-- <div class="main-menu"> -->
<div class="container">
	<div class="header_v3_container">
		<div class="width-logo table-cell sm-logo">
			<?php
			do_action( 'thim_logo' );
			do_action( 'thim_sticky_logo' );
			?>
		</div>

		<?php if ( is_active_sidebar( 'header' ) ) : ?>
			<div class="sidebar-header">
				<?php dynamic_sidebar( 'header' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( wp_is_mobile() ) : ?>
			<div class="menu-mobile-effect navbar-toggle" data-effect="mobile-effect">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</div>
		<?php endif; ?>
	</div>
</div>
