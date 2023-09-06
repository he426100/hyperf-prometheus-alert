<?php

declare(strict_types=1);

/**
 * @link https://github.com/guanguans/notify
 */
return [
    'routes' => [
        [
            'name' => '企业微信群机器人',
            'labels' => [
                'severity' => 'critical'
            ],
            'tpl' => 'prometheus-wx',
            'type' => 'weWork',
            'send_resolved' => false
        ],
        [
            'name' => '钉钉群机器人',
            'labels' => [
                'severity' => 'warning'
            ],
            'tpl' => 'prometheus-dd',
            'type' => 'dingTalk',
            'send_resolved' => false
        ]
    ],
    'silences' => [
    ]
];
