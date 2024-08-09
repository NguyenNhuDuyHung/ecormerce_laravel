(function ($) {
    "use strict";
    var HT = {};

    HT.uploadImageToInput = () => {
        $(".upload-image").click(function () {
            let input = $(this);
            let type = input.attr("data-type");
            HT.setupCKFinder2(input, type);
        });
    };

    HT.setupCKFinder2 = (object, type) => {
        if (typeof type == "undefined") {
            type = "Images";
        }

        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.val(fileUrl);
        };

        finder.popup();
    };

    $(document).ready(function () {
        HT.uploadImageToInput();
    });
})(jQuery);
