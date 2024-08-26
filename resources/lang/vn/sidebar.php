<?php

return [
    'module' => [
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
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => 'Ngôn ngữ',
                    'route' => 'language/index',
                ],
                [
                    'title' => 'Phân quyền',
                    'route' => 'permission/index',
                ],
            ]
        ]
    ]
];
