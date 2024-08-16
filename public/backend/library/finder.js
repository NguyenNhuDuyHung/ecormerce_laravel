(function ($) {
    "use strict";
    var HT = {};

    HT.setupCkeditor = () => {
        if ($(".ck-editor")) {
            $(".ck-editor").each(function () {
                let editor = $(this);
                let elementId = editor.attr("id");
                let elementHeight = editor.attr("data-height");
                HT.ckeditor4(elementId, elementHeight);
            });
        }
    };

    HT.ckeditor4 = (elementId, elementHeight) => {
        if (typeof elementHeight == "undefined") {
            elementHeight = 500;
        }
        CKEDITOR.replace(elementId, {
            autoUpdateElement: true,
            height: elementHeight,
            removeButtons: "",
            entities: true,
            allowedContent: true,
            toolbarGroups: [
                { name: "clipboard", groups: ["clipboard", "undo"] },
                {
                    name: "editing",
                    groups: ["find", "selection", "spellchecker"],
                },
                { name: "links" },
                { name: "insert" },
                { name: "forms" },
                { name: "tools" },
                { name: "document", groups: ["mode", "document", "doctools"] },
                { name: "colors" },
                { name: "others" },
                "/",
                { name: "basicstyles", groups: ["basicstyles", "cleanup"] },
                {
                    name: "paragraph",
                    groups: ["list", "indent", "blocks", "align", "bidi"],
                },
                { name: "styles" },
            ],
        });
    };

    HT.mutipleUploadImageCkeditor = () => {
        $(document).on("click", ".multipleUploadImageCkeditor", function (e) {
            let object = $(this);
            let target = object.attr("data-target");
            HT.browseServerCkeditor(object, "Images", target);

            e.preventDefault();
        });
    };

    HT.browseServerCkeditor = (object, type, target) => {
        if (typeof type == "undefined") {
            type = "Images";
        }

        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            let html = "";
            for (var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;
                html += `<p>
                    <figure>
                        <img src="${image}" alt="">
                        <figcaption>Nhập vào mô tả cho ảnh</figcaption>
                    </figure>
                </p>`;
            }
            CKEDITOR.instances[target].insertHtml(html);
        };

        finder.popup();
    };

    HT.uploadImageToInput = () => {
        $(".upload-image").click(function () {
            let input = $(this);
            let type = input.attr("data-type");
            HT.setupCKFinder2(input, type);
        });
    };

    HT.uploadImageAvatar = () => {
        $(".img-target").click(function () {
            let input = $(this);
            let type = "Images";
            HT.browseServerAvatar(input, type);
        });
    };

    HT.browseServerAvatar = (object, type) => {
        if (typeof type == "undefined") {
            type = "Images";
        }

        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.find("img").attr("src", fileUrl);
            object.siblings("input").val(fileUrl);
        };

        finder.popup();
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

    HT.sortui = () => {
        $("#sorttable").sortable();
        $("#sorttable").disableSelection();
    };

    HT.uploadAlbum = () => {
        $(document).on("click", ".upload-picture", function (e) {
            HT.browseServerAlbum();
            e.preventDefault();
        });
    };

    HT.browseServerAlbum = () => {
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
                    '<input type="hidden" name="album[]" value="' +
                    image +
                    '">';
                html += "</span>";
                html +=
                    '<button class="delete-image"><i class="fa fa-trash"></i></button>';
                html += "</div>";
                html += "</li>";
            }

            $(".click-to-upload").addClass("hidden");
            $("#sorttable").append(html);
            $(".upload-list").removeClass("hidden");
        };
        finder.popup();
    };

    HT.deletePicture = () => {
        $(document).on("click", ".delete-image", function (e) {
            let _this = $(this);
            _this.parents(".ui-state-default").remove();

            if ($(".ui-state-default").length == 0) {
                $(".click-to-upload").removeClass("hidden");
                $(".upload-list").addClass("hidden");
            }
        });
    };

    $(document).ready(function () {
        HT.uploadImageToInput();
        HT.setupCkeditor();
        HT.uploadImageAvatar();
        HT.mutipleUploadImageCkeditor();
        HT.sortui();
        HT.uploadAlbum();
        HT.deletePicture();
    });
})(jQuery);
