// Import the frontend foundation for themes.
import '@concretecms/bedrock/assets/bedrock/js/frontend';

import '@concretecms/bedrock/assets/account/js/frontend';
import '@concretecms/bedrock/assets/desktop/js/frontend';
import '@concretecms/bedrock/assets/forms/js/frontend';
// I wish we could include this in Atomik but if we do it collides with the use of it in the core components.
//import '@concretecms/bedrock/assets/calendar/js/frontend';
import '@concretecms/bedrock/assets/navigation/js/frontend';
import '@concretecms/bedrock/assets/conversations/js/frontend';
import '@concretecms/bedrock/assets/imagery/js/frontend';
import '@concretecms/bedrock/assets/documents/js/frontend';

// Custom feature support
import './features/imagery/hero-image/offset-title';

/**
 * @project:   Bitter Theme
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2021 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

(function ($) {
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');

        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);

    $(function () {
        if ($("nav.main li").length) {
            var $menu = $("nav.main").clone();

            // append language switcher
            if ($(".language-switcher").length) {
                $menu.find("ul").append($("<li/>").append($("<a/>").html($(".language-switcher").data("label")).attr("href", "javascript:void(0);")).append($(".language-switcher ul").clone()));
            }
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
    });

    var resize = function () {
        var isDesktop = true;
        var setupTimer = false;

        if (isDesktop) {
            if ($(window).scrollTop() > 0 && !$("#logo").hasClass("small")) {
                $("#logo").addClass("small");
                setupTimer = true;
            } else if ($(window).scrollTop() === 0 && $("#logo").hasClass("small")) {
                $("#logo").removeClass("small");
                setupTimer = true;
            }

            if (setupTimer) {
                var n = 0;

                var resizeTimer = setInterval(function () {
                    $("main").css({
                        paddingTop: $("header").height()
                    });

                    n++;

                    if (n >= 11) {
                        clearInterval(resizeTimer);
                    }
                }, 50);
            }

            $("main").css({
                paddingTop: $("header").height()
            });
        }
    }

    $(document).ready(function () {
        resize();
        $("#mobile-nav").html($("#desktop-nav .col").html());

        const menu = new MmenuLight(
            document.querySelector("#mobile-nav")
        );

        const navigator = menu.navigation({
            slidingSubmenus: true,
            title: window.bitterThemeConfig.header.title,
            selected: "active"
        });

        const drawer = menu.offcanvas({
            position: "right"
        });

        document.querySelector('a[href="#mobile-nav"]')
            .addEventListener('click', (evnt) => {
                evnt.preventDefault();
                drawer.open();
            });

        $(window).on("resize scroll", resize);


        var im = iframemanager();

        im.run({
            currLang: window.bitterThemeConfig.iframeManager.language,
            services: {
                googlemaps: {
                    embedUrl: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2370.4670861637355!2d9.977333276827116!3d53.549428972348906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47b18f11ccc3d609%3A0x141f297582a9908c!2sLudwig-Erhard-Stra%C3%9Fe%2018%2C%2020459%20Hamburg!5e0!3m2!1sde!2sde!4v1692424755610!5m2!1sde!2sde',
                    iframe: {
                        allow: 'picture-in-picture; fullscreen;'
                    },

                    languages: window.bitterThemeConfig.iframeManager.languages
                }
            }
        });

        var cc = initCookieConsent();

        cc.run({
            current_lang: window.bitterThemeConfig.cookieDisclosure.language,
            autoclear_cookies: true,
            cookie_expiration: 365,
            force_consent: true,
            page_scripts: true,
            gui_options: {
                consent_modal: {
                    layout: 'cloud',
                    position: 'bottom center',
                    transition: 'slide'
                },
                settings_modal: {
                    layout: 'box',
                    transition: 'slide'
                }
            },
            languages: window.bitterThemeConfig.cookieDisclosure.languages
        });
    });
})(jQuery);