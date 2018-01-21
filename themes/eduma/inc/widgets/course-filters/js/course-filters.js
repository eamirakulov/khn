(function ($) {

	function thim_updateQueryStringParameter(url, key, value) {
		if (!url) {
			url = window.location.href;
		}
		var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
		var separator = url.indexOf('?') !== -1 ? "&" : "?";
		var url_match = url.match(re);
		if (url_match) {
			if (value && value != '') {
				return url.replace(re, '$1' + key + "=" + value + '$2');
			} else {
				if( url_match[2] ) {
					url = url.replace(re, '$1');
				}else{
					url = url.replace(re, '');
				}
				return url;
			}
		}
		else {
			//If not match
			if (value && value != '') {
				return url + separator + key + "=" + value;
			} else {
				return url;
			}
		}
	}

	function thim_filters_addLoading() {
		$('body').addClass('course-filter-active').append('<div class="filter-loading"><div class="cssload-container"><div class="cssload-loading"><i></i><i></i><i></i><i></i></div></div></div>');
	}

	function thim_filters_removeLoading() {
		$('body').removeClass('course-filter-active');
		$('.filter-loading').remove();
	}

	function thim_filters_ajaxComplete(html) {
		var archive = $('#lp-archive-courses'),
			filters = $('.widget-course-filters-contain'),
			archive_html = $(html).find('#lp-archive-courses').html();
			//filters_html = $(html).find('.widget-course-filters-contain').html();
		archive.html(archive_html);
		//filters.html(filters_html);
		$('body').removeClass('course-filter-active');
		$('.filter-loading').remove();
	}

	function thim_filters_sendAjax(url) {
		$.ajax({
			type      : 'POST',
			dataType  : 'html',
			url       : url,
			beforeSend: function () {
				thim_filters_addLoading();
			},
			success   : function (html) {
				if (html) {
					thim_filters_ajaxComplete(html);
				}
			},
			error     : function () {
				thim_filters_removeLoading();
			}
		});
	}

	$(document).ready(function () {
		$(document).on('change', '.course_filter_price input[name="lp_price"]', function () {
			var elem = $(this),
				elem_val = elem.val();

			var url_ajax = window.location.protocol + "//" + window.location.host + window.location.pathname,
				url_test = ( elem_val != '' ) ? ( url_ajax + '?lp_price=' + elem_val ) : url_ajax;
			if (!window.location.search) {
				window.history.pushState({path: url_test}, '', url_test);
			} else {
				var url_test = thim_updateQueryStringParameter(window.location.href, 'lp_price', elem_val);
				window.history.pushState({path: url_test}, '', url_test);
			}

			thim_filters_sendAjax(url_test);

		});

		$(document).on('change', '.course_filter_featured input[name="lp_featured"]', function () {
			var elem = $(this),
				elem_val = elem.val();
			if (!elem.is(':checked')) {
				elem_val = '';
			}

			var url_ajax = window.location.protocol + "//" + window.location.host + window.location.pathname,
				archive = $('#lp-archive-courses'),
				url_test = ( elem_val != '' ) ? ( url_ajax + '?lp_featured=' + elem_val ) : url_ajax;
			if (!window.location.search) {
				window.history.pushState({path: url_test}, '', url_test);
			} else {
				var url_test = thim_updateQueryStringParameter(window.location.href, 'lp_featured', elem_val);
				window.history.pushState({path: url_test}, '', url_test);
			}
			thim_filters_sendAjax(url_test);
		});

		$(document).on('change', '.course_filter_orderby [name="lp_orderby"]', function () {
			var elem = $(this),
				elem_val = elem.val();

			var url_ajax = window.location.protocol + "//" + window.location.host + window.location.pathname,
				archive = $('#lp-archive-courses'),
				url_test = ( elem_val != '' ) ? ( url_ajax + '?lp_orderby=' + elem_val ) : url_ajax;
			if (!window.location.search) {
				window.history.pushState({path: url_test}, '', url_test);
			} else {
				var url_test = thim_updateQueryStringParameter(window.location.href, 'lp_orderby', elem_val);
				window.history.pushState({path: url_test}, '', url_test);
			}
			thim_filters_sendAjax(url_test);
		});
	});
})(jQuery);