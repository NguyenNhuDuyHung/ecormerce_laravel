<?php

return [
    'module' => [
        [
            'title' => 'QL Nhóm người dùng',
            'icon' => "fa fa-th-large",
            'name' => 'user',
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
            'name' => 'post',
            'subModule' => [
                [
                    'title' => 'QL Nhóm bài viết',
                    'route' => 'user/catalogue/index',
                ],
                [
                    'title' => 'QL bài viết',
                    'route' => 'user/index',
                ]
            ]
        ]
    ]
];
