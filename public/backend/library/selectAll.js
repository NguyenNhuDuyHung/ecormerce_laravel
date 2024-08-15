(function ($) {
    "use strict";
    var HT = {};
    var $document = $(document);
    var _token = $('meta[name="csrf-token"]').attr("content");

    HT.checkAll = () => {
        if ($("#checkAll").length) {
            $(document).on("click", "#checkAll", function () {
                let isChecked = $(this).prop("checked");

                $(".checkBoxItem").prop("checked", isChecked);
                $(".checkBoxItem").each(function () {
                    let _this = $(this);
                    HT.changeBg(_this);
                });
            });
        }
    };

    HT.checkBoxItem = () => {
        $(document).on("click", ".checkBoxItem", function () {
            let _this = $(this);
            $("#checkAll").prop(
                "checked",
                $(".checkBoxItem:checked").length == $(".checkBoxItem").length
            );
            HT.changeBg(_this);
        });
    };

    HT.changeBg = (object) => {
        let isChecked = $(object).prop("checked");
        if (isChecked) {
            $(object).closest("tr").addClass("active-bg");
        } else {
            $(object).closest("tr").removeClass("active-bg");
        }
    };

    HT.changeStatusAll = () => {
        if ($(".changeStatusAll").length) {
            $(document).on("click", ".changeStatusAll", function (e) {
                let _this = $(this);
                let id = [];

                let option = {
                    value: _this.attr("data-value"),
                    model: _this.attr("data-model"),
                    field: _this.attr("data-field"),
                    _token: _token,
                    ids: id,
                };

                $(".checkBoxItem:checked").each(function () {
                    id.push($(this).val());
                });

                $.ajax({
                    url: "ajax/dashboard/changeStatusAll",
                    type: "post",
                    data: option,
                    dataType: "json",
                    success: function (res) {
                        if (res.flag) {
                            let cssActive1 =
                                "box-shadow: rgb(26, 179, 148) 0px 0px 0px 11px inset; border-color: rgb(26, 179, 148); background-color: rgb(26, 179, 148); transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;";
                            let cssActive2 =
                                "left: 13px; transition: background-color 0.4s ease 0s, left 0.2s ease 0s; background-color: rgb(255, 255, 255);";
                            let cssUnActive1 =
                                "box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; border-color: rgb(223, 223, 223); background-color: rgb(255, 255, 255); transition: border 0.4s, box-shadow 0.4s;";
                            let cssUnActive2 =
                                "left: 0px; transition: background-color 0.4s, left 0.2s;";

                            let cssToApply1, cssToApply2;
                            if (option.value == 1) {
                                cssToApply1 = cssActive1;
                                cssToApply2 = cssActive2;
                            } else {
                                cssToApply1 = cssUnActive1;
                                cssToApply2 = cssUnActive2;
                            }

                            id.forEach((itemId) => {
                                $(".js-switch-" + itemId)
                                    .find("span.switchery")
                                    .attr("style", cssToApply1)
                                    .find("small")
                                    .attr("style", cssToApply2);
                            });

                            $(".checkBoxItem").prop("checked", false);
                            $("#checkAll").prop("checked", false);
                            $(".checkBoxItem").each(function () {
                                HT.changeBg($(this));
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("Lỗi: " + textStatus + errorThrown);
                    },
                });
                e.preventDefault();
            });
        }
    };

    $document.ready(function () {
        HT.checkAll();
        HT.checkBoxItem();
        HT.changeStatusAll();
    });
})(jQuery);
