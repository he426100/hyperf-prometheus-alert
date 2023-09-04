<?php

declare(strict_types=1);

namespace App\Task;

use FriendsOfHyperf\Http\Client\Http;
use FriendsOfHyperf\AsyncTask\AbstractTask;
use function FriendsOfHyperf\Helpers\logs;

class NotifyTask extends AbstractTask
{
    public function __construct(public array $params)
    {
    }

    public function handle(): void
    {
        try {
        } catch (\Throwable $e) {
            logs()->error('BlockNotifyTask: ' . $e->getMessage());
        }
    }
}
