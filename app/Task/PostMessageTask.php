<?php

declare(strict_types=1);

namespace App\Task;

use Guanguans\Notify\Factory;
use Guanguans\Notify\Messages\DingTalk\MarkdownMessage as DDMarkdownMessage;
use Guanguans\Notify\Messages\WeWork\MarkdownMessage as WXMarkdownMessage;
use FriendsOfHyperf\AsyncTask\AbstractTask;
use function FriendsOfHyperf\Helpers\logs;

class PostMessageTask extends AbstractTask
{
    public function __construct(public array $params)
    {
    }

    public function handle(): void
    {
        try {
            $result = match($this->params['type']) {
                'dd' => Factory::dingTalk()->setToken(env('DD_TOKEN'))->setSecret('Prometheus')->setMessage(new DDMarkdownMessage([
                    'title' => 'PrometheusAlert',
                    'text'  => $this->params['text'],
                ]))->send(),
                'wx' => Factory::weWork()->setToken(env('WX_KEY'))->setMessage(new WXMarkdownMessage($this->params['text']))->send(),
                default => throw new \InvalidArgumentException('Invalid type value: ' . $this->params['type']),
            };
            logs()->info($this->params['type'] . ' ' . json_encode($result));
        } catch (\Throwable $e) {
            logs()->error('PostMessageTask: ' . $e->getMessage());
        }
    }
}
