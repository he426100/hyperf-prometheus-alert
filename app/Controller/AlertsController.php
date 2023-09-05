<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AlertsService;
use Hyperf\Di\Annotation\Inject;

class AlertsController extends AbstractController
{
    #[Inject]
    protected AlertsService $service;

    public function index()
    {
        $this->service->handle($this->request->post());
        return [];
    }
}
