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
    });
})(jQuery);