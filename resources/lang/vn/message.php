<?php
return [
    'menu' => [
        'index' => [
            'title' => 'Quản lý menu',
            'tableHeading' => 'Danh sách menu',
        ],
        'create' => [
            'title' => 'Thêm mới Menu',
            'children' => 'Quản lý menu con'
        ],
        'show' => [
            'title' => 'Danh sách menu'
        ],
        'delete' => [
            'title' => 'Xóa menu'
        ],
    ],
    'system' => [
        'index' => [
            'title' => 'Cấu hình hệ thống',
        ],
        'create' => [
            'title' => 'Cập nhật thông tin'
        ],
    ],
    'productCatalogue' => [
        'index' => [
            'title' => 'Quản lý nhóm sản phẩm',
            'tableHeading' => 'Danh sách nhóm sản phẩm'
        ],
        'create' => [
            'title' => 'Thêm mới nhóm sản phẩm'
        ],
        'edit' => [
            'title' => 'Cập nhật nhóm sản phẩm'
        ],
        'delete' => [
            'title' => 'Xóa nhóm sản phẩm'
        ],
    ],
    'product' => [
        'index' => [
            'title' => 'Quản lý sản phẩm',
            'tableHeading' => 'Danh sách sản phẩm'
        ],
        'create' => [
            'title' => 'Thêm mới sản phẩm'
        ],
        'edit' => [
            'title' => 'Cập nhật sản phẩm'
        ],
        'delete' => [
            'title' => 'Xóa sản phẩm'
        ],
        'information' => 'Thông tin chung',
        'code' => 'Mã sản phẩm',
        'made_in' => 'Xuất xứ',
        'price' => 'Giá bán sản phẩm',
    ],
    'attributeCatalogue' => [
        'index' => [
            'title' => 'Quản lý loại thuộc tính',
            'tableHeading' => 'Danh sách loại thuộc tính'
        ],
        'create' => [
            'title' => 'Thêm mới loại thuộc tính'
        ],
        'edit' => [
            'title' => 'Cập nhật loại thuộc tính'
        ],
        'delete' => [
            'title' => 'Xóa loại thuộc tính'
        ],
        'information' => 'Thông tin chung',
        'code' => 'Mã loại thuộc tính',
        'made_in' => 'Xuất xứ',
        'price' => 'Giá bán loại thuộc tính',
    ],
    'attribute' => [
        'index' => [
            'title' => 'Quản lý thuộc tính',
            'tableHeading' => 'Danh sách thuộc tính'
        ],
        'create' => [
            'title' => 'Thêm mới thuộc tính'
        ],
        'edit' => [
            'title' => 'Cập nhật thuộc tính'
        ],
        'delete' => [
            'title' => 'Xóa thuộc tính'
        ],
        'information' => 'Thông tin chung',
        'code' => 'Mã thuộc tính',
        'made_in' => 'Xuất xứ',
        'price' => 'Giá bán thuộc tính',
    ],
    'postCatalogue' => [
        'index' => [
            'title' => "Quản lý  nhóm bài viết",
            'tableHeading' => "Danh sách nhóm bài viết",
        ],

        'create' => [
            'title' => "Thêm mới nhóm bài viết",
        ],

        'edit' => [
            'title' => "Cập nhật thông tin nhóm bài viết",
        ],

        'delete' => [
            'title' => "Xóa nhóm bài viết",
        ],
    ],
    'post' => [
        'index' => [
            'title' => 'Quản lý bài viết',
            'tableHeading' => 'Danh sách bài viết'
        ],
        'create' => [
            'title' => 'Thêm mới bài viết'
        ],
        'edit' => [
            'title' => 'Cập nhật bài viết'
        ],
        'delete' => [
            'title' => 'Xóa bài viết'
        ],
    ],
    'userCatalogue' => [
        'index' => [
            'title' => 'Quản lý nhóm thành viên',
            'tableHeading' => 'Danh sách nhóm thành viên'
        ],
        'create' => [
            'title' => 'Thêm mới nhóm thành viên'
        ],
        'edit' => [
            'title' => 'Cập nhật nhóm thành viên'
        ],
        'delete' => [
            'title' => 'Xóa nhóm thành viên'
        ],
        'permission' => [
            'title' => 'Cập nhật quyền'
        ],
    ],
    'user' => [
        'index' => [
            'title' => 'Quản lý thành viên',
            'tableHeading' => 'Danh sách thành viên'
        ],
        'create' => [
            'title' => 'Thêm mới thành viên'
        ],
        'edit' => [
            'title' => 'Cập nhật thành viên'
        ],
        'delete' => [
            'title' => 'Xóa thành viên'
        ],
    ],
    'permission' => [
        'index' => [
            'title' => 'Quản lý Quyền',
            'tableHeading' => 'Danh sách Quyền'
        ],
        'create' => [
            'title' => 'Thêm mới Quyền'
        ],
        'edit' => [
            'title' => 'Cập nhật Quyền'
        ],
        'delete' => [
            'title' => 'Xóa Quyền'
        ],
    ],
    'generate' => [
        'index' => [
            'title' => 'Quản lý Module',
            'tableHeading' => 'Danh sách Module'
        ],
        'create' => [
            'title' => 'Thêm mới Module'
        ],
        'edit' => [
            'title' => 'Cập nhật Module'
        ],
        'delete' => [
            'title' => 'Xóa Module'
        ],
    ],

    'parent' => 'Chọn danh mục cha',
    'parentNotice' => 'Chọn Root nếu không có danh mục cha',
    'subparent' => 'Chọn danh mục phụ (nếu có)',
    'image' => 'Chọn ảnh đại diện',
    'advance' => 'Cấu hình nâng cao',
    'search' => 'Tìm Kiếm',
    'searchInput' => 'Nhập Từ khóa bạn muốn tìm kiếm...',
    'perpage' => 'bản ghi',
    'title' => 'Tiêu đề',
    'description' => 'Mô tả ngắn',
    'content' => 'Nội dung',
    'upload' => 'Upload nhiều hình ảnh',
    'seo' => 'Cấu hình seo',
    'seoTitle' => 'Bạn chưa có tiêu đề SEO',
    'seoCanonical' => 'https://duong-dan-cua-ban.html',
    'seoDescription' => 'Bạn chưa có mô tả SEO',
    'seoMetaTitle' => 'Tiêu đề SEO',
    'seoMetaKeyword' => 'Từ khóa SEO',
    'seoMetaDescription' => 'Mô tả SEO',
    'canonical' => 'Đường dẫn',
    'character' => 'Ký tự',
    'tableStatus' => 'Tình Trạng',
    'tableAction' => 'Thao tác',
    'tableName' => 'Tiêu đề',
    'tableOrder' => 'Sắp xếp',
    'tableGroup' => 'Nhóm hiển thị:',
    'deleteButton' => 'Xóa dữ liệu',
    'tableHeading' => 'Thông tin chung',
    'save' => 'Lưu lại',
    'publish' => [
        '0' => 'Chọn tình trạng',
        '1' => 'Không kích hoạt',
        '2' => 'Kích hoạt',
    ],
    'follow' => [
        '0' => 'Chọn Điều hướng',
        '1' => 'Follow',
        '2' => 'Nofollow',
    ],
    'album' => [
        'heading' => 'Album Ảnh',
        'image' => 'Chọn Hình',
        'notice' => 'Sử dụng nút chọn hình hoặc click vào đây để thêm hình ảnh'
    ],
    'generalTitle' => 'Thông tin chung',
    'generalDescription' => 'Bạn đang muốn xóa ngôn ngữ có tên là:
    Lưu ý: Không thể khôi phục dữ liệu sau khi xóa. Hãy chắc chắn bạn muốn thực hiện chức năng này',
];