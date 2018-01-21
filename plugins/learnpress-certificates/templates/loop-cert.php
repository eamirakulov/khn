<li>
	<?php $cert_data = LP_Addon_Certificates::instance()->get_json( $cert->cert_id, $cert->course_id, $cert->user_id ); ?>
	<div class="inside">
		<!-- <img src="<?php echo get_post_meta( $cert->cert_id, '_lp_cert_preview', true ); ?>" /> -->
		<div class="course-certificate-preview">
			<div class="learn-press-cert-preview">
				<div id="learn-press-cert-wrap-<?php echo $cert->cert_id; ?>-<?php echo $cert->course_id; ?>">
					<div class="cert-design-viewport">
						<img class="cert-template" src="<?php echo $cert_data['template']; ?>">
						<canvas></canvas>
						<a class="permalink" target="_blank" href="<?php echo learn_press_certificate_permalink( $cert->cert_id, $cert->course_id ); ?>"></a>
					</div>
				</div>
				<script type="text/javascript">

					jQuery(document).ready(function ($) {
						var cert_data = <?php echo json_encode( $cert_data );?>,
							$button = null;
						LP_Model_Certificates = window.LP_Model_Certificates = new $.LP_Certificates.Model(cert_data);
						LP_View_Certificates = window.LP_View_Certificates = new $.LP_Certificates.View({
							model: LP_Model_Certificates,
							$el  : $('#learn-press-cert-wrap-<?php echo $cert->cert_id;?>-<?php echo $cert->course_id;?>')
						});
					});
				</script>
			</div>
		</div>
		<h4>
			<p class="course-cert">
				<a href="<?php echo get_the_permalink( $cert->course_id ); ?>"><?php echo get_the_title( $cert->course_id ); ?></a>
			</p>
		</h4>
	</div>
</li>