const turnOnVariant = document.querySelector(".turnOnVariant");
const variantCheckbox = document.querySelector("#variantCheckbox");
const variantWrapper = document.querySelector(".variant-wrapper");

const toggleVariantWrapper = (e) => {
    let price = document.querySelector("input[name=price]").value;
    let code = document.querySelector("input[name=code]").value;

    if (price == "" || code == "") {
        alert(
            "Bạn phải nhập vào Giá và Mã sản phẩm để sử dụng chức năng này!!"
        );
        e.preventDefault();
        return;
    }
    variantWrapper.classList.toggle("hidden");
};

variantCheckbox.addEventListener("click", toggleVariantWrapper);

turnOnVariant.addEventListener("click", () => {
    variantCheckbox.addEventListener("click", toggleVariantWrapper);
});

(function ($) {
    "use strict";
    var HT = {};

    HT.addVariant = () => {
        if ($(".add-variant").length) {
            $(document).on("click", ".add-variant", function () {
                let html = HT.renderVariantItem(attributeCatalogue);
                $(".variant-body").append(html);
                HT.checkMaxAttributeGroup(attributeCatalogue);
                HT.disabledAttributeCatalogueChoose();
            });
        }
    };

    HT.renderVariantItem = (attributeCatalogue) => {
        let html = "";
        html = html + '<div class="row mb20 variant-item">';
        html = html + '<div class="col-lg-3">';
        html = html + '<div class="attribute-catalogue">';
        html = html =
            html +
            '<select name="attributeCatalogue[]" id="" class="choose-attribute niceSelect">';
        html = html + '<option value="">Chọn Nhóm thuộc tính</option>';
        for (let i = 0; i < attributeCatalogue.length; i++) {
            html =
                html +
                '<option value="' +
                attributeCatalogue[i].id +
                '">' +
                attributeCatalogue[i].names +
                "</option>";
        }
        html = html + "</select>";
        html = html + "</div>";
        html = html + "</div>";
        html = html + '<div class="col-lg-8">';
        html =
            html +
            '<input type="text" name="" disabled class="fake-variant form-control">';
        html = html + "</div>";
        html = html + '<div class="col-lg-1">';
        html =
            html +
            '<button type="button" class="remove-attribute btn btn-danger"><svg data-icon="TrashSolidLarge" aria-hidden="true" focusable="false" width="15" height="16" viewBox="0 0 15 16" class="bem-Svg" style="display: block;"><path fill="currentColor" d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z"></path></svg></button>';
        html = html + "</div>";
        html = html + "</div>";

        return html;
    };

    HT.chooseVariantGroup = () => {
        $(document).on("change", ".choose-attribute", function () {
            let _this = $(this);
            let attributeCatalogueId = _this.val();
            if (attributeCatalogueId != 0) {
                _this
                    .parents(".col-lg-3")
                    .siblings(".col-lg-8")
                    .html(HT.select2Variant(attributeCatalogueId));

                $(".selectVariant").each(function (key, index) {
                    HT.getSelect2($(this));
                });
            } else {
                _this
                    .parents(".col-lg-3")
                    .siblings(".col-lg-8")
                    .html(
                        '<input type="text" name="attribute[" + attributeCatalogueId + "][]"  disabled="" class="fake-variant form-control">'
                    );
            }
            HT.disabledAttributeCatalogueChoose();
        });
    };

    HT.createProductVariant = () => {
        $(document).on("change", ".selectVariant", function () {
            let _this = $(this);
            HT.createVariant();
        });
    };

    HT.createVariant = () => {
        let attributes = [];
        let variants = [];
        let attributeTitle = [];

        $(".variant-item").each(function () {
            let _this = $(this);
            let attr = [];
            let attrVariant = [];
            const attributeCatalogueId = _this.find(".choose-attribute").val();
            const optionText = _this
                .find(".choose-attribute option:selected")
                .text();
            const attribute = $(".variant-" + attributeCatalogueId).select2(
                "data"
            );

            for (let i = 0; i < attribute.length; i++) {
                let item = {};
                let itemVariant = {};
                item[optionText] = attribute[i].text;
                itemVariant[attributeCatalogueId] = attribute[i].id;
                attr.push(item);
                attrVariant.push(itemVariant);
            }
            attributeTitle.push(optionText);
            attributes.push(attr);
            variants.push(attrVariant);
        });

        attributes = attributes.reduce((a, b) =>
            a.flatMap((d) => b.map((e) => ({ ...d, ...e })))
        );

        variants = variants.reduce((a, b) =>
            a.flatMap((d) => b.map((e) => ({ ...d, ...e })))
        );

        HT.createTableHeader(attributeTitle);

        let trClass = [];
        attributes.forEach((item, index) => {
            let $row = HT.createVariantRow(item, variants[index]);
            let classModified =
                "tr-variant-" +
                Object.values(variants[index]).join(", ").replace(/, /g, "-");
            trClass.push(classModified);
            if (!$("table.variantTable tbody tr").hasClass(classModified)) {
                $("table.variantTable tbody").append($row);
            }
        });

        $("table.variantTable tbody tr").each(function () {
            const $row = $(this);
            const rowClasses = $row.attr("class");
            if (rowClasses) {
                const rowClassArray = rowClasses.split(" ");
                let shouldRemove = false;
                rowClassArray.forEach((rowClass) => {
                    if (rowClass == "variant-row") {
                        return;
                    } else if (!trClass.includes(rowClass)) {
                        shouldRemove = true;
                    }
                });
                if (shouldRemove) {
                    $row.remove();
                }
            }
        });

        // let html = HT.renderTableHtml(attributes, attributeTitle, variants);
        // $('table.variantTable').html(html)
    };

    HT.createVariantRow = (attributeItem, variantItem) => {
        let attributeString = Object.values(attributeItem).join(", ");
        let attributeId = Object.values(variantItem).join(", ");
        let classModified = attributeId.replace(/, /g, "-");

        let $row = $("<tr>").addClass(
            "variant-row tr-variant-" + classModified
        );
        let $td;

        $td = $("<td>").append(
            $("<span>")
                .addClass("image img-cover")
                .append(
                    $("<img>")
                        .attr(
                            "src",
                            "https://daks2k3a4ib2z.cloudfront.net/6343da4ea0e69336d8375527/6343da5f04a965c89988b149_1665391198377-image16-p-500.jpg"
                        )
                        .addClass("imageSrc")
                )
        );
        $row.append($td);

        Object.values(attributeItem).forEach((value) => {
            $td = $("<td>").text(value);
            $row.append($td);
        });

        $td = $("<td>").addClass("hidden td-variant");
        let mainPrice = $('input[name="price"]').val();
        let mainSku = $('input[name="code"]').val();
        let inputHiddenFields = [
            { name: "variant[quantity][]", class: "variant_quantity" },
            {
                name: "variant[sku][]",
                class: "variant_sku",
                value: mainSku + "-" + classModified,
            },
            {
                name: "variant[price][]",
                class: "variant_price",
                value: mainPrice,
            },
            { name: "variant[barcode][]", class: "variant_barcode" },
            { name: "variant[file_name][]", class: "variant_filename" },
            { name: "variant[file_url][]", class: "variant_fileurl" },
            { name: "variant[album][]", class: "variant_album" },
            { name: "productVariant[name][]", value: attributeString },
            { name: "productVariant[id][]", value: attributeId },
        ];

        $.each(inputHiddenFields, function (_, field) {
            let $input = $("<input>")
                .attr("type", "text")
                .attr("name", field.name)
                .addClass(field.class);
            if (field.value) {
                $input.val(field.value);
            }
            $td.append($input);
        });

        $row.append($("<td>").addClass("td-quantity").text("-"))
            .append($("<td>").addClass("td-price").text(mainPrice))
            .append(
                $("<td>")
                    .addClass("td-sku")
                    .text(mainSku + "-" + classModified)
            )
            .append($td);
        return $row;
    };

    HT.createTableHeader = (attributeTitle) => {
        let $thead = $("table.variantTable thead");
        let $row = $("<tr>");
        $row.append($("<td>").text("Hình Ảnh"));
        for (let i = 0; i < attributeTitle.length; i++) {
            $row.append($("<td>").text(attributeTitle[i]));
        }

        $row.append($("<td>").text("Số lượng"));
        $row.append($("<td>").text("Giá tiền"));
        $row.append($("<td>").text("SKU"));

        $thead.html($row);
        return $thead;
    };

    HT.renderTableHtml = (attributes, attributeTitle) => {
        let html = "";
        html = html + "<thead>";
        html = html + "<tr>";
        html = html + "<td>Hình ảnh</td>";
        for (let i = 0; i < attributeTitle.length; i++) {
            html = html + "<td>" + attributeTitle[i] + "</td>";
        }

        html = html + "<td>Số lượng</td>";
        html = html + "<td>Giá tiền</td>";
        html = html + "<td>SKU</td>";
        html = html + "</tr>";
        html = html + "</thead>";
        html = html + "<tbody>";

        for (let j = 0; j < attributes.length; j++) {
            html = html + '<tr class="variant-row">';
            html = html + "<td>";
            html =
                html +
                '<span class="image img-cover"><img src="https://daks2k3a4ib2z.cloudfront.net/6343da4ea0e69336d8375527/6343da5f04a965c89988b149_1665391198377-image16-p-500.jpg" alt="" class="imageSrc"></span>';
            html = html + "</td>";
            let attributeArray = [];
            let attributeIdArray = [];
            $.each(attributes[j], function (index, value) {
                html = html + "<td>" + value + "</td>";
                attributeArray.push(value);
            });

            $.each(variants[j], function (index, value) {
                attributeIdArray.push(value);
            });

            let attributeString = attributeArray.join(", ");
            let attributeId = attributeIdArray.join(", ");

            html = html + '<td class="td-quantity">-</td>';
            html = html + '<td class="td-price">-</td>';
            html = html + '<td class="td-sku">-</td>';
            html = html + '<td class="hidden td-variant">';
            html =
                html +
                '<input type="text" name="variant[quantity][]" class="variant_quantity">';
            html =
                html +
                '<input type="text" name="variant[sku][]" class="variant_sku">';
            html =
                html +
                '<input type="text" name="variant[price][]" class="variant_price">';
            html =
                html +
                '<input type="text" name="variant[barcode][]" class="variant_barcode">';
            html =
                html +
                '<input type="text" name="variant[file_name][]" class="variant_filename">';
            html =
                html +
                '<input type="text" name="variant[file_url][]" class="variant_fileurl">';
            html =
                html +
                '<input type="text" name="variant[album][]" class="variant_album">';
            html =
                html +
                '<input type="text" name="attribute[name][]" value="' +
                attributeString +
                '">';
            html =
                html +
                '<input type="text" name="attribute[id][]" value="' +
                attributeId +
                '">';
            html = html + "</td>";
            html = html + "</tr>";
        }

        html = html + "</tbody>";

        return html;
    };

    HT.getSelect2 = (object) => {
        let option = {
            attributeCatalogueId: object.attr("data-catid"),
        };

        $(object).select2({
            minimumInputLength: 2,
            placeholder: "Nhập tối thiểu 2 ký tự để tìm kiếm",
            ajax: {
                url: "ajax/attribute/getAttribute",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        option: option,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (obj, i) {
                            return obj;
                        }),
                    };
                },
                cache: true,
            },
        });
    };

    HT.niceSelect = () => {
        $(".niceSelect").niceSelect();
    };

    HT.destroyNiceSelect = () => {
        if ($(".niceSelect").length) {
            $(".niceSelect").niceSelect("destroy");
        }
    };

    HT.checkMaxAttributeGroup = (attributeCatalogue) => {
        let variantItem = $(".variant-item").length;

        if (variantItem >= attributeCatalogue.length) {
            $(".add-variant").remove();
        } else {
            $(".variant-foot").html(
                '<button type="button" class="add-variant">Thêm phiên bản mới</button>'
            );
        }
    };

    HT.disabledAttributeCatalogueChoose = () => {
        let id = [];
        $(".choose-attribute").each(function () {
            let _this = $(this);
            let selected = _this.find("option:selected").val();
            if (selected != 0) {
                id.push(selected);
            }
        });

        $(".choose-attribute").find("option").removeAttr("disabled");
        for (let i = 0; i < id.length; i++) {
            $(".choose-attribute")
                .find("option[value=" + id[i] + "]")
                .prop("disabled", true);
        }
        HT.destroyNiceSelect();
        HT.niceSelect();
        $(".choose-attribute").find("option:selected").removeAttr("disabled");
    };

    HT.removeAttribute = () => {
        $(document).on("click", ".remove-attribute", function () {
            let _this = $(this);
            _this.parents(".variant-item").remove();
            HT.checkMaxAttributeGroup(attributeCatalogue);
        });
    };

    HT.select2Variant = (attributeCatalogueId) => {
        let html =
            '<select class="selectVariant form-control variant-' +
            attributeCatalogueId +
            '" name="attribute[' +
            attributeCatalogueId +
            '][]"multiple data-catid="' +
            attributeCatalogueId +
            '"></select>';
        return html;
    };

    HT.variantAlbum = () => {
        $(document).on("click", ".click-to-upload-variant", function (e) {
            HT.browseVariantServerAlbum();
            e.preventDefault();
        });
    };

    HT.browseVariantServerAlbum = () => {
        var type = "Images";
        var finder = new CKFinder();

        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            let html = "";
            for (var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;
                html += '<li class="ui-state-default">';
                html += ' <div class="thumb">';
                html += ' <span class="span image img-scaledown">';
                html += '<img src="' + image + '" alt="' + image + '">';
                html +=
                    '<input type="hidden" name="variantAlbum[]" value="' +
                    image +
                    '">';
                html += "</span>";
                html +=
                    '<button class="variant-delete-image"><i class="fa fa-trash"></i></button>';
                html += "</div>";
                html += "</li>";
            }

            $(".click-to-upload-variant").addClass("hidden");
            $("#sortable2").append(html);
            $(".upload-variant-list").removeClass("hidden");
        };
        finder.popup();
    };

    HT.deleteVariantAlbum = () => {
        $(document).on("click", ".variant-delete-image", function () {
            let _this = $(this);
            _this.parents(".ui-state-default").remove();
            if ($(".ui-state-default").length == 0) {
                $(".click-to-upload-variant").removeClass("hidden");
                $(".upload-variant-list").addClass("hidden");
            }
        });
    };

    HT.switchChange = () => {
        $(document).on("change", ".js-switch", function () {
            let _this = $(this);
            let isChecked = _this.prop("checked");
            if (isChecked == true) {
                _this
                    .parents(".col-lg-2")
                    .siblings(".col-lg-10")
                    .find(".disabled")
                    .removeAttr("disabled");
            } else {
                _this
                    .parents(".col-lg-2")
                    .siblings(".col-lg-10")
                    .find(".disabled")
                    .attr("disabled", true);
            }
        });
    };

    HT.updateVariant = () => {
        $(document).on("click", ".variant-row", function () {
            let _this = $(this);
            let variantData = {};
            _this
                .find(".td-variant input[type=text][class^='variant_']")
                .each(function () {
                    let className = $(this).attr("class");
                    variantData[className] = $(this).val();
                });

            let updateVariantBox = HT.updateVariantHtml(variantData);
            if ($(".updateVariantTr").length == 0) {
                _this.after(updateVariantBox);
                HT.switchery();
            }
        });
    };

    HT.switchery = () => {
        $(".js-switch").each(function () {
            // let _this = $(this)
            var switchery = new Switchery(this, {
                color: "#1AB394",
                size: "small",
            });
        });
    };

    HT.variantAlbumList = (album) => {
        let html = "";
        if (album.length && album[0] !== "") {
            for (let i = 0; i < album.length; i++) {
                html = html + '<li class="ui-state-default"> ';
                html = html + '<div class="thumb"> ';
                html = html + '<span class="span image img-scaledown">';
                html =
                    html +
                    '<img src="' +
                    album[i] +
                    '" alt="' +
                    album[i] +
                    '">';
                html =
                    html +
                    '<input type="hidden" name="variantAlbum[]" value="' +
                    album[i] +
                    '">';
                html = html + "</span>";
                html =
                    html +
                    '<button class="variant-delete-image"><i class="fa fa-trash"></i>';
                html = html + "</button>";
                html = html + "</div>";
                html = html + "</li>";
            }
        }
        return html;
    };

    HT.updateVariantHtml = (variantData) => {
        let variantAlbum = variantData.variant_album.split(",");
        let variantAlbumItem = HT.variantAlbumList(variantAlbum);
        let html = "";
        html = html + '<tr class="updateVariantTr">';
        html = html + '<td colspan="6">';
        html = html + '<div class="updateVariant ibox">';
        html = html + '<div class="ibox-title">';
        html =
            html + '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
        html = html + "<h5>Cập nhật thông tin phiên bản</h5>";
        html = html + '<div class="button-group">';
        html = html + '<div class="uk-flex uk-flex-middle">';
        html =
            html +
            '<button type="button" class="cancleUpdate btn btn-danger mr10">Hủy bỏ</button>';
        html =
            html +
            '<button type="button" class="saveUpdateVariant btn btn-success">Lưu lại</button>';
        html = html + "</div>";
        html = html + "</div>";
        html = html + "</div>";
        html = html + "</div>";
        html = html + '<div class="ibox-content">';
        html =
            html +
            '<div class="click-to-upload-variant ' +
            (variantAlbum.length > 0 && variantAlbum[0] !== ""
                ? "hidden"
                : "") +
            '">';
        html = html + '<div class="icon">';
        html = html + '<a type="button" class="upload-variant-picture">';
        html =
            html +
            '<svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>';
        html = html + "</a>";
        html = html + "</div>";
        html =
            html +
            '<div class="small-text">Sử dụng nút chọn hình để thêm mới hình ảnh</div>';
        html = html + "</div>";
        html =
            html +
            ' <ul class="upload-variant-list ' +
            (variantAlbumItem.length ? "" : "hidden") +
            ' sortui ui-sortable clearfix " id="sortable2">' +
            variantAlbumItem +
            "</ul>";
        html = html + '<div class="row mt20 uk-flex uk-flex-middle">';
        html = html + '<div class="col-lg-2 uk-flex uk-flex-middle">';
        html = html + '<label for="" class="mr10">Tồn kho</label>';
        html =
            html +
            '<input type="checkbox" class="js-switch" ' +
            (variantData.variant_quantity !== "" ? "checked" : "") +
            ' data-target="variantQuantity">';
        html = html + "</div>";
        html = html + '<div class="col-lg-10">';
        html = html + '<div class="row">';
        html = html + '<div class="col-lg-3">';
        html = html + '<label for="" class="control-label">Số lượng</label>';
        html =
            html +
            '<input type="text" ' +
            (variantData.variant_quantity == "" ? "disabled" : "") +
            '  name="variant_quantity" value="' +
            variantData.variant_quantity +
            '" class="form-control ' +
            (variantData.variant_quantity == "" ? "disabled" : "") +
            ' int">';
        html = html + "</div>";
        html = html + '<div class="col-lg-3">';
        html = html + '<label for="" class="control-label">SKU</label>';
        html =
            html +
            '<input type="text" name="variant_sku" value="' +
            variantData.variant_sku +
            '" class="form-control text-right">';
        html = html + "</div>";
        html = html + '<div class="col-lg-3">';
        html = html + '<label for="" class="control-label">Giá</label>';
        html =
            html +
            '<input type="text" name="variant_price" value="' +
            HT.addCommas(variantData.variant_price) +
            '" class="form-control int">';
        html = html + "</div>";
        html = html + '<div class="col-lg-3">';
        html = html + '<label for="" class="control-label">Barcode</label>';
        html =
            html +
            '<input type="text" name="variant_barcode" value="' +
            variantData.variant_barcode +
            '" class="form-control text-right">';
        html = html + "</div>";
        html = html + "</div>";
        html = html + "</div>";
        html = html + "</div>";
        html = html + '<div class="row mt20 uk-flex uk-flex-middle">';
        html = html + '<div class="col-lg-2 uk-flex uk-flex-middle">';
        html = html + '<label for="" class="mr10">QL File</label>';
        html =
            html +
            '<input type="checkbox" class="js-switch" data-target="disabled" ' +
            (variantData.variant_filename !== "" ? "checked" : "") +
            ">";
        html = html + "</div>";
        html = html + '<div class="col-lg-10">';
        html = html + '<div class="row">';
        html = html + '<div class="col-lg-6">';
        html = html + '<label for="" class="control-label">Tên File</label>';
        html =
            html +
            '<input type="text" ' +
            (variantData.variant_filename == "" ? "disabled" : "") +
            ' name="variant_file_name" value="' +
            variantData.variant_filename +
            '" class="form-control ' +
            (variantData.variant_filename == "" ? "disabled" : "") +
            '">';
        html = html + "</div>";
        html = html + '<div class="col-lg-6">';
        html = html + '<label for="" class="control-label">Đường dẫn</label>';
        html =
            html +
            '<input type="text" ' +
            (variantData.variant_filename == "" ? "disabled" : "") +
            ' name="variant_file_url" value="' +
            variantData.variant_fileurl +
            '" class="form-control ' +
            (variantData.variant_filename == "" ? "disabled" : "") +
            '">';
        html = html + "</div>";
        html = html + " </div>";
        html = html + "</div>";
        html = html + "</div>";
        html = html + "</div>";
        html = html + "</div>";
        html = html + "</td>";
        html = html + "</tr>";

        return html;
    };

    HT.cancleVariantUpdate = () => {
        $(document).on("click", ".cancleUpdate", function () {
            HT.closeUpdateVariantBox();
        });
    };

    HT.closeUpdateVariantBox = () => {
        $(".updateVariantTr").remove();
    };

    HT.addCommas = (nStr) => {
        nStr = String(nStr);
        nStr = nStr.replace(/\./gi, "");
        let str = "";
        for (let i = nStr.length; i > 0; i -= 3) {
            let a = i - 3 < 0 ? 0 : i - 3;
            str = nStr.slice(a, i) + "." + str;
        }
        str = str.slice(0, str.length - 1);
        return str;
    };

    HT.saveVariantUpdate = () => {
        $(document).on("click", ".saveUpdateVariant", function () {
            let variant = {
                quantity: $("input[name=variant_quantity]").val(),
                sku: $("input[name=variant_sku]").val(),
                price: $("input[name=variant_price]").val(),
                barcode: $("input[name=variant_barcode]").val(),
                filename: $("input[name=variant_file_name]").val(),
                fileurl: $("input[name=variant_file_url]").val(),
                album: $("input[name='variantAlbum[]']")
                    .map(function () {
                        return $(this).val();
                    })
                    .get(),
            };

            $.each(variant, function (index, value) {
                $(".updateVariantTr")
                    .prev()
                    .find(".variant_" + index)
                    .val(value);
            });

            HT.previewVariantTr(variant);
            HT.closeUpdateVariantBox();
        });
    };

    HT.previewVariantTr = (variant) => {
        let option = {
            quantity: variant.quantity,
            price: variant.price,
            sku: variant.sku,
        };
        $.each(option, function (index, value) {
            $(".updateVariantTr")
                .prev()
                .find(".td-" + index)
                .html(value);
        });
        $(".updateVariantTr")
            .prev()
            .find(".imageSrc")
            .attr("src", variant.album[0]);
    };

    HT.setupSelectMultiple = (callback) => {
        if ($(".selectVariant").length) {
            let count = $(".selectVariant").length;
            $(".selectVariant").each(function () {
                let _this = $(this);
                let attributeCatalogueId = _this.attr("data-catid");
                if (attribute != "") {
                    $.get(
                        "ajax/attribute/loadAttribute",
                        {
                            attribute: attribute,
                            attributeCatalogueId: attributeCatalogueId,
                        },
                        function (json) {
                            if (
                                json.items != "undefined" &&
                                json.items.length
                            ) {
                                for (let i = 0; i < json.items.length; i++) {
                                    var option = new Option(
                                        json.items[i].text,
                                        json.items[i].id,
                                        true,
                                        true
                                    );
                                    _this.append(option).trigger("change");
                                }
                            }

                            if (--count == 0 && callback) {
                                callback();
                            }
                        }
                    );
                }

                HT.getSelect2(_this);
            });
        }
    };

    HT.productVariant = () => {
        variant = JSON.parse(atob(variant));

        $(".variant-row").each(function (index, value) {
            let _this = $(this);
            let inputHiddenFields = [
                {
                    name: "variant[quantity][]",
                    class: "variant_quantity",
                    value: variant.quantity[index],
                },
                {
                    name: "variant[sku][]",
                    class: "variant_sku",
                    value: variant.sku[index],
                },
                {
                    name: "variant[price][]",
                    class: "variant_price",
                    value: variant.price[index],
                },
                {
                    name: "variant[barcode][]",
                    class: "variant_barcode",
                    value: variant.barcode[index],
                },
                {
                    name: "variant[filename][]",
                    class: "variant_filename",
                    value: variant.file_name[index],
                },
                {
                    name: "variant[fileurl][]",
                    class: "variant_fileurl",
                    value: variant.file_url[index],
                },
                {
                    name: "variant[album][]",
                    class: "variant_album",
                    value: variant.album[index],
                },
            ];

            for (let i = 0; i < inputHiddenFields.length; i++) {
                _this
                    .find("." + inputHiddenFields[i].class)
                    .val(inputHiddenFields[i].value ? inputHiddenFields[i].value : 0);
            }

            let album = variant.album[index];
            let variantImage = album
                ? album.split(",")[0]
                : "https://daks2k3a4ib2z.cloudfront.net/6343da4ea0e69336d8375527/6343da5f04a965c89988b149_1665391198377-image16-p-500.jpg";

            _this
                .find(".td-quantity")
                .html(HT.addCommas(variant.quantity[index]));
            _this.find(".td-price").html(HT.addCommas(variant.price[index]));
            _this.find(".td-sku").html(HT.addCommas(index));
            _this.find(".imageSrc").attr("src", variantImage);
        });
    };

    $(document).ready(function () {
        HT.addVariant();
        HT.niceSelect();
        HT.chooseVariantGroup();
        HT.removeAttribute();
        HT.createProductVariant();
        HT.variantAlbum();
        HT.deleteVariantAlbum();
        HT.switchChange();
        HT.updateVariant();
        HT.cancleVariantUpdate();
        HT.saveVariantUpdate();
        HT.setupSelectMultiple(() => {
            HT.productVariant();
        });
    });
})(jQuery);
