<?php

declare(strict_types=1);

namespace App\Service;

use App\Task\PostMessageTask;
use FriendsOfHyperf\AsyncTask\Task;
use Hyperf\Di\Annotation\Inject;
use Hyperf\View\RenderInterface;
use function FriendsOfHyperf\Helpers\logs;

// {
//     "status": "firing",
//     "alerts": [
//         {
//             "status": "firing",
//             "labels": {
//                 "alertname": "PrometheusTargetMissing",
//                 "instance": "192.168.1.xx:9100",
//                 "job": "node_exporter",
//                 "severity": "critical"
//             },
//             "annotations": {
//                 "description": "A Prometheus target has disappeared. An exporter might be crashed.\n VALUE = 0\n LABELS = map[__name__:up instance:192.168.1.xx:9100 job:node_exporter]",
//                 "summary": "Prometheus target missing (instance 192.168.1.xx:9100)"
//             },
//             "startsAt": "2023-09-05T06:06:17.909Z",
//             "endsAt": "0001-01-01T00:00:00Z",
//             "generatorURL": "http://40ae56dfb308:9090/graph?g0.expr=up+%3D%3D+0&g0.tab=1",
//             "fingerprint": "826d47bc559c3ec1"
//         },
//         ...
//     ],
//     "externalURL": "http://dde7f544064e:9093",
//     ...
// },
/**
 * @link https://github.com/feiyu563/PrometheusAlert
 * @link https://github.com/guanguans/notify
 * @package App\Service
 */
class AlertsService
{
    #[Inject]
    protected RenderInterface $render;

    public function handle(array $post)
    {
        try {
            foreach ($post['alerts'] as $item) {
                $alert = [
                    ...$post,
                    'alerts' => [$item]
                ];
                $messages = $this->getMessages($item);
                foreach ($messages as $message) {
                    $text = $this->transformAlertMessage($alert, $message['tpl']);
                    $type = $message['type'];
                    Task::deliver(new PostMessageTask(compact('text', 'type')));
                }
            }
        } catch (\Throwable $e) {
            logs()->error('Alerts.error: ' . $e->getMessage());
        }
    }

    public function getMessages(array $alert): array
    {
        $messages = [];
        $routes = config('alerts.routes');
        foreach ($routes as $route) {
            if ($alert['status'] == 'resolved' && !$route['send_resolved']) {
                logs()->info('告警名称: %s 路由规则: %s 路由类型: %s 路由恢复告警: %s', $alert['labels']['alertname'], $route['name'], $route['type'], $route['send_resolved']);
                continue;
            }
            $match = 0;
            foreach ($route['labels'] as $ruleName => $ruleValue) {
                foreach ($alert['labels'] as $labelKey => $labelValue) {
                    if ($ruleName == $labelKey && $ruleValue == $labelValue) {
                        $match++;
                    }
                }
            }
            if (count($route['labels']) == $match) {
                $messages[] = $route;
            }
        }
        return $messages;
    }

    /**
     * 把消息转成模板
     * @param array $message
     * @param string $tpl
     * @return string 
     */
    public function transformAlertMessage(array $message, string $tpl): string
    {
        return $this->render->getContents($tpl, $message);
    }
}
