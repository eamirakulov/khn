<?php $id = 'input-' . md5( time() ); ?>
<input id="<?php echo $id; ?>" class="cert-color-option" name="color" value="<?php echo esc_attr( $field['std'] ); ?>" />
<script type="text/javascript">
	jQuery('#<?php echo $id;?>').wpColorPicker({
		change      : function (hsb, hex, rgb, el) {
			var $input = jQuery(this),
				timer = $input.data('timer');
			timer && clearTimeout(timer);
			timer = setTimeout(function () {
				$input.trigger('change');
			}, 300)
		},
		onChange    : function (hsb, hex, rgb, el) {
			//var el = $( $(this).data('colorpicker').el ).css('background-color', '#' + hex).data('color', '#'+hex);
			//el.trigger('change');
			//alert(el.attr('id'))
			alert(hex)
		},
		onBeforeShow: function (cal) {
			/*var color = ($( $(cal).data('colorpicker').el ).data("color"));
			 color = (new fabric.Color(color)).toHex();
			 $(this).ColorPickerSetColor( color  );*/
		}
	});
</script>