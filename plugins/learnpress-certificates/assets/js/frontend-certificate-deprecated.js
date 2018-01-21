;

(function ($) {
	window.certificates = [];
	function loadImage(src, callback) {
		var img = new Image();
		img.src = src;
		img.onload = function () {
			$.isFunction(callback) && callback({width: this.width, height: this.height});
		}
	}

	function download(url, name) {
		// make the link. set the href and download. emulate dom click
		var $downloadLink = $('<a>').attr({
			href    : url,
			download: name
		}).appendTo(document.body)
		$downloadLink.get(0).click();
		$downloadLink.remove();
	}

	function downloadPNG(canvas, name) {
		//  convert the canvas to a data url and download it.
		download(canvas.toDataURL(), name + '.png');
	}

	function downloadSVG(canvas, name) {
		var data = canvas.toSVG();

		var locfile = new Blob([data], {type: "image/svg+xml;charset=utf-8"});
		var locfilesrc = URL.createObjectURL(locfile);
		download(locfilesrc, name + '.svg');
	}

	window.showCert = showCert = function ($cert, certificate) {
		$(document.body).block_ui({
			position       : 'fixed',
			'z-index'      : 99999,
			backgroundColor: '#000',
			opacity        : 0.5
		});///append('<div id="user-certificate-block" />');
		$cert.appendTo($(document.body)).css({top: $(window).scrollTop() + 50}).fadeIn();
		$('.close', $cert).click(function (evt) {
			evt.preventDefault();
			$(document.body).unblock_ui();
			$(this).closest('.user-certificate').fadeOut();
		});

		var $certificate = new fabric.Canvas($('canvas', $cert).get(0));
		window.certificates.push($certificate);
		var frame = {
			width: $cert.width()
		};
		loadImage(certificate.url, function (dimensions) {

			frame.ratio = frame.width / dimensions.width;
			frame.height = dimensions.height * frame.ratio;
			$certificate.selection = false;
			$certificate.setWidth(frame.width);
			$certificate.setHeight(frame.height);
			$certificate.calcOffset();
			fabric.Image.fromURL(certificate.url, function (img) {
				$certificate.backgroundImage = img;
				$certificate.backgroundImage.width = frame.width;
				$certificate.backgroundImage.height = frame.height;
			});

			$.each(certificate.layers, function () {
				var args = this;
				if (!args.type) return;

				$.each(['fontSize', 'top', 'left'], function () {
					args[this] = args[this] * frame.ratio;
				});
				var layer = new fabric.Text(args.text, args);
				$certificate.add(layer);
				layer.lockMovementX = layer.lockMovementY = true;
				layer.lockScalingX = lockScalingY = true;
				layer.lockRotation = true;
				layer.hasBorders = false;
				layer.hasControls = false;
				layer.setControlsVisibility({
					mt : false,
					mb : false,
					ml : false,
					mr : false,
					tl : false,
					tr : false,
					bl : false,
					br : false,
					mtr: false
				});

			}, this);

			setTimeout(function () {
				$certificate.renderAll();
			}, 150);

			$('.cert-download-png', $cert).unbind('click').click(function (e) {
				e.preventDefault();
				downloadPNG($certificate, $(this).data('name'));
			});

			$('.cert-download-svg', $cert).unbind('click').click(function (e) {
				e.preventDefault();
				downloadSVG($certificate, $(this).data('name'));
			});
		});
	}
})(jQuery);
