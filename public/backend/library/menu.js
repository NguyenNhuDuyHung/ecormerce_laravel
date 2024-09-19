(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");
    HT.createMenuCatalogue = () => {
        $(document).on("submit", ".create-menu-catalogue", function (e) {
            e.preventDefault();

            let _form = $(this);

            let option = {
                name: _form.find('input[name="name"]').val(),
                keyword: _form.find('input[name="keyword"]').val(),
                _token: _token,
            };

            $.ajax({
                url: "ajax/menu/createCatalogue",
                type: "POST",
                data: option,
                dataType: "JSON",
                success: function (data) {
                    console.log(data);

                    if (data.code == 0) {
                        $(".form-error")
                            .removeClass("text-error")
                            .addClass("text-success")
                            .html(data.messages)
                            .show();
                        const menuCatalogueSelect = $(
                            'select[name="menu_catalogue_id"]'
                        );
                        menuCatalogueSelect.append(
                            '<option value="' +
                                data.data.id +
                                '">' +
                                data.data.name +
                                "</option>"
                        );
                    } else {
                        $(".form-error")
                            .removeClass("text-success")
                            .addClass("text-error")
                            .html(data.messages)
                            .show();
                    }
                },
                beforeSend: function () {
                    _form.find(".error").html("");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status == 422) {
                        let errors = jqXHR.responseJSON.errors;

                        for (let field in errors) {
                            let errorMessage = errors[field];
                            errorMessage.forEach((error) => {
                                $("." + field).html(error);
                            });
                        }
                    }
                    console.log("Lỗi: " + textStatus + errorThrown);
                },
            });
        });
    };

    $(document).ready(function () {
        HT.createMenuCatalogue();
    });
})(jQuery);
