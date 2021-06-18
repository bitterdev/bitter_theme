/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

if (typeof counter === "undefined") {
    var counter = {};
}

counter.frontend = {
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
    
    runCounter: function ($el, duration) {
        if ($el.data("inProgress")) {
            return;
        } else {
            var countTo = parseInt($el.html());
            
            $el.data({
                inProgress: true
            });

            $({countNum: 0}).animate({
                countNum: countTo
            }, {
                duration: duration,

                step: function () {
                    $el.text(Math.floor(this.countNum));
                },

                complete: function () {
                    $el.text(this.countNum);
                }
            });
        }
    },
    
    runCounters: function() {
        var self = this;
        
        $(".counter").each(function() {
            if (self.isElementInView($(this), false)) {
                var duration = $(this).data("duration");
                
                $(this).find('.counter-value').each(function() {
                    self.runCounter($(this), duration);
                });
            } 
        });
    },
    
    init: function() {
        var self = this;
        
        if (this.alreadyInitialized === false) {
            $(document).bind("scroll resize", function () {
                self.runCounters();
            });

            this.runCounters();
            
            this.alreadyInitialized = true;
        }
    }
};

$(document).ready(function () {
    counter.frontend.init();
});