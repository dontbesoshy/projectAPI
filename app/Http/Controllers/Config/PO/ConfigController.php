<?php

namespace App\Http\Controllers\Config\PO;

use App\Http\Controllers\Controller;
use App\Services\Config\PO\ConfigService;
use Illuminate\Http\JsonResponse;

class ConfigController extends Controller
{
    /**
     * ConfigController constructor.
     *
     * @param ConfigService $configService
     */
    public function __construct(private readonly ConfigService $configService)
    {
    }

    /**
     * Return configs.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->OK($this->configService->index());
    }
}
