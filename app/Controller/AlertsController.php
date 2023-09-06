<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AlertsService;
use App\Foundation\Traits\ValidateTrait;
use Hyperf\Di\Annotation\Inject;
use function FriendsOfHyperf\Helpers\logs;

class AlertsController extends AbstractController
{
    use ValidateTrait;

    protected $validates = [
        'index' => [
            'status' => 'required',
            'alerts' => 'required',
            'externalURL' => 'required',
        ],
    ];

    protected $messages = [
        'status.required' => 'status required',
        'alerts.required' => 'alerts required',
        'externalURL.required' => 'externalURL required',
    ];

    #[Inject]
    protected AlertsService $service;

    public function index()
    {
        $request = $this->request->post();
        $this->validateData($request, 'index');

        logs()->info('Alerts: ' . (string)$this->request->getBody());
        $this->service->handle($request);
        return [];
    }
}
