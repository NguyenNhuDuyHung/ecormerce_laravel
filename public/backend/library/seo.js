(function ($) {
    "use strict";
    var HT = {};

    HT.seoPreview = () => {
        $("input[name=meta_title]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            $(".meta-title").html(value);
        });

        $("input[name=canonical]").css({
            "padding-left": $(".baseUrl").outerWidth() + 10,
        });

        $("input[name=canonical]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            $(".canonical").html(BASE_URL + value + SUFFIX);
        });

        $("textarea[name=meta_description]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            $(".meta-description").html(value);
        });
    };

    $(document).ready(function () {
        HT.seoPreview();
    });
})(jQuery);
