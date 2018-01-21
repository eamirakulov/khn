<?php
global $wp_query, $wp;
$cert_name = $_REQUEST['view-cert'];
$cert      = learn_press_get_certificate_by_name(urlencode($_REQUEST['view-cert']));
$cert_data = LP_Addon_Certificates::instance()->get_json( $cert->ID, $_REQUEST['course_id'], learn_press_get_profile_user() );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php _e( 'View Certificate', 'learnpress-certificates' ); ?></title>

	<link rel="stylesheet" id="open-sans-css" href="//fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&amp;subset=latin%2Clatin-ext&amp;" type="text/css" media="all">
	<?php do_action( 'wp_enqueue_scripts' ); ?>
	<?php wp_print_styles( 'learn-press-frontend-cert' ); ?>
	<?php wp_print_scripts( 'learn-press-frontend-cert' ); ?>
	<?php wp_print_scripts( 'learn-press-global' ); ?>

	<style>
		html, body {
			margin: 0;
			padding: 0;
		}

		@page {
			size: auto;
			margin: 5mm;
		}
	</style>
	<script>
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' );?>',
			cert_url = '<?php echo learn_press_certificate_permalink( $cert->ID, $_REQUEST['course_id'] );?>';

		'undefined' != typeof jQuery && (jQuery(function ($) {
			var timer = null,
				$body = $(document.body),
				$html = $('html'),
				pwindow = parent.window,
				loop = 0,
				_resize = function () {
					if (loop > 5) {
						loop = 0;
						return;
					}
					var h = $html.outerHeight();
					window.LP.sendMessage({height: h}, window);
					loop++;
				},
				_onresize = function () {
					timer && clearTimeout(timer);
					timer = setTimeout(_resize, 300);
				};
			if (pwindow && window.self !== window.top) {
				LP.Hook.addAction('learn_press_receive_message', function (data) {
					var $ifr = pwindow.jQuery('#learn-press-cert-wrap iframe#lp-iframe-cert');
					$ifr.css({height: data.height});
					//$(window).trigger('resize');
				});
				$(pwindow).on('resize.update-certificate-popup', _onresize);
				$(window).on('resize.learn-press-cert-designer', function () {
					$(pwindow).trigger('resize');
				});
			}
		}));
		function _load_js_url(src, id) {
			var d = document, s = 'script', js, fjs = d.getElementsByTagName(s)[0];
			if (id && d.getElementById(id)) return;
			js = d.createElement(s);
			id && (js.id = id);
			js.src = src;
			fjs.parentNode.insertBefore(js, fjs);
		}
	</script>
</head>

<body>
<div class="learn-press-cert-preview">
	<div id="learn-press-cert-wrap">
		<div class="cert-design-viewport">
			<img class="cert-template" src="<?php echo $cert_data['template']; ?>">
			<canvas></canvas>
		</div>
		<div class="cert-design-actions" data-downloading="<?php _e( 'Downloading...', 'learnpress-certificates' ); ?>">
			<span><?php _e( 'Download as:', 'learnpress-certificates' ); ?></span>
			<a href="" class="download" data-type="png" data-name="<?php echo $cert_name; ?>"><?php _e( 'PNG', 'learnpress-certificates' ); ?></a>
			<span>|</span>
			<a href="" class="download" data-type="jpg" data-name="<?php echo $cert_name; ?>"><?php _e( 'JPG', 'learnpress-certificates' ); ?></a>
			<span>|</span>
			<a href="" class="print" data-type="print" data-name="<?php echo $cert_name; ?>"><?php _e( 'Print', 'learnpress-certificates' ); ?></a>
			<?php if ( $socials = learn_press_certificates_get_socials() ): ?>
		</div>
		<div class="socials-sharing">
			<ul>
				<?php foreach ( $socials as $social ): ?>
					<?php
					$args = array(
						'url'  => learn_press_get_current_url(),
						'text' => get_the_title( $cert->ID )
					);
					?>
					<li>
						<?php do_action( 'learn_press_share_certificate_button', $social, $args ); ?>
						<?php do_action( 'learn_press_share_certificate_button_' . $social, $args ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
		<form id="learn-press-form-download-cert" method="post">
			<input type="hidden" name="download_cert[name]" value="<?php echo $cert_name; ?>" />
		</form>
	</div>
</div>
<script type="text/javascript">
	var cert_data = <?php echo json_encode( $cert_data );?>;
</script>

</body>
</html>
