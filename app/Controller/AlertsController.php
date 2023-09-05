<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AlertsService;
use App\Foundation\Traits\ValidateTrait;
use Hyperf\Di\Annotation\Inject;

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

        $this->service->handle($request);
        return [];
    }
}
