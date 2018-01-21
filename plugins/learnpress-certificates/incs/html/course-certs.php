<?php
global $post;
$certs       = get_posts(
	array(
		'post_type'      => 'lp_cert',
		'posts_per_page' => - 1,
		'post_status'    => 'publish'
	)
);
$course_cert = get_post_meta( $post->ID, '_lp_cert', true );
?>
<?php if ( $certs ): ?>
    <h3 class="learn-press-cert-message">
		<?php _e( 'Select a Certificate', 'learnpress-certificates' ); ?>
    </h3>
    <ul id="learn-press-certs-browse">
		<?php foreach ( $certs as $cert ): $is_selected = $course_cert == $cert->ID; ?>
            <li class="<?php echo $is_selected ? 'selected' : ''; ?>">
                <div class="cert-wrap">
                    <img src="<?php echo get_post_meta( $cert->ID, '_lp_cert_preview', true ); ?>"/>
                    <div class="cert-name">
                        <span><?php echo get_the_title( $cert->ID ); ?></span>
						<?php
						$is_editable = false;
						if ( learn_press_get_current_user_id() == $cert->post_author || learn_press_get_current_user()->is_admin() ) {
							$editlink    = get_edit_post_link( $cert->ID );
							$is_editable = true;
						} else {
							$editlink = 'javascript:void();';
						}
						$selected_label   = __( 'Selected', 'learnpress-certificates' );
						$unselected_label = __( 'Select', 'learnpress-certificates' );
						$hover_label      = __( 'Remove', 'learnpress-certificates' );
						?>
                        <div class="buttons">
                            <button type="button" data-remove-text="<?php echo esc_attr( $hover_label ); ?>"
                                    data-selected-text="<?php echo esc_attr( $selected_label ); ?>"
                                    data-unselected-text="<?php echo esc_attr( $unselected_label ); ?>"
                                    class="button button-select-cert"><?php echo $is_selected ? $selected_label : $unselected_label; ?></button>
                            <a target="_blank" href="<?php echo $editlink; ?>"
                               class="button<?php echo ! $is_editable ? ' disabled' : ''; ?>"><?php _e( 'Edit', 'learnpress-certificates' ); ?></a>
                        </div>
                    </div>
                    <div class="overlay"></div>
                </div>
                <input class="learn-press-cert-checkbox" type="radio" name="learn-press-cert"
                       value="<?php echo $cert->ID; ?>" <?php checked( $course_cert == $cert->ID ); ?> />
            </li>
		<?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><?php esc_html_e( 'No certificates found', 'learnpress-certificates' ); ?></p>
<?php endif; ?>
<a class="button button-primary" target="_blank"
   href="<?php echo esc_url( admin_url( 'post-new.php?post_type=lp_cert' ) ); ?>"><?php _e( 'Add new Certificate', 'learnpress-certificates' ); ?></a>

<script type="text/javascript">
    jQuery(function ($) {
        function _toggle_item($item, is_selected) {
            var $btn = $item.find('.button-select-cert');
            if (is_selected) {
                $item.find('input[name="learn-press-cert"]').prop('checked', true);
                $btn.html($btn.data('remove-text'));
            } else {
                $item.removeClass('selected');
                $btn.html($btn.data('unselected-text'));
            }
        }
        $('#learn-press-certs-browse').on('click', '.button-select-cert', function () {
            if( $(this).hasClass('remove') ){
            		$(this).removeClass('remove');
            		$(this).parents('.cert-wrap').next('input').prop('checked', false);
            }
        });

        $('#learn-press-certs-browse').on('click', '.button-select-cert', function () {
            var $btn = $(this),
                $clicked = $btn.closest('li').toggleClass('selected'),
                is_selected = $clicked.hasClass('selected');
            if (is_selected) {
                _toggle_item($clicked, true);
                $clicked.siblings('li').each(function () {
                    _toggle_item($(this), false);
                })
            } else {
                _toggle_item($clicked, false);
            }
        }).find('.button-select-cert').hover(function () {
            var $this = $(this);
            if (!$this.closest('li').hasClass('selected')) {
                return;
            }
            $this.addClass('remove').html($this.data('remove-text'));
        }, function () {
            var $this = $(this);
            if (!$this.closest('li').hasClass('selected')) {
                return;
            }
            $this.removeClass('remove').html($this.data('selected-text'));
        })
    })
</script>