(function ($) {
    "use strict";
    var HT = {};
    var $document = $(document);

    HT.getLocation = () => {
        $(document).on("change", ".location", function () {
            let _this = $(this);
            let opiton = {
                data: {
                    'location_id': _this.val(),
                },
                target: _this.attr("data-target"),
            };

            HT.sendDataLocation(opiton);
        });
    };

    HT.sendDataLocation = (option) => {
        $.ajax({
            url: "ajax/location/getLocation",
            type: "get",
            data: option,
            dataType: "json",
            success: function (res) {
                $("." + option.target).html(res.html);
                console.log("." + option.target);
            },

            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Lỗi: " + textStatus + errorThrown);
            },
        });
    };

    $document.ready(function () {
        HT.getLocation();
    });
})(jQuery);
