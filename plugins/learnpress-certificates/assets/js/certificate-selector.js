;(function($){
    var CertificateSelector = Backbone.View.extend({
        options: null,
        events: {
            'click .dashicons-plus': 'showBox',
            'click .dashicons-trash': 'removeCert',
            'click .button-select-cert': 'highlightCert',
            'click button.cert-close-box': 'closeBox',
            'click button.cert-select': 'selectCert'
        },
        initialize: function(options){
            this.options = options;
            this.options.cert_id = options.element.value;
            $(options.element).hide();
            this.render();
        },
        render: function() {
            var attributes = {},
                element = this.options.element,
                $template = $(wp.template('cert-selector')(this.options));
            _.each($template.get(0).attributes, function (attr) {
                attributes[attr.name] = attr.value;
            });
            this.$el.attr(attributes).html($template.html()).insertAfter( $(element) );
            if( this.options.cert_id ) {
                this.loadPreview();
            }
        },
        showBox: function(e){
            e.preventDefault();
            this.scrollTop = $('body').scrollTop();
            $('html, body').css("overflow", 'hidden');
            this.$('.cert-list-preview').show().animate({opacity: 1});
        },
        closeBox: function(e){
            e.preventDefault();
            $('html, body').css("overflow", '').scrollTop(this.scrollTop);
            this.$('.cert-list-preview').fadeOut(function(){
                $(this).hide();
            });
        },
        selectCert: function(e){
            var $selected = this.$('.cert-list > ul > li.selected');
            if( ! $selected.length ){
                $selected = this.$('.cert-list > ul > li:first');
            }
            var id = $selected.attr('data-id');
            this.options.element.value = id;
            this.options.cert_id = id;
            this.closeBox(e);
            this.loadPreview();
        },
        removeCert: function(e){
            e.preventDefault();
            this.$('.cert-preview').html('');
            this.options.element.value = '';
            this.$el.removeClass('has-preview');
        },
        highlightCert: function(e){
            e.preventDefault();
            var $el = $(e.target);
            if( ! $el.is('li') ){
                $el = $el.closest('li');
            }
            $el.addClass('selected').siblings().removeClass('selected');
        },
        loadPreview: function(){
            this.$el.addClass('loading-preview');
            $.ajax({
                url: certificateDesigner.ajax,
                type: 'post',
                dataType: 'json',
                data: {
                    cert_id: this.options.cert_id,
                    action: 'cert_load_preview'
                },
                context: this,
                success: function (response) {
                    if( response ){
                        this.options = $.extend({}, this.options, response);
                        var $img = $('<img />').attr('src', this.options.preview)
                        this.$('.cert-preview').html($img);
                        this.$el.addClass('has-preview').removeClass('loading-preview');
                    }
                }
            });
        }
    });
    $.fn.CertificateDesigner = function(options){
        return this.each(function(){
            new CertificateSelector({element: this});
        })
    }
    $(document).ready(function(){
        $('#_lpr_course_certificate').CertificateDesigner();
    })
})(jQuery);