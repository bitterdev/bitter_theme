/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

(function ($) {
    $(function () {
        if ($("nav.main li").length) {
            var $menu = $("nav.main").clone();

            // append language switcher
            if ($(".language-switcher").length) {
                $menu.find("ul").append($("<li/>").append($("<a/>").html($(".language-switcher").data("label")).attr("href", "javascript:void(0);")).append($(".language-switcher ul").clone()));
            }

            var menu = new MmenuLight(
                $menu.get(0)
            );

            menu.navigation();

            var drawer = menu.offcanvas({
                position: "right"
            });

            $(".navbar-toggle").click(function (e) {
                e.preventDefault();
                drawer.open();
            });
        }

        $(".counter-container").each(function () {
            var duration = $(this).data("duration");

            $(this).find(".counter").each(function () {
                var $targetElement = $(this).find(".counter-value");
                var targetValue = parseInt($targetElement.data("target-value"));
                var frameDuration = 1000 / 60;
                var totalFrames = Math.round(duration / frameDuration);
                var frame = 0;

                var counterTimer = setInterval(function () {
                    frame++;

                    var progress = (frame / totalFrames) * (2 - (frame / totalFrames));
                    var counterValue = Math.round(targetValue * progress);

                    $targetElement.html(counterValue);

                    if (frame === totalFrames) {
                        clearInterval(counterTimer);
                    }
                }, frameDuration);
            });
        });

        $(".progressbar").each(function () {
            var duration = $(this).data("duration");
            var targetValue = $(this).data("target-value");

            $(this).find(".filled").animate({
                "width": targetValue + "%"
            }, duration);
        });

        if ($("main").hasClass("centered")) {
            particlesJS("ccm-page-container", {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 700
                        }
                    },


                    "color": {
                        "value": getComputedStyle(document.documentElement).getPropertyValue('--bitter-theme-accent-color').trim()
                    },

                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },

                        "polygon": {
                            "nb_sides": 5
                        }
                    },


                    "opacity": {
                        "value": 0.5,
                        "random": false,
                        "anim": {
                            "enable": false,
                            "speed": 0.1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },


                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 10,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },


                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": getComputedStyle(document.documentElement).getPropertyValue('--bitter-theme-accent-color').trim(),
                        "opacity": 0.4,
                        "width": 1
                    },

                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },


                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },

                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },

                        "resize": true
                    },

                    "modes": {
                        "grab": {
                            "distance": 140,
                            "line_linked": {
                                "opacity": 1
                            }
                        },


                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },

                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },

                        "push": {
                            "particles_nb": 4
                        },

                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },


                "retina_detect": true
            });
        }
    });
})(jQuery);