<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HotwireNativeConfigurationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'patterns' => [
                [
                    'patterns' => ['.*'],
                    'properties' => [
                        'uri' => 'hotwire://fragment/web',
                        'pull_to_refresh_enabled' => true,
                    ],
                ],
                [
                    'patterns' => ['/create/?$', '/edit/?$', '/delete/?$', '/login/?$'],
                    'properties' => [
                        'context' => 'modal',
                        'pull_to_refresh_enabled' => false,
                    ],
                ],
            ],
        ]);
    }
}
