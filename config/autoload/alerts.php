<?php

declare(strict_types=1);

return [
    'routes' => [
        [
            'labels' => [
                'severity' => 'critical'
            ],
            'tpl' => 'prometheus-wx',
            'type' => 'wx'
        ],
        [
            'labels' => [
                'severity' => 'warning'
            ],
            'tpl' => 'prometheus-dd',
            'type' => 'dd'
        ]
    ],
    'silences' => [
    ]
];
