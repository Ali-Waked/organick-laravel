<?php

namespace App\Http\Controllers;

use App\Actions\GetAllAbilities;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Enums\AbilityStatus;
use App\Services\AbilityService;
// use Illuminate\\Response;
use Illuminate\Support\Facades\Config;

class AbilityController extends Controller
{

    /**
     * Get All Abilities
     * @param \App\Services\AbilityService $abilityService
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(AbilityService $abilityService): JsonResponse
    {
        return $abilityService->all();
    }
}
