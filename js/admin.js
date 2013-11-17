;(function($){
    var mdapp = mdapp || {};
    mdapp.YahooWeather = {
        init: function() {
            this.cache();
            this.events();
        },

        cache: function() {
            this.$wrapper = $('.widget-liquid-right');
            this.$widget = this.$wrapper.find('[id*="_mdyahooweather"]');
            this.$actionBtn = this.$widget.find('.widget-action');
            this.$content = this.$widget.find('.widget-inside');
        },

        events: function() {
            this.$actionBtn.on('click', $.proxy(this.expand, this));
        },

        expand: function(e) {
            if ( this.$content.css('display') == 'none' ) {
                this.$widget.css({
                    'margin-left': '-185px',
                    'width': '450px'
                });
            }
        }
    }
    mdapp.YahooWeather.init();
})(jQuery);