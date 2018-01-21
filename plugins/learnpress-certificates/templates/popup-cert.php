<?php
$cert_data = LP_Addon_Certificates::instance()->get_json( $cert_id, 0, $user_id );
$cert_name = get_post_field( 'post_name', $cert_id );
$close     = '<a href="" class="close">' . __( 'Close', 'learnpress-certificates' ) . '</a>';
?>

<button class="learn-press-popup-certificate"><?php _e( 'View Certificate', 'learnpress-certificates' ); ?></button>

<script id="tmpl-popup-certificate" type="text/html">
	<div class="learn-press-cert-preview popup">
		<div id="learn-press-cert-wrap">
			<?php learn_press_display_message( __( 'Congrats! You have earned a certificate for this course', 'learnpress-certificates' ) . $close ); ?>
			<div class="lp-iframe-wrap">
				<iframe id="lp-iframe-cert" src="<?php echo learn_press_certificate_permalink( $cert_id, get_the_ID() ); ?>"></iframe>
			</div>
		</div>
	</div>
</script>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		var $popupCertificate = null;
		function showPopup() {
			LearnPress.overflow($('body'), 'hidden');

			if (!$popupCertificate) {
				$popupCertificate = $(LP.template('tmpl-popup-certificate', {}))
					.appendTo(document.body)
					.on('click', '.close', function (e) {
						e.preventDefault();
						$('.learn-press-cert-preview.popup').fadeOut(function () {
							LearnPress.overflow($('body'));
							$(window).unbind('resize.resize-popup-certificate');
						});
					});
			}

			$(window).bind('resize.resize-popup-certificate', function () {
				var $wrap = $('#learn-press-cert-wrap'),
					$iframeContainer = $wrap.find('.lp-iframe-wrap');
				$iframeContainer.css({
					height: $wrap.height() - $iframeContainer.position().top
				});
			});
			$popupCertificate.fadeIn(function () {
				$(window).trigger('resize');
			})
		}

		$button = $('.learn-press-popup-certificate').click(function () {
			showPopup();
		})

		<?php if( !empty( $popup )): ?>
		$button.first().trigger('click');
		<?php endif;?>
	});
</script>