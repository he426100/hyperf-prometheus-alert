<?php

declare(strict_types=1);

namespace AppTest\Service;

use App\Service\AlertsService;
use Hyperf\Context\ApplicationContext;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AlertsServiceTest extends TestCase
{
    public function testGetMessages(): void
    {
        $container = ApplicationContext::getContainer();
        $service = $container->get(AlertsService::class);

        // 构造测试数据
        $alert = [
            'status' => 'firing',
            'alerts' => [
                [
                    'status' => 'firing',
                    'labels' => [
                        'alertname' => 'PrometheusTargetMissing',
                        'instance' => '192.168.1.xx:9100',
                        'job' => 'node_exporter',
                        'severity' => 'critical',
                    ],
                    'annotations' => [
                        'description' => 'A Prometheus target has disappeared. An exporter might be crashed.\n VALUE = 0\n LABELS = map[__name__:up instance:192.168.1.xx:9100 job:node_exporter]',
                        'summary' => 'Prometheus target missing (instance 192.168.1.xx:9100)'
                    ],
                    'startsAt' => '2023-09-05T06:06:17.909Z',
                    'generatorURL' => 'https://example.com'
                ]
            ],
            'commonLabels' => [
                'alertname' => 'PrometheusTargetMissing',
                'job' => 'node_exporter',
                'severity' => 'critical'
            ],
            'externalURL' => 'https://example.com'
        ];

        $result = $service->getMessages($alert['alerts'][0]);
        $this->assertCount(1, $result);
        $this->assertSame('prometheus-wx', $result[0]['tpl']);

        $text = $service->transformAlertMessage($alert, $result[0]['tpl']);
        $this->assertStringContainsString('PROMETHEUS-告警信息', $text);
    }
}
