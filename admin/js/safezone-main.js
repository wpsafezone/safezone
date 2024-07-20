function appHeight() {
    document.documentElement.style.setProperty("--app-height", window.innerHeight + "px"), document.documentElement.style.setProperty("--app-width", window.innerWidth + "px");
}

window.addEventListener("resize", appHeight),
    appHeight(),
    $(document).ready(function () {
        function e(e) {
            const t = e.closest(".table");
            e = 0 < t.find("input[data-table-checkbox]:checked").length;
            t.find(".table-actions").toggleClass("active", e);
        }

        $("input[data-table-checkbox]").each(function () {
            e($(this));
        }),
            $("input[data-table-checkbox]").on("change", function () {
                e($(this));
            });
    }),
    $(".table-dropdown__toggle").each(function () {
        new bootstrap.Dropdown($(this), {popperConfig: {strategy: "fixed"}});
    }),
    $(document).on("click", "[data-license-banner-close]", function () {
        $(".license-banner").addClass("hide");
    })
