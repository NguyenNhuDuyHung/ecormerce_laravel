(function ($) {
    "use strict";
    var HT = {};
    var $document = $(document);

    HT.select2 = () => {
        if ($(".setupSelect2").length) {
            $(".setupSelect2").select2();
        }
    };

    $document.ready(function () {
        HT.select2();
    });
})(jQuery);
