/**
 * LearnPress Course Review addon
 *
 * WARNING: This script may not work correct with LP version before 1.0
 *
 * @version 1.0
 */
;(function ($) {
	if (typeof LP == 'undefined' && typeof LearnPress != 'undefined') {
		window.LP = LearnPress;
	}
	function CourseReview() {
		var $reviewForm = $('#course-review').appendTo(document.body),
			$reviewBtn = $(".write-a-review"),
			$stars = $('.review-fields ul > li span', $reviewForm).each(function (i) {
				$(this).hover(function () {
					if (submitting) {
						return;
					}
					$stars.map(function (j) {
						$(this).toggleClass('hover', j <= i);
					})
				}, function () {
					if (submitting) {
						return;
					}
					var selected = $reviewForm.find('input[name="rating"]').val();
					if (selected) {
						$stars.map(function (j) {
							$(this).toggleClass('hover', j < selected);
						});
					} else {
						$stars.removeClass('hover')
					}
				}).click(function (e) {
					if (submitting) {
						return;
					}
					e.preventDefault();
					$reviewForm.find('input[name="rating"]').val($stars.index($(this)) + 1);
				})
			}),
			that = this,
			submitting = false,
			showForm = null,
			closeForm = null,
			addReview = null;

		showForm = this.showForm = function () {
			var _completed = function () {
				$('input[type="text"], textarea', this).val('');
				$stars.removeClass('hover');
			}
			$reviewForm.fadeIn(_completed);
		}

		closeForm = this.closeForm = function () {
			var _completed = function () {
				$('button, input[type="text"], textarea', $reviewForm).prop('disabled', false);
				$reviewForm.removeClass('submitting').data('selected', '');
				$stars.removeClass('hover')
			}
			$reviewForm.find('input[name="rating"]').val('')
			$(document).focus();
			$reviewForm.fadeOut(_completed);
		}

		addReview = this.addReview = function () {
			var $reviewTitle = $('input[name="review_title"]', $reviewForm);
			var $reviewContent = $('textarea[name="review_content"]', $reviewForm);
			var rating = $reviewForm.find('input[name="rating"]').val();
			var course_id = $(this).attr('data-id');

			if (0 == $reviewTitle.val().length) {
				alert(learn_press_course_review.localize.empty_title)
				$reviewTitle.focus();
				return;
			}

			if (0 == $reviewContent.val().length) {
				alert(learn_press_course_review.localize.empty_content)
				$reviewContent.focus();
				return;
			}

			if (!rating) {
				alert(learn_press_course_review.localize.empty_rating)
				return;
			}
			$reviewForm.addClass('submitting');
			$.ajax({
				url     : window.location.href,
				data    : $('form', $reviewForm).serialize(),
				dataType: 'text',
				success : function (response) {
					submitting = false;
					response = LP.parseJSON(response);
					if (response.result == 'success') {
						closeForm();
						LP.reload();
					} else {
						$('button, input[type="text"], textarea', $reviewForm).prop('disabled', false);
						$reviewForm.removeClass('submitting').addClass('error');
						$reviewForm.find('message').html(response.message);
					}
				},
				error   : function (response) {
					response = LP.parseJSON(response);
					submitting = false;
					$('button, input[type="text"], textarea', $reviewForm).prop('disabled', false);
					$reviewForm.removeClass('submitting').addClass('error');
					$reviewForm.find('message').html(response.message);
				}
			});
			$('button, input[type="text"], textarea', $reviewForm).prop('disabled', true);

		}

		$reviewBtn.click(function (e) {
			e.preventDefault();
			that.showForm();
		});

		$reviewForm
			.on('click', '.submit-review', addReview)
			.on('click', '.close', function (e) {
				e.preventDefault();
				closeForm();
			})
	}

	$(document).ready(function () {
		new CourseReview();
	});

})
(jQuery);
jQuery(document).ready(function ($) {

	return;
	var $review = $('#review');

	function close_form() {
		$review.fadeOut('fast');
		$(document.body).unbind('click.close_review_form')
		$(window).unbind('scroll.review-position');
	}

	$(".write-a-review").click(function (event) {
		event.preventDefault();

		$('input, textarea', $review).val('');
		stars.removeClass('hover')
		$review
			.removeData('selected')
			.css({
				top      : $(window).scrollTop() + 50,
				marginTop: -$(window).scrollTop()
			})
			.fadeIn("fast", function () {
				$('input:first', $review).focus();
			});
		$(window).on('scroll.review-position', function () {
			$review.css({
				marginTop: -$(window).scrollTop()
			})
		});
		$(document.body).on('click.close_review_form', ">.block-ui", close_form)
	});

	$(".close", $review).click(function (evt) {
		evt.preventDefault();
		close_form();
	});

	$('.cancel', $review).click(close_form);

	$(document).keyup(function (e) {
		if (e.keyCode == 27 && $review.is(':visible')) {
			event.preventDefault();
			close_form();
		}
	});

	var stars = $('.review-fields ul > li span', $review).each(function (i) {
		$(this).hover(function () {
			stars.map(function (j) {
				j <= i ? $(this).addClass('hover') : $(this).removeClass('hover');
			})
		}, function () {
			var selected = $review.data('selected');
			stars.map(function (j) {
				j <= selected ? $(this).addClass('hover') : $(this).removeClass('hover');
			})
		}).click(function (e) {
			e.preventDefault();
			$review.data('selected', i)
		});
	})

	$(document).on('click', '#course-review-load-more', function () {
		var $button = $(this);
		if (!$button.is(':visible')) return;
		$button.hide();
		var paged = parseInt($(this).attr('data-paged')) + 1;
		$('#course-reviews .loading').show();
		$.ajax({
			type    : "POST",
			dataType: 'html',
			url     : window.location.href,
			data    : {
				action: 'learn_press_load_course_review',
				paged : paged
			},
			success : function (response) {
				var $content = $(response),
					$loading = $('#course-reviews .loading').hide();
				$content.find('.course-reviews-list > li:not(.loading)').insertBefore($loading);
				if ($content.find('#course-review-load-more').length) {
					$button.show().attr('data-paged', paged);
				} else {
					$button.remove();
				}
			}
		});
	});
	$('.submit-review').click(function (event) {
		event.preventDefault();

		var $review_title = $('input[name="review-title"]');
		var $review_content = $('textarea[name="review-content"]');
		var review_rate = $review.data('selected');
		var course_id = $(this).attr('data-id');

		if (0 == $review_title.val().length) {
			alert('Please enter the review title')
			$review_title.focus();
			return;
		}

		if (0 == $review_content.val().length) {
			alert('Please enter the review content')
			$review_content.focus();
			return;
		}

		if (review_rate == undefined) {
			alert('Please select your rating')
			return;
		}
		$review.block_ui();
		$('.submitting', $review).show();
		$.ajax({
			type    : "POST",
			dataType: 'html',
			url     : ajaxurl,
			data    : {
				action        : 'learn_press_add_course_review',
				review_rate   : parseInt(review_rate) + 1,
				review_title  : $review_title.val(),
				review_content: $review_content.val(),
				course_id     : course_id
			},
			success : function (html) {
				$('.course-rate').replaceWith($(html))
				$('.submitting', $review).hide();
				$(".close", $review).trigger('click');
				$('button.write-a-review').remove();
			}
		})
	})
})
