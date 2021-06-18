/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

(function ($) {
    var progressbarAddon = {
        alreadyInitialized: false,

        isElementInView: function ($element, fullyInView) {

            // Thanks to http://stackoverflow.com/users/41669/scott-dowding

            var pageTop = $(window).scrollTop();
            var pageBottom = pageTop + $(window).height();
            var elementTop = $element.offset().top;
            var elementBottom = elementTop + $element.height();

            if (fullyInView === true) {
                return ((pageTop < elementTop) && (pageBottom > elementBottom));
            } else {
                return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
            }
        },

        runFiller: function ($el, value, duration) {
            if ($el.data("inProgress")) {
                return;
            } else {
                $el.data({
                    inProgress: true
                });

                $el.css("width", "0px");

                $el.animate({
                    width: value + "%"
                }, {
                    duration: duration
                });
            }
        },

        runFillers: function() {
            var self = this;

            $(".progressbar-addon-container.animated").each(function() {
                if (self.isElementInView($(this), false)) {
                    var $progressbarFiller = $(this).find('.progressbar-filled');
                    var duration = $(this).data("duration");
                    var value = $(this).data("value");

                    self.runFiller($progressbarFiller, value, duration);
                } 
            });
        },

        init: function() {
            var self = this;

            if (this.alreadyInitialized === false) {
                $(document).bind("scroll resize", function () {
                    self.runFillers();
                });

                this.runFillers();

                this.alreadyInitialized = true;
            }
        }
    };

    $(document).ready(function () {
        progressbarAddon.init();
    });
})(jQuery);