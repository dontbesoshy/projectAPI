<?php

namespace App\Services\Config\PO;

use App\Models\Config\Config;
use App\Resources\Config\ConfigCollection;
use App\Services\BasicService;

class ConfigService extends BasicService
{
    /**
     * Return configs.
     *
     * @return ConfigCollection
     */
    public function index(): ConfigCollection
    {
        $configs = Config::query()->where('active', true)->get();

        return new ConfigCollection($configs);
    }
}
