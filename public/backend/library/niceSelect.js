(function ($) {
    "use strict";
    var HT = {};
    var $document = $(document);

    HT.niceSelect = () => {
        $('.niceSelect').niceSelect();
    }
   
    $document.ready(function () {
        HT.niceSelect();
    });
})(jQuery);
