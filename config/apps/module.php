<?php

return [
    'module' => [
        [
            'title' => 'QL Nhóm người dùng',
            'icon' => "fa fa-th-large",
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm người dùng',
                    'route' => 'user/catalogue/index',
                ],
                [
                    'title' => 'QL nguời dùng',
                    'route' => 'user/index',
                ]
            ]
        ],
        [
            'title' => 'QL Nhóm bài viết',
            'icon' => "fa fa-th-large",
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm bài viết',
                    'route' => 'post/catalogue/index',
                ],
                [
                    'title' => 'QL bài viết',
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
                    'title' => 'QL ngôn ngữ',
                    'route' => 'language/index',
                ],
            ]
        ]
    ]
];
