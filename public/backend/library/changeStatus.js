(function ($) {
    "use strict";
    var HT = {};
    var $document = $(document);
    var _token = $('meta[name="csrf-token"]').attr("content");
    HT.changeStatus = () => {
        if ($(".status").length) {
            $(document).on("change", ".status", function () {
                let _this = $(this);
                let option = {
                    value: parseInt(_this.val()),
                    modelId: parseInt(_this.attr("data-modelId")),
                    model: _this.attr("data-model"),
                    field: _this.attr("data-field"),
                    _token: _token,
                };

                $.ajax({
                    url: "ajax/dashboard/changeStatus",
                    type: "post",
                    data: option,
                    dataType: "json",
                    success: function (res) {
                        
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("Lỗi: " + textStatus + errorThrown);
                    },
                });
            });
        }
    };

    $document.ready(function () {
        HT.changeStatus();
    });
})(jQuery);
