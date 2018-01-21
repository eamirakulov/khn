(function ($) {
    $(document).ready(function () {
        var url_ajax = thim_dashboard.admin_ajax;

        check_empty_column();

        $('.tc-dashboard-wrapper')
            .sortable({
                placeholder: "tc-ui-state-highlight",
                opacity: 0.8,
                handle: '.tc-box-header',
                items: '.tc-box',
                cursor: 'move',
                appendTo: '.tc-sortable',
                update: function (event, ui) {
                    var data = {};

                    $('.tc-sortable').each(function () {
                        var self = $(this);
                        var boxes = self.find('> *');
                        var col = self.attr('data-column');
                        var boxesArr = [];
                        boxes.each(function () {
                            var b = $(this);
                            boxesArr.push(b.attr('data-id'));
                        });
                        data[col] = boxesArr;
                    });

                    check_empty_column();

                    _request(data);
                }
            });

        function check_empty_column() {
            $('.tc-sortable').each(function () {
                var self = $(this);
                self.removeClass('tc-sortable-empty');
                self.find('.tc-box-temporary').remove();
                var boxes = self.find('> *');
                if (boxes.length === 0) {
                    self.addClass('tc-sortable-empty');
                    self.html('<div class="tc-box ui-sortable-handle tc-box-temporary"></div>');
                }
            });
        }

        function _request(data) {
            window.onbeforeunload = function() {
                return '';
            };

            $.ajax({
                method: 'POST',
                url: url_ajax,
                data: data,
                complete: function () {
                    window.onbeforeunload = null;
                }
            })
        }
    });
})(jQuery);