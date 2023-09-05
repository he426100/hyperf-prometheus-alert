<?php

declare(strict_types=1);

namespace App\Task;

use FriendsOfHyperf\AsyncTask\AbstractTask;
use function FriendsOfHyperf\Helpers\logs;

class PostMessageTask extends AbstractTask
{
    public function __construct(public array $params)
    {
    }

    public function handle(): void
    {
        $class = '\\App\\Lib\\Posts\\' . ucfirst($this->params['type']);
        if (!class_exists($class)) {
            throw new \InvalidArgumentException('Invalid type value: ' . $this->params['type']);
        }
        try {
            $result = (new $class($this->params['text']))->send();
            logs()->info($this->params['type'] . ' ' . json_encode($result));
        } catch (\Throwable $e) {
            logs()->error('PostMessageTask: ' . $e->getMessage());
        }
    }
}
