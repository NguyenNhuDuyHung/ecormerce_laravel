<?php

return [
    'module' => [
        [
            'title' => 'QL sản phẩm',
            'icon' => 'fa fa-cube',
            'name' => ['product','attribute'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm sản phẩm',
                    'route' => 'product/catalogue/index'
                ],
                [
                    'title' => 'QL sản phẩm',
                    'route' => 'product/index'
                ],
                [
                    'title' => 'QL Loại thuộc tính',
                    'route' => 'attribute/catalogue/index'
                ],
                [
                    'title' => 'QL thuộc tính',
                    'route' => 'attribute/index'
                ],

            ]
        ],
        [
            'title' => 'Nhóm người dùng',
            'icon' => "fa fa-th-large",
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'Nhóm người dùng',
                    'route' => 'user/catalogue/index',
                ],
                [
                    'title' => 'Nguời dùng',
                    'route' => 'user/index',
                ]
            ]
        ],
        [
            'title' => 'Nhóm bài viết',
            'icon' => "fa fa-th-large",
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Nhóm bài viết',
                    'route' => 'post/catalogue/index',
                ],
                [
                    'title' => 'Bài viết',
                    'route' => 'post/index',
                ]
            ]
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => "fa fa-th-large",
            'name' => ['language', 'permission', 'generate'],
            'subModule' => [
                [
                    'title' => 'Ngôn ngữ',
                    'route' => 'language/index',
                ],
                [
                    'title' => 'Phân quyền',
                    'route' => 'permission/index',
                ],
                [
                    'title' => 'Module',
                    'route' => 'generate/index',
                ],

            ]
        ]
    ]
];
